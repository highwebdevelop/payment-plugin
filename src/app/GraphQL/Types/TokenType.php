<?php


namespace Payment\System\App\GraphQL\Types;


use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;

class TokenType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Token',
        'description'   => 'Access token and refresh tokens',
    ];

    /**
     * @return array
     */
    public function fields(): array
    {
        return [
            'accessToken' => [
                'type' => Type::string(),
                'description' => 'The access token',
            ],
            'refreshToken' => [
                'type' => Type::string(),
                'description' => 'The refresh token'
            ],
            'expiresIn' => [
                'type' => Type::int(),
                'description' => 'The access token expiration time in seconds'
            ]
        ];
    }
}
