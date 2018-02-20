<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function getAgentTableInfo(Request $request)
    {
        $agentDatas = User::where(['usertype'=>'agent'])->orderBy('created_at')->get();
        $i = 1;
        if (count($agentDatas) > 0) {
            foreach ($agentDatas as $agentData) {
                $agent_id = '<input type="hidden" class="agent_id" value="' . $agentData->id . '">';
                if ($agentData->isAvailable==1) {
                    $agent_status = '<a href="#" class="btn btn-success"><i class="fa fa-circle"></i></a>';
                } else {
                    $agent_status = '<a href="#" disabled class="btn btn-success"><i class="fa fa-circle-o"></i></a>';
                }

                if ($agentData->isVerified==1) {
                    $isVerified = '<a href="#" class="btn disabled btn-success-alt"><i class="ti ti-check"></i></a>';
                } else {
                    $isVerified = '<a href="#" class="btn disabled btn-info-alt"><i class="ti ti-info-alt"></i></a>';
                }

                $agent_list['data'][] = array(
                $i++ . $agent_id,
                $agentData->username,
                $agentData->email,
                $agentData->phoneno,
                $agentData->address,
                $agentData->postcode,
                $agentData->state,
                $agentData->devicename,
                $agentData->longitude,
                $agentData->latitude,
                $isVerified,
                $agent_status,
                $agentData->created_at->format("Y-m-d h:i:s"),
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

    public function getAgentDetailInfo($agentId = null)
    {
        if (!$agentId) {
            return response()->json();
        }
        $agentInfo = User::where('id', $agentId)->first();
        return response()->json($agentInfo);
    }

    public function create(Request $request)
    {
        $insertData = array(
          'username'=>$request->agentName,
          'email'=>$request->email,
          'phoneno'=>$request->phoneNum,
          'address'=>$request->address,
          'postcode'=>$request->post_Code,
          'state'=>$request->state,
          'country'=>$request->country,
          'status'=>$request->agent_status,
          'usertype'=>'agent',
        );
        User::create($insertData);
        return response()->json(array('status'=>'success'));
    }

    public function update(Request $request)
    {
        $updateData = array(
            'username'=>$request->agentName,
            'email'=>$request->email,
            'phoneno'=>$request->phoneNum,
            'address'=>$request->address,
            'postcode'=>$request->post_Code,
            'state'=>$request->state,
            'country'=>$request->country,
            'status'=>$request->agent_status,
        );
    }

    public function removeAgent(Request $request)
    {
        try {
            if ($request->adminUpdateID) {
                User::findOrFail($request->adminUpdateID)->delete();
                return response()->json(array('status'=>true));
            } else {
                return response()->json(array('status'=>false));
            }
        } catch (\Exception $exception) {
            return response()->json(array('status'=>false));
        }
    }

    public function resetPassword(Request $request)
    {
        if ($request->password) {
            $updateData = array('password'=>$request->password);
            if (User::findOrFail($request->agent_id)->update($updateData)) {
                return response()->json(array('status'=>true));
            } else {
                return response()->json(array('status'=>false));
            }
        } else {
            return response()->json(array('status'=>'no password'));
        }
    }
    public function getCustomTableInfo(Request $request)
    {
        $customerDatas = User::where(['usertype'=>'customer'])->orderBy('created_at')->get();
        $i = 1;
        if (count($customerDatas) > 0) {
            foreach ($customerDatas as $customerData) {
                $agent_id = '<input type="hidden" class="agent_id" value="' . $customerData->id . '">';
                if ($customerData->isAvailable==1) {
                    $customer_stauts = '<a href="#" class="btn btn-success"><i class="fa fa-circle"></i></a>';
                } else {
                    $customer_stauts = '<a href="#" disabled class="btn btn-success"><i class="fa fa-circle-o"></i></a>';
                }

                if ($customerData->isVerified==1) {
                    $isVerified = '<a href="#" class="btn disabled btn-success-alt"><i class="ti ti-check"></i></a>';
                } else {
                    $isVerified = '<a href="#" class="btn disabled btn-info-alt"><i class="ti ti-info-alt"></i></a>';
                }


                $agent_list['data'][] = array(
                    $i++ . $agent_id,
                    $customerData->username,
                    $customerData->email,
                    $customerData->phoneno,
                    $customerData->address,
                    $customerData->postcode,
                    $customerData->state,
                    $customerData->devicename,
                    $customerData->longitude,
                    $customerData->latitude,
                    $isVerified,
                    $customer_stauts,
                    $customerData->created_at->format("Y-m-d h:i:s"),
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
    //
    public function getCustomDetailInfo($coustomId = null)
    {
        if (!$coustomId) {
            return response()->json();
        }
        $customerInfo = User::where('id', $coustomId)->first();
        return response()->json($customerInfo);
    }


    //
}
