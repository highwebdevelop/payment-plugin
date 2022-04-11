<?php


namespace Payment\System\App\Services;


use Payment\System\App\Exceptions\InvoiceNotFoundException;
use Payment\System\App\Models\Invoice;
use Payment\System\App\Repositories\InvoiceRepository;
use Payment\System\App\Repositories\ItemRepository;
use Payment\System\App\Services\External\PaymentService;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * @var InvoiceRepository
     * @var ItemRepository
     */
    private $invoiceRepository;
    /**
     * InvoiceService constructor.
     * @param InvoiceRepository $invoiceRepository
     * @param ItemRepository $itemRepository
     */
    public function __construct(
        InvoiceRepository $invoiceRepository,
        ItemRepository $itemRepository
    ){
        $this->invoiceRepository = $invoiceRepository;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @param array $items
     * @param $paymentServiceInvoice
     * @param $user_id
     * @param $method
     * @param $type
     * @return Invoice
     */
    public function make(array $items, $paymentServiceInvoice, $user_id)
    {
        $approvalLink =  $paymentServiceInvoice->approvalLink;
        $approvalLink .= '?paymentMethod=' . $items[0]['method'];
        $approvalLink .= '&paymentType=' . $items[0]['type'];
        $attributes = [
            'user_id' => $user_id,
            'uuid' => $paymentServiceInvoice->id,
            'status' => PaymentService::STATUS_PENDING,
            'description' => $this->setDescription($items),
            'approvalLink' => $approvalLink,
            'paymentMethod' => $items[0]['method'],
            'paymentType' => $items[0]['type'],
            'plan_id' => $items[0]['planId'],
            'expires_at' => Carbon::now()->addMonth(),
        ];

        $invoice = $this->invoiceRepository->create($attributes);

        foreach ($items as $item) {
            $attributes = [
                'invoice_id' => $invoice->id,
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => isset($item['description']) ? $item['description'] : null,
//                'paymentMethod' => $item['method'],
//                'paymentType' => $item['type'],
//                'p$itemRepositorylan_id' => $item['planId'],
            ];

            $this->itemRepository->create($attributes);
        }

        return $invoice;
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws InvoiceNotFoundException
     */
    public function findByUuidOrFail($uuid)
    {
        $invoice = $this->invoiceRepository->firstByUuid($uuid);
        if (!$invoice) {
            throw new InvoiceNotFoundException();
        }

        return $invoice;
    }

    /**
     * @param Invoice $invoice
     * @return int
     */
    public function getPrice($invoice)
    {
        $price = 0;
        $items = $invoice->items;
        foreach ($items as $item) {
            $price += $item['price'];
        }

        return $price;
    }

    /**
     * @param Invoice $invoice
     * @return int
     */
    public function setDescription($items)
    {
        $description = '';
        foreach ($items as $item) {
            $description .= $item['name'] . ', ';
        }

        return $description;
    }
}
