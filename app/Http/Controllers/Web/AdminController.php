<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Admin;

class AdminController extends Controller
{
    public function getAdminTableInfo(Request $request)
    {
        $adminData = Admin::all();
        $i = 1;
        $agent_list = [];
        if (count($adminData) > 0) {
            foreach ($adminData as $agent) {
                if ($agent->admin_level == 1) {
                    continue;
                }
                $agent_id = '<input type="hidden" class="adminId" value="' . $agent->id . '">';
                if ($agent->admin_level == 2) {
                    $agent_level = '<button type="button" class="btn bg-info waves-effect">superAdmin</button>';
                } elseif ($agent->admin_level == 3) {
                    $agent_level = '<button type="button" class="btn bg-info waves-effect">admin</button>';
                } else {
                    $agent_level = '<button type="button" class="btn bg-info waves-effect">admin1</button>';
                }
                if ($agent->usr_status==1) {
                    $adminStatus = '<a href="#" class="btn btn-success"><i class="fa fa-circle"></i></a>';
                } else {
                    $adminStatus = '<a href="#" disabled class="btn btn-success"><i class="fa fa-circle-o"></i></a>';
                }

                $agent_list['data'][] = array(
                    $i++ . $agent_id,
                    $agent->name,
                    $agent->email,
                    $agent->created_at,
                    $agent_level,
                    $adminStatus
                );
            }
        } else {
            $agent_list['data'][] = array(
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
        return response()->json($agent_list);
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
        $agent = array(
          'email'=>$request->input('email'),
          'password'=>bcrypt($request->input('password')),
          'admin_level'=>$request->input('admin_level'),
          'name'=>$request->input('name'),
          'usr_status'=>1
        );

        Admin::create($agent);

        return response()->json(array('status'=>'success'));
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
    //
}
