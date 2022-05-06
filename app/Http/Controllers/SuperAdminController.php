<?php

namespace App\Http\Controllers;

use App\Mail\welcomeMail;
use App\Model\User;
use App\Model\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:1,1')->only(['index','show']);
        $this->middleware('permission:2,1')->only(['store']);
        $this->middleware('permission:3,1')->only(['edit','update']);
        $this->middleware('permission:4,1')->only(['updateStatus']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $adminRole = Role::where('role_id','1')->first();
            $data = $adminRole->users()->where('is_active', '!=',2);

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('is_active', function($data){
                    if(checkOptionPermission(array(1),4)){
                        $check = "";
                        $disable = ($data->user_id == auth()->user()->user_id)?"disabled":"";
                        if($data->is_active == 1){
                            $check = "<div class='custom-control custom-switch custom-control-inline'>
                                        <input type='checkbox' class=' status custom-control-input' name='status' id='$data->user_id' value='0' $disable checked>
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
                    if(checkOptionPermission(array(1),3)) {
                        $button = '<button type="button" name="edit" id="'.$data->user_id.'" title="Edit Superadmin Information" class="edit btn-icon btn btn-primary btn-round btn-sm waves-effect waves-light"><i class="feather icon-edit"></i> </button>';
                    }
                    if(checkOptionPermission(array(1),4) && $data->user_id != auth()->user()->user_id) {
                        $button .= ' <button type="button"  name="deleteUser" id="' . $data->user_id . '" title="Delete Superadmin" value="2" class="deleteUser btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-trash-2"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action','is_active'])
                ->make(true);
        }
        return view('superAdmin.index');
    }

    /**
     * Display a list of archive Super admin.
     *s
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function restore(Request $request)
    {
        if($request->ajax())
        {
            $adminRole = Role::where('role_id','1')->first();
            $data = $adminRole->users()->where('is_active', 2);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($data){
                    $button = "";
                    if(checkOptionPermission(array(1),4) && $data->user_id != auth()->user()->user_id) {
                        $button .= ' <button type="button"  name="restoreSuperadmin" id="' . $data->user_id . '" title="Restore Super Admin" value="1" class="restoreSuperadmin btn-icon btn btn-danger btn-round btn-sm waves-effect waves-light"><i class="feather icon-rotate-ccw"></i> </button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('superAdmin.restore');
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

        $superadminRole = Role::where('role_id','1')->first();
        $user->roles()->attach($superadminRole);

        $mailContent['username'] = $user->username;
        $mailContent['email'] = $user->email;
        $mailContent['password'] = $password;
        Mail::to($mailContent['email'])->send(new welcomeMail($mailContent));

        return response()->json(['success' => 'Super Admin is added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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

        return response()->json(['success' => 'Super Admin is successfully updated']);
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

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'is_active' => $request['is_active'],
        );

        User::where( "user_id" ,$id)->update($form_data);

        return response()->json(['success' => 'Super Admin status is updated']);
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
