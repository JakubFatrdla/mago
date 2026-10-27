use mago_syntax::cst::Access;
use mago_syntax::cst::ArrayElement;
use mago_syntax::cst::Assignment;
use mago_syntax::cst::ClassLikeMemberSelector;
use mago_syntax::cst::Expression;
use mago_syntax::cst::ForeachKeyValueTarget;
use mago_syntax::cst::ForeachValueTarget;
use mago_syntax::cst::Method;
use mago_syntax::cst::NamedArgument;
use mago_syntax::cst::PositionalArgument;
use mago_syntax::cst::UnaryPostfix;
use mago_syntax::cst::UnaryPrefix;
use mago_syntax::cst::UnaryPrefixOperator;
use mago_syntax::cst::Unset;
use mago_syntax::cst::Variable;
use mago_syntax::walker::Walker;
use mago_word::WordSet;
use mago_word::concat_word;
use mago_word::word;

/// The properties a method body may write: assigned, incremented, unset, iterated into, referenced or passed
/// as an argument, which may be by reference.
#[derive(Debug, Default)]
pub(super) struct PropertyWrites {
    pub names: WordSet,
    /// A property is written by a computed name, so any property may be.
    pub any: bool,
}

pub(super) fn collect_property_writes(method: &Method<'_>, writes: &mut PropertyWrites) {
    PropertyWriteCollector.walk_method(method, writes);
}

struct PropertyWriteCollector;

impl<'ast, 'arena> Walker<'ast, 'arena, PropertyWrites> for PropertyWriteCollector {
    fn walk_in_assignment(&self, assignment: &'ast Assignment<'arena>, writes: &mut PropertyWrites) {
        writes.add_target(assignment.lhs);
    }

    fn walk_in_unary_prefix(&self, unary_prefix: &'ast UnaryPrefix<'arena>, writes: &mut PropertyWrites) {
        if matches!(
            unary_prefix.operator,
            UnaryPrefixOperator::PreIncrement(_)
                | UnaryPrefixOperator::PreDecrement(_)
                | UnaryPrefixOperator::Reference(_)
        ) {
            writes.add_target(unary_prefix.operand);
        }
    }

    fn walk_in_unary_postfix(&self, unary_postfix: &'ast UnaryPostfix<'arena>, writes: &mut PropertyWrites) {
        writes.add_target(unary_postfix.operand);
    }

    fn walk_in_unset(&self, unset: &'ast Unset<'arena>, writes: &mut PropertyWrites) {
        for value in unset.values.iter() {
            writes.add_target(value);
        }
    }

    fn walk_in_foreach_value_target(&self, target: &'ast ForeachValueTarget<'arena>, writes: &mut PropertyWrites) {
        writes.add_target(target.value);
    }

    fn walk_in_foreach_key_value_target(
        &self,
        target: &'ast ForeachKeyValueTarget<'arena>,
        writes: &mut PropertyWrites,
    ) {
        writes.add_target(target.key);
        writes.add_target(target.value);
    }

    fn walk_in_positional_argument(&self, argument: &'ast PositionalArgument<'arena>, writes: &mut PropertyWrites) {
        writes.add_target(argument.value);
    }

    fn walk_in_named_argument(&self, argument: &'ast NamedArgument<'arena>, writes: &mut PropertyWrites) {
        writes.add_target(argument.value);
    }
}

impl PropertyWrites {
    fn add_target(&mut self, target: &Expression<'_>) {
        match target {
            Expression::Parenthesized(parenthesized) => self.add_target(parenthesized.expression),
            Expression::ArrayAccess(array_access) => self.add_target(array_access.array),
            Expression::ArrayAppend(array_append) => self.add_target(array_append.array),
            Expression::UnaryPrefix(unary_prefix)
                if matches!(unary_prefix.operator, UnaryPrefixOperator::Reference(_)) =>
            {
                self.add_target(unary_prefix.operand);
            }
            Expression::Array(array) => array.elements.iter().for_each(|element| self.add_element(element)),
            Expression::List(list) => list.elements.iter().for_each(|element| self.add_element(element)),
            Expression::Access(Access::Property(property_access)) if is_this(property_access.object) => {
                match &property_access.property {
                    ClassLikeMemberSelector::Identifier(identifier) => {
                        self.names.insert(concat_word!(b"$", identifier.value));
                    }
                    _ => self.any = true,
                }
            }
            Expression::Access(Access::StaticProperty(static_property_access)) => {
                match &static_property_access.property {
                    Variable::Direct(variable) => {
                        self.names.insert(word(variable.name));
                    }
                    _ => self.any = true,
                }
            }
            _ => {}
        }
    }

    fn add_element(&mut self, element: &ArrayElement<'_>) {
        match element {
            ArrayElement::KeyValue(element) => self.add_target(element.value),
            ArrayElement::Value(element) => self.add_target(element.value),
            _ => {}
        }
    }
}

fn is_this(expression: &Expression<'_>) -> bool {
    matches!(expression, Expression::Variable(Variable::Direct(variable)) if variable.name == b"$this")
}
