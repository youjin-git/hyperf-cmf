<?php


namespace App\Controller\Api\Merchant;


use App\Controller\AbstractController;
use App\Model\Product\Product;
use App\Service\Product\ProductService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;

/**
 * @ApiController(tag="商家",prefix="merchant",description="")
 */
class Merchant extends AbstractController
{
    /**
     * @Inject()
     * @var ProductService
     */
    protected $productServer;

    /**
     * @PostApi(path="detail", description="获取用户信息")
     * @FormData(key="id|id", rule="required")
     */
    public function detail()
    {
        $data = $this->getValidatorData();
        $datail = $this->productServer->detail($data['id'], [], ['merchant']);
        succ($datail);

    }


}