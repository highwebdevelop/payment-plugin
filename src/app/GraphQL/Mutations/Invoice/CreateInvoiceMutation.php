<?php

declare(strict_types=1);

namespace Payment\System\App\GraphQL\Mutations\Invoice;

use Payment\System\App\Managers\InvoiceManager;
use Closure;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;
use Rebing\GraphQL\Support\SelectFields;

class CreateInvoiceMutation extends Mutation
{
    /**
     * @var
     */
    private $invoiceManager;

    /**
     * CreateInvoiceMutation constructor.
     * @param InvoiceManager $invoiceManager
     */
    public function __construct(InvoiceManager $invoiceManager)
    {
        $this->invoiceManager = $invoiceManager;
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'invoiceItem',
    ];

    public function authorize($root, array $args, $ctx, ResolveInfo $resolveInfo = null, Closure $getSelectFields = null): bool
    {
        return !Auth::guest();
    }

    /**
     * @return Type
     */
    public function type(): Type
    {
        return GraphQL::type('invoiceItem');
    }

    /**
     * @return Type
     */
    public function args(): array
    {
        return [
            'items' => [
                'name' => 'invoiceItem',
                'type' => Type::listOf(GraphQL::type('invoiceItemInput')),
//                'type' => Type::string()
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
            'items.*.' => [
                'name' => 'required|string',
                'price' => 'required|integer',
                'description' => 'nullable|string',
            ],
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
        $items = Arr::get($args, 'invoiceItem');

        return  $this->invoiceManager->createInvoice($items, Auth::user());
    }
}
