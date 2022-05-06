<?php

namespace App\Http\Controllers\ExecutiveDashboard;

use App\Http\Controllers\Controller;
use App\Model\Alerts\Alerts;
use App\Model\DimVendor;
use App\Model\edVendor;
use App\Model\User;
use App\Model\Vendors;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExecutiveDashboard extends Controller
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
        $data = auth()->user()->getUserVendor()->where('is_active', 1)->pluck('vendor_id')->all();

        $edVendor = auth()->user()->getUserEdVendor()->get()->first();
        //set ed vendor if not exist
        if(!isset($edVendor->fk_vendor_id_ed)){
            if(sizeof($data) >= 3 ){
                $edVendor = edVendor::create([
                    'fk_user_id'=> auth()->user()->user_id,
                    'fk_vendor_id_ed'=> $data[0],
                    'fk_vendor1_id_confirm_po'=> $data[0],
                    'fk_vendor2_id_confirm_po'=> $data[1],
                    'fk_vendor3_id_confirm_po'=> $data[2],
                ]);
            }else{
                $edVendor = edVendor::create([
                    'fk_user_id'=> auth()->user()->user_id,
                    'fk_vendor_id_ed'=> "0",
                    'fk_vendor1_id_confirm_po'=> "0",
                    'fk_vendor2_id_confirm_po'=> "0",
                    'fk_vendor3_id_confirm_po'=> "0",
                ]);
            }
        }
        $data = auth()->user()->getUserVendor()->where('is_active', 1)->pluck('vendor_id')->all();
        $GoldVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('tier', '!=', '(3P)')->where('tier', 'Gold')->get();
        $PlatinumVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('tier', '!=', '(3P)')->where('tier', 'Platinum')->get();
        $SilverVendors = DimVendor::whereIN('rdm_vendor_id', $data)->where('tier', '!=', '(3P)')->where('tier', 'Silver')->get();
        $vendor_info = Vendors::where('vendor_id',$edVendor->fk_vendor_id_ed)->where('tier', '!=', '(3P)')->where('is_active', 1)->get()->first();
        $vendor_id = isset($vendor_info) ? $vendor_info->vendor_id : 0 ;
        $vendor_name = isset($vendor_info) ? $vendor_info->vendor_name : "-" ;
        return view('executiveDashboard/ed1_visual')
            ->with('edVendor_id', $vendor_id)
            ->with('edVendor_name', $vendor_name)
            ->with('goldVendors', $GoldVendors)
            ->with('platinumVendors', $PlatinumVendors)
            ->with('silverVendors', $SilverVendors);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getEDReport(Request $request)
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

        if(!checkDateRange(3, $startDate, $endDate)){
            return response()->json(['error' => 'Your selected date is not valid']);
        }

        $user_id = auth()->user()->user_id;
        $role_id = auth()->user()->roles()->get()->first()->role_id;

        //call Model Static Function for Calling Store Procedure
        $SC_YTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsYtd($request['type']);
        $NR_YTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedYtd($request['type']);
        $SC_MTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsMtd($request['type'], $startDate);
        $NR_MTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedMtd($request['type'], $startDate);
        $shippedCogsTable =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsTable($request['type'], $user_id, $role_id, $startDate);
        $netReceivedTable =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedTable($request['type'], $user_id, $role_id, $startDate);

        for($i = 0; $i < sizeof($shippedCogsTable); $i++){
            $shippedCogsTable[$i]->alert = 'no';
            $alert = Alerts::getReportedAlerts($shippedCogsTable[$i]->fk_vendor_id, $user_id, 'sale', 'monthly', 'summary', $startDate, $endDate);
            for ($j = 0; $j < sizeof($alert); $j++) {
                switch ($request['type']){
                    case 0:
                        $shipped_cogs =  (int) (preg_replace('/[\$,]/', '', $shippedCogsTable[$i]->shipped_cogs));
                        $reported_value = (int) (preg_replace('/[\$,]/', '', $alert[$j]->reported_value));
                        if($shipped_cogs == $reported_value && $alert[$j]->reported_attribute == 'shipped_cogs'){
                            $shippedCogsTable[$i]->alert = 'yes';
                        }
                        break;
                    case 1:
                        $shipped_units = (int)(preg_replace('/[\$,]/', '', $shippedCogsTable[$i]->shipped_units));
                        $reported_value = (int)(preg_replace('/[\$,]/', '', $alert[$j]->reported_value));
                        if($shipped_units == $reported_value && $alert[$j]->reported_attribute == 'shipped_unit'){
                            $shippedCogsTable[$i]->alert = 'yes';
                        }
                        break;
                }
            }
        }

        return response()->json([
            'SC_YTD' => $SC_YTD,
            'NR_YTD' => $NR_YTD,
            'SC_MTD' => $SC_MTD,
            'NR_MTD' => $NR_MTD,
            'shippedCogsTable' => $shippedCogsTable,
            'netReceivedTable' => $netReceivedTable,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getVendorDetails(Request $request)
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

        if(!checkDateRange(3, $startDate, $endDate)){
            return response()->json(['error' => 'Your selected date is not valid']);
        }

        $vendor = auth()->user()->getUserEdVendor()->get()->first();
        $user_id = auth()->user()->user_id;

        //call Model Static Function for Calling Store Procedure
        $dimVendor = DimVendor::where('rdm_vendor_id', $vendor->fk_vendor_id_ed)->where('is_active', 1)->get()->first();
        $vendorDetailSC = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailSC($request['type'], $dimVendor->rdm_vendor_id);
        $vendorDetailNR = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailNR($request['type'], $dimVendor->rdm_vendor_id);
        $vendorDetailSCMTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailSCMTD($request['type'], $dimVendor->rdm_vendor_id, $startDate);
        $vendorDetailNRMTD = \App\Model\ExecutiveDashboard\ExecutiveDashboard::vendorDetailNRMTD($request['type'], $dimVendor->rdm_vendor_id, $startDate);
        $saleSummaryAlerts = Alerts::getReportedAlerts($dimVendor->rdm_vendor_id, $user_id, 'sale', 'monthly', 'summary', $startDate, $endDate);

        return response()->json([
            'vendorDetailSC' => $vendorDetailSC,
            'vendorDetailNR' => $vendorDetailNR,
            'vendorDetailSCMTD' => $vendorDetailSCMTD,
            'vendorDetailNRMTD' => $vendorDetailNRMTD,
            'vendor' => $dimVendor->rdm_vendor_id,
            'vendorAlerts' => $saleSummaryAlerts,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getShippedCogsTrailing(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
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

        if(!checkDateRange(3, $startDate, $endDate)){
            return response()->json(['error' => 'Your selected date is not valid']);
        }
        //call Model Static Function for Calling Store Procedure
        $shippedCogsTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::shippedCogsTrailing($request['type'], $request['vendor'], $startDate);

        return response()->json([
            'shippedCogsTrailing' => $shippedCogsTrailing,
        ]);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function getNetReceivedTrailing(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
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

        if(!checkDateRange(3, $startDate, $endDate)){
            return response()->json(['error' => 'Your selected date is not valid']);
        }

        //call Model Static Function for Calling Store Procedure
        $netReceivedTrailing =  \App\Model\ExecutiveDashboard\ExecutiveDashboard::netReceivedTrailing($request['type'], $request['vendor'], $startDate);

        return response()->json([
            'netReceivedTrailing' => $netReceivedTrailing,
        ]);
    }

    /**
     * Set Executive Dashboard Reporting Vendor.
     * @param Request $request
     * @return JsonResponse
     */
    public function setEDVendor(Request $request)
    {
        $rules = array(
            'vendor' => ['required'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'fk_vendor_id_ed' => $request['vendor'],
        );

        edVendor::where("fk_user_id" ,auth()->user()->user_id)->update($form_data);
        $vendor = Vendors::where('vendor_id',$request['vendor'])->where('is_active', 1)->get()->first();
        return response()->json(['success' => 'Executive Dashboard Vendor is changed','vendor' => $vendor->vendor_name ]);
    }

}
