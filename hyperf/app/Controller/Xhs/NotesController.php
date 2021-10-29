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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * @Middleware(CheckAdminMiddleware::class)
 * @ApiController(tag="小红书笔记",prefix="xhs/notes",description="")
 */
class NotesController extends AbstractController
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
        $client = $this->clientFactory->create(['verify'=>false]);
        for ($j = 1; $j <= $highestRow; $j++) {
            $url = $objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue();
            if($url){
            $note_id = end(explode('/',explode('?',$url)[0]));
            if(!$this->xhsNotesModel->where('note_id',$note_id)->first()){
                \Swoole\Coroutine::sleep(0.5);
                //开始采集
                $data = $client->get('https://api01.idataapi.cn/post/xiaohongshu_ids',[
                    'query'=>[
                        'apikey'=>'RPCY9k3YIxrTmqV8hkfTk7jIsRZG4bWcsDQHzApIDf72rAvSOhWLUOPVRCmpopLU',
                        'id'=>$note_id
                    ]
                ])->getBody()->getContents();

                $data = json_decode($data,true);

                if($data['retcode'] !== '000000'){
                    err($data['message']);
                }
                $data = $data['data'][0];
                $createData = [
                    'url'=>$url,
                    'note_id'=>$note_id,
                    'poster_id' => $data['posterId'],
                    'comments' => $data['commentCount'],
                    'nickname' => $data['posterScreenName'],
                    'image'=>$data['imageUrls'][0]??'',
                    'title'=>$data['title'],
                    'likes' => $data['likeCount'],
                    'publish_time' => $data['publishDate'],
                    'views' => $data['viewCount'],
                    'favorite' => $data['favoriteCount'],
                ];
                $this->xhsNotesModel->create($createData);
            }
            }
        }
        succ();
    }

    /**
     * @PostApi(path="delete", description="删除")
     * @FormData(key="ids|ID", rule="")
     */
    public function delete(){
        $data = $this->getValidatorData();
        $this->xhsNotesModel->whereIn('id',json_decode($data['ids'],true))->delete();
        _GetLastSql();
        succ();
    }
    /**
     * @PostApi(path="export", description="获取用户信息")
     */
    public function export(){

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $line = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $header = [
            'title'=>'文章标题',
            'nickname'=>'小红书昵称',
            'poster_id'=>'发表人ID',
            'likes'=>'点赞',
            'favorite'=>'收藏',
            'comments'=>'评论',
            'publish_time_format'=>'发布时间',
        ];

        $data = $this->xhsNotesModel->get();

        $i=0;
        foreach ($header as $key=>$v){
            $sheet->getColumnDimension($line[$i])->setAutoSize(true);
            $sheet->setCellValue($line[$i] . '1', (string)$v);
            $i++;
        }

        foreach($data as $k=>$v){
            $i=0;
            foreach ($header as $key=>$item) {
                    $sheet->setCellValue($line[$i] . ($k+2), (string)$v[$key]."\t");
                    $i++;
            }
        }

//        $ress = $this->xhsNotesModel->get();
//        foreach($ress as $kk=> $res) {
//            $sheet->setactivesheetindex($kk);
//            $i = 0;
//            foreach ($title as $k => $vv) {
//                    $sheet->setCellValue($line[$i] . '2', (string)$res[$k]);
//                    $i++;
//            }
//            $i = 0;
////            foreach ($title as $k => $v) {
////                $sheet->setCellValue($line[$i] . '1', $v);
////                $sheet->getColumnDimension($line[$i])->setWidth(50);
////                $i++;
////            }
//
//            $titles = [
//                'month' => '月份',
//                'price' => '总费用',
//                'social_security_base_price' => '社保基数',
//                'advance_payment_price' => '社保总费率',
//                'fund_base_price' => '公积金基数',
//                'fund_rate' => '公积金总费率',
//            ];
//            $i = 0;
//            foreach ($titles as $kk => $vv) {
//                $sheet->setCellValue($line[$i] . '3', $vv);
//                $i++;
//            }
//
//
//            foreach ($res['order_detail'] as $k => $v) {
//                $i = 0;
//                foreach ($titles as $kk => $vv) {
//                    dump($line[$i] . ($k + 3), (string)$v[$kk]);
//                    $sheet->setCellValue($line[$i] . ($k + 4), (string)$v[$kk]);
//                    $i++;
//                }
//            }
//            $sheet->getRowDimension(1)->setRowHeight(20);
//        }
//        $name = $res['insured']['name'];

        $name = time();
        $writer   = new Xlsx($spreadsheet);
        $path = config('file.storage.local.root').'/excel/'.$name.'.xlsx';
        $writer->save($path);
//        $spreadsheet->disconnectWorksheets();
        succ($path);
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
            Elm::input('name', '姓名'),
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
            $where['nickname'] = ['like'=>'%'.$data['keywords'].'%'];
        }

        if(isset($data['title']) && $data['title']){
            $where['title'] = ['like'=>'%'.$data['title'].'%'];
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
     * @PostApi(path="add", description="添加")
     * @FormData(key="wechat|关键词", rule="")
     * @FormData(key="url|url", rule="")
     * @FormData(key="name|name", rule="")
     * @FormData(key="email|关键词", rule="")
     * @FormData(key="mechanism|关键词", rule="")
     * @FormData(key="remark|关键词", rule="")
     */
    public function add(){
        $data = $this->getValidatorData();
        $xhsTalent = $this->xhsTalentModel->create($data);
        succ();
    }


    /**
     * @PostApi(path="lists", description="获取用户信息")
     * @FormData(key="keywords|关键词", rule="")
     * @FormData(key="is_del|is_del", rule="")
     * @FormData(key="title|title", rule="")
     * @FormData(key="sort|sort", rule="")
     */
    public function lists(){
       $data = $this->getValidatorData();

       $where = $this->makeWhere($data);
       if($data['is_del']){
           $xhsNotesModel = $this->xhsNotesModel->withTrashed();
       }else{
           $xhsNotesModel = $this->xhsNotesModel;
       }
       $lists = $xhsNotesModel
           ->where2query($where)
           ->where(function($query)use($data){
           })
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
