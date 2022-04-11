<?php


namespace Payment\System\App\Managers;


use App\Models\User;
use Payment\System\App\Services\External\PaymentService;
use Payment\System\App\Services\PaymentMethodService;
use Payment\System\App\Services\PlanService;
use Payment\System\App\Services\SubscriptionService;
use Payment\System\App\Services\TransactionService;
use Illuminate\Support\Facades\DB;
use Throwable;

class SubscriptionManager
{
    private $subscriptionService;
    private $planService;
    private $paymentService;
    private $transactionService;
    private $paymentMethodService;

    /**
     * SubscriptionManager constructor.
     * @param SubscriptionService $subscriptionService
     * @param PlanService $planService
     * @param PaymentService $paymentService
     * @param TransactionService $transactionService
     * @param PaymentMethodService $paymentMethodService
     */
    public function __construct(
        SubscriptionService $subscriptionService,
        PlanService $planService,
        PaymentService $paymentService,
        TransactionService $transactionService,
        PaymentMethodService $paymentMethodService
    )
    {
        $this->subscriptionService = $subscriptionService;
        $this->planService = $planService;
        $this->paymentService = $paymentService;
        $this->transactionService = $transactionService;
        $this->paymentMethodService = $paymentMethodService;
    }

    /**
     * @param $planId
     * @param User $user
     * @param array $relations
     * @return mixed
     * @throws Throwable
     */
    public function subscribe($planId, User $user, array $relations, $paymentMethodId)
    {
        DB::beginTransaction();

        try {
            $plan = $this->planService->findByIdOrFail($planId);
            $paymentMethod = $this->paymentMethodService->findByIdOrFail($paymentMethodId);
            $subscription = $this->paymentService->subscribe($planId, $paymentMethod->operator);
            $subscription = $this->subscriptionService->subscribe($user, $plan, $subscription->id, $subscription->approvalLink, $paymentMethod);
//            $this->transactionService->createFromSubscription(
//                $subscription,
//                $paymentMethod,
//                $user
//            );

            if (!empty($relations)) {
                $subscription->load($relations);
            }
        } catch (Throwable $exception) {
            DB::rollBack();
            throw $exception;
        }

        DB::commit();

        return $subscription;
    }
}
