<?php

declare(strict_types=1);

namespace Typhoon\Type\Generator\Spec;

use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\MixedT;

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
    constr('bitmask', 'T', [tpl('T', 'int')], [prop('intType', 'Type')]),
    // float
    single('float', 'float', 'floatRange()'),
    constr('floatValue', 'T', [tpl('T', 'float')], [prop('value', 'numeric-string')], 'floatRange($value, $value)'),
    constr('floatRange', 'T', [tpl('T', 'float')], [prop('min', '?numeric-string'), prop('max', '?numeric-string')]),
    // string
    single('string', 'string'),
    single('nonEmptyString', 'non-empty-string'),
    single('truthyString', 'truthy-string'),
    single('numericString', 'numeric-string'),
    single('lowercaseString', 'lowercase-string'),
    constr('stringValue', 'T', [tpl('T', 'string')], [prop('value', 'T', nativeType: 'string')]),
    constr('class', 'class-string<T>', [tpl('T', 'object')], [prop('objectType', 'Type<T>')]),
    single('literalString', 'literal-string', 'literal(string)'),
    // scalar aliases
    single('arrayKey', 'array-key', 'union([int, string])'),
    single('numeric', 'numeric', 'union([int, float, numericString])'),
    single('scalar', 'scalar', 'union([bool, int, float, string])'),
    // array
    constr('list', 'T', [tpl('T', 'list')], [prop('valueType', 'Type', MixedT::T), prop('elements', 'list<Type>'), prop('isNonEmpty', 'bool')]),
    single('arrayDefault', 'array', 'array()'),
    constr('array', 'T', [tpl('T', 'array')], [prop('keyType', 'Type', ArrayKeyT::T), prop('valueType', 'Type', MixedT::T), prop('elements', 'list<ArrayElement>'), prop('isNonEmpty', 'bool')]),
    // object
    single('objectDefault', 'object', 'object()'),
    constr('namedObject', 'T', [tpl('T', 'object')], [prop('class', 'class-string<T>'), prop('templateArguments', 'list<Type>')], 'object(supertypes: [$t])'),
    constr('object', 'T', [tpl('T', 'object')], [prop('templates', 'list<Template>'), prop('supertypes', 'list<NamedObjectT>'), prop('properties', 'list<Property>')]),
    single('selfDefault', 'object', 'self()'),
    constr('self', 'T', [tpl('T', 'object')], [prop('templateArguments', 'list<Type>')]),
    single('parentDefault', 'object', 'parent()'),
    constr('parent', 'T', [tpl('T', 'object')], [prop('templateArguments', 'list<Type>')]),
    single('staticDefault', 'object', 'static()'),
    constr('static', 'T', [tpl('T', 'object')], [prop('templateArguments', 'list<Type>')]),
    // iterable
    single('iterableDefault', 'iterable', 'iterable()'),
    constr('iterable', 'iterable<K, V>', [tpl('K'), tpl('V')], [prop('keyType', 'Type<K>', MixedT::T), prop('valueType', 'Type<V>', MixedT::T)]),
    // callable
    single('callableDefault', 'callable', 'callable()'),
    constr('callable', 'T', [tpl('T', 'callable')], [prop('templates', 'list<Template<Variance::Invariant>>'), prop('parameters', 'list<Parameter>'), prop('returnType', 'Type', MixedT::T)]),
    single('closureDefault', 'Closure', 'namedObject(Closure::class)'),
    constr('closure', 'T', [tpl('T', 'Closure')], [prop('templates', 'list<Template<Variance::Invariant>>'), prop('parameters', 'list<Parameter>'), prop('returnType', 'Type', MixedT::T)], "intersection([\nclosureDefault,\ncallable(\$templates, \$parameters, \$returnType),\n])"),
    // resource
    single('resource', 'resource'),
    // intersection
    constr('intersection', 'T', [tpl('T')], [prop('types', 'non-empty-list<Type>')]),
    // union
    constr('union', 'T', [tpl('T')], [prop('types', 'non-empty-list<Type<T>>')]),
    // literal
    constr('literal', 'T', [tpl('T')], [prop('type', 'Type<T>')]),
    // constant
    constr('constant', 'T', [tpl('T')], [prop('name', 'non-empty-string')]),
    constr('classConstant', 'T', [tpl('T')], [prop('classType', 'Type'), prop('name', 'non-empty-string')]),
    constr('classConstantMask', 'T', [tpl('T')], [prop('classType', 'Type'), prop('mask', 'non-empty-string')]),
    // array-access
    constr('keyOf', 'key-of<T>', [tpl('T')], [prop('arrayType', 'Type<T>')]),
    constr('valueOf', 'value-of<T>', [tpl('T')], [prop('arrayType', 'Type<T>')], 'offset($arrayType, keyOf($arrayType))'),
    constr('offset', 'T[K]', [tpl('T'), tpl('K')], [prop('arrayType', 'Type<T>'), prop('keyType', 'Type<K>')]),
    // relations
    constr('isSubtype', 'T', [tpl('T', 'bool')], [prop('leftType', 'Type'), prop('rightType', 'Type')]),
    // ternary
    constr('ternary', 'Then|Else', [tpl('Then'), tpl('Else')], [prop('conditionType', 'Type<bool>'), prop('thenType', 'Type<Then>'), prop('elseType', 'Type<Else>')]),
    // alias
    constr('alias', 'T', [tpl('T')], [prop('class', 'class-string'), prop('name', 'non-empty-string'), prop('templateArguments', 'list<Type>')]),
    // template
    constr('template', 'T', [tpl('T')]),
    // mixed
    single('mixed', 'mixed'),
];
