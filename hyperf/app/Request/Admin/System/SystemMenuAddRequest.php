<?php

declare(strict_types=1);

namespace App\Request\Admin\System;

use App\Request\BaseRequest;
use Hyperf\Validation\Request\FormRequest;

class SystemMenuAddRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'pid' => 'required',
            'name' => 'required',
            'path' => 'nullable',
            'title' => 'required',
            'type' => 'required',
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
