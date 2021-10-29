<?php


namespace App\Dao\System;


use App\Dao\BaseDao;
use App\Form\Elm;

use Hyperf\Database\Model\Builder;
use think\exception\ValidateException;

class GroupDataDao extends BaseDao
{
    public function MakeWith(): array
    {
        return ['group'];
    }

    public function MakeWhere(Builder $query, $params)
    {
        if (isset($params['id']) && $params['id']) {
            $query->where('id', $params['id']);
        }
        if (isset($params['group_id']) && $params['group_id']) {
            $query->where('group_id', $params['group_id']);
        }
    }


    public function form($id, $groupId, $formData = [])
    {
        $fields = App(GroupDao::class)->fields($groupId);
        dump($fields, $formData);
        $form = Elm::createForm($id == 0 ? '/admin/group_data/add/' . $groupId : '/admin/group_data/update/' . $groupId);
        $rules = [];
        foreach ($fields as $field) {
            if ($field['type'] == 'image') {
                $rules[] = Elm::YjUpload()->field($field['field'])->name('图片');
                continue;
            } else if (in_array($field['type'], ['select', 'checkbox', 'radio'])) {
                $options = array_map(function ($val) {
                    [$value, $label] = explode(':', $val, 2);
                    return compact('value', 'label');
                }, explode("\n", $field['param']));
                $rule = Elm::{$field['type']}($field['field'], $field['name'])->options($options);
                if ($field['type'] == 'select') {
                    $rule->filterable(true)->prop('allow-create', true);
                }
                $rules[] = $rule;
                continue;
            }
            if ($field['type'] == 'file') {
//                $rules[] = Elm::uploadFile($field['field'], $field['name'], Route::buildUrl('configUpload', ['field' => 'file'])->build())->headers(['X-Token' => request()->token()]);
                continue;
            }
            $rules[] = Elm::{$field['type']}($field['field'], $field['name'], '');
        }
        $rules[] = Elm::number('sort', '排序', 0);
        $rules[] = Elm::switches('status', '是否显示', 1)->activeValue(1)->inactiveValue(0)->inactiveText('关闭')->activeText('开启');
        $id && ($rules[] = Elm::hidden('id', $id));
        $form->setRule($rules);
        $data = $form->setTitle($id == 0 ? '添加数据' : '编辑数据')->formData($formData);
        return $data;
    }

    public function checkData(array $data, array $fieldRule)
    {
        foreach ($fieldRule as $rule) {
            if (!isset($data[$rule['field']]) || $data[$rule['field']] === '') {
                return $this->error($rule['name'] . '不能为空');
            }
            if ($rule['type'] === 'number' && $data[$rule['field']] < 0) {
                return $this->error($rule['name'] . '不能小于0');
            }
        }
        return true;
    }

}