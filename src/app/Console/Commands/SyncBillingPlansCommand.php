<?php

namespace Payment\System\App\Console\Commands;

use Payment\System\App\Services\External\PaymentService;
use Payment\System\App\Services\PlanService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncBillingPlansCommand extends Command
{
    private $paymentService;
    private $planService;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncing billing plans';

    /**
     * SyncBillingPlansCommand constructor.
     * @param PaymentService $paymentService
     */
    /**
     * SyncBillingPlansCommand constructor.
     * @param PaymentService $paymentService
     * @param PlanService $planService
     */
    public function __construct(
        PaymentService $paymentService,
        PlanService $planService
    )
    {
        parent::__construct();
        $this->paymentService = $paymentService;
        $this->planService = $planService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $plans = $this->paymentService->getPlans();
            foreach ($plans as $plan) {
                $localPlan = $this->planService->syncFromPayment($plan);
                if ($localPlan->created_at == $localPlan->updated_at) {
                    $this->info("The plan [$localPlan->name] was created successfully.");
                } else {
                    $this->info("The plan [$localPlan->name] was synced successfully.");
                }
            }

            $this->info('The plans were synced successfully.');

        } catch (Throwable $exception) {
            DB::rollBack();
            Log::error('SyncBillingPlansCommand failed, details:' . $exception->getMessage());
            $this->info('Command was failed.');
            return;
        }

        DB::commit();
    }
}
