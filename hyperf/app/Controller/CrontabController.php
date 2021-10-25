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
use App\Model\UserBill;
use App\Model\UserProject;
use App\Model\Waste;
use App\Request\RegiterRequest;
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
 * @ApiController(tag="定时脚本",prefix="crontab",description="")
 */
class CrontabController extends AbstractController
{
    /**
     * @Inject()
     * @var Project
     */
    protected $projectModel;

    /**
     * @Inject()
     * @var UserProject
     */
    protected $userProjectModel;
    /**
     * @Inject()
     * @var User
     */
    protected $user;

    /**
     * @Inject()
     * @var UserBill
     */
    protected $userBillModel;

    /**
     * @PostApi(path="send_price", description="发放余额")
     * @GetApi(path="send_price", description="发放余额")
     */
    public function send_price()
    {
        //当前时间
        $nowDate = date('Y-m-d');

        //查询所有人的项目

        $userProjectInfo = $this->userProjectModel
            ->where('start_time','<=',$nowDate)
            ->where('end_time','>=',$nowDate)
            ->where('last_send_time','<',$nowDate)
            ->where('status', 1)->orderBy('last_send_time')->first();


        if(empty($userProjectInfo)){
            return true;
        }
        //查看该项目是否结束
        $projectInfo = $this->projectModel->where('id', $userProjectInfo['project_id'])->first();
        if ($projectInfo['status'] !== 1) { //项目结束
            $userProjectInfo->status = -2;
            $userProjectInfo->save();
            return true;
        }

        $uid = $userProjectInfo->uid;

        $projectPrice = $userProjectInfo->price;

        $lastSendTime = $userProjectInfo->last_send_time;
        $start_time = $userProjectInfo->start_time;


        if($lastSendTime<$start_time){
            $newLastSendTime = $start_time;
        }else{
            $newLastSendTime = date('Y-m-d', strtotime ("+1 day", strtotime($lastSendTime)));
        }



        Db::beginTransaction();
        try {

            //给用户添加余额
            $userInfo = $this->user->where('id',$uid)->first();
            $balance =  $userInfo['balance'];
            $this->user->where('id',$uid)->increment('balance',$projectPrice);
            //添加账单
            $this->userBillModel->create([
                'uid'=>$uid,
                'type'=>1,
                'title'=>$projectInfo->name.'-'.$newLastSendTime.'-返现',
                'balance'=>$balance+$projectPrice,
                'number'=>$projectPrice,
                'send_time'=>$newLastSendTime,
                'link_id'=>$projectInfo->id
            ]);

            $userProjectInfo->increment('return_days');
            $userProjectInfo->increment('return_prices',$projectPrice);

            // Do something...
            $userProjectInfo->last_send_time = $newLastSendTime;
            $userProjectInfo->save();
            p('执行成功');
            Db::commit();
        } catch (\Throwable $ex) {
            p($ex);
            Db::rollBack();
        }


    }

    /**
     * @PostApi(path="send_price_bak", description="发放余额")
     * @GetApi(path="send_price_bak", description="发放余额")
     */

    public function send_price_bak()
    {
        echo '开始执行';

        //查询所有人的项目
        $userProjectInfo = $this->userProjectModel->where('create_time','<',date('Y-m-d'))->where('last_send_time','<',date('Y-m-d'))->where('status', 1)->orderBy('last_send_time')->first();

        if(empty($userProjectInfo)){
            return true;
        }

        //查看该项目是否结束
        $projectInfo = $this->projectModel->where('id', $userProjectInfo['project_id'])->first();

        if ($projectInfo['status'] !== 1) { //项目结束
            $userProjectInfo->status = -2;
            $userProjectInfo->save();
            return true;
        }


        $uid = $userProjectInfo->uid;
        $projectPrice = $projectInfo->price;

        $lastSendTime = $userProjectInfo->last_send_time;
        $createTime = $userProjectInfo->create_time->toDateTimeString();
        
        if($lastSendTime<$createTime){
            $lastSendTime = $createTime;
        }

        $newLastSendTime = date('Y-m-d', strtotime ("+1 day", strtotime($lastSendTime)));

        p($newLastSendTime);


        Db::beginTransaction();
        try {
            //给用户添加余额
            $userInfo = $this->user->where('id',$uid)->first();
            $balance =  $userInfo['balance'];
            $this->user->where('id',$uid)->increment('balance',$projectPrice);
            //添加账单

            $this->userBillModel->create([
                'uid'=>$uid,
                'type'=>1,
                'title'=>$projectInfo->name.'--返现',
                'balance'=>$balance+$projectPrice,
                'number'=>$projectPrice,
                'send_time'=>$newLastSendTime,
                'link_id'=>$projectInfo->id
            ]);

            // Do something...
            $userProjectInfo->last_send_time = $newLastSendTime;
            $userProjectInfo->save();
            p('执行成功');
            Db::commit();
        } catch (\Throwable $ex) {
            p($ex);
            Db::rollBack();
        }


    }
}
