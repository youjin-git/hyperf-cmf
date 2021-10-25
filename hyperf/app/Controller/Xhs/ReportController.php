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
use App\Model\Xhs\XhsReport;
use App\Model\Xhs\XhsTalent;
use App\Model\Xhs\XhsTalentFriend;
use FormBuilder\Driver\CustomComponent;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * @Middleware(CheckAdminMiddleware::class)
 * @ApiController(tag="提报",prefix="xhs/report",description="")
 */
class ReportController extends AbstractController
{
    /**
     * @Inject()
     * @var XhsReport
     */
    protected $xhsReportModel;
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
     * @PostApi(path="create", description="create")
     * @FormData(key="id|id", rule="")
     */
    public function create()
    {
        $id = $this->request->input('id');
        if($id){
            $formData = [];
//            $formData = $this->bannerModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }
        $form =  Elm::createForm('xhs/report/add');
         $form->setRule([
             Elm::select('types','提报类型')->options(function (){
                $options = [];
                dump(systemConfig('report_types'));
                foreach(systemConfig('report_types') as $k=>$v){
                    $options[] = Elm::option($v['value'], $v['label']);
                }
                return $options;
             })->required(),
             Elm::input('report_price','提报价格')->required(),
             Elm::hidden('id',$id)

        ]);
        $lists = $form->setTitle('添加提报')->formData($formData);
        succ(formToData($lists));
        return [];
    }

    public function makeWhere($where){
        return ['record_id'=>0];
    }

    /**
     * @PostApi(path="lists", description="获取用户信息")
     * @FormData(key="keywords|关键词", rule="")
     * @FormData(key="order|order", rule="")
     * @FormData(key="sort|sort", rule="")
     */
    public function lists(){
       $data = $this->getValidatorData();
       $where = $this->makeWhere($data);

       $lists = $this->xhsReportModel
           ->orderBy($data['order']?:'create_time',$data['sort']?:'desc')
           ->where($where)->paginate()->toArray();
        $report_types = systemConfig('report_types');
        $report_types_format = [];
        foreach ($report_types as $v){
            $report_types_format[$v['value']] = $v['label'];
        }
       foreach($lists['data'] as &$v){
           $v['types_name'] = $report_types_format[$v['types']];
       }
       succ($lists);
    }

    /**
     * @PostApi(path="add", description="添加提报")
     * @FormData(key="id|关键词", rule="required")
     * @FormData(key="types|关键词", rule="required")
     * @FormData(key="report_price|提报价格", rule="required")
     */
    public function add(){
        $params = $this->getValidatorData();
        $info = $this->xhsTalentFriendModel
            ->with(['talent'=>function($query){
            }])
            ->where('id',$params['id'])->first();

        $data = [
            'types'=>$params['types'],
            'views'=>$info['views'],
            'report_price'=>$params['report_price'],
            'price'=>$info['price'.$params['types']],
            'image'=>$info['talent']['image'],
            'liked'=>$info['talent']['liked'],
            'location'=>$info['talent']['location'],
            'nickname'=>$info['talent']['nickname'],
            'fans'=>$info['talent']['fans'],
            'follows'=>$info['talent']['follows'],
            'gender'=>$info['talent']['gender'],
            'desc'=>$info['talent']['desc'],
            'notes'=>$info['talent']['notes'],
            'talent_id'=>$info['talent_id'],
        ];

        $res = $this->xhsReportModel->create($data);
        $res?succ():err();
    }

    /**
     * @PostApi(path="record", description="叮当")
     * @FormData(key="ids|IDS", rule="required")
     */
    public function record(){
        $data = $this->getValidatorData();
        $admin_id = $this->getUid();
        $ids = json_decode($data['ids'],true);
        $res = $this->xhsReportModel
            ->whereIn('id',$ids)
            ->update(['record_id'=>time(),'record_admin_id'=>$admin_id]);
        $res?succ():err();
    }

    /**
     * @PostApi(path="edit", description="编辑")
     * @FormData(key="num|关键词", rule="")
     * @FormData(key="rate|url", rule="")
     * @FormData(key="remark|关键词", rule="")
     */
    public function edit(){
        $id = $this->request->input('id');
        $data = $this->getValidatorData();
        $xhsTalent = $this->xhsReportModel->where('id',$id)->first();
        $xhsTalent->fill($data);
        $xhsTalent->save();
        succ();
    }
    /**
     * @PostApi(path="record_lists", description="叮当")
     */
    public function record_lists(){
        $data = $this->xhsReportModel->select(['record_id','record_admin_id'])
            ->where('record_id','>',0)
            ->orderBy('create_time','desc')
            ->with(['xhsReport','admin'])->groupBy('record_id')->paginate()->toArray();

        $report_types = systemConfig('report_types');

        $report_types_format = [];
        foreach ($report_types as $v){
            $report_types_format[$v['value']] = $v['label'];
        }


        foreach($data['data'] as $key=>&$v){
            $v['record_time'] = date('Y-m-d H:i:s',$v['record_id']);
        }

        unset($v);



        $data1 = $data['data'];
        $lists = [];
        foreach($data1 as $k=>$v){
            foreach($v['xhs_report'] as $key=>$vv){
                $list = [];
                $vv['types_name'] = $report_types_format[$vv['types']];
                $list['record_time'] = $v['record_time'];
                $list['admin_name'] = $v['admin']['username'];
                $list['rowspan'] = $key==0?count($v['xhs_report']):0;
                $list['colspan'] = $key==0?1:0;
                $list = array_merge($list,$vv);
                $lists[] = $list;
            }
        }
        $data['data'] = $lists;
        succ($data);
    }

}
