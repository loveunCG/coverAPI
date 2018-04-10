<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
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
use App\Model\JobsModel;
use App\Model\CompanyModel;
use Illuminate\Support\Facades\Storage;
use App\Model\AssignJob;

class NotificationCronJobCommand extends Command
{
    protected $signature = 'send:notification';

    protected $description = 'Command to send notification';

	public function __construct()
	{
		parent::__construct();
	}

	public function sendNotification($tokens, $message) {
		$url = 'https://fcm.googleapis.com/fcm/send';
		$fields = array(
			'to' => $tokens,
			'data' => $message
		);

		$headers = array(
			'Authorization:key = AIzaSyALhA0lbxUhtlcYMNV6vE82z68xPMmfyFg',
			'Content-Type: application/json'
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		info(json_encode($fields));
		$result = curl_exec($ch);
		info($result);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
			# code...
		}
		curl_close($ch);
	}

	public function handle()
	{
		$users = JobsModel::join('users', 'users.id', '=', 'jobs.user_id')
				->whereRaw('Date(jobs.expired_date) >= CURDATE()')
				->whereRaw('Date(jobs.expired_date) <= DATE_SUB(CURDATE(), INTERVAL - 1 MONTH)')
				->get();
		foreach ($users as $value) {
			$message = array("message" => "New Notification");
			$this->sendNotification($value->devicetoken, $message);
			# code...
		}
	}
}