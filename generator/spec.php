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
    constr('intMask', 'T', [tpl('T', 'int')], [prop('ints', 'Type')]),
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
    constr('classString', 'class-string<T>', [tpl('T', 'object')], [prop('object', 'Type<T>')]),
    single('literalString', 'literal-string', 'literal(string)'),
    // scalar aliases
    single('arrayKey', 'array-key', 'union([int, string])'),
    single('numeric', 'numeric', 'union([int, float, numericString])'),
    single('scalar', 'scalar', 'union([bool, int, float, string])'),
    // array
    constr('list', 'list<V>', [tpl('V')], [prop('value', 'Type<V>', MixedT::T), prop('elements', 'list<ArrayElement>'), prop('isNonEmpty', 'bool')]),
    single('arrayDefault', 'array', 'array()'),
    constr('array', 'array<K, V>', [tpl('K', 'array-key'), tpl('V')], [prop('key', 'Type<K>', ArrayKeyT::T), prop('value', 'Type<V>', MixedT::T), prop('elements', 'array<ArrayElement>'), prop('isNonEmpty', 'bool')]),
    // object
    single('objectDefault', 'object', 'object()'),
    constr('namedObject', 'T', [tpl('T', 'object')], [prop('class', 'class-string<T>'), prop('templateArguments', 'list<Type>')], 'object(superTypes: [$t])'),
    constr('object', 'T', [tpl('T', 'object')], [prop('templates', 'list<Template>'), prop('superTypes', 'list<NamedObjectT>'), prop('properties', 'list<Property>')]),
    single('selfDefault', 'object', 'self()'),
    constr('self', 'T', [tpl('T', 'object')], [prop('templateArguments', 'list<Type>')]),
    single('parentDefault', 'object', 'parent()'),
    constr('parent', 'T', [tpl('T', 'object')], [prop('templateArguments', 'list<Type>')]),
    single('staticDefault', 'object', 'static()'),
    constr('static', 'T', [tpl('T', 'object')], [prop('templateArguments', 'list<Type>')]),
    // iterable
    single('iterableDefault', 'iterable', 'iterable()'),
    constr('iterable', 'iterable<K, V>', [tpl('K'), tpl('V')], [prop('key', 'Type<K>', MixedT::T), prop('value', 'Type<V>', MixedT::T)]),
    // callable
    single('callableDefault', 'callable', 'callable()'),
    constr('callable', 'T', [tpl('T', 'callable')], [prop('templates', 'list<Template<Variance::Invariant>>'), prop('parameters', 'list<Parameter>'), prop('returns', 'Type', MixedT::T)]),
    single('closureDefault', 'Closure', 'namedObject(Closure::class)'),
    constr('closure', 'T', [tpl('T', 'Closure')], [prop('templates', 'list<Template<Variance::Invariant>>'), prop('parameters', 'list<Parameter>'), prop('returns', 'Type', MixedT::T)], "intersection([\nclosureDefault,\ncallable(\$templates, \$parameters, \$returns),\n])"),
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
    constr('classConstant', 'T', [tpl('T')], [prop('class', 'Type'), prop('name', 'non-empty-string')]),
    constr('classConstantMask', 'T', [tpl('T')], [prop('class', 'Type'), prop('namePrefix', 'string')]),
    // array-access
    constr('key', 'key-of<T>', [tpl('T')], [prop('array', 'Type<T>')]),
    constr('value', 'value-of<T>', [tpl('T')], [prop('array', 'Type<T>')], 'offset($array, key($array))'),
    constr('offset', 'T[K]', [tpl('T'), tpl('K')], [prop('array', 'Type<T>'), prop('key', 'Type<K>')]),
    // relations
    constr('isSubtype', 'T', [tpl('T', 'bool')], [prop('left', 'Type'), prop('right', 'Type')]),
    constr('isSupertype', 'T', [tpl('T', 'bool')], [prop('left', 'Type'), prop('right', 'Type')], 'isSubtype($right, $left)'),
    // ternary
    constr('ternary', 'Then|Else', [tpl('Then'), tpl('Else')], [prop('condition', 'Type<bool>'), prop('then', 'Type<Then>'), prop('else', 'Type<Else>')]),
    // alias
    constr('alias', 'T', [tpl('T')], [prop('class', 'class-string'), prop('name', 'non-empty-string'), prop('templateArguments', 'list<Type>')]),
    // template
    constr('template', 'T', [tpl('T')]),
    // mixed
    single('mixed', 'mixed'),
];
