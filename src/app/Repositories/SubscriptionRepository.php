<?php


namespace Payment\System\App\Repositories;


use Payment\System\App\Models\PaymentMethod;
use Payment\System\App\Models\Plan;
use Payment\System\App\Models\Subscription;
use App\Models\User;
use Payment\System\App\Services\External\PaymentService;

class SubscriptionRepository extends Repository
{

    /**\
     * SubscriptionRepository constructor.
     * @param Subscription $model
     */
    public function __construct(Subscription $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->getModel()
            ->where('subscription_id', $id)
            ->first();
    }

    public function findByUser($id)
    {
        return $this->getModel()
            ->where('user_id', $id)
            ->first();
    }

    /**
     * @param User $user;
     * @param Plan $plan;
     * @param $subscriptionId
     * @return mixed
     */
    public function create(User $user, Plan $plan, $subscriptionId, $approvalLink, PaymentMethod $paymentMethod)
    {
        $subscription = new Subscription([
            'approvalLink' => $approvalLink,
            'subscriptionId' => $subscriptionId,
            'status' => PaymentService::STATUS_PENDING,
        ]);
        $subscription->paymentMethod()->associate($paymentMethod);
        $subscription->user()->associate($user);
        $subscription->plan()->associate($plan);
        $subscription->save();
        return $subscription;
    }
}
