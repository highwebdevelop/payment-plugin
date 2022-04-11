<?php


namespace Payment\System\App\Services;


use Payment\System\App\Exceptions\PlanNotFoundException;
use Payment\System\App\Models\Plan;
use Payment\System\App\Repositories\PlanRepository;

class PlanService extends Service
{
    const TYPE_MONTHLY = 'monthly';
    const TYPE_ANNUAL = 'annual';
    const TYPE_WEEKLY = 'weekly';

    private $planRepository;

    /**
     * PlanService constructor.
     * @param PlanRepository $planRepository
     */
    public function __construct(PlanRepository $planRepository)
    {
        $this->planRepository = $planRepository;
    }

    /**
     * @param array $withRelations
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]
     */
    public function all(array $withRelations)
    {
        return $this->planRepository->with($withRelations)->all();
    }

    /**
     * @param $planId
     * @return mixed
     * @throws PlanNotFoundException
     */
    public function findByIdOrFail($planId)
    {
        $plan = $this->planRepository->findById($planId);
        if (!$plan) {
            throw new PlanNotFoundException();
        }

        return $plan;
    }

    /**
     * @param $paymentPlan
     * @return mixed
     */
    public function syncFromPayment($paymentPlan)
    {
        $plan = $this->planRepository->findById($paymentPlan->id);
        if (!$plan) {
            $plan = $this->createFromPayment($paymentPlan);
        } else {
            $plan->fill([
                'name' => $paymentPlan->name,
                'type' => $paymentPlan->type,
                'price' => $paymentPlan->price,
                'currency' => $paymentPlan->currency
            ])->save();
        }

        return $plan;
    }

    /**
     * @param $plan
     * @return mixed
     */
    public function createFromPayment($plan)
    {
        return Plan::create([
            'planId' => $plan->id,
            'name' => $plan->name,
            'type' => $plan->type,
            'price' => $plan->price,
            'currency' => $plan->currency
        ]);
    }
}
