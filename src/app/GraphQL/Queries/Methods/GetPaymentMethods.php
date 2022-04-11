<?php

declare(strict_types=1);

namespace Payment\System\App\GraphQL\Queries\Methods;

use Payment\System\App\Models\PaymentMethod;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\SelectFields;

class GetPaymentMethods extends Query
{
    protected $attributes = [
        'name' => 'GetPaymentMethods'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('paymentMethod'));
    }

    public function args(): array
    {
        return [

        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $resolveInfo, Closure $getSelectFields)
    {
        if (!request()->header('Xcode')) {
            $code = 'US';
        }
        else {
            $code = request()->header('Xcode');
        }
        return PaymentMethod::whereHas('country', function ($q) use ($code) {
            return $q->where('code', $code);
        })
            ->where('is_active', true)
            ->get();
    }
}
