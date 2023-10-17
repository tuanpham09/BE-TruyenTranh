<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserStatus;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::join('department', 'department.id', '=', 'users.department_id')
            ->join('users_status', 'users_status.id', '=', 'users.status_id')
            ->select(
                'users.*',
                'department.name as department',
                'users_status.name as status'
            )
            ->get();

        return  response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $user_status = UserStatus::all();
        $departments = Department::all();

        return response()->json([
            'user_status' => $user_status,
            'departments' => $departments
        ]);
    }

    public function store(Request $request)
    {
        $user = new User;
        dd($request);
        if ($request["department_id"] === null && $request["status_id"] === null) {
            $user->create([

                "username" => $request["username"],
                "name" => $request["name"],
                "email" => $request["email"],
                "password" => Hash::make($request["password"]),
                "department_id" => 1,
                "status_id" => 2,
            ]);
        } else {
            $user->create([

                "username" => $request["username"],
                "name" => $request["name"],
                "email" => $request["email"],
                "password" => Hash::make($request["password"]),
                "department_id" => $request["department_id"],
                "status_id" => $request["status_id"],
            ]);
        }

        return response()->json([
            "message" => "success",

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
        return User::findOrFail($id);
        //
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
        $departments = Department::find($id);

        return response()->json([
            'user' => $user,
            'user_status' => $user_status,
            'departments' => $departments
        ]);
    }


    public function update(Request $request, $id)
    {
        //
        $validated = $request->validate([
            "status_id" => "required",
            "username" => "required|unique:users,username," . $id,
            "name" => "required|max:255",
            "email" => "required|email",
            "department_id" => "required",
        ]);
        $user = User::find($id)->update([
            "status_id" => $request["status_id"],
            "username" => $request["username"],
            "name" => $request["name"],
            "email" => $request["email"],
            "password" => Hash::make($request["password"]),
            "department_id" => $request["department_id"],
            "change_password_at" => now()
        ]);
        return response()->json([
            "message" => "Success",

        ]);
    }


    public function destroy($id)
    {
        //
        User::destroy($id);
        return response()->json([
            "message" => "Success"
        ]);
    }
}
