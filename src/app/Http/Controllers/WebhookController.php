<?php

namespace Payment\System\App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Payment\System\App\Exceptions\InvoiceNotFoundException;
use Payment\System\App\Exceptions\SubscriptionNotFoundException;
use Payment\System\App\Models\PaymentMethod;
use App\Models\User;
use Payment\System\App\Repositories\SubscriptionRepository;
use Payment\System\App\Services\External\PaymentService;
use Payment\System\App\Services\InvoiceService;
use Payment\System\App\Services\TransactionService;
use Payment\System\App\Services\WebhookService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * @var SubscriptionRepository $subscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * @var PaymentService $paymentService
     */
    private $paymentService;

    /**
     * @var TransactionService $transactionService
     */
    private $transactionService;

    /**
     * @var InvoiceService $invoiceService
     */
    private $invoiceService;

    /**
     * WebhookController constructor.
     * @param SubscriptionRepository $subscriptionRepository
     * @param PaymentService $paymentService
     * @param TransactionService $transactionService
     * @param InvoiceService $invoiceService
     */
    public function __construct(
        SubscriptionRepository $subscriptionRepository,
        PaymentService $paymentService,
        TransactionService $transactionService,
        InvoiceService $invoiceService
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->paymentService = $paymentService;
        $this->transactionService = $transactionService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function handleWebhook(Request $request) {
        $kind = $request->get('type');
        $uuid = $request->get('entity');

        switch ($kind) {
            case WebhookService::SUBSCRIPTION_CHARGE_FAILED:
            case WebhookService::SUBSCRIPTION_CHARGED_SUCCESSFULLY: return $this->updateSubscription($uuid);
            case WebhookService::TRANSACTION_CREATED: return $this->createTransaction($uuid);
            case WebhookService::INVOICE_CHARGE_FAILED:
            case WebhookService::INVOICE_CHARGED_SUCCESSFULLY: return $this->updateInvoice($uuid);
            case WebhookService::TRANSACTION_CHARGE_FAILED:
            case WebhookService::TRANSACTION_CHARGED_SUCCESSFULLY: return $this->updateTransaction($uuid);
        }
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws SubscriptionNotFoundException
     */
    public function updateSubscription($uuid) {

        $subscription = $this->subscriptionRepository->findById($uuid);

        if(!$subscription) {
            throw new SubscriptionNotFoundException();
        }

        try {
            $paymentSubscription = $this->paymentService->getSubscription($subscription);
        } catch (\Exception $e) {
            Log::error('Subscription not found in payment service');
            die(1);
        }

        $subscription->status = $paymentSubscription->status;
        $subscription->recurringCycle = $paymentSubscription->recurringCycle;
        $subscription->recurringDate = $paymentSubscription->recurringDate;

        $subscription->save();

        $paymentTransactions = $paymentSubscription->transactions;

        $this->transactionService->createFromSubscription(
            $subscription,
            PaymentMethod::find($subscription->paymentMethod->id),
            User::find($subscription->user->id)
        );

        return $subscription;
    }

    /**
     * @param $uuid
     * @return mixed
     * @throws InvoiceNotFoundException
     */
    public function updateInvoice($uuid) {

        $invoice = $this->invoiceService->findByUuidOrFail($uuid);

        try {
            $paymentInvoice = $this->paymentService->getInvoice($invoice);
            Log::info((array) $paymentInvoice);
        } catch (\Exception $e) {
            Log::error('Invoice not found in payment service');
            die(1);
        }

        $invoice->status = $paymentInvoice->status;
        $invoice->save();

        return $invoice;
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function updateTransaction($uuid) {

        $transaction = $this->transactionService->findByUuidOrFail($uuid);

        try {
            $paymentTransaction = $this->paymentService->getTransaction($transaction->uuid);
        } catch (\Exception $e) {
            Log::error('Transaction not found in payment service' . $e->getMessage());
            die(1);
        }

        $transaction->status = $paymentTransaction->status;
        $transaction->save();

        return $transaction;
    }

    /**
     * @param $uuid
     * @return mixed
     */
    public function createTransaction($uuid) {
        try {
            $paymentTransaction = $this->paymentService->getTransaction($uuid);
        } catch (\Exception $e) {
            Log::error('Transaction not found in payment service');
            die(1);
        }

        return $this->transactionService->createFromWebhook($paymentTransaction);
    }
}
