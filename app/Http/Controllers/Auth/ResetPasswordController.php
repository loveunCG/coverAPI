<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;
use JWTAuth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|min:6',
        'verifyCode' => 'required'
      ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'please insert correct Email, password, verifyCode', 'data' => null, 'response_code' => 1], 200);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'please insert correct Email', 'data' => null, 'response_code' => 1], 200);
        }
        if ($user->verifyToken!=$request->verifyCode) {
            return response()->json(['message' => 'please insert correct verify code', 'data' => null, 'response_code' => 1], 200);
        }
        $user->password = Hash::make($request->password);
        //$user->setRememberToken(Str::random(60));
        $user->save();
        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);
        return response()->json(['message' => 'Successfully Reset password', 'data' => $user,'token'=>$token, 'response_code' => 1], 200);
    }
}
