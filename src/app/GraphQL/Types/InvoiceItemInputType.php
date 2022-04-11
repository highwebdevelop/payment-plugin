<?php

namespace Payment\System\App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\InputType as GraphQLInputType;

class InvoiceItemInputType extends GraphQLInputType
{
    protected $attributes = [
        'name' => 'InvoiceItemInput',
        'description' => 'item type for input'
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
            'method' => [
                'type' => Type::string(),
                'description' => 'The description of the item',
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'The description of the item',
            ],
            'planId' => [
                'type' => Type::string(),
                'description' => 'Plan of the subscription',
            ],
        ];
    }
}
