<?php

namespace App\Service\Product;

use App\Model\Product\Category;
use App\Model\Product\Product;
use App\Service\Service;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class ProductCategoryService extends Service
{
    public function getModel()
    {
        return $this->productCategroyModel;
    }


    /**
     * @Inject()
     * @var Category
     */
    protected $productCategroyModel;

    protected $path = '/';

    public function getPathTag()
    {
        return $this->path;
    }

    public function lists($where = [], $order = ['id', 'desc'], $with = [])
    {
        return $this->productCategroyModel->where($where)->orderBy($order[0], $order[1])->with($with)->paginate();
    }

    public function listsByTree($where = [])
    {
        $data = $this->productCategroyModel->with('picture')->where($where)->get()->toArray();
        _GetLastSql();

        return list_to_tree($data);
    }

    public function getTreeFormat($where = [])
    {
        $data = $this->productCategroyModel->where($where)->select('cate_name as label', 'pid', 'id as value')->where('is_show', 1)->get()->each(function ($item) {
            $item->value = $item->value;
        })->toArray();

        return list_to_tree($data, 0, 'value', 'pid', 'children');
    }


    public function detail($id = 0, $where = [], $with = [])
    {
        return $this->productCategroyModel->where('id', $id)->where($where)->with($with)->first();
    }

    public function add($params = [])
    {
        $params['path'] = $this->getPathById($params['pid']);
        $params['level'] = $this->getLevelById($params['pid']);
        $data = $this->productCategroyModel->create($params);
        return $data;
    }

    public function getPathById(int $id)
    {
        if ($path = $this->productCategroyModel->where('id', $id)->value('path')) {
            $path = $path . $id . $this->getPathTag();
        }
        return $path ?: $this->getPathTag();
    }

    public function getLevelById(int $id)
    {
        $level = 0;
        if (($parentLevel = $this->productCategroyModel->where('id', $id)->value('level')) !== null)
            $level = $parentLevel + 1;
        return $level;
    }

    public function getChild(int $id)
    {
        return $this->productCategroyModel->where('path', 'like', $this->getPathById($id) . '%')->get()->toArray();
    }

    public function hasChild(int $id)
    {
        return (bool)$this->getChild($id);
    }

    public function view(int $id)
    {
        return $this->productCategroyModel->where('id', $id)->first();
    }


    public function delete($id)
    {
        if ($data = $this->getChild($id)) {
            return $this->error('存在子类');
        }

        return $this->productCategroyModel->where('id', $id)->delete();
    }

    public function edit($id, $data)
    {
        $productCategory = $this->productCategroyModel->where('id', $id)->first();
        if (!$productCategory) {
            return $this->error('不存在该数据');
        }
        $productCategory->fill($data);
        return $productCategory->save();
    }

}