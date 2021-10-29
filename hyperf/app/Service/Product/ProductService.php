<?php

namespace App\Service\Product;

use App\Dao\Product\ProductDao;
use App\Model\Product\Product;
use App\Service\Service;
use Hyperf\Database\Model\Builder;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class ProductService extends Service
{
    /**
     * @Inject()
     * @var Product
     */
    protected $productModel;

    /**
     * @Inject()
     * @var ProductDao
     */
    protected $productDao;

    public function getModel()
    {
        return $this->productModel;
    }

    public function make(Builder $query, $params)
    {
        if (isset($params['id']) && $params['id']) {
            $query->where('id', $params['id']);
        }
    }

    public function list($params = [])
    {
        return $this->productDao->DaoWhere($params)->DaoOrder()->paginate();
    }

    public function add($params)
    {
        $productObject = $this->productModel->create($params);
        $productObject->productDownload()->create($params);
        $productObject->productContent()->create($params);
        return $productObject;
    }

    public function edit($id, $params)
    {
        $productObject = $this->productModel->find($id);
        $productObject->fill($params);
        $productObject->productDownload()->update(array_intersect_key($params, ['url']));
        $productObject->productContent()->update(array_intersect_key($params, ['conent']));
        return $productObject->save();
    }

    public function detail($params)
    {
        return $this->where($params)->with(['productDownload', 'productContent'])->first();
    }

}