<?php

declare(strict_types=1);

namespace Typhoon\TypeGenerator;

use Typhoon\Type\ArrayKeyT;
use Typhoon\Type\MixedT;

require_once __DIR__ . '/TypeSpec.php';

return [
    type('never', 'never'),
    type('void', 'void'),
    type('null', 'null'),
    // bool
    type('false', 'false'),
    type('true', 'true'),
    // int
    type('intRange', 'int')->prop('min', 'null|int|numeric-string')->prop('max', 'null|int|numeric-string'),
    type('intMaskOf', 'positive-int')->prop('of', 'Type'),
    // float
    type('floatRange', 'float')->prop('min', 'null|int|float|numeric-string')->prop('max', 'null|int|float|numeric-string'),
    // string
    type('string', 'string'),
    type('nonEmptyString', 'non-empty-string'),
    type('numericString', 'numeric-string'),
    type('lowercaseString', 'lowercase-string'),
    type('stringValue', 'string')->prop('value', 'string'),
    type('classString', 'class-string')->prop('of', 'Type'),
    // array related
    type('list', 'list')->prop('value', 'Type')->prop('elements', 'array<non-negative-int, ArrayElement>')->prop('isNonEmpty', 'bool'),
    type('array', 'array')->prop('key', 'Type', ArrayKeyT::T)->prop('value', 'Type', MixedT::T)->prop('elements', 'array<ArrayElement>')->prop('isNonEmpty', 'bool'),
    type('keyOf', 'mixed')->prop('of', 'Type'),
    type('offset', 'mixed')->prop('value', 'Type')->prop('key', 'Type'),
    // object
    type('object', 'object')->prop('templates', 'list<Template>')->prop('superClasses', 'list<SuperClass>')->prop('properties', 'list<Property>'),
    type('self', 'object')->prop('templateArguments', 'list<Type>'),
    type('parent', 'object')->prop('templateArguments', 'list<Type>'),
    type('static', 'object')->prop('templateArguments', 'list<Type>'),
    // callable
    type('callable', 'callable')->prop('templates', 'list<Template<Variance::Invariant>>')->prop('parameters', 'list<Parameter>')->prop('returns', 'Type', MixedT::T),
    // resource
    type('resource', 'resource'),
    // constant
    type('constant', 'mixed')->prop('name', 'non-empty-string'),
    type('classConstant', 'mixed')->prop('on', 'Type')->prop('name', 'non-empty-string'),
    type('classConstantMask', 'mixed')->prop('on', 'Type')->prop('namePrefix', 'string'),
    // template
    type('template', 'mixed', class: true),
    // alias
    type('alias', 'mixed')->prop('class', 'class-string')->prop('name', 'non-empty-string')->prop('templateArguments', 'list<Type>'),
    // intersection
    type('intersection', 'mixed')->prop('of', 'non-empty-list<Type>'),
    // union
    type('union', 'mixed')->prop('of', 'non-empty-list<Type>'),
    // conditional
    type('isSubtype', 'bool')->prop('left', 'Type')->prop('right', 'Type'),
    type('ternary', 'mixed')->prop('condition', 'Type')->prop('then', 'Type')->prop('else', 'Type'),
    // todo literal
];
