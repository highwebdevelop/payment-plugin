<?php


namespace Payment\System\App\Services;


use Payment\System\App\Models\PaymentMethod;
use Payment\System\App\Models\Plan;
use App\Models\User;
use Payment\System\App\Repositories\SubscriptionRepository;

class SubscriptionService extends Service
{
    /**
     * @var SubscriptionRepository
     */
    private $subscriptionRepository;

    /**
     * SubscriptionService constructor.
     * @param SubscriptionRepository $subscriptionRepository
     */
    public function __construct(SubscriptionRepository $subscriptionRepository)
    {
        $this->subscriptionRepository = $subscriptionRepository;
    }

    /**
     * @param $id
     * @param array $withRelations
     * @return mixed
     */
    public function get($id, array $withRelations = [])
    {
        return $this->subscriptionRepository
            ->findByUser($id);
    }

    /**
     * @param User $user
     * @param Plan $plan
     * @param $subscriptionId
     * @return mixed
     */
    public function subscribe(User $user, Plan $plan, $subscriptionId, $approvalLink, PaymentMethod $paymentMethod)
    {
        return $this->subscriptionRepository->create($user, $plan, $subscriptionId, $approvalLink, $paymentMethod);
    }
}
