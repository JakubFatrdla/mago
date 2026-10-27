use std::cell::RefCell;

use mago_names::ResolvedNames;
use mago_word::Word;

use mago_codex::metadata::CodebaseMetadata;
use mago_syntax::cst::Expression;

use crate::context::docblock_facts::DocblockFacts;
use crate::utils::expression::get_expression_id;

#[derive(Debug)]
pub struct AssertionContext<'ctx, 'arena, A> {
    pub resolved_names: &'ctx ResolvedNames<'arena>,
    pub arena: &'arena A,
    pub codebase: &'ctx CodebaseMetadata,
    pub this_class_name: Option<Word>,
    pub trust_existence_checks: bool,
    /// Recorded by the pass on docblock types and applied by the native pass, see [`Self::native_types_only`].
    pub docblock_facts: Option<&'ctx RefCell<DocblockFacts>>,
    pub native_types_only: bool,
}

impl<A> AssertionContext<'_, '_, A> {
    #[inline]
    pub fn get_expression_id(&self, expression: &Expression<'_>) -> Option<Word> {
        get_expression_id(expression, self.this_class_name, self.resolved_names, Some(self.codebase))
    }
}

impl<A> Clone for AssertionContext<'_, '_, A> {
    fn clone(&self) -> Self {
        *self
    }
}

impl<A> Copy for AssertionContext<'_, '_, A> {}
