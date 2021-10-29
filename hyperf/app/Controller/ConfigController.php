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
use Hyperf\Apidog\Annotation\PostApi;

/**
 * @ApiController(tag="配置获取",prefix="config",description="")
 */
class ConfigController extends AbstractController
{
    /**
     * @PostApi(path="init", description="配置获取")
     */
    public function  init(){
            $data = c(['agreement','site_name','site_logo','order_agreement','advance_payment']);
            $data['site_logo'] = getFilePath($data['site_logo']);
            succ($data);
     }
}
