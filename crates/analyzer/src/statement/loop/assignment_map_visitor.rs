use std::collections::BTreeMap;
use std::collections::BTreeSet;

use mago_syntax::cst::ArgumentList;
use mago_syntax::cst::ArrowFunction;
use mago_syntax::cst::Assignment;
use mago_syntax::cst::Closure;
use mago_syntax::cst::Expression;
use mago_syntax::cst::ForeachTarget;
use mago_syntax::cst::MethodCall;
use mago_syntax::cst::MethodPartialApplication;
use mago_syntax::cst::Statement;
use mago_syntax::cst::UnaryPostfix;
use mago_syntax::cst::UnaryPrefix;
use mago_syntax::cst::Unset;
use mago_syntax::cst::Variable;
use mago_syntax::walker::MutWalker;
use mago_word::Word;
use mago_word::WordSet;
use mago_word::word;

use crate::utils::expression::get_root_expression_id;

pub fn get_assignment_map<'ast, 'arena>(
    pre_conditions: &[&'ast Expression<'arena>],
    post_expressions: &[&'ast Expression<'arena>],
    statements: &'ast [Statement<'arena>],
) -> (BTreeMap<Word, BTreeSet<Word>>, Option<Word>) {
    let mut walker = AssignmentMapWalker::default();

    for pre_condition in pre_conditions {
        walker.walk_expression(pre_condition, &mut ());
    }

    for statement in statements {
        walker.walk_statement(statement, &mut ());
    }

    for post_expression in post_expressions {
        walker.walk_expression(post_expression, &mut ());
    }

    let first_variable_id = walker.assignment_map.first_key_value().map(|(key, _)| *key);

    (walker.assignment_map, first_variable_id)
}

#[derive(Debug, Clone, Default)]
struct AssignmentMapWalker {
    assignment_map: BTreeMap<Word, BTreeSet<Word>>,
}

impl<'ast, 'arena> MutWalker<'ast, 'arena, ()> for AssignmentMapWalker {
    fn walk_unary_postfix(&mut self, unary_postfix: &'ast UnaryPostfix<'arena>, _context: &mut ()) {
        let root_expression_id = get_root_expression_id(unary_postfix.operand);

        if let Some(root_expression_id) = root_expression_id {
            self.assignment_map.entry(root_expression_id).or_default().insert(root_expression_id);
        }
    }

    fn walk_unary_prefix(&mut self, unary_prefix: &'ast UnaryPrefix<'arena>, context: &mut ()) {
        if unary_prefix.operator.is_increment_or_decrement() {
            let root_expression_id = get_root_expression_id(unary_prefix.operand);

            if let Some(root_expression_id) = root_expression_id {
                self.assignment_map.entry(root_expression_id).or_default().insert(root_expression_id);
            }
        } else {
            self.walk_expression(unary_prefix.operand, context);
        }
    }

    fn walk_assignment(&mut self, assignment: &'ast Assignment<'arena>, _context: &mut ()) {
        let right_expression_id = get_root_expression_id(assignment.rhs).unwrap_or_else(|| Word::from("isset"));

        if let Some(array_elements) = assignment.lhs.get_array_like_elements() {
            for array_element in array_elements {
                if let Some(expression) = array_element.get_value() {
                    let left_expression_id = get_root_expression_id(expression);

                    if let Some(left_expression_id) = left_expression_id {
                        self.assignment_map.entry(left_expression_id).or_default().insert(right_expression_id);
                    }
                }
            }
        } else {
            let left_expression_id = get_root_expression_id(assignment.lhs);

            if let Some(left_expression_id) = left_expression_id {
                self.assignment_map.entry(left_expression_id).or_default().insert(right_expression_id);
            }
        }
    }

    fn walk_in_argument_list(&mut self, argument_list: &'ast ArgumentList<'arena>, _context: &mut ()) {
        for argument in &argument_list.arguments {
            let root_expression_id = get_root_expression_id(argument.value());

            if let Some(root_expression_id) = root_expression_id {
                self.assignment_map.entry(root_expression_id).or_default().insert(root_expression_id);
            }
        }
    }

