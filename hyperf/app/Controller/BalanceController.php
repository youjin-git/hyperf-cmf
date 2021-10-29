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
use App\Middleware\CheckLoginMiddleware;
use App\Model\Hy\Banner;
use App\Model\Admin\ConfigValue;
use App\Model\Hy\News;
use App\Model\Project;
use App\Model\User;
use App\Model\UserBalance;
use App\Model\UserBalanceLog;
use App\Model\UserBill;
use App\Model\UserExtract;
use App\Model\UserProject;
use App\Model\Waste;
use App\Request\RegiterRequest;
use App\Service\Pay\AliPay;
use App\Service\Pay\PayFactory;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Apidog\Annotation\ApiVersion;
use Hyperf\Apidog\Annotation\ApiServer;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\ApiResponse;
use Hyperf\Apidog\Annotation\Body;
use Hyperf\Apidog\Annotation\DeleteApi;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\Header;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\ApiDefinition;
use Hyperf\Apidog\Annotation\ApiDefinitions;
use Hyperf\Apidog\Annotation\Query;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;


/**
 * @Middleware(CheckLoginMiddleware::class)
 * @ApiController(tag="余额管理",prefix="balance",description="")
 */
class BalanceController extends AbstractController
{
    /**
    * @Inject()
    * @var User
    */
    protected $user;

    /**
     * @Inject()
     * @var UserBalance
     */
    protected $userBalance;

    /**
     * @Inject()
     * @var UserBalanceLog
     */
    protected $userBalanceLogModel;
    /**
     * @PostApi(path="add", description="添加提现记录")
     * @FormData(key="price|提现余额", rule="required")
     * @FormData(key="type|提现方式", rule="required")
     * @FormData(key="openid|openid", rule="")
     */
    public function  add(){
        $data =   Context::get('validator.data');
        $uid = Context::get('uid');
        $data['uid'] = $uid;
        $balanceInfo = $this->userBalance->create($data);

        $pay_type = $data['type'];
        //开始生成支付系统
        /* @var $payFactory AliPay */
        $payFactory =  PayFactory::create($pay_type);
        //$orderInfo->price
        $payFactory->setPrice(0.01);
        $payFactory->setTitle($balanceInfo->id.'充值');
        $payFactory->setOrderSn($balanceInfo->id);
        $payFactory->setNotifyUrl($pay_type=='AliPay'?'/notify/zfb/balance':'/notify/wx/balance');
        dump($pay_type=='AliPay'?'/notify/zfb/balance':'/notify/wx/balance');
        $payFactory->setOpenid($data['openid']);
        $url = $payFactory->exec();
        succ($url);
    }


    /**
     * @PostApi(path="lists", description="账单列表")
     */
    public function  lists(){
        $uid = Context::get('uid');
        $lists = $this->userBalanceLogModel->orderBy('id','desc')->where('uid',$uid)->paginate();
        succ($lists);
    }




}
