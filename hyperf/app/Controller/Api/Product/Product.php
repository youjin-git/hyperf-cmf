<?php


namespace App\Controller\Api\Product;


use App\Controller\AbstractController;
use App\Service\Product\ProductService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;

/**
 * @ApiController(tag="产品",prefix="api/product",description="")
 */
class Product extends AbstractController
{
    /**
     * @Inject()
     * @var ProductService
     */
    protected $productService;

    /**
     * @PostApi(path="detail", description="获取用户信息")
     * @FormData(key="id|id", rule="required")
     */
    public function detail()
    {
        $data = $this->getValidatorData();
        $datail = $this->productService->detail(['id' => $data['id']]);
        succ($datail);
    }

    /**
     * @PostApi(path="new", description="获取用户信息")
     */
    public function new()
    {
        $list = $this->productService
            ->where()
            ->with('image')
            ->orderBy('create_time', 'desc')
            ->limit(10)
            ->get();
        succ($list);
    }

    /**
     * @PostApi(path="list", description="获取用户信息")
     */
    public function list()
    {
        $params = $this->request->all();
        $list = $this->productService->list($params);
        _GetLastSql();
        succ($list);
    }


}