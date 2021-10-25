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
use App\Model\Admin\ConfigValue;
use App\Model\User;
use App\Model\Waste;
use App\Request\RegiterRequest;
use App\Request\WasteRequest;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;

/**
 * @AutoController()
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

//    protected $fileModel;

    public function init(){
        $res = $this->configValueModel->_get([
            'waste_type',
            "harmful_ingredients",
            "danger_character",
            "packing",
        ]);

        $data = [];
        foreach($res as $key=>$v){
            foreach($v as $vv){
                $data[$key][] = ['value'=>$vv,'label'=>false];
            }
        }
        succ($data);
    }
    /**
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function lists(){
        $uid = Context::get('uid');
        $lists = $this->wasteModel->where('user_id',$uid)->get()->each(function ($item){
            $picture = explode(',',$item->picture);
            $data = [];

            $item->type = explode(',',$item->type?:'');
            $item->harmful_ingredients = explode(',',$item->harmful_ingredients?:'');
            $item->danger_character = explode(',',$item->danger_character?:'');
            $item->packing = explode(',',$item->packing?:'');


            foreach($picture as $v){
                $data[] =['id'=>$v,'url'=>getFilePath($v)];
            }
            $item->picture = $data;
            return $item;
        });


        succ($lists);
    }

    /**
     * @Middleware(CheckLoginMiddleware::class)
     */
    public function _add(WasteRequest $request){
            $data = $request->validated();
            $uid = Context::get('uid');
            $data['user_id'] = $uid;

        $data['type'] = implode(',',$data['type']);
        $data['harmful_ingredients'] = implode(',',$data['harmful_ingredients']);
        $data['danger_character'] = implode(',',$data['danger_character']);
        $data['packing'] = implode(',',$data['packing']);
        $data['picture'] = implode(',',array_column($data['picture'], 'id'));


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
        $data['type'] = implode(',',$data['type']);
        $data['harmful_ingredients'] = implode(',',$data['harmful_ingredients']);
        $data['danger_character'] = implode(',',$data['danger_character']);
        $data['packing'] = implode(',',$data['packing']);
        $data['picture'] = implode(',',array_column($data['picture'], 'id'));
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
