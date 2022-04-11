<?php


namespace Payment\System\App\Managers;


use App\Models\User;
use Payment\System\App\Services\External\PaymentService;
use Payment\System\App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;

class InvoiceManager
{
    /**
     * @var InvoiceService
     */
    private $invoiceService;
    /**
     * @var PaymentService
     */
    private $paymentService;

    /**
     * InvoiceManager constructor
     * @param InvoiceService $invoiceServicey
     * @param PaymentService $paymentService
     */
    public function __construct(
        InvoiceService $invoiceService,
        PaymentService $paymentService
    )
    {
        $this->invoiceService = $invoiceService;
        $this->paymentService = $paymentService;
    }

    /**
     * @param array $items
     * @param User $user
     * @param $method
     * @param $type
     * @return mixed
     * @throws \Payment\System\App\Services\External\Payment\Exceptions\PaymentApiErrorException
     * @throws \Throwable
     */
    public function createInvoice(array $items, $user)
    {
        $invoiceData=[[
            "name" => $items[0]['name'],
            "price"=>$items[0]['price'],
            "description"=>$items[0]['description']
        ]];

        DB::beginTransaction();
        try {
            $paymentServiceInvoice = $this->paymentService->invoicing($invoiceData);
            $invoice = $this->invoiceService->make($items, $paymentServiceInvoice, $user->id);
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();

        return $invoice;
    }
}
