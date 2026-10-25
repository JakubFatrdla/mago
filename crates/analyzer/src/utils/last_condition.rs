use foldhash::HashSet;

use mago_span::HasSpan;
use mago_span::Span;
use mago_syntax::cst::Block;
use mago_syntax::cst::DoWhile;
use mago_syntax::cst::Expression;
use mago_syntax::cst::For;
use mago_syntax::cst::ForBody;
use mago_syntax::cst::Foreach;
use mago_syntax::cst::ForeachBody;
use mago_syntax::cst::Function;
use mago_syntax::cst::If;
use mago_syntax::cst::IfBody;
use mago_syntax::cst::Match;
use mago_syntax::cst::MatchArm;
use mago_syntax::cst::Method;
use mago_syntax::cst::MethodBody;
use mago_syntax::cst::Namespace;
use mago_syntax::cst::NamespaceBody;
use mago_syntax::cst::Program;
use mago_syntax::cst::Statement;
use mago_syntax::cst::SwitchDefaultCase;
use mago_syntax::cst::SwitchExpressionCase;
use mago_syntax::cst::Try;
use mago_syntax::cst::TryCatchClause;
use mago_syntax::cst::TryFinallyClause;
use mago_syntax::cst::While;
use mago_syntax::cst::WhileBody;
use mago_syntax::walker::MutWalker;

/// The conditions PHPStan's `LastConditionVisitor` marks as last, where an always-true check is not reported:
/// the last `elseif` of an `if` whose `else` is missing or only throws, the last condition of the last `match`
/// arm, and the condition of an `if` without `else`, or of its last `elseif`, that a closing `throw` follows.
pub fn collect_last_conditions(program: &Program<'_>) -> HashSet<Span> {
    let mut last_conditions = HashSet::default();
    LastConditionCollector.walk_program(program, &mut last_conditions);

    last_conditions
}

struct LastConditionCollector;

impl<'ast, 'arena> MutWalker<'ast, 'arena, HashSet<Span>> for LastConditionCollector {
    fn walk_in_if(&mut self, r#if: &'ast If<'arena>, last_conditions: &mut HashSet<Span>) {
        let (else_if_conditions, else_statements): (Vec<&Expression<'_>>, Option<Vec<&Statement<'_>>>) =
            match &r#if.body {
                IfBody::Statement(body) => (
                    body.else_if_clauses.iter().map(|clause| clause.condition).collect(),
                    body.else_clause.as_ref().map(|clause| statement_list(clause.statement)),
                ),
                IfBody::ColonDelimited(body) => (
                    body.else_if_clauses.iter().map(|clause| clause.condition).collect(),
                    body.else_clause.as_ref().map(|clause| clause.statements.iter().collect()),
                ),
            };

        if let Some(last_else_if_condition) = else_if_conditions.last()
            && else_statements
                .is_none_or(|statements| matches!(statements.as_slice(), [statement] if is_throw(statement)))
        {
            mark(last_conditions, last_else_if_condition);
        }

