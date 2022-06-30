<?php

namespace App\Http\Controllers;

use JWTAuth;
use Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthControllerV2 extends Controller
{
    public function register(Request $request)
    {
    	//Validate data
        $data = $request->only('username', 'email', 'password', 'role', 'confirm_password');
        $validator = Validator::make($data, [
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'confirm_password' => 'required|string|same:password',
            'role' => 'required|in:COMPANY,USER,company,user'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        //Request is valid, create new user
        $user = User::create([
        	'username' => $request->username,
        	'email' => $request->email,
        	'password' => bcrypt($request->password),
            'role' => $request->role
        ]);

        return Helper::SuccessResponse(true, $user, "User created successfully", Response::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $input = $request->only('username', 'password');

        //valid credential
        $validator = Validator::make($input, [
            'username' => 'required|string',
            'password' => 'required|string|min:6|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return Helper::ErrorResponse('Invalid request: '.$validator->errors()->first(), Response::HTTP_BAD_REQUEST);
        }

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        //Request is validated
        //Crean token
        try {
            $credentials = array();
            $credentials[$fieldType] = $input['username'];
            $credentials['password'] = $input['password'];

            if (!$token = JWTAuth::attempt($credentials)) {
                return Helper::ErrorResponse('Login credentials are invalid', Response::HTTP_BAD_REQUEST);
            }
        } catch (JWTException $e) {
            return Helper::ErrorResponse('Could not create token', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

 		return Helper::SuccessResponse(true, ['token' => $token], 'Token created successfully', Response::HTTP_CREATED);
    }

    public function logout(Request $request)
    {
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        if ($validator->fails()) {
            return Helper::ErrorResponse('Error', Response::HTTP_UNAUTHORIZED);
        }

        try {
            JWTAuth::invalidate($request->token);

            return Helper::SuccessResponse(true, null, 'User has been logged out', Response::HTTP_ACCEPTED);
        } catch (JWTException $exception) {
            return Helper::ErrorResponse('Sorry, user cannot be logged out', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }
}