    fn walk_out_method_call(&mut self, method_call: &'ast MethodCall<'arena>, _context: &mut ()) {
        let root_expression_id = get_root_expression_id(method_call.object);

        if let Some(root_expression_id) = root_expression_id {
            self.assignment_map.entry(root_expression_id).or_default().insert(Word::from("isset"));
        }
    }

    fn walk_out_method_partial_application(
        &mut self,
        method_partial_application: &'ast MethodPartialApplication<'arena>,
        _context: &mut (),
    ) {
        let root_expression_id = get_root_expression_id(method_partial_application.object);

        if let Some(root_expression_id) = root_expression_id {
            self.assignment_map.entry(root_expression_id).or_default().insert(Word::from("isset"));
        }
    }

    fn walk_in_unset(&mut self, unset: &'ast Unset<'arena>, _context: &mut ()) {
        for unset_value in &unset.values {
            let root_expression_id = get_root_expression_id(unset_value);

            if let Some(root_expression_id) = root_expression_id {
                self.assignment_map.entry(root_expression_id).or_default().insert(root_expression_id);
            }
        }
    }

    // Prevent walking into closure and arrow function bodies
    fn walk_closure(&mut self, _closure: &'ast Closure<'arena>, _context: &mut ()) {}
    fn walk_arrow_function(&mut self, _arrow_function: &'ast ArrowFunction<'arena>, _context: &mut ()) {}
}

pub fn get_written_variables<'ast, 'arena>(
    pre_conditions: &[&'ast Expression<'arena>],
    post_expressions: &[&'ast Expression<'arena>],
    statements: &'ast [Statement<'arena>],
) -> WordSet {
    let mut walker = WrittenVariablesWalker::default();

    for expression in pre_conditions.iter().chain(post_expressions) {
        walker.walk_expression(expression, &mut ());
    }

    for statement in statements {
        walker.walk_statement(statement, &mut ());
    }

    walker.written_variables
}

#[derive(Debug, Default)]
struct WrittenVariablesWalker {
    written_variables: WordSet,
}

impl WrittenVariablesWalker {
    fn add_target(&mut self, target: &Expression<'_>) {
        if let Some(elements) = target.get_array_like_elements() {
            for element in elements {
                if let Some(value) = element.get_value() {
                    self.add_target(value);
                }
            }
        } else if let Expression::Variable(Variable::Direct(variable)) = target {
            self.written_variables.insert(word(variable.name));
        }
    }
}

impl<'ast, 'arena> MutWalker<'ast, 'arena, ()> for WrittenVariablesWalker {
    fn walk_in_assignment(&mut self, assignment: &'ast Assignment<'arena>, _context: &mut ()) {
        self.add_target(assignment.lhs);
    }

    fn walk_in_unary_prefix(&mut self, unary_prefix: &'ast UnaryPrefix<'arena>, _context: &mut ()) {
        if unary_prefix.operator.is_increment_or_decrement() {
            self.add_target(unary_prefix.operand);
        }
    }

    fn walk_in_unary_postfix(&mut self, unary_postfix: &'ast UnaryPostfix<'arena>, _context: &mut ()) {
        self.add_target(unary_postfix.operand);
    }

    fn walk_in_unset(&mut self, unset: &'ast Unset<'arena>, _context: &mut ()) {
        for value in &unset.values {
            self.add_target(value);
        }
    }

    fn walk_in_foreach_target(&mut self, foreach_target: &'ast ForeachTarget<'arena>, _context: &mut ()) {
        if let Some(key) = foreach_target.key() {
            self.add_target(key);
        }

        self.add_target(foreach_target.value());
    }

    fn walk_closure(&mut self, _closure: &'ast Closure<'arena>, _context: &mut ()) {}
    fn walk_arrow_function(&mut self, _arrow_function: &'ast ArrowFunction<'arena>, _context: &mut ()) {}
}
