use foldhash::HashMap;
use foldhash::HashSet;
use mago_algebra::clause::Clause;
use mago_span::Span;

/// A condition's formula key: the conditional object, the creating object and the condition expression.
pub type FormulaKey = (Span, Span, Span);

/// What the pass on docblock types derived, for the native pass to apply: PHPStan narrows its native types by
/// the conditions it resolves on the PHPDoc types, and ends flow at a call whose PHPDoc type is `never`.
#[derive(Debug, Default)]
pub struct DocblockFacts {
    /// `None` when the condition's formula differed between analyses, as it does across loop iterations.
    formulas: HashMap<FormulaKey, Option<Vec<Clause>>>,
    never_calls: HashSet<Span>,
}

impl DocblockFacts {
    pub fn record_formula(&mut self, key: FormulaKey, formula: &[Clause]) {
        self.formulas
            .entry(key)
            .and_modify(|recorded| {
                if recorded.as_deref() != Some(formula) {
                    *recorded = None;
                }
            })
            .or_insert_with(|| Some(formula.to_vec()));
    }

    /// The formula the pass on docblock types built for a condition, if it built the same one every time.
    #[must_use]
    pub fn formula(&self, key: &FormulaKey) -> Option<&Vec<Clause>> {
        self.formulas.get(key)?.as_ref()
    }

    pub fn record_never_call(&mut self, call_span: Span) {
        self.never_calls.insert(call_span);
    }

    #[must_use]
    pub fn is_never_call(&self, call_span: &Span) -> bool {
        self.never_calls.contains(call_span)
    }
}
