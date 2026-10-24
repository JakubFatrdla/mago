use std::collections::BTreeMap;

use mago_codex::ttype::atomic::TAtomic;
use mago_codex::ttype::atomic::array::TArray;
use mago_codex::ttype::atomic::array::keyed::TKeyedArray;
use mago_codex::ttype::union::TUnion;
use mago_codex::ttype::wrap_atomic;

use crate::plugin::context::InvocationInfo;
use crate::plugin::context::ProviderContext;
use crate::plugin::provider::Provider;
use crate::plugin::provider::ProviderMeta;
use crate::plugin::provider::function::FunctionReturnTypeProvider;
use crate::plugin::provider::function::FunctionTarget;

static META: ProviderMeta = ProviderMeta::new(
    "php::array::array_fill_keys",
    "array_fill_keys",
    "Returns a shape with exactly the given literal keys, like PHPStan",
);

#[derive(Default)]
pub struct ArrayFillKeysProvider;

impl Provider for ArrayFillKeysProvider {
    fn meta() -> &'static ProviderMeta {
        &META
    }
}

impl FunctionReturnTypeProvider for ArrayFillKeysProvider {
    fn targets() -> FunctionTarget {
        FunctionTarget::Exact(b"array_fill_keys")
    }

    fn get_return_type(
        &self,
        context: &ProviderContext<'_, '_, '_>,
        invocation: &InvocationInfo<'_, '_, '_>,
    ) -> Option<TUnion> {
        let keys_type = context.get_expression_type(invocation.get_argument(0, &[b"keys"])?)?;
        let value_type = context.get_expression_type(invocation.get_argument(1, &[b"value"])?)?;

        if !keys_type.is_single() {
            return None;
        }

        let keys = sealed_values(keys_type.get_single())?;
        let mut known_items = BTreeMap::new();
        for (optional, key_type) in keys {
            known_items.insert(key_type.get_single_array_key()?, (optional, value_type.clone()));
        }

        let non_empty = known_items.values().any(|(optional, _)| !optional);
        let mut shape = TKeyedArray::new().with_known_items(known_items);
        shape.non_empty = non_empty;

        Some(wrap_atomic(TAtomic::Array(TArray::Keyed(shape))))
    }
}

fn sealed_values(keys: &TAtomic) -> Option<Vec<(bool, &TUnion)>> {
    match keys {
        TAtomic::Array(TArray::List(list)) if list.element_type.is_never() => {
            Some(list.known_elements.as_ref()?.values().map(|(optional, value)| (*optional, value)).collect())
        }
        TAtomic::Array(TArray::Keyed(keyed)) if keyed.parameters.is_none() => {
            Some(keyed.known_items.as_ref()?.values().map(|(optional, value)| (*optional, value)).collect())
        }
        _ => None,
    }
}
