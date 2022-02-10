<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/5
 * Time: 0:00
 */

namespace App\Service\EasyWeChat;


use EasyWeChat\Factory;
use Hyperf\Framework\ApplicationFactory;
use Hyperf\Utils\ApplicationContext;
use Symfony\Contracts\Cache\CacheInterface;
use GuzzleHttp\Client;

class BaseService
{
    public $appId = 'wx96ed9d6460eb122f';
    public $secret = '74038f3f2831a2250b6fc8433a173bfe';

    protected $service;

    public function getConfig()
    {
//        $wechat = systemConfig(['site_url', 'routine_appId', 'routine_appsecret']);
        return [
            'app_id' => $this->appId,
            'secret' => $this->secret,
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => BASE_PATH . '/wechat.log',
            ],
            'guzzle' => [
                'timeout' => 3.0, // 超时时间（秒）
                'verify' => false, // 关掉 SSL 认证（强烈不建议！！！）
            ]
        ];
    }



    public function getApp(){
            $config = $this->getConfig();
            $app = Factory::miniProgram($config);
            $config = $app['config']->get('http', []);
            $config['verify'] = false;
            $app->rebind('http_client', new Client($config));
            $app['cache'] = ApplicationContext::getContainer()->get(\Psr\SimpleCache\CacheInterface::class);
            return $app;
    }
}