<?php


namespace App\Request;


use Hyperf\Utils\Collection;
use Hyperf\Validation\Request\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function collection(): Collection
    {
        return _Collect($this->getValidatorInstance()->validated());
    }
}