<?php

namespace Payment\System\App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Payment\System\App\Http\Requests\InvoiceRequest;
use Payment\System\App\Http\Requests\SubscribeRequest;
use Payment\System\App\Managers\InvoiceManager;
use Payment\System\App\Managers\SubscriptionManager;
//use Payment\System\App\Models\InnerAppPlan;
//use Payment\System\App\Models\InnerAppSubscription;
use Payment\System\App\Models\Invoice;
use Payment\System\App\Models\PaymentMethod;
use Payment\System\App\Models\Plan;
use App\Models\User;
use Payment\System\App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{

    /**
     * @var SubscriptionService
     */
    private $subscriptionService;


    /**
     * @var SubscriptionManager
     */
    private $subscriptionManager;


    /**
     * @var InvoiceManager
     */
    private $invoiceManager;

    /**
     * SubscriptionController constructor.
     * @param SubscriptionService $subscriptionService
     * @param SubscriptionManager $subscriptionManager
     * @param $invoiceManager
     */
    public function __construct(SubscriptionService $subscriptionService, SubscriptionManager $subscriptionManager, InvoiceManager $invoiceManager)
    {
        $this->subscriptionService = $subscriptionService;
        $this->subscriptionManager = $subscriptionManager;
        $this->invoiceManager = $invoiceManager;
    }

    public function status()
    {
        if (!Auth::check()) return response()->json([
            'error' => true,
            'message' => 'error.no_auth'
        ], 401);

        $result = User::with('subscription', 'invoice', 'subscription.plan', 'invoice.plan')
            ->where('id', Auth::user()->id)
            ->first();
        return response()->json([
            'data' => $result
        ], 200);
    }

    public function subscribe(SubscribeRequest $request)
    {
        $subscribe = $this->subscriptionManager->subscribe(
            $request->get('planId'),
            \Illuminate\Support\Facades\Auth::user(),
            [],
            $request->get('paymentMethod')
        );
        return response()->json([
            'data' => $subscribe
        ], 200);
    }

    public function invoice(InvoiceRequest $request)
    {
        $plan = Plan::where('planId', $request->planId)->first();
        $paymentMethod = PaymentMethod::find($request->paymentMethodId);

        $invoice = Invoice::where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->where('plan_id', $plan->id)
            ->where('paymentMethod', $paymentMethod->operator)
            ->where('paymentType', $paymentMethod->method)
            ->first();

        if ($invoice == null) {
            $invoice = $this->invoiceManager->createInvoice(array(
                [
                    'name' => $plan->name,
                    'price' => $plan->price,
                    'description' => 'Subscription for out service'
                ]
            ),
                $request->user(),
                $paymentMethod->operator,
                $paymentMethod->method,
                $plan->id
            );
        }


        return response()->json([
            'data' => $invoice
        ], 200);
    }

    public function getTrial()
    {
        if (!Auth::check()) return response()->json([
            'error' => true,
            'message' => 'error.no_auth'
        ], 401);

        $user = Auth::user();

        if ($user->is_trial) {
            return response()->json([
                'error' => [
                    'status' => true,
                    'message' => 'trial.error.set_before'
                ]
            ], 400);
        }

        $user->is_trial = true;
        $user->trial_expires_in = Carbon::now()->addDays(3)->toDateTimeString();
        $user->save();

        return response()->json([
            'data' => $user
        ], 200);
    }

//    public function innerSubscribe(Request $request)
//    {
//        if (!Auth::check()) return response()->json([
//            'error' => true,
//            'message' => 'error.no_auth'
//        ], 401);
//
//        $plan = InnerAppPlan::where('storeProductId', $request->planId)
//            ->where('store_type', $request->storeType)
//            ->first();
//
//        $subscription = new InnerAppSubscription();
//
//        $subscription->status = $request->status;
//        $subscription->expires_at = $request->expiresAt;
//        $subscription->user_id = Auth::id();
//
//        $subscription->innerAppPlan()->associate($plan)->save();
//
//        return response()->json([
//            'data' => $subscription
//        ]);
//
//    }

}
