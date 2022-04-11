<?php


namespace Payment\System\App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TransactionType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Transaction',
        'description'   => 'transaction of payment',
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id' => [
                'alias' => 'id',
                'type' => Type::string(),
                'description' => 'The id of the transaction',
            ],
            'subscription' => [
                'type' => GraphQL::type('subscription'),
                'description' => 'The subscription of the transaction',
            ],
            'status' => [
                'type' => Type::string()
            ],
            'price' => [
                'type' => Type::float(),
                'description' => 'The price of the transaction',
            ],
            'date' => [
                'type' => Type::string(),
                'alias' => 'created_at'
            ],
            'currency' => [
                'type' => Type::string()
            ],
            'payUrl' => [
                'type' => Type::string()
            ],
            'paymentMethod' => [
                'type' => GraphQL::type('paymentMethod')
            ]
        ];
    }
}
