<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\User;
use Illuminate\Http\Request;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function generateToken()
    {
        $length = 6;
        $chars = '0123456789';
        $chars_length = (strlen($chars) - 1);
        $string = $chars{rand(0, $chars_length)};
        for ($i = 1; $i < $length; $i = strlen($string)) {
            $r = $chars{rand(0, $chars_length)};
            if ($r != $string{$i - 1}) {
                $string .= $r;
            }
        }
        return $string;
    }

    public function sendEmailToken(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
        if (!$validator->fails()) {
            $user = User::where('email', $request->email)->first();
            if ($user) {
                $token = $this->generateToken();
                $user->verifyToken = $token;
                $user->save();
                try {
                    Mail::to($user['email'])->send(new VerifyEmail($user));
                } catch (\Exception $exception) {
                    return response()->json(['message' => 'Email address not exist', 'data' =>$exception, 'response_code' => 0], 500);
                }
            } else {
                return response()->json(['message' => 'Can not send Mail', 'data' =>[], 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'please input correct Email', 'data' => [], 'response_code' => 1], 200);
        }
        return response()->json(['message' => 'Successfully sent reset Password code via your email', 'data' => [], 'response_code' => 1], 200);
    }
}
