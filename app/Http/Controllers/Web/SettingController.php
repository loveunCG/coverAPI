<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\InsuranceModel;


class SettingController extends Controller
{
    public function getInsuranceTable(Request $request)
    {
        $insurances = InsuranceModel::orderBy('insur_id', 'desc')->get();
        $i = 1;
        if (count($insurances) > 0) {
            foreach ($insurances as $data) {
                $insurance_id = '<input type="hidden" class="insurance_id" value="' . $data->insur_id . '">';

                $insurance_list['data'][] = array(
                    $i++ . $insurance_id,
                    $data->insurance_name,
                    $data->insur_comment
                );
            }
        } else {
            $insurance_list['data'][] = array(
                '',
                '',
                '',
                '',
                ''
            );
        }
        return response()->json($insurance_list);
    }
    //

    public function getInsurance($insurance_id = null)
    {
        $insurData = InsuranceModel::where(['insur_id'=>$insurance_id])->first();
        return response()->json($insurData);
    }


    public function addInsurance(Request $request)
    {
        if ($request->has('insurance_id')) {
            $where = ['insur_id'=>$request->insurance_id];
            $updateData = array(
                'insurance_name'=>$request->insuranceName,
                'insur_comment'=>$request->insuranceComment
            );
            $insuranceUpdate = InsuranceModel::where($where);
            $insuranceUpdate->update($updateData);
        } else {
            $insertData = array(
                'insurance_name'=>$request->insuranceName,
                'insur_comment'=>$request->insuranceComment
            );
            InsuranceModel::create($insertData);
        }
        return response()->json(array('status'=>true));
    }
    //
}
