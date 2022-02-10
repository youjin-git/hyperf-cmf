<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/5
 * Time: 0:18
 */

namespace App\Service\EasyWeChat;


class MiniProgramService extends BaseService
{
    public function getTelAndMobile($code,$iv,$encryptedData){
        $app = $this->getApp();
        $session = $app->auth->session($code);
        dump($session);
        if(isset($session['errcode'])){
            _Error($session['errmsg']);
        }
        $openid = $session['openid'];
        $decryptedData = $app->encryptor->decryptData($session['session_key'], $iv, $encryptedData);
        dump($decryptedData);
        return [$decryptedData['phoneNumber'],$openid];
    }
}