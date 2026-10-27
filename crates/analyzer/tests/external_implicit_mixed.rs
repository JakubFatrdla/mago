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

function union_with_null(array $a, bool $b): void
{
    $x = $b ? $a['x'] : null;
    if ($x) {}
}

function explicit_union_with_null(mixed $m, bool $b): void
{
    $x = $b ? $m : null;
    if ($x) {}
}

function equal_union_explicit_first(mixed $m, array $a, bool $b): void
{
    $x = $b ? $m : $a['x'];
    if ($x) {}
}

function equal_union_implicit_first(mixed $m, array $a, bool $b): void
{
    $x = $b ? $a['x'] : $m;
    if ($x) {}
}

function branch_merge(mixed $m, array $a, bool $b): void
{
    if ($b) {
        $x = $m;
    } else {
        $x = $a['x'];
    }
    if ($x) {}
}

function narrowed_after_guard(array $a): void
{
    $x = $a['x'];
    if (!$x) {
        return;
    }
    if ($x) {}
}

function explicit_narrowed_after_guard(mixed $x): void
{
    if (!$x) {
        return;
    }
    if ($x) {}
}

function narrowed_not_null(array $a): void
{
    $x = $a['x'];
    if ($x !== null) {
        if ($x) {}
    }
}

function nested_isset(array $a): void
{
    if (isset($a['x']['y'])) {
        if ($a['x']['y']) {}
    }
}

function nested_isset_of_iterated(array $rows): void
{
    foreach ($rows as $row) {
        if (isset($row['values']['text'])) {
            if ($row['values']['text']) {}
        }
    }
}

function iterated_offset(array $a): void
{
    foreach ($a['rows'] as $row) {
        if ($row) {}
    }
}

function unset_offset(array $rows): void
{
    foreach ($rows as $row) {
        unset($row['a']);
        if ($row['b']) {}
    }
}

final class Holder
{
    public $untyped;

    /** @var mixed */
    public $docMixed;

    public mixed $nativeMixed = null;
}

function untyped_property(Holder $h): void
{
    if ($h->untyped) {}
}

function docblock_mixed_property(Holder $h): void
{
    if ($h->docMixed) {}
}

function native_mixed_property(Holder $h): void
{
    if ($h->nativeMixed) {}
}

function property_of_mixed(mixed $m): void
{
    if ($m->flag) {}
}

function dynamic_property(\stdClass $o): void
{
    if ($o->flag) {}
}

function isset_property_of_mixed(mixed $m): void
{
    if (isset($m->flag)) {
        if ($m->flag) {}
    }
}

function property_exists_on_object(object $o): void
{
    if (property_exists($o, 'flag')) {
        if ($o->flag) {}
    }
}

function method_and_property_exist(object $o): void
{
    if (method_exists($o, 'run') && property_exists($o, 'flag')) {
        if ($o->flag) {}
    }
}

function property_exists_on_named(Holder $h): void
{
    if (property_exists($h, 'extra')) {
        if ($h->extra) {}
    }
}

trait ReadsUserProperty
{
    public function trait_user_property(): void
    {
        if ($this->row) {}
    }
}

final class TypedUser
{
    use ReadsUserProperty;

    private ?\stdClass $row = null;
}

final class UntypedUser
{
    use ReadsUserProperty;
}

function untyped_parameter($m): void
{
    if ($m) {}
}

function untyped_source()
{
    return 1;
}

function untyped_return(): void
{
    if (untyped_source()) {}
}

function mixed_source(): mixed
{
    return 1;
}

function mixed_return(): void
{
    if (mixed_source()) {}
}

function method_of_mixed(mixed $m): void
{
    if ($m->run()) {}
}

final class Flagged
{
    public function flag(): bool
    {
        return true;
    }
}

function method_of_object(object $o): void
{
    if ($o->flag()) {}
}

/** @param Flagged|object $o */
function method_of_class_or_object($o): void
{
    if ($o->flag()) {}
}

final class Unfinished extends MissingParent
{
}

function method_of_class_or_unfinished(Flagged|Unfinished $o): void
{
    if ($o->flag()) {}
}

final class Magic
{
    public function __call(string $name, array $arguments): mixed
    {
        return null;
    }
}

function undocumented_magic_call(Magic $m): void
{
    if ($m->run()) {}
}

function key_exists_on_mixed(mixed $json): void
{
    if (array_key_exists('meta', $json)) {
        if ($json['meta']) {}
    }
}

function is_array_on_mixed(mixed $json): void
{
    if (is_array($json)) {
        if ($json['meta']) {}
    }
}
";

const EXPECTED: &[(&str, &str)] = &[
    ("native_array_element", "implicit-mixed"),
    ("docblock_mixed_value", "explicit-mixed"),
    ("native_mixed", "explicit-mixed"),
    ("union_with_null", "implicit-mixed"),
    ("explicit_union_with_null", "explicit-mixed"),
    ("equal_union_explicit_first", "implicit-mixed"),
    ("equal_union_implicit_first", "implicit-mixed"),
    ("branch_merge", "implicit-mixed"),
    ("narrowed_after_guard", "implicit-mixed"),
    ("explicit_narrowed_after_guard", "explicit-mixed"),
    ("narrowed_not_null", "implicit-mixed"),
    ("nested_isset", "implicit-mixed"),
    ("nested_isset_of_iterated", "implicit-mixed"),
    ("iterated_offset", "implicit-mixed"),
    ("unset_offset", "implicit-mixed"),
    ("untyped_property", "implicit-mixed"),
    ("docblock_mixed_property", "explicit-mixed"),
    ("native_mixed_property", "explicit-mixed"),
    ("property_of_mixed", "implicit-mixed"),
    ("dynamic_property", "implicit-mixed"),
    ("isset_property_of_mixed", "implicit-mixed"),
    ("property_exists_on_object", "implicit-mixed"),
    ("method_and_property_exist", "implicit-mixed"),
    ("property_exists_on_named", "implicit-mixed"),
    ("trait_user_property", "explicit-mixed"),
    ("untyped_parameter", "implicit-mixed"),
    ("untyped_return", "implicit-mixed"),
    ("mixed_return", "explicit-mixed"),
    ("method_of_mixed", "implicit-mixed"),
    ("method_of_object", "implicit-mixed"),
    ("method_of_class_or_object", "implicit-mixed"),
    ("method_of_class_or_unfinished", "implicit-mixed"),
    ("undocumented_magic_call", "implicit-mixed"),
    ("key_exists_on_mixed", "implicit-mixed"),
    ("is_array_on_mixed", "explicit-mixed"),
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
