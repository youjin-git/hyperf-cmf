<?php

namespace App\Service\System;


use app\common\dao\BaseDao;

use app\common\repositories\BaseRepository;
use App\Dao\System\GroupDataDao;
use app\model\groupData\SystemGroupData;
use App\Service\BaseService;
use App\Service\Service;
use FormBuilder\Exception\FormBuilderException;
use FormBuilder\Factory\Elm;
use FormBuilder\Form;
use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Paginator\LengthAwarePaginator;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\ValidateException;
use think\facade\Route;
use think\Model;

class GroupDataService extends BaseService
{
    /**
     * @Inject()
     * @var GroupDataDao
     */
    public $groupDataDao;

    public function add($data, $fieldRule)
    {
        if (!$this->groupDataDao->checkData($data['value'], $fieldRule)) {
            return false;
        }
        return $this->groupDataDao->create($data);
    }

    public function delete($id)
    {
        return $this->groupDataDao->where('id', $id)->delete();
    }

    public function update($id, $data, $fieldRule)
    {
        if (!$this->groupDataDao->checkData($data['value'], $fieldRule)) {
            return false;
        }
        $groupData = $this->groupDataDao->DaoWhere(['id' => $id])->first();
        $groupData->fill($data);
        return $groupData->save();
    }

    public function create(int $merId, array $data, array $fieldRule)
    {
        $this->checkData($data['value'], $fieldRule);
        $data['mer_id'] = $merId;

        return $this->dao->create($data);
    }


    public function merUpdate($merId, $id, $data, $fieldRule)
    {
        $this->checkData($data['value'], $fieldRule);
        return $this->dao->merUpdate($merId, $id, $data);
    }


    public function checkData(array $data, array $fieldRule)
    {
        foreach ($fieldRule as $rule) {
            if (!isset($data[$rule['field']]) || $data[$rule['field']] === '') {
                throw new ValidateException($rule['name'] . '不能为空');
            }
            if ($rule['type'] === 'number' && $data[$rule['field']] < 0)
                throw new ValidateException($rule['name'] . '不能小于0');
        }
    }


    public function list($params)
    {

        $list = $this->groupDataDao->DaoWhere($params)->DaoWith()->paginate();

        $list->each(function ($item) {
            $value = $item->value;
            unset($item->value);
            $types = array_column($item->group->fields, 'type', 'field');
            foreach ($value as $key => $v) {
                $item->{$key} = $this->analysis($v, $types[$key]);
            }
        });

        return $list;
    }

    private function analysis($value, $type)
    {
        switch ($type) {
            case 'image':
                $value = getFilePath($value);
                break;
            default:
                break;
        }
        return $value;
    }


    public function updateForm(int $groupId, int $merId, int $id)
    {
        $data = $this->dao->getGroupDataWhere($merId, $groupId)->where('group_data_id', $id)->find()->toArray();
        $value = $data['value'];
        unset($data['value']);
        $data += $value;
        return $this->form($groupId, $id, $data);
    }


    public function groupData(string $key, ?int $page = null, ?int $limit = 10)
    {
        $make = di()->get(GroupService::class);
        $groupId = $make->keyById($key);
        if (!$groupId) return [];
        return $this->list(['group_id' => $groupId]);
    }


    public function idByData(int $id, int $merId)
    {
        $data = $this->dao->merGet($id, $merId);
        if (!$data) return;
        return json_decode($data['value']);
    }


    public function groupDataId(string $key, int $merId, ?int $page = null, ?int $limit = 10)
    {
        $make = app()->make(GroupService::class);
        $groupId = $make->keyById($key);
        if (!$groupId) return [];
        return $this->dao->getGroupDataId($merId, $groupId, $page, $limit);
    }

}