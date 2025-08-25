<?php

declare(strict_types=1);

namespace Typhoon\TypeGenerator;

use Typhoon\Type\Internal\TermType;

require_once __DIR__ . '/TypeSpec.php';

return [
    // trivial
    type('never', 'never', TermType::class),
    type('void', 'void', TermType::class),
    type('null', 'null', TermType::class),
    type('false', 'false', TermType::class),
    type('true', 'true', TermType::class),
    type('intRange', 'int')->prop('min', '?numeric-string')->prop('max', '?numeric-string'),
    type('floatRange', 'float')->prop('min', '?numeric-string')->prop('max', '?numeric-string'),
    type('stringValue', 'string', TermType::class)->prop('value', 'string'),
    type('numericString', 'numeric-string', TermType::class),
    type('lowercaseString', 'lowercase-string', TermType::class),
    type('nonEmptyString', 'non-empty-string', TermType::class),
    type('string', 'string', TermType::class),
    type('resource', 'resource', TermType::class),
    // compound
    type('list', 'list<mixed>')->prop('valueType', 'Type')->prop('elements', 'array<non-negative-int, ArrayElement>'),
    type('array', 'array<mixed>')->prop('keyType', 'Type')->prop('valueType', 'Type')->prop('elements', 'array<ArrayElement>'),
    type('classString', 'class-string')->prop('objectType', 'Type'),
    type('object', 'object')->prop('properties', 'array<non-empty-string, Property>'),
    type('callable', 'callable')->prop('templates', 'list<TemplateT>')->prop('parameters', 'list<Parameter>')->prop('returnType', 'Type'),
    // template
    type('template', 'mixed')->prop('name', 'non-empty-string')->prop('variance', 'Variance')->prop('upperBound', 'Type'),
    // todo varianceAware
    // operation
    type('intersection', 'mixed')->prop('types', 'non-empty-list<Type>'),
    type('union', 'mixed')->prop('types', 'non-empty-list<Type>'),
    // todo intMask, key, offset, is, conditional
    // reference
    type('constant', 'mixed')->prop('name', 'non-empty-string'),
    type('classConstant', 'mixed')->prop('objectType', 'Type')->prop('name', 'non-empty-string'),
    type('classConstantMask', 'mixed')->prop('objectType', 'Type')->prop('namePrefix', 'string'),
    type('namedObject', 'object')->prop('class', 'class-string')->prop('templateArguments', 'list<Type>'),
    type('alias', 'mixed')->prop('classType', 'Type')->prop('name', 'non-empty-string')->prop('templateArguments', 'list<Type>'),
    type('self', 'object')->prop('resolvedObjectType', '?Type')->prop('templateArguments', 'list<Type>'),
    type('parent', 'object')->prop('resolvedObjectType', '?Type')->prop('templateArguments', 'list<Type>'),
    type('static', 'object')->prop('resolvedObjectType', '?Type')->prop('templateArguments', 'list<Type>'),
    // todo argument
    // marker
    // todo literal
];
