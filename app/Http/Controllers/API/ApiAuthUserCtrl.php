<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Model\PhoneVerify;
use App\Model\ReferralCodeModel;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Twilio\Exceptions\EnvironmentException;
use JWTAuth;
use Aloha\Twilio\Twilio;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Auth\Events\Registered;

// use Aloha\Twilio\Support\Laravel\Facade;

class ApiAuthUserCtrl extends Controller
{
    use AuthenticatesUsers, RegistersUsers {
        AuthenticatesUsers::redirectPath insteadof RegistersUsers;
        AuthenticatesUsers::guard insteadof RegistersUsers;
    }

    protected function validator(array $data)
    {
        $messages = [
            'email.unique' => 'We need to know your e-mail address!',
        ];
        return Validator::make($data, [
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phoneno' => 'required|string|max:255',
            'devicename' => 'required|string|max:255',
            'usertype' => 'required|string|max:255',
            'devicetoken' => 'required|string|max:255',
            'verifyToken' => 'required|string|max:255'
        ], $messages);
    }

    public function authenticate(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'invalid_credentials', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['message' => 'could_not_create_token', 'data' => null, 'response_code' => 0], 500);
        }
        if ($request->has(['email', 'devicetoken'])) {
            $user = User::where(['email' => $request->email])->first();
            $user->devicetoken = $request->devicetoken;
            $user->save();
        } else {
            return response()->json(['message' => 'Device Token updation Problem', 'data' => null, 'response_code' => 0], 200);
        }
        return response()->json(['message' => 'successfully login and user is verified',
            'data' => User::where(['email' => $request->email])->first(), 'token'=>$token,
            'response_code' => 1, 'userVerified' => [1]], 200);
    }

    public function create(array $data)
    {
        $user = [
                'username' => $data['username'],
                'email' => $data['email'],
                'phoneno' => $data['phoneno'],
                'verifyToken' => $data['verifyToken'],
                'devicename' => $data['devicename'],
                'devicetoken' => $data['devicetoken'],
                'usertype' => $data['usertype'],
                'email' => $data['email'],
                'status' => 0,
                'isAvailable' => 1,
                'password' => bcrypt($data['password'])
            ];
        return User::create($user);
    }

    public function signup(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return response()->json(['message' => 'Signup is failed', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }

        if ($request->has('refferalcode')) {
            $user = $this->create($request->all());
            $user->update($request->only('refferalcode'));
        } else {
            // if (!PhoneVerify::where(array('verify_num'=>$request->verifyToken, 'phone_num'=>$request->phoneno))->first()) {
            //     return response()->json(['message' => 'Phone verifying is failed', 'data' => null, 'response_code' => 0], 200);
            // }
            $user = $this->create($request->all());
            $referal = new ReferralCodeModel();
            $referal->user_id = $user->id;
            $referal->rederral_code = str_random(6);
            $referal->save();
        }
        try {
            $credentials = array('email' => $request->email, 'password' =>$request->password);
            $token = JWTAuth::attempt($credentials);
            return response()->json(['message' => 'Signup is successully', 'data' => $user, 'token'=>$token, 'response_code' => 1], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
        }
    }

    public function sms_content()
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

    public function sendSMS(Request $request)
    {
        $message = $this->sms_content();

        if (!$request->has('phoneno')) {
            return response()->json(['message' => 'please insert correct phone number', 'data' => null, 'response_code' => 0], 200);
        }

        try {
            $twilio = new Twilio('ACc4dd7a92b85eaf6f7238e4e1876981fe', 'e427af11aee1056bab5135223116fd06', '+17866295475', false);
            $twilio->message("$request->phoneno", 'Verify code for EASYCOVER security is : '.$message.' please check this. Thank you!');
            // Twilio::message($request->phoneno, $message);
            $checkPhone = PhoneVerify::where('phone_num', $request->phoneno)->first();
            if (empty($checkPhone)) {
                $phoneVerify = new PhoneVerify();
                $phoneVerify->phone_num = $request->phoneno;
                $phoneVerify->verify_num = $message;
                $phoneVerify->save();
            } else {
                $phoneVerify = PhoneVerify::findOrFail($checkPhone->id);
                $phoneVerify->verify_num = $message;
                $phoneVerify->save();
            }
            if ($phoneVerify->id) {
                return response()->json(['message' => 'SMS is already sent', 'data' => null, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'there is no verify number', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Cannot send SMS', 'data' => $exception, 'response_code' => 0], 500);
        }
    }

    public function update(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $id = $user->id;
        $username = ($request->username != null) ? $request->username : $user->username;
        $email = ($request->email != null) ? $request->email : $user->email;
        $phone = ($request->phoneno != null) ? $request->phoneno : $user->phoneno;
        $nrc = ($request->nrc != null) ? $request->nrc : $user->nrc;
        $dob = ($request->dob != null) ? $request->dob : $user->dob;
        $address = ($request->address != null) ? $request->address : $user->address;
        $postcode = ($request->postcode != null) ? $request->postcode : $user->postcode;
        $state = ($request->state != null) ? $request->state : $user->state;
        $country = ($request->country != null) ? $request->country : $user->country;
        // $password = ($request->password != null) ? $request->password  : $user->password;
        if ($request->hasFile('photo') > 0) {
            $photo = ($request->hasFile('photo') != null) ? asset('/').'storage/app/'.((Storage::disk('local')->put('/public/photos', $request->photo))): $user->image;
            $data = [
                "username" => $username,
                "email" => $email,
                "phoneno" => $phone,
                "nrc" => $nrc,
                "dob" => $dob,
                "address" => $address,
                "postcode" => $postcode,
                "state" => $state,
                "country" => $country,
                "image" => $photo,
            ];
        } else {
            $data = [
                "username" => $username,
                "email" => $email,
                "phoneno" => $phone,
                "nrc" => $nrc,
                "dob" => $dob,
                "address" => $address,
                "postcode" => $postcode,
                "state" => $state,
                "country" => $country,
            ];
        }
        $customer = User::findOrFail($id)->update($data);
        if ($customer) {
            try {
                return response()->json(['message' => 'update successfully ', 'data' =>$customer, 'response_code' => 1], 200);
            } catch (\Exception $exception) {
                return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
            }
        } else {
            return response()->json(['message' => 'updation problem occure ', 'data' => null, 'response_code' => 0], 200);
        }
    }

    public function checkPhoneVerify(Request $request)
    {
        try {
            if ($request->has(['phoneno', 'verifyToken'])) {
                $where = array('phone_num' => $request->phoneno, 'verify_num'=>$request->verifyToken );
                $resultVerify = PhoneVerify::where($where)->get();
                if (count($resultVerify) > 0) {
                    return response()->json(['message' => 'verifying is okay!', 'data' => null, 'response_code' => 1], 200);
                } else {
                    return response()->json(['message' => 'verifying is failed', 'data' => null, 'response_code' => 0], 200);
                }
            } else {
                return response()->json(['message' => 'There is no phone number or verify number', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'server error', 'data' => $e, 'response_code' => 0], 200);
        }
    }

    public function getReferral()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $where = array('user_id' => $user->id);
        try {
            $referralCode = ReferralCodeModel::where($where)->first();
            return response()->json(['message' => 'get referal Code', 'data' => ['referral_code'=>$referralCode->rederral_code], 'response_code' => 0], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'getting is failed', 'data' => null, 'response_code' => 0], 200);
        }
    }
}
