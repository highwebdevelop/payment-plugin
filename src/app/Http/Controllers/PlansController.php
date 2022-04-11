<?php

namespace Payment\System\App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Payment\System\App\Services\PlanService;
use Illuminate\Http\Request;

/**
 * Class PlansController
 * @package App\Http\Controllers
 */
class PlansController extends Controller
{

    /**
     * @var PlanService
     */
    private $planService;

    /**
     * PlansController constructor.
     * @param PlanService $planService
     */
    public function __construct(PlanService $planService)
    {
        $this->planService = $planService;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model[]|\Illuminate\Http\JsonResponse
     */
    public function all()
    {
        if (!Auth::check()) return response()->json([
            'error' => true,
            'message' => 'error.no_auth'
        ], 401);

        return  $this->planService->all([]);

    }
}