        match &r#if.body {
            IfBody::Statement(body) => {
                mark_trailing_if(last_conditions, &statement_list(body.statement));
                for clause in body.else_if_clauses.iter() {
                    mark_trailing_if(last_conditions, &statement_list(clause.statement));
                }

                if let Some(clause) = &body.else_clause {
                    mark_trailing_if(last_conditions, &statement_list(clause.statement));
                }
            }
            IfBody::ColonDelimited(body) => {
                mark_trailing_if(last_conditions, &body.statements.iter().collect::<Vec<_>>());
                for clause in body.else_if_clauses.iter() {
                    mark_trailing_if(last_conditions, &clause.statements.iter().collect::<Vec<_>>());
                }

                if let Some(clause) = &body.else_clause {
                    mark_trailing_if(last_conditions, &clause.statements.iter().collect::<Vec<_>>());
                }
            }
        }
    }

    fn walk_in_match(&mut self, r#match: &'ast Match<'arena>, last_conditions: &mut HashSet<Span>) {
        if let Some(MatchArm::Expression(arm)) = r#match.arms.iter().last()
            && let Some(condition) = arm.conditions.iter().last()
        {
            mark(last_conditions, condition);
        }
    }

    fn walk_in_function(&mut self, function: &'ast Function<'arena>, last_conditions: &mut HashSet<Span>) {
        mark_trailing_if_in_block(last_conditions, &function.body);
    }

    fn walk_in_method(&mut self, method: &'ast Method<'arena>, last_conditions: &mut HashSet<Span>) {
        if let MethodBody::Concrete(block) = &method.body {
            mark_trailing_if_in_block(last_conditions, block);
        }
    }

    fn walk_in_namespace(&mut self, namespace: &'ast Namespace<'arena>, last_conditions: &mut HashSet<Span>) {
        match &namespace.body {
            NamespaceBody::Implicit(body) => {
                mark_trailing_if(last_conditions, &body.statements.iter().collect::<Vec<_>>());
            }
            NamespaceBody::BraceDelimited(block) => mark_trailing_if_in_block(last_conditions, block),
        }
    }

    fn walk_in_switch_expression_case(
        &mut self,
        case: &'ast SwitchExpressionCase<'arena>,
        last_conditions: &mut HashSet<Span>,
    ) {
        mark_trailing_if(last_conditions, &case.statements.iter().collect::<Vec<_>>());
    }

    fn walk_in_switch_default_case(
        &mut self,
        case: &'ast SwitchDefaultCase<'arena>,
        last_conditions: &mut HashSet<Span>,
    ) {
        mark_trailing_if(last_conditions, &case.statements.iter().collect::<Vec<_>>());
    }

    fn walk_in_try(&mut self, r#try: &'ast Try<'arena>, last_conditions: &mut HashSet<Span>) {
        mark_trailing_if_in_block(last_conditions, &r#try.block);
    }

    fn walk_in_try_catch_clause(&mut self, clause: &'ast TryCatchClause<'arena>, last_conditions: &mut HashSet<Span>) {
        mark_trailing_if_in_block(last_conditions, &clause.block);
    }

    fn walk_in_try_finally_clause(
        &mut self,
        clause: &'ast TryFinallyClause<'arena>,
        last_conditions: &mut HashSet<Span>,
    ) {
        mark_trailing_if_in_block(last_conditions, &clause.block);
    }

    fn walk_in_do_while(&mut self, do_while: &'ast DoWhile<'arena>, last_conditions: &mut HashSet<Span>) {
        mark_trailing_if(last_conditions, &statement_list(do_while.statement));
    }

    fn walk_in_while(&mut self, r#while: &'ast While<'arena>, last_conditions: &mut HashSet<Span>) {
        match &r#while.body {
            WhileBody::Statement(statement) => mark_trailing_if(last_conditions, &statement_list(statement)),
            WhileBody::ColonDelimited(body) => {
                mark_trailing_if(last_conditions, &body.statements.iter().collect::<Vec<_>>());
            }
        }
    }

    fn walk_in_for(&mut self, r#for: &'ast For<'arena>, last_conditions: &mut HashSet<Span>) {
        match &r#for.body {
            ForBody::Statement(statement) => mark_trailing_if(last_conditions, &statement_list(statement)),
            ForBody::ColonDelimited(body) => {
                mark_trailing_if(last_conditions, &body.statements.iter().collect::<Vec<_>>());
            }
        }
    }

    fn walk_in_foreach(&mut self, foreach: &'ast Foreach<'arena>, last_conditions: &mut HashSet<Span>) {
        match &foreach.body {
            ForeachBody::Statement(statement) => mark_trailing_if(last_conditions, &statement_list(statement)),
            ForeachBody::ColonDelimited(body) => {
                mark_trailing_if(last_conditions, &body.statements.iter().collect::<Vec<_>>());
            }
        }
    }
}

/// The statements PHPStan's parser keeps for a body: those of a block, or the single statement.
fn statement_list<'ast, 'arena>(statement: &'ast Statement<'arena>) -> Vec<&'ast Statement<'arena>> {
    match statement {
        Statement::Block(block) => block.statements.iter().collect(),
        statement => vec![statement],
    }
}

fn mark_trailing_if_in_block(last_conditions: &mut HashSet<Span>, block: &Block<'_>) {
    mark_trailing_if(last_conditions, &block.statements.iter().collect::<Vec<_>>());
}

fn mark_trailing_if(last_conditions: &mut HashSet<Span>, statements: &[&Statement<'_>]) {
    let [.., Statement::If(r#if), last] = statements else {
        return;
    };

    if !is_throw(last) {
        return;
    }

    let condition = match &r#if.body {
        IfBody::Statement(body) if body.else_clause.is_none() => {
            body.else_if_clauses.iter().last().map_or(r#if.condition, |clause| clause.condition)
        }
        IfBody::ColonDelimited(body) if body.else_clause.is_none() => {
            body.else_if_clauses.iter().last().map_or(r#if.condition, |clause| clause.condition)
        }
        _ => return,
    };

    mark(last_conditions, condition);
}

fn is_throw(statement: &Statement<'_>) -> bool {
    matches!(statement, Statement::Expression(expression) if matches!(expression.expression, Expression::Throw(_)))
}

fn mark(last_conditions: &mut HashSet<Span>, condition: &Expression<'_>) {
    last_conditions.insert(condition.unparenthesized().span());
}
