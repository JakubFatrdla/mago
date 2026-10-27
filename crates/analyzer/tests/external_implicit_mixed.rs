#![allow(clippy::expect_used, clippy::missing_panics_doc)]

use std::borrow::Cow;
use std::num::NonZeroUsize;
use std::path::Path;
use std::sync::Arc;
use std::sync::LazyLock;

use mago_allocator::LocalArena;
use mago_analyzer::Analyzer;
use mago_analyzer::analysis_result::AnalysisResult;
use mago_analyzer::external::ExternalAnalysisSession;
use mago_analyzer::external::ExternalAnalyzer;
use mago_analyzer::external::ExternalAnalyzerHandle;
use mago_analyzer::plugin::PluginRegistry;
use mago_analyzer::settings::Settings;
use mago_codex::populator::populate_codebase;
use mago_codex::scanner::scan_program;
use mago_database::file::File;
use mago_database::file::FileType;
use mago_extension::WorkerCommand;
use mago_extension::WorkerPool;
use mago_extension::WorkerPoolOptions;
use mago_names::resolver::NameResolver;
use mago_php_version::PHPVersion;
use mago_prelude::Prelude;
use mago_syntax::parser::parse_file;
use mago_word::WordSet;

static PRELUDE: LazyLock<Prelude> = LazyLock::new(Prelude::build);

const SOURCE: &str = r"<?php

declare(strict_types=1);

function native_array_element(array $a): void
{
    if ($a['x']) {}
}

/** @param array<string, mixed> $a */
function docblock_mixed_value(array $a): void
{
    if ($a['x']) {}
}

function native_mixed(mixed $m): void
{
    if ($m) {}
}

";

const EXPECTED: &[(&str, &str)] = &[
    ("native_array_element", "implicit-mixed"),
    ("docblock_mixed_value", "explicit-mixed"),
    ("native_mixed", "explicit-mixed"),
];

#[test]
fn extensions_see_implicit_mixed_where_phpstan_types_it_implicit() {
    let repository = Path::new(env!("CARGO_MANIFEST_DIR")).join("../..");
    let available = repository.join("vendor/autoload.php").is_file()
        && std::process::Command::new("php").arg("--version").output().is_ok_and(|output| output.status.success());
    assert!(
        available || std::env::var_os("MAGO_REQUIRE_PHP_SDK_TESTS").is_none(),
        "PHP and vendor dependencies are required for the external implicit mixed test"
    );
    if !available {
        return;
    }

    let command = WorkerCommand::new("php")
        .with_argument(repository.join("composer/tests/Sdk/Fixtures/analyzer-implicit-mixed-worker.php"))
        .with_current_directory(repository.clone());
    let pool = WorkerPool::spawn(command, NonZeroUsize::MIN, WorkerPoolOptions::default())
        .expect("failed to start the external analyzer worker pool");
    let external = ExternalAnalyzer::initialize([Arc::new(pool)], PHPVersion::PHP85, &[], false)
        .expect("failed to initialize the external analyzer");
    let mut registry = PluginRegistry::with_library_providers();
    registry.set_external_analyzer(Arc::new(ExternalAnalyzerHandle::ready(external)));
    registry.prepare_external_analyzer().expect("failed to prepare the external analyzer");

    let file = Arc::new(File::new(
        Cow::Borrowed(b"src/implicit-mixed.php"),
        FileType::Host,
        None,
        Cow::Borrowed(SOURCE.as_bytes()),
    ));
    let arena = LocalArena::new();
    let program = parse_file(&arena, &file);
    assert!(!program.has_errors(), "the implicit mixed fixture should parse");
    let resolved_names = NameResolver::new(&arena).resolve(program);
    let mut codebase = PRELUDE.metadata.clone();
    codebase.extend(scan_program(&arena, &file, program, &resolved_names, PHPVersion::PHP85));
    let mut symbol_references = PRELUDE.symbol_references.clone();
    populate_codebase(&mut codebase, &mut symbol_references, WordSet::default(), foldhash::HashSet::default());

    let session = ExternalAnalysisSession::from_files([Arc::clone(&file)]);
    let mut settings = Settings::new(PHPVersion::PHP85);
    settings.find_unused_definitions = false;
    settings.find_unused_expressions = false;
    settings.find_unused_parameters = false;
    let analyzer = Analyzer::new(&arena, &file, &resolved_names, &codebase, &registry, settings)
        .with_external_analysis_session(&session);
    let mut result = AnalysisResult::new(symbol_references);
    let artifacts =
        analyzer.analyze_with_artifacts(program, &mut result).expect("analysis with the external analyzer failed");
    let hooks = registry
        .run_external_after_file_analysis_hooks(&file, program, &resolved_names, &artifacts, &codebase, Some(&session))
        .expect("the external node hook failed");

    let mut reported = hooks
        .issues
        .iter()
        .filter_map(|issue| {
            let code = issue.code.as_deref()?;
            let code = ["implicit-mixed", "explicit-mixed"].into_iter().find(|kind| code.ends_with(kind))?;

            Some((enclosing_function(issue.primary_span()?.start.offset as usize), code))
        })
        .collect::<Vec<_>>();
    reported.sort_unstable();
    let mut expected = EXPECTED.to_vec();
    expected.sort_unstable();
    assert_eq!(reported, expected);
}

fn enclosing_function(offset: usize) -> &'static str {
    SOURCE[..offset]
        .rsplit("function ")
        .next()
        .and_then(|declaration| declaration.split('(').next())
        .expect("every condition sits in a function")
}
