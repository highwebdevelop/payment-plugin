<?php

declare(strict_types=1);

namespace Payment\System\App\GraphQL\Mutations\Subscription;

use Payment\System\App\Managers\SubscriptionManager;
use App\Models\User;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class SubscribeWithEmailOnlyMutation extends Mutation
{

    protected $authService;
    private $subscriptionManager;

    public function __construct(SubscriptionManager $subscriptionManager)
    {
        $this->subscriptionManager = $subscriptionManager;
    }

    protected $attributes = [
        'name' => 'subscribeWithEmailOnly',
        'description' => 'A mutation'
    ];

    public function type(): Type
    {
        return  GraphQL::type('subscriptionWithAuth');
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
            'email' => [
                'name' => 'email',
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
            'email' => [
                'required',
                'string'
            ],
            'paymentMethodId' => [
                'required',
                'string',
            ]
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        $selectedRelations = $getSelectFields()->getRelations();
        $user = User::where('email', $args['email'])->first();
        if (!User::where('email', $args['email'])->first()) {
            $user = $this->authService->registerByEmail($args['email']);
        }

        $token = ['accessToken' => $user->createToken('Laravel Password Grant Client')->accessToken];

        $subscription = $this->subscriptionManager->subscribe(
            $args['planId'],
            $user,
            $selectedRelations,
            $args['paymentMethodId']
        );

        return [
            'token' => $token,
            'subscription' => $subscription
        ];
    }
}
