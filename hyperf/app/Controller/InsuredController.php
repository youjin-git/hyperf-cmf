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
use App\Model\Insure;
use App\Model\Insured;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use Hyperf\Apidog\Annotation\FormData;

/**
 * @Middleware(CheckLoginMiddleware::class)
 * @ApiController(tag="参保人管理",prefix="insured",description="")
 */
class InsuredController extends AbstractController
{
    /**
     * @Inject()
     * @var Insured
     */
    protected $insuredModel;

    /**
     * @PostApi(path="init", description="参保人添加初始化")
     */

    public function init(){
        $res = c([
            'nation',
            "education",
            "household"
        ]);
//        $data = [];
//        foreach($res as $key=>$v){
//            foreach($v as $vv){
//                $data[$key][] = ['value'=>$vv,'label'=>false];
//            }
//        }
        succ($res);
    }

    /**
     * @PostApi(path="add", description="参保人添加")
     * @FormData(key="name|姓名", rule="required")
     * @FormData(key="id_card|身份证号码", rule="required")
     * @FormData(key="household|户籍性质", rule="required")
     * @FormData(key="nation|民族", rule="required")
     * @FormData(key="provice|户籍所在省市", rule="required")
     * @FormData(key="is_tax|有无单位给你发工资报税", rule="required",description="1代表有,0代表无")
     * @FormData(key="is_first_insured|有无单位给你发工资报税", rule="required",description="1代表有,0代表无")
     * @FormData(key="education|学历", rule="required")
     * @FormData(key="id_card_positive_picture_id|身份证正面", rule="required")
     * @FormData(key="id_card_back_picture_id|身份证反面", rule="required")
     */
    public function add(){
            $data =   Context::get('validator.data');
            $uid = Context::get('uid');
            $data['uid'] = $uid;
            //开始添加
            $res = $this->insuredModel->create($data);
            $res?succ():err();
    }
    /**
     * @PostApi(path="edit", description="参保人编辑")
     * @FormData(key="id|id", rule="required")
     * @FormData(key="name|姓名", rule="")
     * @FormData(key="id_card|身份证号码", rule="")
     * @FormData(key="household|户籍性质", rule="")
     * @FormData(key="nation|民族", rule="")
     * @FormData(key="provice|户籍所在省市", rule="")
     * @FormData(key="is_tax|有无单位给你发工资报税", rule="",description="1代表有,0代表无")
     * @FormData(key="is_first_insured|有无单位给你发工资报税", rule="",description="1代表有,0代表无")
     * @FormData(key="education|学历", rule="")
     * @FormData(key="id_card_positive_picture_id|身份证正面", rule="")
     * @FormData(key="id_card_back_picture_id|身份证反面", rule="")
     */
    public function edit(){
        $data = Context::get('validator.data');
        //开始添加
        $res = $this->insuredModel->where('id',$data['id'])->update($data);
        $res?succ():err();
    }
    /**
     * @PostApi(path="lists", description="参保人列表")
     */
    public function  lists(){
        $uid = Context::get('uid');
        $lists = $this->insuredModel->orderBy('id','desc')->where('uid',$uid)->paginate();
        succ($lists);
    }

    /**
     * @PostApi(path="detail", description="参保人详情")
     * @FormData(key="id|id", rule="required")
     */
    public function  detail(){
        $uid = Context::get('uid');
        $params = Context::get('validator.data');
        $info = $this->insuredModel->where('id',$params['id'])->where('uid',$uid)->first();

        succ($info);
    }

    /**
     * @PostApi(path="delete", description="参保人删除")
     * @FormData(key="id|id", rule="required")
     */
    public function  delete(){
        $uid = Context::get('uid');
        $params = Context::get('validator.data');
         $this->insuredModel->where('id',$params['id'])->where('uid',$uid)->delete();
        succ();
    }



}
