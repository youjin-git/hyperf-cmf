<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class RegiterRequest extends FormRequest
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
            'company_name'=>'required', //公司名称
            'waste_data'=>'required',
            'nickname'=>'',
            'icon'=>'',
            'phone'=>'required', //手机号码
            'password'=>'required', //密码
            'legal_code'=>'', //法人代码
            'discharge_permit'=>'', //排污许可证
            'province'=>'', //省
            'city'=>'', //市
            'area'=>'', //区
            'address'=>'', //公司地址
            'postcode'=>'', //邮编
            'director'=>'', //危废负责人
            'department'=>'', //部门
            'fax'=>'', //传真
            'email'=>'',//邮箱
            'collection_real_name'=>'', //收运联系人
            'collection_phone'=>'', //收运电话
            'product_name'=>'', //主要产品
            'industry'=>'', //行业
            'company_type'=>'', //公司类型
        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [
            'company_name.required'=>'公司名称必填',
            'phone.required'=>'手机号码必填',
            'password.required'=>'密码必填',
        ];
    }
}
