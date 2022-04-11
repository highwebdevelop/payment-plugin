<?php


namespace Payment\System\App\Services;


use Payment\System\App\Exceptions\TransactionNotFoundException;
use Payment\System\App\Models\PaymentMethod;
use Payment\System\App\Models\Subscription;
use Payment\System\App\Models\Transaction;
use App\Models\User;
use Payment\System\App\Repositories\TtransactionRepository;
use Illuminate\Support\Str;

class TransactionService extends Service
{
    const STATUS_PENDING = 'processing';
    const STATUS_ACTIVE = 'approved';
    const STATUS_CANCELED = 'rejected';

    /**
     * @var TtransactionRepository $transactionRepository
     */
    private $transactionRepository;

    /**
     * @var InvoiceService $invoiceService
     */
    private $invoiceService;

    /**
     * TransactionService constructor.
     * @var TtransactionRepository $transactionRepository
     * @var InvoiceService $invoiceService
     */
    public function __construct(
        TtransactionRepository $transactionRepository,
        InvoiceService $invoiceService
    ) {
        $this->transactionRepository = $transactionRepository;
        $this->invoiceService = $invoiceService;
    }

    /**
     * @param Subscription $subscription
     * @param null $paymentSubscription
     * @return mixed
     */
    public function createFromSubscription(
        Subscription $subscription,
        PaymentMethod $paymentMethod,
        User $user
    )
    {

        $transaction = new Transaction([
            'currency' => $subscription->plan->currency,
            'uuid' => Str::uuid()->toString(),
            'price' => $subscription->plan->price,
            'status' => TransactionService::STATUS_PENDING,
        ]);

        $transaction->user()->associate($user);
        $transaction->paymentMethod()->associate($paymentMethod);
        $transaction->payment()->associate($subscription);
        $transaction->save();

        return $transaction;
    }

    /**
     * @param $paymentTransaction
     * @return mixed
     */
    public function createFromWebhook($paymentTransaction)
    {
        $attributes = [
            'paymentId' => null,
            'paymentPlatform' => $paymentTransaction->paymentPlatform,
            'currency' => $paymentTransaction->currency,
            'uuid' => $paymentTransaction->id,
            'price' => $paymentTransaction->price,
            'status' => $paymentTransaction->status,
        ];

        if($paymentTransaction->invoice) {
            $invoice = $this->invoiceService->findByUuidOrFail($paymentTransaction->invoice->id);
            $attributes['invoice_id'] = $invoice->id;
            $attributes['user_id'] = $invoice->user_id;
        } else {
            \Log::error('The action not founded for create transaction');
            die(1);
        }

        return Transaction::create($attributes);
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws TransactionNotFoundException
     */
    public function findByUuidOrFail($uuid)
    {
        $transaction = $this->transactionRepository->firstByUuid($uuid);
        if (!$transaction) {
            throw new TransactionNotFoundException();
        }

        return $transaction;
    }
}
