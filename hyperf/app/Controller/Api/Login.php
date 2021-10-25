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
 * @ApiController(tag="授权",prefix="api/login",description="")
 */
class Login extends AbstractController
{

    /**
     * @Inject()
     * @var MiniProgramService
     */
    protected $MiniProgramService;

    /**
     * @Inject()
     * @var UserService
     */
    protected $userService;

    /**
     * @PostApi(path="openid", description="微信小程序授权")
     * @FormData(key="code|code", rule="required")
     */
    public function openid()
    {
        $data = $this->getValidatorData();

        $userInfoCong = $MiniProgramService = $this->MiniProgramService->getSession($data['code']);



//        $session_key = $userInfoCong['session_key'];
//
//        $data = $this->request->inputs([
//            'spread_spid',
//            'spread_code',
//            'iv',
//            'encryptedData',
//        ]);

//        $userInfo = json_decode($this->request->input('rawData'), true);
//        $userInfo = array_change_key_case($userInfo, CASE_LOWER);
//        dump($userInfo);
//        $userInfo = $this->MiniProgramService->encryptor($session_key, $data['iv'], $data['encryptedData']);
//        if (!$userInfo) {
//            err('openid获取失败');
//        }

        $userInfo['openid'] = $userInfoCong['openid'] ?? '';
        $userInfo['unionid'] = $userInfoCong['unionid'] ?? $userInfo['unionId'] ?? '';
        if (!$userInfo['openid']) err('openid获取失败');
        $wechatUser = $this->userService->regByOpenid($userInfo['openid'], $userInfo);
        succ(['token' => $this->userService->getToken($wechatUser->id)]);
    }


}