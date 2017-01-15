<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 16/9/26
 * Time: 下午6:49
 */

namespace App\Api\Controllers;

use App\Api\Requests\DpCodeRequest;
use App\Api\Requests\SearchDoctorForIdRequest;
use App\Api\Transformers\Transformer;
use App\DoctorRelation;
use App\User;
use Illuminate\Http\Request;

class SearchController extends BaseController
{
    /**
     * Doctors
     *
     * @param SearchDoctorForIdRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function doctors(SearchDoctorForIdRequest $request)
    {
        $idList = explode(',', $request->get('id_list'));
        $newIdList = array();

        /**
         * 自动过滤1-5内置用户：
         */
        foreach ($idList as $item){
            if($item > 5){
                array_push($newIdList, $item);
            }
        }

        $doctors = User::find($newIdList);

        $data = array();
        foreach ($doctors as $doctor) {
            array_push($data, Transformer::searchDoctorTransform_2($doctor));
        }

        return response()->json(compact('data'));
    }

    /**
     * @param DpCodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDoctorInfoForDpCode(DpCodeRequest $request)
    {
        $my = User::getAuthenticatedUser();
        if (!isset($my->id)) {
            return $my;
        }

        $user = User::getDoctorForDpCode($request->get('dp_code'));
        if (isset($user['id']) && $user['id'] != '' && $user['id'] != null) {
            $user['dp_code'] = User::getDpCode($user['id']);
            $user['is_friend'] = (DoctorRelation::getIsFriend($my->id, $user['id'])[0]->count) == 2 ? true : false;
            $data = Transformer::searchDoctorTransform_dpCode($user);

            return response()->json(compact('data'));
        } else {
            return response()->json(['success' => ''], 204);
        }
    }
}
