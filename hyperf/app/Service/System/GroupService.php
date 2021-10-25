<?php

namespace App\Service\System;

use App\Dao\System\GroupDao;
use App\Model\GroupData\SystemGroupData;
use App\Service\Service;
use FormBuilder\Annotation\Info;
use FormBuilder\Exception\FormBuilderException;
use FormBuilder\Factory\Elm;
use FormBuilder\Form;
use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Inject;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Route;
use think\Model;


class GroupService extends Service
{
    /**
     * @Inject()
     * @var GroupDao
     */
    public $groupDao;

    public function list($params)
    {
        return $this->groupDao->DaoWhere($params)->paginate();
    }

    const TYPES = ['input' => '文本框', 'number' => '数字框', 'textarea' => '多行文本框', 'radio' => '单选框', 'checkbox' => '多选框', 'select' => '下拉框', 'file' => '文件上传', 'image' => '图片上传', 'color' => '颜色选择框'];


    public function create(array $data)
    {
        $data['fields'] = $this->groupDao->verifyFields($data['fields']);
        return $this->groupDao->create($data);
    }

    public function first($id)
    {
        return $this->groupDao->DaoWhere(['id' => $id])->first();
    }

    public function update(int $id, array $data)
    {
        $data['fields'] = $this->groupDao->verifyFields($data['fields']);
        return $this->groupDao->where('id', $id)->update($data);
    }


    public function updateForm(int $id)
    {
        return $this->form($id, $this->dao->get($id)->toArray());
    }


    public function page(int $page, int $limit)
    {
        $list = $this->dao->page($page, $limit)->hidden(['fields', 'sort'])->select();
        $count = $this->dao->count();
        return compact('count', 'list');
    }


    public function keys(int $id): array
    {
        return array_column($this->fields($id), 'field');
    }


    public function delete($id)
    {
        Db::transaction(function () use ($id) {
            $this->delete($id);

            /** @var GroupDataRepository $make */
            $make = app()->make(GroupDataRepository::class);
            $make->clearGroup($id);
        });
    }


    public function keyById(string $key)
    {
        return $this->groupDao->where('key', $key)->value('id');
    }


    public function getGroupData($merId, $groupId, ?int $page = null, ?int $limit = 10)
    {
        $query = $this->SystemGroupDataModel->where('mer_id', $merId)->where('group_id', $groupId)->where('status', 1)
            ->orderBy('sort', ' DESC');
//        $groupData = $query->paginate();

        if (!is_null($page)) $query->skip($limit * ($page - 1))->take($limit);

        $groupData = $query->pluck('value');
//        foreach ($query->pluck('value') as $k => $v) {
//            $groupData[] = json_decode($v, true);
//        }
        return $groupData;
    }
}