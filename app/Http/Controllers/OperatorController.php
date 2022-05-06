<?php

namespace App\Http\Controllers;

use App\Mail\welcomeMail;
use App\Model\User;
use App\Model\Role;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class OperatorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,11')->only(['index','show']);
        $this->middleware('permission:2,11')->only(['store']);
        $this->middleware('permission:3,11')->only(['edit','update']);
        $this->middleware('permission:4,11')->only(['updateStatus','restore']);
    }

    /**
     * Display a listing of the resource.
     *s
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $operatorRole = Role::where('role_id','4')->first();
            $data = $operatorRole->users()->where('is_active', '!=',2);

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function($data){

                    if(checkOptionPermission(array(11),4)){
                        $check = "";
                        $disable = ($data->user_id == auth()->user()->user_id)?"disabled":"";
                        if($data->is_active == 1){
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class=' status custom-control-input' name='status' id='$data->user_id' value='0' $disable  checked>
                                        <label class='custom-control-label' for='$data->user_id'>
                                        </label>
                                    </div>";
                        }else{
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class='status custom-control-input' name='status' id='$data->user_id' value='1' $disable>
                                        <label class='custom-control-label' for='$data->user_id'>
                                        </label>
                                    </div>";
                        }
                        return $check;
                    }else{
                        return ($data->is_active == 1)?"Active":"Inactive";
                    }

                })
                ->addColumn('action', function($data){
                    $button = "";
                    if(checkOptionPermission(array(11),3)) {
                        $button = '<button type="button" name="edit" id="' . $data->user_id . '" title="Edit Operator Information" class="edit btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i> </button>';
                    }
                    if(checkOptionPermission(array(11),4) && $data->user_id != auth()->user()->user_id) {
                        $button .= ' <button type="button"  name="deleteUser" id="' . $data->user_id . '" title="Delete Operator" value="2" class="deleteUser btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action','is_active'])
                ->make(true);
        }
        return view('operator.index');
    }

    /**
     * Display a list of archive operator.
     *s
     * @param Request $request
     * @return Application|Factory|View
     * @throws Exception
     */
    public function restore(Request $request)
    {
        if($request->ajax())
        {
            $operatorRole = Role::where('role_id','4')->first();
            $data = $operatorRole->users()->where('is_active', 2);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = "";
                    if(checkOptionPermission(array(11),4) && $data->user_id != auth()->user()->user_id) {
                        $button .= ' <button type="button"  name="restoreOperator" id="' . $data->user_id . '" title="Restore Operator" value="1" class="restoreOperator btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-rotate-ccw"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('operator.restore');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {

        $rules = array(
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:mgmt_user'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $password = generatePassword();

        $form_data = array(
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => Hash::make($password),
        );

        $user = User::create($form_data);

        $operatorRole = Role::where('role_id','4')->first();
        $user->roles()->attach($operatorRole);

        $mailContent['username'] = $user->username;
        $mailContent['email'] = $user->email;
        $mailContent['password'] = $password;
        Mail::to($mailContent['email'])->send(new welcomeMail($mailContent));

        return response()->json(['success' => 'Operator is added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        if(request()->ajax())
        {
            $data = User::findOrFail($id);
            return response()->json(['result' => $data , 'role' => $data->roles()->get()->first()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        if(request()->ajax())
        {
            $data = User::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $rules = array(
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique("mgmt_user")->ignore($request->hidden_id, 'user_id'), ],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'username' => $request['username'],
            'email' => $request['email'],
        );

        User::where( "user_id" ,$id)->update($form_data);

        return response()->json(['success' => 'Operator is successfully updated']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $rules = array(
            'is_active' => ['required', 'int'],
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'is_active' => $request['is_active'],
        );

        User::where( "user_id" ,$id)->update($form_data);

        return response()->json(['success' => 'Operator status is updated']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
