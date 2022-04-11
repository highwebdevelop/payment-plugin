<?php

declare(strict_types=1);

namespace Payment\System\App\GraphQL\Queries\Subscription;

use Payment\System\App\Services\SubscriptionService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class DetailsQuery extends Query
{
    /**
     * @var SubscriptionService
     */
    private $subscriptionService;
    /**
     * @var string[]
     */
    protected $attributes = [
        'name' => 'subscription details',
        'description' => 'billing subscription details'
    ];

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * @param mixed $root
     * @param array $args
     * @param mixed $ctx
     * @param ResolveInfo|null $resolveInfo
     * @param Closure|null $getSelectFields
     * @return bool
     */
    public function authorize(
        $root,
        array $args,
        $ctx,
        ResolveInfo $resolveInfo = null,
        Closure $getSelectFields = null
    ): bool
    {
        return !Auth::guest();
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::string()
            ],
        ];
    }

    /**
     * @param array $args
     * @return array|\string[][]
     */
    public function rules(array $args = []): array
    {
        return [
            'id' => [
                'required',
                'string'
            ]
        ];
    }

    /**
     * @return Type
     */
    public function type(): Type
    {
        return Type::listOf(GraphQL::type('subscription'));
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $resolveInfo
     * @param Closure $getSelectFields
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $selectedRelations = $getSelectFields()->getRelations();
        /**
         * @var \App\Models\User $user
         */
        $user = Auth::user();
        return  $this->subscriptionService->get($user, $selectedRelations);
    }
}
