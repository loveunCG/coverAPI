<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\CompanyModel;
use Illuminate\Support\Facades\Validator;
use App\Model\InsuranceModel;

class SettingController extends Controller
{
    public function getInsuranceTable(Request $request)
    {
        $insurances = InsuranceModel::orderBy('id', 'desc')->get();
        $i = 1;
        if (count($insurances) > 0) {
            foreach ($insurances as $data) {
                $insurance_id = '<input type="hidden" class="insurance_id" value="' . $data->id . '">';

                $insurance_list['data'][] = array(
                    $i++ . $insurance_id,
                    $data->insurance_name,
                    $data->insur_comment,
                    $data->created_at->format("Y-m-d h:i:s")
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
        $insurData = InsuranceModel::where(['id'=>$insurance_id])->first();
        return response()->json($insurData);
    }


    public function addInsurance(Request $request)
    {
        if ($request->has('insurance_id')) {
            $where = ['id'=>$request->insurance_id];
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

    public function getCompanyTableInfo(Request $request)
    {
        $companys = CompanyModel::all();
        $i = 1;
        if (count($companys) > 0) {
            foreach ($companys as $data) {
                $company_id = '<input type="hidden" class="company_id" value="' . $data->id . '">';
                
                $company_list['data'][] = array(
                    $i++ . $company_id,
                    $data->company_name,
                    $data->insurance->insurance_name,
                    $data->created_at->format("Y-m-d h:i:s")
                );
            }
        } else {
            $company_list['data'][] = array(
                '',
                '',
                '',
                '',
                ''
            );
        }
        return response()->json($company_list);
    }
    //

    public function getCompany($id = null)
    {
        $companyData = CompanyModel::findOrFail($id);
        return response()->json($companyData);
    }

    public function addCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'company_name' => 'required',
          'company_conment' => 'required',
          'insurance_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Submit error', 'data' => $validator->errors(), 'response_code' => 0], 200);
        }
        $saveData = [];
        try {
            $saveData['company_name'] = $request->company_name;
            $saveData['company_conment'] = $request->company_conment;
            $saveData['insurance_id'] = $request->insurance_id;
            if ($request->has('company_id')) {
                CompanyModel::findOrFail($request->company_id)->update($saveData);
                return response()->json(['message' => 'update succesfully!', 'data' => null, 'response_code' => 1], 200);
            } else {
                CompanyModel::create($saveData);
                return response()->json(['message' => 'Insert succesfully!', 'data' => null, 'response_code' => 1], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'server error!', 'data' => $e, 'response_code' => 0], 200);
        }
    }
}
