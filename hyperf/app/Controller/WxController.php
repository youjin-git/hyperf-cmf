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


use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\ConfigValue;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use App\Request\WasteRequest;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Exceptions\Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;

/**
 * @AutoController()
 */
class WxController extends AbstractController
{
    public function get_code(){
        $config = [
            'app_id' => 'wx8006fee89cbd4b6f',
            'secret' => 'a61a133235acd86d81d7803661a7d4d3',
            'response_type' => 'array',
        ];
        $app = Factory::officialAccount($config);
        $redirectUrl = $app->oauth->scopes(['snsapi_userinfo'])->redirect(c('site_url'));
        succ($redirectUrl);
    }


    public function get_openid(){
        $code = $this->request->input('code');
        dump($code);
        $config = [
            'app_id' => 'wx8006fee89cbd4b6f',
            'secret' => 'a61a133235acd86d81d7803661a7d4d3',
            'response_type' => 'array',
            'guzzle' => [
                'timeout' => 3.0, // 超时时间（秒）
                'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
            ]
        ];

        $app = Factory::officialAccount($config);
        $app->oauth->setGuzzleOptions(['verify'=>false]);
        $user = $app->oauth->userFromCode($code);

        succ($user->getId());
    }

    public function getTel(){

    }
}
