<?php


namespace App\Controller\Swagger;


use App\Controller\AbstractController;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\View\RenderInterface;

/**
 * @ApiController(tag="余额管理",prefix="swagger",description="")
 */
class SwaggerController extends AbstractController
{

    /**
     * @Inject()
     * @var RenderInterface
     */
    protected $render;

    /**
     * @PostApi(path="", description="认证详情")
     * @GetApi(path="", description="认证详情")
     */
     public function index(){
        return $this->render->render('swagger/index_tpl',['swaggerjsonurl'=>'swagger/swagger.json']);
     }
}