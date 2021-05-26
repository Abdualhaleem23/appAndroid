<?php

namespace App\Http\Controllers;

use App\Models\AccountIfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }



        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|between:2,100',
            'email'      => 'required|string|email|max:100|unique:users',
            'password'   => 'required|string|confirmed|min:6',
            'code_libya' => 'required|max:13|min:11|unique:users',
            'code_bank'  => 'required|min:3|max:100|unique:users'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            ['password' => bcrypt($request->password)]
        ));
        $user->Token_code = auth()->attempt($validator->validated());
        return response()->json([
            'message' => 'User successfully registered',
            'successes' => true,
            'data' => $user,
           // 'token' =>$token = auth()->attempt($validator->validated())
        ], 201);


    }


    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }


    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }


    public function userProfile() {
       $data  = auth()->user();
       $getinfo = AccountIfo::where(['user_id' => auth()->user()->id]);
       if ($getinfo->count() == 0){
           $data2 = null;
       }else{
         $data2 =  $getinfo->get();
       }
        return response()->json([
            'data' => $data ,
            'data2'=>$data2,
            'success' => true]);
    }
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'successes' => true,
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'data' => auth()->user()
        ]);
    }

}
