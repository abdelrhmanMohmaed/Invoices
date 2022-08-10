<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::get();
        return view('web.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        return view('web.users.add_user', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'active' => $request->active,
                'role_id' => $request->role_id
            ]);

            return redirect('users')->with('success', 'تم الاضافه بنجاح');
        } catch (\Exception $e) {
            return $e;
            return redirect('users')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
        $user = User::find($id);
        $roles = Role::all();
        return view('web.users.edit', compact('user', 'roles'));
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
        $request->validate(
            [
                'name' => 'required|string|min:3|max:50',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'required|string|min:6|confirmed',
                'active' => 'required|in:1,0',
                'role_id' => 'required|exists:roles,id',
            ],
            [
                'required' => 'هذا الحقل مطلوب',
                'string' => 'هذا الحقل يجب ان يكون حروف وارقام',
                'password.confirmed' => 'هذا الحقل يجب ان يكون مطابق لحقل التاكيد',
                'email.unique' => ' هذاالاميل مستخدم من قبل',
            ]
        );
        try {
            User::where('id', $id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'active' => $request->active,
                'role_id' => $request->role_id,
            ]);

            return redirect('users')->with('success', 'تم الاضافه بنجاح');
        } catch (\Exception $e) {
            return $e;
            return redirect('users')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::where('id', $request->user_id)->first();
        $user->delete();
        return redirect('users')->with('success', 'تم الحذف بنجاح');
    }
    public function status($id)
    {
        $user = User::where('id', $id)->first();

        try {
            $user->update([
                'active' =>  !$user->active,
            ]);
            return redirect('users')->with('success', 'تم التحديث بنجاح');
        } catch (\Exception $e) {
            return redirect('users')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }
}
