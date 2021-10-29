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
use App\Model\Admin\File;
use App\Model\Xhs\XhsNotes;
use App\Model\Xhs\XhsTalent;
use App\Model\Xhs\XhsTalentFriend;
use App\Model\Xhs\XhsTalentNotes;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * @Middleware(CheckAdminMiddleware::class)
 * @ApiController(tag="小红书",prefix="xhs/talent",description="")
 */
class TalentController extends AbstractController
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

    /**
     * @Inject()
     * @var XhsTalentNotes
     */
    protected $xhsTalentNotes;

    /**
     * @Inject()
     * @var XhsNotes
     */
    protected $xhsNotesModel;
    /**
     * @Inject()
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @Inject()
     * @var File
     */
    protected $fileModel;

    /**
     * @PostApi(path="import_notes", description="导入笔记")
     * @FormData(key="id|关键词", rule="")
     */
    public function import_notes(){
        $data = $this->getValidatorData();
        $path = $this->fileModel->where('id',$data['id'])->value('path');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $objPHPExcel = $reader->load(config('file.storage.local.root').$path);
        $sheet = $objPHPExcel->getSheet(0);   //excel中的第一张sheet
        $highestRow = $sheet->getHighestRow();       // 取得总行数
        for ($j = 1; $j <= $highestRow; $j++) {
            $url = $objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue();
            $note_id = end(explode('/',explode('?',$url)[0]));
            if(!$this->xhsNotesModel->where('note_id',$note_id)->first()){
                //开始采集
                $this->xhsNotesModel->create(compact('url','note_id'));
            }
        }
        succ();

//        $client = $this->clientFactory->create(['verify'=>false]);
//        $data = $client->get('https://api01.idataapi.cn/profile/xiaohongshu',[
//            query=>[
//                'apikey'=>'RPCY9k3YIxrTmqV8hkfTk7jIsRZG4bWcsDQHzApIDf72rAvSOhWLUOPVRCmpopLU',
//                'uid'=>$uuid
//            ]
//        ])->getBody()->getContents();
//
//        $data = json_decode($data,true);
//        if($data['retcode'] !== '000000'){
//            err($data['message']);
//        }
//        $data = $data['data'];
//        $createData = [
//            'friend' => $data['friendCount'],
//            'follows' => $data['followCount'],
//            'image' => $data['avatarUrl'],
//            'desc' => $data['biography'],
//            'notes' => $data['postCount'],
//            'fans' => $data['fansCount'],
//            'nickname' => $data['userName'],
//        ];
    }

    /**
     * @PostApi(path="top", description="获取用户信息")
     * @FormData(key="id|置顶", rule="")
     * @FormData(key="top|是否置顶", rule="")
     */
    public function top(){
        $id = $this->getValidatorData('id');
        $is_top = $this->getValidatorData('top');

        $xhsTalent = $this->xhsTalentModel->where('id',$id)->first();
        $xhsTalent->fill(['top'=>$is_top]);
        $xhsTalent->save();
        succ();
    }
    /**
     * @PostApi(path="parsing", description="获取用户信息")
     * @FormData(key="id|关键词", rule="")
     */
    public function parsing(){
            $data = $this->getValidatorData();
            $path = $this->fileModel->where('id',$data['id'])->value('path');

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $objPHPExcel = $reader->load(config('file.storage.local.root').$path);
            $sheet = $objPHPExcel->getSheet(0);   //excel中的第一张sheet

            $highestRow = $sheet->getHighestRow();       // 取得总行数

            for ($j = 1; $j <= $highestRow; $j++) {
                $url = $objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue();
                $uuid = end(explode('/',explode('?',$url)[0]));
                if(!$this->xhsTalentModel->where('uuid',$uuid)->first()){
                    //开始采集
                    $this->xhsTalentModel->create(compact('url','uuid'));
                }
            }

            succ();
    }

    /**
     * @PostApi(path="create", description="获取用户信息")
     * @FormData(key="id|关键词", rule="")
     */
    public function create(){
        $id = $this->request->input('id');
        if($id){
            $formData = $this->xhsTalentModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }
        $form =  Elm::createForm($id?'xhs/talent/edit':'xhs/talent/add');
        $form->setRule([
            Elm::input('url', '采集链接'),
            Elm::input('wechat', '微信号'),
            Elm::input('email', '邮箱'),
            Elm::input('mechanism', '机构'),
            Elm::input('remark', '备注'),
            Elm::hidden('id',$id)
        ]);
        $lists = $form->setTitle($id?'编辑达人':'添加达人')->formData($formData);
        succ(formToData($lists));
        return [];
    }

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
     * @PostApi(path="edit", description="编辑")
     * @FormData(key="wechat|关键词", rule="")
     * @FormData(key="url|url", rule="")
     * @FormData(key="name|name", rule="")
     * @FormData(key="email|关键词", rule="")
     * @FormData(key="mechanism|关键词", rule="")
     * @FormData(key="remark|关键词", rule="")
     */
    public function edit(){
        $id = $this->request->input('id');
        $data = $this->getValidatorData();
        dump($data);
        $xhsTalent = $this->xhsTalentModel->where('id',$id)->first();
        $xhsTalent->fill($data);
        $xhsTalent->save();
        succ();
    }

    /**
     * @PostApi(path="delete", description="删除")
     * @FormData(key="id|ID", rule="")
     */
    public function delete(){
        $data = $this->getValidatorData();
        $this->xhsTalentModel->where('id',$data['id'])->delete();
        succ();
    }
    /**
     * @PostApi(path="add", description="添加")
     * @FormData(key="wechat|关键词", rule="")
     * @FormData(key="url|url", rule="required")
     * @FormData(key="email|关键词", rule="")
     * @FormData(key="mechanism|关键词", rule="")
     * @FormData(key="remark|关键词", rule="")
     */
    public function add(){
        $data = $this->getValidatorData();
        $data['uuid'] = end(explode('/',explode('?',$data['url'])[0]));
        dd($data);
        $xhsTalent = $this->xhsTalentModel->create($data);
        succ();
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
           ->with('friend')
           ->orderBy('top','ace')
           ->orderBy($data['order']?:'create_time',$data['sort']?:'desc')
           ->paginate();
       _GetLastSql();
       succ($lists);
    }

    /**
     * @PostApi(path="waitCollentNum", description="获取用户信息")
     */
    public function waitCollentNum(){
        $count = $this->xhsTalentModel->whereNull('collenct_time')->count();
        succ($count);
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
     * @PostApi(path="notes", description="notes")
     * @FormData(key="id|Id", rule="")
     */
    public function notes(){
      $params = $this->getValidatorData();
      $res = $this->xhsTalentNotes->where('talent_id',$params['id'])->paginate(10);
      succ($res);
    }


}
