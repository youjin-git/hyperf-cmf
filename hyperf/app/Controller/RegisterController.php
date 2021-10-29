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


use App\Model\Admin\ConfigValue;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;

/**
 * @AutoController()
 */
class RegisterController extends AbstractController
{
    /**
     * @Inject()
     * @var ConfigValue
     */
    protected $configValueModel;

    /**
     * @Inject()
     * @var User
     */
    protected $userModel;

    /**
     * @Inject()
     * @var Waste
     */
    protected $wasteModel;

    public function init(){
        $res = $this->configValueModel->_get([
            'industry',
            'company'
        ]);

        $data = [];
        foreach($res as $key=>$v){
            foreach($v as $vv){
                $data[$key][] = ['value'=>$vv,'label'=>$vv];
            }
        }

        succ($data);
    }

    public function register(RegiterRequest $request)
    {
        $data = $request->validated();

        //查看是否已经提交过申请
        if($this->userModel->where('phone',$data['phone'])->exists()){
                err('该用户已经注册或提交过申请');
        }

        if(!($waste_data = json_decode($data['waste_data'],true))){
                err('请填写申请的废物信息');
        }

        Db::beginTransaction();
        try{
            if($user = $this->userModel->create($data)){
                //开始添加废物
                foreach ($waste_data as &$v){
                    $v['user_id'] = $user->id;
                    $v['type'] = implode(',',$v['type']);
                    $v['harmful_ingredients'] = implode(',',$v['harmful_ingredients']);
                    $v['danger_character'] = implode(',',$v['danger_character']);
                    $v['packing'] = implode(',',$v['packing']);
                    $v['picture'] = implode(',',array_column($v['picture'], 'id'));

                    $this->wasteModel->create($v);
                }
                Db::commit();
            }
        } catch(\Throwable $e){
              dump($e);
            Db::rollBack();
            err('添加失败');
        }
        succ($user);
    }
}
