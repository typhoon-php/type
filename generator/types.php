<?php

declare(strict_types=1);

namespace Typhoon\TypeGenerator;

require_once __DIR__ . '/TypeSpec.php';

return [
    // trivial
    type('never', 'never'),
    type('void', 'void'),
    type('null', 'null'),
    type('false', 'false'),
    type('true', 'true'),
    type('intRange', 'int')->prop('min', '?numeric-string')->prop('max', '?numeric-string'),
    type('floatRange', 'float')->prop('min', '?float')->prop('max', '?float'),
    type('stringValue', 'string')->prop('value', 'string'),
    type('string', 'string'),
    type('resource', 'resource'),
    // compound
    type('union', 'mixed')->prop('types', 'non-empty-list<Type>'),
    type('intersection', 'mixed')->prop('types', 'non-empty-list<Type>'),
    type('diff', 'mixed')->prop('minuend', 'Type')->prop('subtrahend', 'Type'),
    type('list', 'list<mixed>')->prop('valueType', 'Type')->prop('elements', 'array<non-negative-int, ArrayElement>'),
    type('array', 'array<mixed>')->prop('keyType', 'Type')->prop('valueType', 'Type')->prop('elements', 'array<ArrayElement>'),
    type('classString', 'class-string')->prop('objectType', 'Type'),
    type('object', 'object')->prop('properties', 'array<non-empty-string, Property>'),
    type('callable', 'callable')->prop('templates', 'list<TemplateT>')->prop('parameters', 'list<Parameter>')->prop('returnType', 'Type'),
    // reference
    type('constant', 'mixed')->prop('name', 'non-empty-string'),
    type('classConstant', 'mixed')->prop('objectType', 'Type')->prop('name', 'non-empty-string'),
    type('classConstantMask', 'mixed')->prop('objectType', 'Type')->prop('namePrefix', 'string'),
    type('namedObject', 'object')->prop('name', 'class-string')->prop('templateArguments', 'list<Type>'),
    type('alias', 'mixed')->prop('classType', 'Type')->prop('name', 'non-empty-string')->prop('templateArguments', 'list<Type>'),
    type('self', 'object')->prop('resolvedObjectType', '?Type')->prop('templateArguments', 'list<Type>'),
    type('parent', 'object')->prop('resolvedObjectType', '?Type')->prop('templateArguments', 'list<Type>'),
    type('static', 'object')->prop('resolvedObjectType', '?Type')->prop('templateArguments', 'list<Type>'),
    // template
    type('template', 'mixed')->prop('name', 'non-empty-string')->prop('variance', 'Variance')->prop('upperBound', 'Type'),
];
