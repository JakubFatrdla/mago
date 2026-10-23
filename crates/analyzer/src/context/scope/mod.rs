use mago_word::Word;

pub mod case_scope;
pub mod conditional_scope;
pub mod control_action;
pub mod finally_scope;
pub mod if_scope;
pub mod loop_scope;

#[inline]
pub fn is_access_path(var_id: &[u8]) -> bool {
    var_id.contains(&b'[')
        || memchr::memmem::find(var_id, b"->").is_some()
        || memchr::memmem::find(var_id, b"::").is_some()
}

#[inline]
pub fn var_has_root(var_id: Word, root_var_id: Word) -> bool {
    if var_id == root_var_id {
        return true;
    }

    let var_bytes = var_id.as_bytes();
    let root_bytes = root_var_id.as_bytes();
    if !var_bytes.starts_with(root_bytes) {
        return false;
    }

    let after_root = &var_bytes[root_bytes.len()..];
    after_root.starts_with(b"->") || after_root.starts_with(b"::") || after_root.starts_with(b"[")
}

#[inline]
pub fn is_narrowed_function_call(var_id: &[u8]) -> bool {
    var_id.starts_with(b"@")
}

pub fn narrowed_function_call_reads(narrowed_call_id: Word, root_var_id: Word) -> bool {
    let call_bytes = narrowed_call_id.as_bytes();
    let root_bytes = root_var_id.as_bytes();
    if !is_narrowed_function_call(call_bytes) {
        return false;
    }

    let mut quote = None;
    let mut position = 0;
    while position < call_bytes.len() {
        let byte = call_bytes[position];
        match quote {
            Some(_) if byte == b'\\' => position += 1,
            Some(open) if byte == open => quote = None,
            Some(_) => {}
            None if byte == b'\'' || byte == b'"' => quote = Some(byte),
            None if call_bytes[position..].starts_with(root_bytes)
                && call_bytes
                    .get(position + root_bytes.len())
                    .is_none_or(|next| !(next.is_ascii_alphanumeric() || *next == b'_' || *next >= 0x80)) =>
            {
                return true;
            }
            None => {}
        }

        position += 1;
    }

    false
}

#[inline]
pub fn var_references_dynamic(var_id: Word, referenced_var_id: Word) -> bool {
    if is_narrowed_function_call(var_id.as_bytes()) {
        return narrowed_function_call_reads(var_id, referenced_var_id);
    }

    let var_bytes = var_id.as_bytes();
    let ref_bytes = referenced_var_id.as_bytes();
    if ref_bytes.len() >= var_bytes.len() {
        return false;
    }

    let mut start = 1;
    while start <= var_bytes.len() {
        let Some(pos) = memchr::memmem::find(&var_bytes[start..], ref_bytes) else {
            return false;
        };
        let end = start + pos + ref_bytes.len();
        if end == var_bytes.len() || (!var_bytes[end].is_ascii_alphanumeric() && var_bytes[end] != b'_') {
            return true;
        }

        start += pos + 1;
    }

    false
}

#[cfg(test)]
mod tests {
    use super::*;
    use mago_word::word;

    #[test]
    fn test_var_has_root() {
        assert!(var_has_root(word(b"$foo"), word(b"$foo")));
        assert!(var_has_root(word(b"$foo[bar]"), word(b"$foo")));
        assert!(var_has_root(word(b"$foo->bar"), word(b"$foo")));
        assert!(var_has_root(word(b"$foo::bar"), word(b"$foo")));
        assert!(var_has_root(word(b"$foo->bar[0]"), word(b"$foo")));
        assert!(var_has_root(word(b"$foo->bar[0]->baz"), word(b"$foo")));
        assert!(!var_has_root(word(b"$foo[bar]"), word(b"$bar")));
        assert!(var_has_root(word(b"$foo[bar]"), word(b"$foo[bar]")));
        assert!(!var_has_root(word(b"$foo[bar]"), word(b"$foo[bar][baz]")));
        assert!(!var_has_root(word(b"$foo[bar]"), word(b"$foo[bar][baz]")));
    }

    #[test]
    fn test_var_references_dynamic() {
        assert!(var_references_dynamic(word(b"$this->{$name}"), word(b"$name")));
        assert!(var_references_dynamic(word(b"$this->$name"), word(b"$name")));
        assert!(var_references_dynamic(word(b"$arr[$name]"), word(b"$name")));
        assert!(var_references_dynamic(word(b"$this->{$name}->foo"), word(b"$name")));
        assert!(!var_references_dynamic(word(b"$this->name"), word(b"$name")));
        assert!(!var_references_dynamic(word(b"$this->$names"), word(b"$name")));
        assert!(!var_references_dynamic(word(b"$name"), word(b"$name")));
        assert!(!var_references_dynamic(word(b"$name->foo"), word(b"$name")));
        assert!(!var_references_dynamic(word(b"$name[0]"), word(b"$name")));
    }

    #[test]
    fn test_narrowed_function_call_reads() {
        assert!(narrowed_function_call_reads(word(b"@intval($name)"), word(b"$name")));
        assert!(narrowed_function_call_reads(word(b"@count($rows['key'])"), word(b"$rows")));
        assert!(narrowed_function_call_reads(word(b"@intval('a', $name)"), word(b"$name")));
        assert!(!narrowed_function_call_reads(word(b"@intval($names)"), word(b"$name")));
        assert!(!narrowed_function_call_reads(word(b"@intval('$name')"), word(b"$name")));
        assert!(!narrowed_function_call_reads(word(b"@intval('it\\'s $name')"), word(b"$name")));
        assert!(!narrowed_function_call_reads(word(b"@intval(\"$name\")"), word(b"$name")));
        assert!(!narrowed_function_call_reads(word(b"$name"), word(b"$name")));
    }
}
