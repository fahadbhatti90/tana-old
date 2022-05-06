<?php

namespace App\Http\Controllers\ExecutiveDashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class newConfirmPO extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */
    public function index()
    {
        return view('executiveDashboard/ed2_newVisual');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getEDConfirmPOReport(Request $request)
    {
        $rules = array(
            'type' => ['required'],
            'date_range' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['error' => 'Please select all filters']);
        }

        $dateRange= explode(" - ", $request['date_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if(!checkDateRange(5, $startDate, $endDate)){
            return response()->json(['error' => 'Your selected date is not valid']);
        }

        $user_id = auth()->user()->user_id;
        $role_id = auth()->user()->roles()->get()->first()->role_id;

        //call Model Static Function for Calling Store Procedure
        $weekly_po_report = \App\Model\ExecutiveDashboard\ConfirmPO::weeklyPOReport($request['type']);
        $po_report_all_vendor = \App\Model\ExecutiveDashboard\ConfirmPO::weeklyConfirmedPOReport($request['type'], $user_id, $role_id, $startDate);
        $po_confirmed_rate_all_vendor = \App\Model\ExecutiveDashboard\ConfirmPO::tanaAllVendorsPOConfirmRate($user_id, $role_id, $startDate);

        return response()->json([
            'po_report' => $weekly_po_report,
            'po_report_all_vendor' => $po_report_all_vendor,
            'po_confirmed_rate_all_vendor' => $po_confirmed_rate_all_vendor,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getEDConfirmPOVendorReport(Request $request)
    {
        $rules = array(
            'date_range' => ['required'],
            'vendor_id' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['error' => 'Please select all filters']);
        }

        $dateRange= explode(" - ", $request['date_range']);// split date range on " - "
        $startDate = date('Y-m-d', strtotime($dateRange[0])); // convert String to time and set date formate "Y-m-d" for Starting Date
        $endDate = date('Y-m-d', strtotime($dateRange[1])); // convert String to time and set date formate "Y-m-d" for ending Date

        if(!checkDateRange(5, $startDate, $endDate)){
            return response()->json(['error' => 'Your selected date is not valid']);
        }

        $vendor = auth()->user()->getUserEdVendor()->get()->first();

        //call Model Static Function for Calling Store Procedure
        $vendor_confirmation_rate = \App\Model\ExecutiveDashboard\ConfirmPO::POConfirmRateByVendor($request['vendor_id'], $startDate);

        return response()->json([
            'vendor_confirmation_rate' => $vendor_confirmation_rate,
        ]);
    }
}
