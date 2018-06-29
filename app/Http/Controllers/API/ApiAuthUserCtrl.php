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
use App\Model\CompanyModel;
use Illuminate\Support\Facades\Storage;
use App\User;

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
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
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
                'password' => bcrypt($data['password']),
                'latitude'=>$data['latitude'],
                'longitude'=>$data['longitude']
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
            return response()->json(['message' => 'Signup is successfully', 'data' => $user, 'token'=>$token, 'response_code' => 1], 200);
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

        //extract data from the post
        //set POST variables
        $url = 'https://api.twilio.com/2010-04-01/Accounts/ACdc7e1a5c2afd8c7f442741dfd9cef07e/Messages.json';
        $fields = array(
            'To' => urlencode($request->phoneno),
            'From' => urlencode("+19402203497"),
            'Body' => urlencode($message)
        );

        $fields_string = "";

        //url-ify the data for the POST
        foreach ($fields as $key=>$value) {
            $fields_string .= $key.'='.$value.'&';
        }
        rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_USERPWD, "ACdc7e1a5c2afd8c7f442741dfd9cef07e:2e38ac44561e3a14e5cc5302b3411f40");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //execute post
        $result = curl_exec($ch);
        $json_result = json_decode($result);

        //close connection
        curl_close($ch);

        if ($json_result->error_code === null) {
            $checkPhone = PhoneVerify::where('phone_num', $request->phoneno)->first();
            if (empty($checkPhone)) {
                $phoneVerify = new PhoneVerify();
                $phoneVerify->phone_num = $request->phoneno;
                $phoneVerify->verify_num = $message;
                $phoneVerify->save();
            } else {
                $phoneVerify = PhoneVerify::findOrFail($checkPhone->id);
                $phoneVerify->verify_num = $message;
                $phoneVerify->update();
            }
            if ($phoneVerify->id) {
                return response()->json(['message' => 'SMS is sent', 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'there is no verify number', 'response_code' => 0], 200);
            }
        } else {
            return response()->json(['message' => $json_result->error_message, 'response_code' => 0], 500);
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
        $photo = ($request->photo != null) ? $request->photo : $user->image;
        $user->username = $username;
        $user->email = $email;
        $user->username = $username;
        $user->phoneno = $phone;
        $user->dob = $dob;
        $user->nrc = $nrc;
        $user->address = $address;
        $user->postcode = $postcode;
        $user->state = $state;
        $user->country = $country;
        $user->image = $photo;
        try {
            if ($user->save()) {
                return response()->json(['message' => 'update successfully ', 'data' =>$user, 'response_code' => 1], 200);
            } else {
                return response()->json(['message' => 'updation problem occure ', 'data' => null, 'response_code' => 0], 200);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Server Error', 'data' => null, 'response_code' => 0], 500);
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
            return response()->json(['message' => 'get referal Code', 'data' => ['referral_code'=>$referralCode->rederral_code], 'response_code' => 1], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'getting is failed', 'data' => null, 'response_code' => 0], 200);
        }
    }

    public function getcompany()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $companys = CompanyModel::all();
        try {
            return response()->json(['message' => 'get company infomation', 'data' => $companys, 'response_code' => 1], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'getting is failed', 'data' => $e, 'response_code' => 0], 200);
        }
    }
}
