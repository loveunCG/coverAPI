<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Admin;
use Hash;

class AdminController extends Controller
{
    public function getAdminTableInfo(Request $request)
    {
        $adminData = Admin::all();
        $i = 1;
        $admin_list = [];
        if (count($adminData) > 0) {
            foreach ($adminData as $admin) {
                if ($admin->admin_level == 1) {
                    continue;
                }
                $admin_id = '<input type="hidden" class="adminId" value="' . $admin->id . '">';
                if ($admin->admin_level == 1) {
                    $admin_level = '<button type="button" class="btn btn-info-alt waves-effect">Super Admin</button>';
                } elseif ($admin->admin_level == 2) {
                    $admin_level = '<button type="button" class="btn btn-primary-alt waves-effect">job manager</button>';
                } else {
                    $admin_level = '<button type="button" class="btn btn-inverse-alt waves-effect">user manager</button>';
                }
                if ($admin->usr_status==1) {
                    $adminStatus = '<a href="#" class="btn btn-success"><i class="fa fa-circle"></i></a>';
                } else {
                    $adminStatus = '<a href="#" disabled class="btn btn-success"><i class="fa fa-circle-o"></i></a>';
                }
                $admin_list['data'][] = array(
                    $i++ . $admin_id,
                    $admin->name,
                    $admin->email,
                    $admin_level,
                    $admin->created_at->format("Y-m-d h:i:s"),
                    $adminStatus
                );
            }
        } else {
            $admin_list['data'][] = array(
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            );
        }
        return response()->json($admin_list);
    }

    public function getAdminInfo($adminId = null)
    {
        if (!$adminId) {
            return response()->json();
        }
        $adminIfo = Admin::where('id', $adminId)->first();
        $sendData = array('id'=>$adminIfo->id,
        'name'=>$adminIfo->name,
        'email'=>$adminIfo->email,
        'usr_status'=>$adminIfo->usr_status,
        'admin_level'=>$adminIfo->admin_level);
        return response()->json($sendData);
    }

    public function duplicationEmail(Request $request)
    {
        $email = $request->email;
        $adminIfo = Admin::where('email', $email)->get();
        if (count($adminIfo) > 0) {
            $sendData = array('status'=>true);
        } else {
            $sendData = array('status'=>false);
        }
        return response()->json($sendData);
    }

    public function createAdmin(Request $request)
    {
        $validator = Validator::make($request->all(), [
                  'email' => 'required|string|email|max:255|unique:admins',
                  'password' => 'required',
                  'name' => 'required|string|max:255',
                  'admin_level' => 'required'
      ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Submit error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        try {
            $admin = array(
              'email'=>$request->email,
              'password'=>bcrypt($request->password),
              'admin_level'=>$request->admin_level,
              'name'=>$request->name,
              'devicename'=>'chrome',
              'usertype' => 'admin',
              'usr_status' => 1
            );
            Admin::create($admin);
            return response()->json(['message' => 'Create admin successfully!', 'data' => null, 'response_code' => 1], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Can not create admin account!', 'data' => $e, 'response_code' => 0], 200);
        }
    }

    public function updateAdmin(Request $request)
    {
        $id = $request->admin_id;
        $user = Admin::findOrFail($id);
        $updateData = array('name'=>$request->name, 'admin_level'=>$request->admin_level);
        $user->update($updateData);
        return response()->json(array('status'=>'success'));
    }

    public function activate(Request $request)
    {
        $updateData = array('usr_status'=>$request->status);
        $user = Admin::findOrFail($request->adminUpdateID);
        $user->update($updateData);
        return response()->json(array('status'=>'success'));
    }

    public function resetPassword(Request $request)
    {
        $updateData = array('password'=>bcrypt($request->password));
        $user = Admin::findOrFail($request->admin_id);
        $user->update($updateData);
        return response()->json(array('status'=>'success'));
    }

    public function removeAdmin(Request $request)
    {
        if (Gate::allows('isSuperAdmin')) {
            return response()->json(array('status'=>'error'));
        }
        $user = Admin::findOrFail($request->adminUpdateID);
        $user->delete();
        return response()->json(array('status'=>'success'));
    }

    public function changePassword(Request $request)
    {
        $user = Auth::getUser();
        $validator = Validator::make($request->all(), [
          'old_password' => 'required',
          'new_password' => 'required|confirmed',
          'new_password_confirmation' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Submit error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = bcrypt($request->get('new_password'));
            $user->save();
            return response()->json(['message' => 'Reset Password successfully!', 'data' => [], 'response_code' => 1], 200);
        } else {
            return response()->json(['message' => 'current password is incorrect!', 'data' => [], 'response_code' => 0], 200);
        }
    }

    public function getAuthuserInfo()
    {
        return response()->json(['message' => 'success', 'data' => Auth::getUser(), 'response_code' => 1], 200);
    }

    //
}
