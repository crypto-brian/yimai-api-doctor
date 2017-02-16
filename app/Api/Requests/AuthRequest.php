<?php
/**
 * Created by PhpStorm.
 * User: lyx
 * Date: 16/4/20
 * Time: 下午2:27
 */

namespace App\Api\Requests;

use App\Http\Requests\Request;

/**
 * Class AuthRequest
 * @package App\Api\Requests
 */
class AuthRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'phone' => 'required|digits_between:11,11'
        ];

        if (isset($_POST['password'])) {
            $rules = [
                'phone' => 'required|digits_between:11,11|unique:doctors',
                'password' => 'required|between:6,60',
                'verify_code' => 'required|digits_between:4,4|exists:user_verify_codes,code,phone,' . $_POST['phone'],
                'inviter_dp_code' => 'between:6,7'
            ];
        }

        return $rules;
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'required' => ':attribute不能为空',
            'digits_between' => ':attribute必须为:min位长的数字',
            'unique' => ':attribute已注册',
            'between' => ':attribute长度必须在:min和:max之间',
            'exists' => ':attribute错误'
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'phone' => '手机号码',
            'password' => '用户密码',
            'verify_code' => '验证码',
            'inviter_dp_code' => '邀请码'
        ];
    }

    /**
     * @param array $errors
     * @return mixed
     */
    public function response(array $errors)
    {
        return response()->json(['message' => current($errors)[0]], 403);
    }
}
