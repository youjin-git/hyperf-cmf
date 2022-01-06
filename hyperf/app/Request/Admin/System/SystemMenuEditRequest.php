<?php

declare(strict_types=1);

namespace App\Request\Admin\System;

use App\Request\BaseRequest;
use Hyperf\Validation\Request\FormRequest;

class SystemMenuEditRequest extends BaseRequest
{


    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id' => 'required',
            'title' => 'required',
            'pid' => 'required',
            'type' => 'required',
            'name' => 'required',
            'icon' => 'nullable',
            'path' => 'required',
            'hidden' => 'required',
            'component' => 'required',
            'active' => 'nullable',
            'hidden_breadcrumb' => 'nullable',
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
