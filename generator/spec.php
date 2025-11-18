<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Spec;

use Typhoon\Type;
use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\Mask;
use Typhoon\Type\MixedT;

$typeClass = '\\' . Type::class;
$closureClass = '\\' . \Closure::class;
$maskClass = '\\' . Mask::class;
$mixedT = \sprintf('\%s::T', MixedT::class);
$arrayKeyT = \sprintf('\%s::T', ArrayKeyT::class);
$templateArgumentsProp = prop('templateArguments', "list<{$typeClass}>");

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
    constr('bitmask', 'T', [tpl('T', 'int')], [prop('intType', $typeClass)]),
    // float
    single('float', 'float', 'floatRange()'),
    constr('floatValue', 'T', [tpl('T', 'float')], [prop('value', 'float')], 'floatRange($value, $value)'),
    constr('floatRange', 'T', [tpl('T', 'float')], [prop('min', '?float'), prop('max', '?float')]),
    // string
    single('string', 'string'),
    single('nonEmptyString', 'non-empty-string'),
    single('truthyString', 'truthy-string'),
    single('numericString', 'numeric-string'),
    single('lowercaseString', 'lowercase-string'),
    constr('stringValue', 'T', [tpl('T', 'string')], [prop('value', 'T', nativeType: 'string')]),
    constr('class', 'class-string<T>', [tpl('T', 'object')], [prop('objectType', "{$typeClass}<T>")]),
    // array
    constr('list', 'list<V>', [tpl('V')], [prop('valueType', "{$typeClass}<V>", $mixedT), prop('elementTypes', "list<{$typeClass}>"), prop('isNonEmpty', 'bool')]),
    single('arrayBare', 'array', 'array()'),
    constr('array', 'array<K, V>', [tpl('K', 'array-key'), tpl('V')], [prop('keyType', "{$typeClass}<K>", $arrayKeyT), prop('valueType', "{$typeClass}<V>", $mixedT), prop('elements', 'list<ArrayElement>'), prop('isNonEmpty', 'bool')]),
    // object
    single('object', 'object', 'objectShape()'),
    constr('namedObject', 'T', [tpl('T', 'object')], [prop('class', 'class-string<T>'), $templateArgumentsProp]),
    constr('objectShape', 'T', [tpl('T', 'object')], [prop('properties', 'list<Property>')]),
    // iterable
    single('iterableBare', 'iterable', 'iterable()'),
    constr('iterable', 'iterable<K, V>', [tpl('K'), tpl('V')], [prop('keyType', "{$typeClass}<K>", $mixedT), prop('valueType', "{$typeClass}<V>", $mixedT)]),
    // callable
    single('callableBare', 'callable'),
    constr('callable', 'T', [tpl('T', 'callable')], [prop('parameters', 'list<Parameter>'), prop('returnType', $typeClass, $mixedT)]),
    constr('closure', 'T', [tpl('T', $closureClass)], [prop('parameters', 'list<Parameter>'), prop('returnType', $typeClass, $mixedT)], "intersection([\nnamedObject({$closureClass}::class),\ncallable(\$parameters, \$returnType),\n])"),
    // resource
    single('resource', 'resource'),
    // constant
    constr('constant', 'T', [tpl('T')], [prop('name', 'non-empty-string')]),
    constr('constantMask', 'T', [tpl('T')], [prop('mask', $maskClass)]),
    constr('classConstant', 'T', [tpl('T')], [prop('class', 'class-string'), prop('name', 'non-empty-string')]),
    constr('classConstantMask', 'T', [tpl('T')], [prop('class', 'class-string'), prop('mask', $maskClass)]),
    // intersection
    constr('intersection', 'T', [tpl('T')], [prop('types', "non-empty-list<{$typeClass}>")]),
    // union
    constr('union', 'T', [tpl('T')], [prop('types', "non-empty-list<{$typeClass}<T>>")]),
    // union aliases
    single('arrayKey', 'array-key', 'union([int, string])'),
    single('numeric', 'numeric', 'union([int, float, numericString])'),
    single('scalar', 'scalar', 'union([bool, int, float, string])'),
    single('mixed', 'mixed', 'union([null, false, true, int, float, string, arrayBare, object, resource])'),
];
