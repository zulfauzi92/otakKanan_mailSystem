<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
Use Crypt;

class UserController extends Controller
{
    public function logout() {

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status'=>'user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['status'=>'token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['status'=>'token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['status'=>'token_absent'], $e->getStatusCode());

        }

        if (Auth::guard('user')->check()){
            auth()->guard('user')->logout();
            return response()->json(['status' => 'Successfully logged out']);
        }
        return response()->json(['status' => 'Failed logged out']);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['status' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['status' => 'could_not_create_token'], 500);
        }

        $status = "Login is Success";
        return response()->json(compact('token', 'status'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:8',
            'email' => 'required|string|email|max:255|min:17|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json(['status' => $validator->errors()->toJson()], 400);
        }

        try{
            $user = User::create([
                'name' => $request->get('name'),
                'email' => $request->get('email'),
                'password' => Hash::make($request->get('password')),
                'role'=> 'user'
            ]);
        }
        catch(\Exception $e){
            return response()->json(['status'=>$e->getMessage()]);
        }

        $token = JWTAuth::fromUser($user);
        $status = "register is success";

        return response()->json(compact('user','token', 'status'),201);
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status'=>'user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['status'=>'token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['status'=>'token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['status'=>'token_absent'], $e->getStatusCode());

        }

        $status = "Token is Valid";

        return response()->json(compact(['user', 'status']));
    }

    public function update(Request $request) {

        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['status'=>'user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['status'=>'token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['status'=>'token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['status'=>'token_absent'], $e->getStatusCode());

        }

        $user = JWTAuth::parseToken()->authenticate();

        if($request->get('name')==NULL){
            $name = $user->name;
        } else{
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255'
            ]);

            if($validator->fails()){
                return response()->json(['status' => $validator->errors()->toJson()], 400);
            }
            $name = $request->get('name');
        }

        if($request->get('email')==NULL){
            $email = $user->email;
        } else{
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255|unique:user'
            ]);

            if($validator->fails()){
                return response()->json(['status' => $validator->errors()->toJson()], 400);
            }
            $email = $request->get('email');
        }

        if($request->get('password')==NULL){
            $password = $user->password;
            $user->update([
                'name' => $name,
                'email' => $email,
                'password' => $password
            ]);

            $status = "Token is Valid";

            return response()->json(compact(['user', 'status']));
        } else{
            $validator = Validator::make($request->all(), [
                'password' => 'required|string|min:6'
            ]);

            if($validator->fails()){
                return response()->json(['status' => $validator->errors()->toJson()], 400);
            }

            $password = $request->get('password');
        }

        $user->update([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);

        $status = "Token is Valid";

        return response()->json(compact(['user', 'status']));

    }
}
