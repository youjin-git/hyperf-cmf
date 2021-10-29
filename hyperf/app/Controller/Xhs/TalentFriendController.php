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

namespace App\Controller\Xhs;

use App\Controller\AbstractController;
use App\Form\Elm;
use App\Middleware\CheckAdminMiddleware;
use App\Model\Xhs\XhsTalent;
use App\Model\Xhs\XhsTalentFriend;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * @Middleware(CheckAdminMiddleware::class)
 * @ApiController(tag="小红书",prefix="xhs/talent_friend",description="")
 */
class TalentFriendController extends AbstractController
{
    /**
     * @Inject()
     * @var XhsTalent
     */
    protected $xhsTalentModel;

    /**
     * @Inject()
     * @var XhsTalentFriend
     */
    protected $xhsTalentFriendModel;

    protected function makeWhere($data){
        $where = [];
        if(isset($data['fans']) && $data['fans']){
            [$start,$end] = explode('-',$data['fans']);
            $where['fans'] = ['between'=>[$start,$end]];
        }
        if(isset($data['notes']) && $data['notes']){
            [$start,$end] = explode('-',$data['notes']);
            $where['notes'] = ['between'=>[$start,$end]];
        }

        if(isset($data['keywords']) && $data['keywords']){
            $where['nickname'] = ['like'=>$data['keywords']];
        }

        return $where;
    }
    /**
     * @PostApi(path="lists", description="获取用户信息")
     * @FormData(key="keywords|关键词", rule="")
     * @FormData(key="order|order", rule="")
     * @FormData(key="sort|sort", rule="")
     * @FormData(key="fans|fans", rule="")
     * @FormData(key="notes|notes", rule="")
     * @FormData(key="keywords|keywords", rule="")
     */
    public function lists(){
        $data = $this->getValidatorData();
        $where = $this->makeWhere($data);

        $lists = $this->xhsTalentModel
            ->where2query($where)
            ->has('friend')
            ->with('friend')
            ->orderBy($data['order']?:'create_time',$data['sort']?:'desc')
            ->paginate();

        succ($lists);

//       $data = $this->getValidatorData();
//       $where = $this->makeWhere($data);
//       $lists = $this->xhsTalentFriendModel
//           ->whereHas('talent',function($query)use($where){
//                 return where2query($where,$query);
//           })
//           ->with('talent')
//           ->orderBy($data['order']?:'create_time',$data['sort']?:'desc')
//           ->paginate();
//       succ($lists);
    }

    /**
     * @PostApi(path="add_friend", description="获取用户信息")
     * @FormData(key="id|id", rule="")
     */
    public function addFriend(){
        $data = $this->getValidatorData();
        $res = $this->xhsTalentFriendModel->updateOrCreate(['talent_id'=>$data['id']]);
        succ($res);
    }

    /**
     * @PostApi(path="create", description="create")
     * @FormData(key="id|id", rule="")
     */
    public function create()
    {
        $id = $this->request->input('id');
        if($id){
//            $formData = [];
            $formData = $this->xhsTalentFriendModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }
        $form =  Elm::createForm('xhs/talent_friend/update');
        $form->setRule([
            Elm::input('price1','图文价格报备')->col(10),
            Elm::input('price2','图文价格不报备')->col(10),
            Elm::input('price3','视频价格报备')->col(10),
            Elm::input('price4','视频价格不报备')->col(10),
            Elm::input('views','平均阅读量'),
            Elm::input('remark','备注'),
            Elm::hidden('id',$id)
        ]);
        $lists = $form->setTitle('编辑')->formData($formData);
        succ(formToData($lists));
        return [];
    }

    /**
     * @PostApi(path="update", description="获取用户信息")
     * @FormData(key="price1|价格", rule="")
     * @FormData(key="price2|价格", rule="")
     * @FormData(key="price3|价格", rule="")
     * @FormData(key="price4|价格", rule="")
     * @FormData(key="id|Id", rule="")
     * @FormData(key="remark|remark", rule="")
     * @FormData(key="views|views", rule="")
     */
    public function update(){
        $data = $this->getValidatorData();
        $id = $data['id'];
        $res = $this->xhsTalentFriendModel->where('id',$id)->update($data);
        _GetLastSql();
        succ($res);
    }

}
