<?php

namespace App\Http\Controllers\SellerCenter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\loadReport;
use App\Model\SellerCentral\LoadSellerCenter;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class LoadSellerCenterController extends Controller
{
    /**
     * LoadSellerCenterController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:3,8')->only(['index', 'loadDailyDropship']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('sellerCenter.load');
    }

    /**
     * Load Daily SellerCenter Record to SDM Facts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadDailyDropship(Request $request)
    {
        $rules = array(
            'load_daily_seller_center_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['error' => 'Please select date range']);
        }

        $dateRange = explode(" - ", $request['load_daily_seller_center_range']); // split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        //call Model Static Function for Calling Store Procedure
        $dailyDropshipResponse = LoadSellerCenter::loadDailySellerCenter($startDate, $endDate);

        broadcast(new loadReport("SellerCenter daily records are loaded", 'Check SellerCenter Daily Report'));

        return response()->json([
            'success' => 'SC sale daily records are successfully loaded',
            'response' => $dailyDropshipResponse
        ]);
    }
}
