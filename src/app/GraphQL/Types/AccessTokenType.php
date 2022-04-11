<?php


namespace Payment\System\App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class AccessTokenType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'AccessToken',
        'description'   => 'Access token',
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'id' => [
                'type' => Type::string(),
                'description' => 'The access token',
            ],
           'revoked' => [
               'type' => Type::boolean(),
               'description' => 'Is access token revoked'
           ]
        ];
    }
}
