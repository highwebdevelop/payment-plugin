<?php

declare(strict_types=1);

namespace Payment\System\App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PaymentMethodType extends GraphQLType
{
    protected $attributes = [
        'name' => 'PaymentMethod',
    ];

    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::id()
            ],
            'operator' => [
                'type' => Type::string()
            ],
            'method' => [
                'type' => Type::string()
            ],
            'logo' => [
                'type' => Type::string(),
                'resolve' => function($root, $args) {
                    return 'https://chefvpn.com/'. $root->logo;
                }
            ],
            'isRecurrent' => [
                'type' => Type::boolean(),
                'alias' => 'is_recurrent'
            ]
        ];
    }
}
