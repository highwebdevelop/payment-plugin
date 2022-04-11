<?php


namespace Payment\System\App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PlanType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Plan',
        'description'   => 'billing plan',
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id' => [
                'alias' => 'planId',
                'type' => Type::string(),
                'description' => 'Id of the plan',
            ],
            'name' => [
                'type' => Type::string(),
                'description' => 'Name of the plan'
            ],
            'type' => [
                'type' => Type::string(),
                'description' => 'Type of the plan'
            ],
            'price' => [
                'type' => Type::float(),
                'description' => 'Price of the plan'
            ],
        ];
    }
}
