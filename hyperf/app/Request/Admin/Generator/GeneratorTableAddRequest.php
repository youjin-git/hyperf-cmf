<?php


namespace App\Request\Admin\Generator;


use App\Request\BaseRequest;

class GeneratorTableAddRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'name' => 'required',
        ];
    }

    /**
     * 获取已定义验证规则的错误消息
     */
    public function messages(): array
    {
        return [
        ];
    }

}