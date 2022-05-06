<?php

namespace App\Http\Controllers;

use App\Model\Vendors;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;


class VendorsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,5')->only(['index','show']);
        $this->middleware('permission:2,5')->only(['store']);
        $this->middleware('permission:3,5')->only(['edit','update']);
        $this->middleware('permission:4,5')->only(['updateStatus', 'restore']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = auth()->user()->getUserVendor();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($data) {
                    if(checkOptionPermission(array(5),4)){
                        $check = "";
                        if ($data->is_active == 1) {
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                    <input type='checkbox' class=' status custom-control-input' name='status' id='$data->vendor_id' value='0' checked>
                                    <label class='custom-control-label' for='$data->vendor_id'>
                                    </label>
                                </div>";
                        } else {
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                    <input type='checkbox' class='status custom-control-input' name='status' id='$data->vendor_id'  value='1'>
                                    <label class='custom-control-label' for='$data->vendor_id'>
                                    </label>
                                </div>";
                        }
                        return $check;
                    }else{
                        return ($data->is_active == 1)?"Active":"Inactive";
                    }
                })
                ->addColumn('action', function ($data) {
                    $button = "";
                    if(checkOptionPermission(array(5),3)){
                        $button = '<button type="button" name="edit" id="' . $data->vendor_id . '" title="Edit Vendor Information" class="edit btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i> </button>';
                    }
                    if(checkOptionPermission(array(5),4)) {
                        $button .= ' <button type="button"  name="deleteVendor" id="' . $data->vendor_id . '" title="Delete Vendor" value="2" class="deleteVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
        return view('user-vendors.index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        if ($request->ajax()) {
            $data = auth()->user()->getUserArchiveVendor();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = "";
                    if(checkOptionPermission(array(5),4)) {
                        $button .= ' <button type="button"  name="restoreVendor" id="' . $data->vendor_id . '" title="Restore Vendor" value="1" class="restoreVendor btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-rotate-ccw"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('user-vendors.restore');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'vendor_name' => ['required', 'string', 'max:255', 'unique:mgmt_vendor,vendor_name,NULL,vendor_id,domain,'.$request['domain']],
            'domain' => ['required', 'string', 'max:3'],
            'tier' => ['required', 'string', 'max:255'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'vendor_name' => $request['vendor_name'],
            'domain' => $request['domain'],
            'tier' => $request['tier'],

        );

        Vendors::create($form_data);

        return response()->json(['success' => 'Vendor is added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $data = Vendors::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'vendor_name' => ['required', 'string', 'max:255', 'unique:mgmt_vendor,vendor_name,'.$id.',vendor_id,domain,'.$request['domain']],
            'domain' => ['required', 'string', 'max:3'],
            'tier' => ['required', 'string', 'max:255'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'vendor_name' => $request['vendor_name'],
            'domain' => $request['domain'],
            'tier' => $request['tier'],
        );

        Vendors::where("vendor_id", $id)->update($form_data);

        return response()->json(['success' => 'Vendor is successfully updated']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        $rules = array(
            'is_active' => ['required', 'int'],
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'is_active' => $request['is_active'],
        );

        Vendors::where("vendor_id", $id)->update($form_data);

        return response()->json(['success' => 'Vendor status is updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
