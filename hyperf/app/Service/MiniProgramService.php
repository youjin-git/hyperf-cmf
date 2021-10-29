<?php


namespace App\Service;


use EasyWeChat\Factory;
use EasyWeChat\Kernel\Traits\HasHttpRequests;
use EasyWeChat\MiniProgram\Application;
use Psr\SimpleCache\CacheInterface;
use Hyperf\Utils\ApplicationContext;

class MiniProgramService
{
    use HasHttpRequests;

    protected $service;

    public function create()
    {
        $config = $this->getConfig();
        $this->service = Factory::miniProgram($config);
        $this->service['cache'] = ApplicationContext::getContainer()->get(CacheInterface::class);
        return $this->service;
    }

    public function getConfig()
    {
        $wechat = systemConfig(['site_url', 'routine_appId', 'routine_appsecret']);

        return [
            'app_id' => $wechat['routine_appId'],
            'secret' => $wechat['routine_appsecret'],
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => BASE_PATH . '/wechat.log',
            ]
        ];
    }

    public function getSession($code)
    {
        return $this->create()->auth->session($code);
    }


    public function encryptor($sessionKey, $iv, $encryptData)
    {
        return $this->create()->encryptor->decryptData($sessionKey, $iv, $encryptData);
    }
}