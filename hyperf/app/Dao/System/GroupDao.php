<?php


namespace App\Dao\System;


use App\Dao\BaseDao;
use Hyperf\Database\Model\Builder;


class GroupDao extends BaseDao
{

    /**
     *
     */
    const TYPES = ['input' => '文本框', 'number' => '数字框', 'textarea' => '多行文本框', 'radio' => '单选框', 'checkbox' => '多选框', 'select' => '下拉框', 'file' => '文件上传', 'image' => '图片上传', 'color' => '颜色选择框'];


    public function MakeWith(): array
    {
        return [];
    }

    public function MakeWhere(Builder $query, $params)
    {
        if (isset($params['id']) && $params['id']) {
            $query->where('id', $params['id']);
        }
    }

    public function fields($id)
    {
        return $this->where('id', $id)->value('fields');
    }

    public function verifyFields(array $fields)
    {
        if (!count($fields))
            err('字段最少设置一个');
        $data = [];
        $fieldKey = [];
        foreach ($fields as $field) {
            if (!isset($field['type']))
                err('字段类型不能为空');
            if (!isset($field['field']))
                err('字段key不能为空');
            if (!isset($field['name']))
                err('字段名称不能为空');
            if (in_array($field['field'], $fields))
                err('字段key不能重复');
            $fieldKey[] = $field['field'];
            $data[] = [
                'name' => $field['name'],
                'field' => $field['field'],
                'type' => $field['type'],
                'param' => $field['param'] ?? ''
            ];
        }
        return json_encode($data);
    }


//    public function

}