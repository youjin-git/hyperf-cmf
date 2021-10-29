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
use App\Model\UserExtract;
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
 * @Middleware(CheckLoginMiddleware::class)
 * @ApiController(tag="提现管理",prefix="extract",description="")
 */
class ExtractController extends AbstractController
{
    /**
* @Inject()
* @var User
*/
    protected $user;

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
     * @var UserBill
     */
    protected $userBillModel;

    /**
     * @Inject()
     * @var UserExtract
     */
    protected $userExtractModel;

    /**
     * @PostApi(path="lists", description="提现列表")
     */
    public function  lists(){
        $uid = Context::get('uid');
        $lists = $this->userExtractModel->orderBy('create_time','desc')->where('uid',$uid)->paginate();
        succ($lists);
    }

    /**
     * @PostApi(path="add", description="添加提现记录")
     * @FormData(key="extract_price|提现余额", rule="required")
     */
    public function  add(){
        $uid = Context::get('uid');
        $extract_price = (float)$this->request->input('extract_price')?:err('请填写提现余额');
        $extract_type = $this->request->input('extract_type')?:err('请选择提现方式');
        //查看用户的余额
        if($extract_price<10){
            err('提现金额最少10元');
        }
        if($extract_price>100){
            err('提现金额最大100元');
        }

        Db::beginTransaction();
        try {
            $userInfo = $this->user->lock(true)->where('id',$uid)->first()?:err();
            $balance = $userInfo->balance;
            p($balance<$extract_price);
            if($userInfo->balance<$extract_price){
                err('余额不足',2000);
            }
            $userInfo->balance -=  $extract_price;
            $userInfo->save();
            //添加提现记录
            $userExtract = $this->userExtractModel->create([
                'uid'=>$uid,
                'extract_type'=>$extract_type,
                'extract_price'=>$extract_price,
                'balance'=>$balance-$extract_price,
                'status'=>0
            ]);
            //添加到记录里面去

            $this->userBillModel->create([
                'extract_type'=>$extract_type,
                'uid'=>$uid,
                'type'=>2,
                'title'=>'提现',
                'balance'=>$balance-$extract_price,
                'number'=>-$extract_price,
                'link_id'=>$userExtract->id
            ]);

            Db::commit();
         } catch (\Throwable $ex) {
              p($ex);
               Db::rollBack();
               err($ex->getMessage());
        }
        succ();
    }


}
