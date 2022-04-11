<?php

declare(strict_types=1);

namespace Payment\System\App\GraphQL\Types;

use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class SubscriptionWithAuthType extends GraphQLType
{
    protected $attributes = [
        'name' => 'SubscriptionWithAuth',
    ];

    public function fields(): array
    {
        return [
            'subscription' => [
                'type' =>  GraphQL::type('subscription')
            ],
            'token' => [
                'type' =>  GraphQL::type('token'),
            ]
        ];
    }
}
