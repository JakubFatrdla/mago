#![allow(clippy::unwrap_used)]

use std::borrow::Cow;

use mago_allocator::LocalArena;
use mago_codex::scanner::scan_program;
use mago_database::file::File;
use mago_names::resolver::NameResolver;
use mago_php_version::PHPVersion;
use mago_syntax::parser::parse_file;

const SOURCE: &str = "<?php\nclass Chat {\n    /**\n     * @param int $userId\n     */\n    public function add(int $userRow): int { return $userRow; }\n}\n";

#[test]
fn unknown_param_tag_is_reported_on_the_signature_like_phpstan() {
    let arena = LocalArena::new();
    let file = File::ephemeral(Cow::Borrowed(b"unknown_param.php"), Cow::Owned(SOURCE.as_bytes().to_vec()));
    let program = parse_file(&arena, &file);
    let names = NameResolver::new(&arena).resolve(program);
    let mut metadata = scan_program(&arena, &file, program, &names, PHPVersion::LATEST);

    let issues: Vec<_> = metadata.take_issues(true).into_iter().collect();
    assert_eq!(issues.len(), 1);
    assert_eq!(issues[0].code.as_deref(), Some("invalid-param-tag"));

    let primary = issues[0].primary_span().unwrap();
    let signature = "public function add";
    let signature_start = SOURCE.find(signature).unwrap() as u32;
    assert_eq!(primary.start.offset, signature_start);
    assert_eq!(primary.end.offset, signature_start + signature.len() as u32);
}
