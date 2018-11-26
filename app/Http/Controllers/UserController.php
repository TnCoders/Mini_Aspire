<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\User;
use Illuminate\Http\Request;
use Validator;
class UserController extends Controller
{


    /**
     * ! Securing the API endpoints
     */

    public function __construct()
    {
//        $this->middleware('auth:api')->except(['index','store','update','destroy']);
        $this->middleware('auth:apiemployee')->only(['index','store','show','update','destroy']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return  new UsersResource(User::all());

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'address' => 'nullable',
            'account_number' => 'required|numeric',
            'balance' => 'nullable|numeric',
            'comments' => 'nullable',
            'password' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 401);

        }

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,

            'address' => $request->address,
            'account_number' => $request->account_number,
            'balance' => $request->balance,
            'comments' => $request->comments,
            'password' => bcrypt($request->password),
        ]);



        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UserResource
     */
    public function show(User $user)
    {
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param User $user
     * @return UserResource
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->only(['balance', 'comments']));

        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, 204);
    }



}
