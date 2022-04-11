<?php

declare(strict_types=1);

namespace Payment\System\App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class invoiceItemType extends GraphQLType
{
    protected $attributes = [
        'name' => 'InvoiceItem',
        'description' => 'A type'
    ];

    public function fields(): array
    {
        return [
            'name' => [
                'type' => Type::string(),
                'description' => 'The name of the item',
            ],
            'price' => [
                'type' => Type::float(),
                'description' => 'The price of the item'
            ],
            'description' => [
                'type' => Type::string(),
                'description' => 'The description of the item',
            ],
        ];
    }
}
