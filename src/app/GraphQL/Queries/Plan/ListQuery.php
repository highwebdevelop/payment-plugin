<?php

declare(strict_types=1);

namespace Payment\System\App\GraphQL\Queries\Plan;

use Payment\System\App\Services\PlanService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;

class ListQuery extends Query
{

    private $planService;

    protected $attributes = [
        'name' => 'plans',
        'description' => 'Billing plans'
    ];

    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }

    /**
     * @param mixed $root
     * @param array $args
     * @param mixed $ctx
     * @param ResolveInfo|null $resolveInfo
     * @param Closure|null $getSelectFields
     * @return bool
     */
    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return true;
    }

    /**
     * @return Type
     */
    public function type(): Type
    {
        return Type::listOf(GraphQL::type('plan'));
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

        return  $this->planService->all($selectedRelations);
    }
}
