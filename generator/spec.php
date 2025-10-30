<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Spec;

use Brick\Math\BigNumber;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\Mask;
use Typhoon\Type\MixedT;
use Typhoon\Type\Type;

$type = '\\' . Type::class;
$bigNumber = '\\' . BigNumber::class;
$closure = '\\' . \Closure::class;
$mask = '\\' . Mask::class;

return [
    single('never', 'never'),
    single('void', 'void'),
    single('null', 'null'),
    // bool
    single('false', 'false'),
    single('true', 'true'),
    single('bool', 'bool', 'union([false, true])'),
    // int
    single('int', 'int', 'intRange()'),
    constr('intValue', 'T', [tpl('T', 'int')], [prop('value', 'T', nativeType: 'int')], 'intRange($value, $value)'),
    constr('intRange', 'T', [tpl('T', 'int')], [prop('min', '?int'), prop('max', '?int')]),
    single('negativeInt', 'negative-int', 'intRange(max: -1)'),
    single('nonPositiveInt', 'non-positive-int', 'intRange(max: 0)'),
    single('nonZeroInt', 'non-zero-int', 'union([negativeInt, positiveInt])'),
    single('nonNegativeInt', 'non-negative-int', 'intRange(min: 0)'),
    single('positiveInt', 'positive-int', 'intRange(min: 1)'),
    constr('bitmask', 'T', [tpl('T', 'int')], [prop('intType', $type)]),
    // float
    single('float', 'float', 'floatRange()'),
    constr('floatValue', 'T', [tpl('T', 'float')], [prop('value', $bigNumber)], 'floatRange($value, $value)'),
    constr('floatRange', 'T', [tpl('T', 'float')], [prop('min', '?' . $bigNumber), prop('max', '?' . $bigNumber)]),
    // string
    single('string', 'string'),
    single('nonEmptyString', 'non-empty-string'),
    single('truthyString', 'truthy-string'),
    single('numericString', 'numeric-string'),
    single('lowercaseString', 'lowercase-string'),
    single('literalString', 'literal-string'),
    constr('stringValue', 'T', [tpl('T', 'string')], [prop('value', 'T', nativeType: 'string')]),
    constr('class', 'class-string<T>', [tpl('T', 'object')], [prop('objectType', "{$type}<T>")]),
    // scalar aliases
    single('arrayKey', 'array-key', 'union([int, string])'),
    single('numeric', 'numeric', 'union([int, float, numericString])'),
    single('scalar', 'scalar', 'union([bool, int, float, string])'),
    // array
    constr('list', 'T', [tpl('T', 'list')], [prop('valueType', $type, MixedT::T), prop('elementTypes', "list<{$type}>"), prop('isNonEmpty', 'bool')]),
    single('arrayDefault', 'array', 'array()'),
    constr('array', 'T', [tpl('T', 'array')], [prop('keyType', $type, ArrayKeyT::T), prop('valueType', $type, MixedT::T), prop('elements', 'list<ArrayElement>'), prop('isNonEmpty', 'bool')]),
    // object
    single('objectDefault', 'object', 'object()'),
    constr('namedObject', 'T', [tpl('T', 'object')], [prop('class', 'class-string<T>'), prop('templateArguments', "list<{$type}>")], 'object(supertypes: [$t])'),
    constr('object', 'T', [tpl('T', 'object')], [prop('templates', 'list<Template>'), prop('supertypes', 'list<NamedObjectT>'), prop('properties', 'list<Property>')]),
    constr('self', 'T', [tpl('T', 'object')], [prop('templateArguments', "list<{$type}>")]),
    constr('parent', 'T', [tpl('T', 'object')], [prop('templateArguments', "list<{$type}>")]),
    constr('static', 'T', [tpl('T', 'object')], [prop('templateArguments', "list<{$type}>")]),
    // iterable
    single('iterableDefault', 'iterable', 'iterable()'),
    constr('iterable', 'iterable<K, V>', [tpl('K'), tpl('V')], [prop('keyType', "{$type}<K>", MixedT::T), prop('valueType', "{$type}<V>", MixedT::T)]),
    // callable
    single('callableDefault', 'callable', 'callable()'),
    constr('callable', 'T', [tpl('T', 'callable')], [prop('templates', 'list<Template<Variance::Invariant>>'), prop('parameters', 'list<Parameter>'), prop('returnType', $type, MixedT::T)]),
    constr('closure', 'T', [tpl('T', $closure)], [prop('templates', 'list<Template<Variance::Invariant>>'), prop('parameters', 'list<Parameter>'), prop('returnType', $type, MixedT::T)], "intersection([\nnamedObject({$closure}::class),\ncallable(\$templates, \$parameters, \$returnType),\n])"),
    // resource
    single('resource', 'resource'),
    // intersection
    constr('intersection', 'T', [tpl('T')], [prop('types', "non-empty-list<{$type}>")]),
    // union
    constr('union', 'T', [tpl('T')], [prop('types', "non-empty-list<{$type}<T>>")]),
    // constant
    constr('constant', 'T', [tpl('T')], [prop('name', 'non-empty-string')]),
    constr('constantMask', 'T', [tpl('T')], [prop('mask', $mask)]),
    constr('classConstant', 'T', [tpl('T')], [prop('class', 'class-string'), prop('name', 'non-empty-string')]),
    constr('classConstantMask', 'T', [tpl('T')], [prop('class', 'class-string'), prop('mask', $mask)]),
    // array-access
    constr('keyOf', 'key-of<T>', [tpl('T')], [prop('arrayType', "{$type}<T>")]),
    constr('valueOf', 'value-of<T>', [tpl('T')], [prop('arrayType', "{$type}<T>")], 'offset($arrayType, keyOf($arrayType))'),
    constr('offset', 'T[K]', [tpl('T'), tpl('K')], [prop('arrayType', "{$type}<T>"), prop('keyType', "{$type}<K>")]),
    // relations
    constr('isSubtype', 'T', [tpl('T', 'bool')], [prop('leftType', $type), prop('rightType', $type)]),
    // ternary
    constr('ternary', 'T', [tpl('T', 'mixed')], [prop('conditionType', $type), prop('thenType', $type), prop('elseType', $type)]),
    // alias
    constr('alias', 'T', [tpl('T')], [prop('class', 'class-string'), prop('name', 'non-empty-string'), prop('templateArguments', "list<{$type}>")]),
    // template
    constr('template', 'T', [tpl('T')]),
    // mixed
    single('mixed', 'mixed', 'union([null, scalar, arrayDefault, objectDefault, resource])'),
];
