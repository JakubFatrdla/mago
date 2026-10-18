use std::path::Path;
use std::path::PathBuf;
use std::process::Command;
use std::process::Stdio;

fn mago_bin() -> PathBuf {
    let path = std::env::var("CARGO_BIN_EXE_mago")
        .ok()
        .or_else(|| option_env!("CARGO_BIN_EXE_mago").map(String::from))
        .unwrap_or_else(|| "mago".to_string());

    PathBuf::from(path)
}

fn can_run_mago() -> bool {
    Command::new(mago_bin())
        .arg("--help")
        .stdout(Stdio::null())
        .stderr(Stdio::null())
        .status()
        .is_ok_and(|status| status.success())
}

fn workspace(report_unmatched_ignores: bool) -> tempfile::TempDir {
    let temp_dir = tempfile::tempdir().unwrap();
    let workspace = temp_dir.path();
    std::fs::create_dir(workspace.join("src")).unwrap();
    std::fs::write(
        workspace.join("mago.toml"),
        format!(
            r#"
php-version = "8.4"
[source]
paths = ["src"]
[analyzer]
report-unmatched-ignores = {report_unmatched_ignores}
ignore = [
    "invalid-return-statement",
    {{ code = "invalid-return-statement", in = ["src/"] }},
    {{ pattern = "never matches", code = "mixed-assignment" }},
]
"#
        ),
    )
    .unwrap();
    std::fs::write(workspace.join("src/example.php"), "<?php\nfunction f(): int { return 'not an int'; }\n").unwrap();

    temp_dir
}

fn issues(workspace: &Path, extra_args: &[&str]) -> Vec<(String, String)> {
    let output = Command::new(mago_bin())
        .args(["--workspace", workspace.to_str().unwrap(), "analyze", "--reporting-format", "json"])
        .args(extra_args)
        .output()
        .expect("failed to run mago");
    let stdout = String::from_utf8_lossy(&output.stdout);
    let json = &stdout[stdout.find('{').unwrap_or(0)..];
    if json.trim().is_empty() {
        return vec![];
    }

    let report: serde_json::Value = serde_json::from_str(json).expect("analyze --reporting-format json prints JSON");
    report["issues"]
        .as_array()
        .unwrap()
        .iter()
        .map(|issue| (issue["code"].as_str().unwrap().to_string(), issue["message"].as_str().unwrap().to_string()))
        .collect()
}

#[test]
fn test_unscoped_analyze_reports_ignore_entries_that_matched_nothing() {
    if !can_run_mago() {
        return;
    }

    let workspace = workspace(true);

    assert_eq!(
        issues(workspace.path(), &[]),
        vec![
            (
                "unmatched-ignore".to_string(),
                "Ignore entry `invalid-return-statement` in `src/` matched no issue.".to_string()
            ),
            (
                "unmatched-ignore".to_string(),
                "Ignore entry `mixed-assignment` pattern `never matches` matched no issue.".to_string()
            ),
        ]
    );
}

#[test]
fn test_scoped_analyze_does_not_report_unmatched_ignore_entries() {
    if !can_run_mago() {
        return;
    }

    let workspace = workspace(true);

    assert_eq!(issues(workspace.path(), &["src/example.php"]), vec![]);
}

#[test]
fn test_unmatched_ignore_entries_are_not_reported_by_default() {
    if !can_run_mago() {
        return;
    }

    let workspace = workspace(false);

    assert_eq!(issues(workspace.path(), &[]), vec![]);
}
