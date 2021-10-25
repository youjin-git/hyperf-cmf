<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Util;

use App\Controller\AbstractController;
use App\Model\Admin;
use App\Model\User;
use App\Service\UserService;
use Hyperf\Cache\Cache;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Container;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use Psr\Container\ContainerInterface;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\GetApi;

/**
 * @ApiController(tag="验证码",prefix="util/captcha",description="")
 */
class CaptchaController extends AbstractController
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @GetApi(path="get", description="图片验证码")
     * @PostApi(path="get", description="图片验证码")
     */
    public function get()
    {


        $builder = new CaptchaBuilder(null,new PhraseBuilder(4));
        $builder->build();
//        p($this->container);
        $cache = $this->container->get(\Psr\SimpleCache\CacheInterface::class);
        //生成key
        $generator = $this->container->get(IdGeneratorInterface::class);
        $key = (string)$generator->generate();
//        $key = uniqid(microtime(true), true);
        $code = $builder->getPhrase();
        $cache->set('captcha' . $key,$code,600);
        $image = $builder->inline();
        succ(compact('key','image','code'));
    }

    public function check($key,$code){
        $cache = $this->container->get(\Psr\SimpleCache\CacheInterface::class);
        $_code  = $cache->get('captcha' . $key);

        if (!$_code) {
            err('验证码过期');
        }

        if (strtolower($_code) != strtolower($code)) {
           err('验证码错误');
        }
        //删除code
        $cache->delete('captcha' . $key);
        return true;
    }

    public function checkCode(string $key,string $code){
        $cache = $this->container->get(\Psr\SimpleCache\CacheInterface::class);

        $_code  = $cache->get('captcha' . $key);

        if (!$_code) {
            err('验证码过期');
        }

        if (strtolower($_code) != strtolower($code)) {
            err('验证码错误');
        }
        //删除code
        $cache->delete('captcha' . $key);

        return true;
    }

}
