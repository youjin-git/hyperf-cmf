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
namespace App\Controller\Admin;


use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\ConfigValue;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use App\Request\WasteRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use App\Controller\AbstractController;
use App\Middleware\CheckAdminMiddleware;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class WasteController extends AbstractController
{

    /**
     * @Inject()
     * @var ConfigValue
     */
    protected $configValueModel;

    /**
     * @Inject()
     * @var Waste
     */
    protected $wasteModel;

    /**
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function lists(){
        $uid =$this->request->input('uid');

        $lists = $this->wasteModel->where('user_id',$uid)->paginate()->toArray();

        foreach($lists['data'] as &$v){

             $v['picture'] = getFilePath(explode(',',$v['picture']));

        }

        succ($lists);
    }

    /**
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function _add(WasteRequest $request){
            $data = $request->validated();
            $uid = Context::get('uid');
            $data['user_id'] = $uid;
            $this->wasteModel->create($data);
            succ();
    }

    /**
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function _edit(WasteRequest $request){
        $data = $request->validated();
        $uid = Context::get('uid');
        $id = $this->request->input('id')?:err('id is empty');
        if($this->wasteModel->where('user_id',$uid)->where('id',$id)->update($data)){
            succ();
        }else{
            err('编辑失败');
        }

    }

    /**
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function _delete(){
        $id = $this->request->input('id')?:err('id is empty');
        $uid = Context::get('uid');
        if($this->wasteModel->where('user_id',$uid)->where('id',$id)->delete()){
            succ();
        }else{
            err('删除失败');
        }


    }

}
