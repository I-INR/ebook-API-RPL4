<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        return[
            'NIS' => 3103120111,
            'Name' => 'Ilham Nur Ramadhan',
            'phone' => '085292365482',
            'Class' => 'XI RPL 4'
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function register(Request $request){
        $field = $request->validate([
            'name'  => 'required|string|max::100',
            'email'  => 'required|string|unique:users,email',
            'password'  => 'required|string|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $field['name'],
            'email' => $field['email'],
            'password' => bcrypt($field['password']),
        ]);

        $token = $user->createToken('tokenku')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response,201);
    }

    public function login(Request $request){
        $field = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // check email
        $user = User::where('email', $field['email'])->first();

        // check password
        if (!$user|| !Hash::check($field['password'], $user->password)){
            return response([
                'message' => 'unauthorized',
            ], 401);
        }

        $token = $user->createToken('tokenku')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response,201);
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();

        return ['message' => 'Logged out'];
    }

}
