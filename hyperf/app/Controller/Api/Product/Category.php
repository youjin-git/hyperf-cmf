<?php


namespace App\Controller\Api\Product;

use App\Controller\AbstractController;
use App\Middleware\CheckLoginMiddleware;
use App\Service\StoreCategoryService;
use App\Service\UserService;
use FormBuilder\Annotation\Info;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;



/**
 * @ApiController(tag="授权",prefix="api/store/product/category",description="")
 */
class Category extends AbstractController
{

    /**
     * @Inject()
     * @var StoreCategoryService
     */
   protected $storeCategoryService;

    /**
     * @PostApi(path="lst", description="获取用户信息")
     */
    public function list(){
        $list = $this->storeCategoryService->getTreeList();
        succ($list);
    }

}