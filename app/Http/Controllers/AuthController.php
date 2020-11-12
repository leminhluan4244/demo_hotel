<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\Response;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $this->config([
            'rule' => [
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|confirmed'
            ],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(
                $this->errors->all()
            );
        } else {
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            $user->save();
            return Response::response(array(
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ));
        }
    }

    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $this->config([
            'rule' => [
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ],
            'request' => $request,
        ]);
        // Run check validate if false
        $this->exam();
        if ($this->status == self::VALIDATE) {
            return Response::errors(array('message' => 'Unauthorized'));
        } else {
            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)){
                return Response::errors(
                    $this->errors->all()
                );
            }
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            return Response::response(array(
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ));
        }
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return Response::response(array(
            'message' => 'Successfully logged out'
        ));
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
