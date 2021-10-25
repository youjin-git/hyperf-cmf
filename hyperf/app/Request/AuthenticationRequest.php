<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class AuthenticationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'real_name'=>'required',
            'id_card'=>'required',
            'wx_picture_id'=>'required',
            'zfb_picture_id'=>'required',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [
            'real_name.required' => '姓名为空',
            'id_card.required'  => '身份证为空',
            'wx_picture_id.required' => '请上传微信收款码',
            'id_card.zfb_picture_id'  => '请上传支付宝收款码',
        ];
    }
}
