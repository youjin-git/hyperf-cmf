<?php


namespace Yj\Request;


use Hyperf\Utils\Collection;
use Hyperf\Validation\Request\FormRequest;
use function Swoole\Coroutine\Http\request;

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


    public function prepareForValidation()
    {
//        $validator = $this->getValidatorInstance();
//        $data = $validator->getData();
//        if (isset($data['hospitalid']) && ($hospitalid = $data['hospitalid']) && in_array($hospitalid, $this->hospital)) {
//            $hospitalregionid = App(HospitalRegionModel::class)->DaoWhere([
//                'hospitalid' => $data['hospitalid']
//            ])->value('id');
//            if (empty($data['hospitalregionid'])) {
//                $validator->setData(['hospitalregionid' => $hospitalregionid] + $data);
//            }
//        }
    }

    public function rules()
    {
        return [
            'hospitalid1' => 'required',
        ];
    }


    public function setRules($rules = [])
    {
        $validator = $this->getValidatorInstance();
        $validator->setRules($rules);
        return $this;
    }

    public function messages(): array
    {
        return [
        ];
    }
}