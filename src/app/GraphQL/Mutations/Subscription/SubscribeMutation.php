<?php


namespace Payment\System\App\GraphQL\Mutations\Subscription;


use Payment\System\App\Managers\SubscriptionManager;
use Payment\System\App\Services\External\PaymentService;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class SubscribeMutation extends Mutation
{
    /**
     * @var
     */
    private $subscriptionManager;

    public function __construct(SubscriptionManager $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return !Auth::guest();
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'subscribe to plan'
    ];

    /**
     * @return Type
     */
    public function type(): Type
    {
        return GraphQL::type('subscription');
    }

    /**
     * @return array
     */
    public function args(): array
    {
        return [
            'planId' => [
                'name' => 'planId',
                'type' => Type::nonNull(Type::string())
            ],
            'paymentMethodId' => [
                'name' => 'paymentMethodId',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    /**
     * @param array $args
     * @return array
     */
    public function rules(array $args = []): array
    {
        return [
            'planId' => [
                'required',
                'string'
            ],
            'paymentMethodId' => [
                'required',
                'string',
            ]
        ];
    }

    /**
     * @param $root
     * @param $args
     * @param $context
     * @param ResolveInfo $resolveInfo
     * @param \Closure $getSelectFields
     * @return mixed
     * @throws \Throwable
     */
    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, \Closure $getSelectFields)
    {
        $selectedRelations = $getSelectFields()->getRelations();

        return  $this->subscriptionManager->subscribe(
            $args['planId'],
            Auth::user(),
            $selectedRelations,
            $args['paymentMethodId']
        );
    }
}
