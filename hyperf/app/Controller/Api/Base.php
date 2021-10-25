<?php


namespace App\Controller\Api;

use App\Controller\AbstractController;
use App\Service\MiniProgramService;
use App\Service\UserService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Symfony\Component\Serializer\Annotation\Ignore;

/**
 * @ApiController(tag="首页",prefix="api",description="")
 */
class Base extends AbstractController
{
    /**
     * @PostApi(path="home", description="微信小程序授权")
     */
    public function home()
    {
        $banner = systemGroupData('home_banner', 1, 10);
        $menu = systemGroupData('home_menu');
        $hot = systemGroupData('', 1, 4);
        $ad = systemConfig(['home_ad_phome_hotic', 'home_ad_url']);
        $category = [];

        succ(compact('banner', 'menu', 'hot', 'ad', 'category'));
    }
}