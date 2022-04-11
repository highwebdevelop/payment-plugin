<?php


namespace Payment\System\App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SubscriptionType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Subscription',
        'description'   => 'billing subscription',
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id' => [
                'alias' => 'subscriptionId',
                'type' => Type::string(),
                'description' => 'Id of the subscription',
            ],
            'plan' => [
                'type' => GraphQL::type('plan'),
                'description' => 'Plan of the subscription',
            ],
            'transactions' => [
                'type' => Type::listOf(GraphQL::type('transaction')),
                'description' => 'Type of the plan'
            ],
            'price' => [
                'type' => Type::float(),
                'description' => 'Price of the plan'
            ],
            'approvalLink' => [
                'type' => Type::string(),
                'description' => 'For redirect approval link of the subscription',
            ],
            'date' => [
                'type' => Type::string(),
                'alias' => 'created_at'
            ],
            'status' => [
                'type' => Type::string(),
            ],
            'recurringDate' => [
                'type' => Type::string()
            ],
            'endsAt' => [
                'type' => Type::string()
            ],
            'paymentMethod' => [
                'type' => GraphQL::type('paymentMethod')
            ]
        ];
    }
}
