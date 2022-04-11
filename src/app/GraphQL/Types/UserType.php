<?php


namespace Payment\System\App\GraphQL\Types;


use Payment\System\App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class UserType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'User',
        'description'   => 'A user',
        'model'         => User::class,
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of the user',
            ],
            'email' => [
                'type' => Type::string(),
                'description' => 'The email of user',
            ],
            'subscription' => [
                'type' => GraphQL::type('subscription'),
                'description' => 'The user subscriptions'
            ],
            'invoice' => [
                'type' => GraphQL::type('invoice'),
                'description' => 'The user invoices'
            ],
            'transactions' => [
                'type' => Type::listOf(GraphQL::type('transaction')),
            ]
        ];
    }
}
