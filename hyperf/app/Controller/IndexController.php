<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;


use App\Controller\AbstractController;
use App\Model\Hy\Banner;
use App\Model\Admin\ConfigValue;
use App\Model\Hy\News;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use GuzzleHttp\Middleware;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\View\RenderInterface;

/**
 * @ApiController(tag="访问路径",description="")
 */
class IndexController extends AbstractController
{

    /**
     * @Inject()
     * @var RenderInterface
     */
    protected $render;

    /**
     * @GetApi(path="/", description="后台访问路径")
     */
    public function index()
    {
        return $this->render->render('index');
    }

    /**
     * @GetApi(path="admin", description="后台访问路径")
     */
    public function admin()
    {
        return $this->render->render('system');
    }

}
