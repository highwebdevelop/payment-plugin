<?php


namespace Payment\System\App\GraphQL\Mutations\Token;


use Payment\System\App\Exceptions\AccessTokenNotFoundException;
use Payment\System\App\Services\AccessTokenService;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class RevokeAccessTokenMutation extends Mutation
{
    /**
     * @var
     */
    protected $accessTokenService;

    public function __construct(AccessTokenService $accessTokenService)
    {
        $this->accessTokenService = $accessTokenService;
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'revokeAccessToken'
    ];

    /**
     * @return Type
     */
    public function type(): Type
    {
        return GraphQL::type('accessToken');
    }

    /**
     * @return array
     */
    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::string())
            ],
        ];
    }

    /**
     * @param array $args
     * @return array
     */
    public function rules(array $args = []): array
    {
        return [
            'id' => [
                'required',
                'string'
            ],
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $resolveInfo
     * @param \Closure $getSelectFields
     * @throws AccessTokenNotFoundException
     * @throws Payment\System\App\Exceptions\AccessTokenAlreadyRevokedException
     */
    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, \Closure $getSelectFields)
    {
        $selectedRelations = $getSelectFields()->getRelations();
        $accessToken = $this->accessTokenService->getById($args['id']);
        if (!$accessToken) {
            throw new AccessTokenNotFoundException();
        }

        Auth::user()->can('use', $accessToken);

        return  $this->accessTokenService->revoke($accessToken, $selectedRelations);
    }
}
