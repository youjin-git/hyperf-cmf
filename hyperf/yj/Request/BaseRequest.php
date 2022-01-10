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


    public function setDefault(Collection $default){
        if($default->isNotEmpty()){
            $validator = $this->getValidatorInstance();
            $validatorData = $validator->getData();
           $default->each(function ($value,$key)use(&$validatorData){
                    if(!isset($validatorData[$key])){
                        $validatorData[$key] = $value;
                    }
            });
            $validator->setData($validatorData);
        }
        return $this;
    }

    public function setRules(Collection $rules)
    {
        $validator = $this->getValidatorInstance();
        $validator->setRules($rules->toArray());
        return $this;
    }

    public function messages(): array
    {
        return [
        ];
    }
}