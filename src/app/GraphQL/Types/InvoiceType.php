<?php

namespace Payment\System\App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class InvoiceType extends GraphQLType
{
    protected $attributes = [
        'name' => 'Invoice',
        'description' => 'invoice',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'alias' => 'uuid',
                'type' => Type::string(),
                'description' => 'The id of the invoice',
            ],
            'currency' => [
                'type' => Type::string(),
            ],
            'status' => [
                'type' => Type::string(),
                'description' => 'The status of the invoice',
            ],
            'approvalLink' => [
                'type' => Type::string(),
                'description' => 'The approvalLink of the invoice',
            ],
            'items' => [
                'type' => Type::listOf(GraphQL::type('invoiceItem')),
                'description' => 'The items of the invoice',
            ],
            'transactions' => [
                'type' => Type::listOf(GraphQL::type('transaction')),
                'description' => 'The transaction of the invoice',
            ]
        ];
    }
}
