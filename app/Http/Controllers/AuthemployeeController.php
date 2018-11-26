<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Validator;
class AuthemployeeController extends Controller
{


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:apiemployee', ['except' => 'logout']);
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required|unique:employees',
            'email' => 'required|unique:employees',
            'password' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 401);

        }


        $employee = Employee::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = auth()->guard('apiemployee')->login($employee);

        return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json(['error' => $validator->errors()], 401);

        }
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->guard('apiemployee')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 120
        ]);
    }
}
