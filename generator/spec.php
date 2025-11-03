<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Spec;

use Brick\Math\BigDecimal;
use Typhoon\Type;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\Mask;
use Typhoon\Type\MixedT;
use Typhoon\Type\TemplateArgument;

$typeClass = '\\' . Type::class;
$templateArguments = prop('templateArguments', \sprintf('list<\%s>', TemplateArgument::class));
$bigDecimalClass = '\\' . BigDecimal::class;
$closureClass = '\\' . \Closure::class;
$maskClass = '\\' . Mask::class;
$mixedT = \sprintf('\%s::T', MixedT::class);
$arrayKeyT = \sprintf('\%s::T', ArrayKeyT::class);

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
    constr('intRange', 'T', [tpl('T', 'int')], [prop('min', 'int', 'PHP_INT_MIN'), prop('max', 'int', 'PHP_INT_MIN')]),
    single('negativeInt', 'negative-int', 'intRange(max: -1)'),
    single('nonPositiveInt', 'non-positive-int', 'intRange(max: 0)'),
    single('nonZeroInt', 'non-zero-int', 'union([negativeInt, positiveInt])'),
    single('nonNegativeInt', 'non-negative-int', 'intRange(min: 0)'),
    single('positiveInt', 'positive-int', 'intRange(min: 1)'),
    constr('bitmask', 'T', [tpl('T', 'int')], [prop('intType', $typeClass)]),
    // float
    single('float', 'float', 'floatRange()'),
    constr('floatValue', 'T', [tpl('T', 'float')], [prop('value', $bigDecimalClass)], 'floatRange($value, $value)'),
    constr('floatRange', 'T', [tpl('T', 'float')], [prop('min', '?' . $bigDecimalClass), prop('max', '?' . $bigDecimalClass)]),
    // string
    single('string', 'string'),
    single('nonEmptyString', 'non-empty-string'),
    single('truthyString', 'truthy-string'),
    single('numericString', 'numeric-string'),
    single('lowercaseString', 'lowercase-string'),
    single('literalString', 'literal-string'),
    constr('stringValue', 'T', [tpl('T', 'string')], [prop('value', 'T', nativeType: 'string')]),
    constr('class', 'class-string<T>', [tpl('T', 'object')], [prop('objectType', "{$typeClass}<T>")]),
    // scalar aliases
    single('arrayKey', 'array-key', 'union([int, string])'),
    single('numeric', 'numeric', 'union([int, float, numericString])'),
    single('scalar', 'scalar', 'union([bool, int, float, string])'),
    // array
    constr('list', 'T', [tpl('T', 'list')], [prop('valueType', $typeClass, $mixedT), prop('elementTypes', "list<{$typeClass}>"), prop('isNonEmpty', 'bool')]),
    single('arrayBare', 'array', 'array()'),
    constr('array', 'T', [tpl('T', 'array')], [prop('keyType', $typeClass, $arrayKeyT), prop('valueType', $typeClass, $mixedT), prop('elements', 'list<ArrayElement>'), prop('isNonEmpty', 'bool')]),
    // object
    single('objectBare', 'object', 'object()'),
    constr('namedObject', 'T', [tpl('T', 'object')], [prop('class', 'class-string<T>'), $templateArguments], 'object(supertypes: [$t])'),
    constr('object', 'T', [tpl('T', 'object')], [prop('templates', 'list<Template>'), prop('supertypes', 'list<NamedObjectT>'), prop('properties', 'list<Property>')]),
    constr('self', 'T', [tpl('T', 'object')], [$templateArguments]),
    constr('parent', 'T', [tpl('T', 'object')], [$templateArguments]),
    constr('static', 'T', [tpl('T', 'object')], [$templateArguments]),
    // iterable
    single('iterableBare', 'iterable', 'iterable()'),
    constr('iterable', 'iterable<K, V>', [tpl('K'), tpl('V')], [prop('keyType', "{$typeClass}<K>", $mixedT), prop('valueType', "{$typeClass}<V>", $mixedT)]),
    // callable
    single('callableBare', 'callable'),
    constr('callable', 'T', [tpl('T', 'callable')], [prop('templates', 'list<Template<Variance::Invariant>>'), prop('parameters', 'list<Parameter>'), prop('returnType', $typeClass, $mixedT)]),
    constr('closure', 'T', [tpl('T', $closureClass)], [prop('templates', 'list<Template<Variance::Invariant>>'), prop('parameters', 'list<Parameter>'), prop('returnType', $typeClass, $mixedT)], "intersection([\nnamedObject({$closureClass}::class),\ncallable(\$templates, \$parameters, \$returnType),\n])"),
    // resource
    single('resource', 'resource'),
    // intersection
    constr('intersection', 'T', [tpl('T')], [prop('types', "non-empty-list<{$typeClass}>")]),
    // union
    constr('union', 'T', [tpl('T')], [prop('types', "non-empty-list<{$typeClass}<T>>")]),
    // constant
    constr('constant', 'T', [tpl('T')], [prop('name', 'non-empty-string')]),
    constr('constantMask', 'T', [tpl('T')], [prop('mask', $maskClass)]),
    constr('classConstant', 'T', [tpl('T')], [prop('class', 'class-string'), prop('name', 'non-empty-string')]),
    constr('classConstantMask', 'T', [tpl('T')], [prop('class', 'class-string'), prop('mask', $maskClass)]),
    // array-access
    constr('keyOf', 'key-of<T>', [tpl('T')], [prop('arrayType', "{$typeClass}<T>")]),
    constr('valueOf', 'value-of<T>', [tpl('T')], [prop('arrayType', "{$typeClass}<T>")], 'offset($arrayType, keyOf($arrayType))'),
    constr('offset', 'T[K]', [tpl('T'), tpl('K')], [prop('arrayType', "{$typeClass}<T>"), prop('keyType', "{$typeClass}<K>")]),
    // relations
    constr('isSubtype', 'T', [tpl('T', 'bool')], [prop('leftType', $typeClass), prop('rightType', $typeClass)]),
    // ternary
    constr('ternary', 'T', [tpl('T', 'mixed')], [prop('conditionType', $typeClass), prop('thenType', $typeClass), prop('elseType', $typeClass)]),
    // alias
    constr('alias', 'T', [tpl('T')], [prop('class', 'class-string'), prop('name', 'non-empty-string'), $templateArguments]),
    // template
    // todo internal constructor
    constr('template', 'T', [tpl('T')], [prop('name', 'non-empty-string')]),
    // mixed
    single('untyped', 'mixed', 'mixed'),
    single('mixed', 'mixed', 'union([null, scalar, arrayBare, objectBare, resource])'),
];
