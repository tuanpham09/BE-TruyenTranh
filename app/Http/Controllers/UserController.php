<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Permission;
use App\Models\UserStatus;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::join('permissions', 'permissions.id', '=', 'users.permission_id')
            ->join('users_status', 'users_status.id', '=', 'users.status_id')
            ->select(
                'users.*',
                'permissions.name as permissions',
                'users_status.name as status'
            )
            ->get();

        return  response()->json([
            "status" => 200,
            "message" => "Success",
            "data" => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user = new User();

        $user_status = UserStatus::all();
        $permissions = Permission::all();

        return response()->json([
            'user_status' => $user_status,
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validations  = FacadesValidator::make($input, [
            "status_id" => "required",
            "username" => "required|unique:users,username,",
            "name" => "required|max:255",
            "email" => "required|email",
            "permission_id" => "required",
        ]);

        if ($validations->fails()) {

            return response()->json([
                "message" => "Error",
                "status" => 422,
                "data" => $validations->errors()
            ]);
        }
        
        $user = User::create([

            "username" => $request["username"],
            "name" => $request["name"],
            "email" => $request["email"],
            "password" => Hash::make($request["password"]),
            "permission_id" => $request["permission_id"],
            "status_id" => $request["status_id"],
        ]);


        return response()->json([
            "status" => 200,
            "message" => "Success",
            "data" => $user
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user =  User::findOrFail($id);
        return response()->json([
            "status" => 200,
            "message" => "Success",
            "data" => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $user = User::findOrFail($id);
        $user_status = UserStatus::find($id);
        $permissions = Permission::find($id);

        return response()->json([
            'user' => $user,
            'user_status' => $user_status,
            'permissions' => $permissions
        ]);
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
        //
        $input = $request->all();
        $validations  = FacadesValidator::make($input, [
            "status_id" => "required",
            "username" => "required|unique:users,username," . $id,
            "name" => "required|max:255",
            "email" => "required|email",
            "permission_id" => "required",
        ]);
        if ($validations->fails()) {

            return response()->json([
                "message" => "Error",
                "status" => 422,
                "data" => $validations->errors()
            ]);
        }
        $user = User::find($id)->update([
            "status_id" => $request["status_id"],
            "username" => $request["username"],
            "name" => $request["name"],
            "email" => $request["email"],
            "password" => Hash::make($request["password"]),
            "permission_id" => $request["permission_id"],
            "change_password_at" => now()
        ]);
        return response()->json([
            "message" => "Success",
            "status" => 200,
            "data" => $user
        ]);
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
        User::destroy($id);
        return response()->json([
            "message" => "Delete success",
            "status" => 200,
            "data" => null

        ]);
    }
}