<?php

namespace App\Controller\Admin\College;

use App\Controller\Admin\BaseController;
use App\Dao\College\CollegeSubjectDao;
use App\Dao\College\CollegeTaskDao;
use App\Dao\System\SystemAreasDao;
use App\Form\Elm;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\GetApi;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="/admin/task")
 */
class TaskController extends BaseController
{

    /**
     * @Inject()
     * @var SystemAreasDao
     */
    protected $systemAreasDao;

    /**
     * @Inject()
     * @var CollegeTaskDao
     */
    protected $collegeTaskDao;
    /**
     * @PostApi(path="form",description="任务表单")
     * @FormData(key="id",rule="")
     */
    public function Form()
    {
        $params = $this->getValidatorData();
        if($id = $params->get('id')){
           $formData =  $this->collegeTaskDao->detail($id);
           dump($formData);
           _GetLastSql(1);
        }else{
            $formData = collect();
        }
        $form = Elm::createForm($id?'/admin/task/edit':'/admin/task/add',[],['labelPosition'=>'right']);

        $form->setRule([
            Elm::input('username','姓名')->required()->col(12),
            Elm::radio('gender', '性别')->options(function (){
                return [['value'=>'1','label'=>'男'],['value'=>'2','label'=>'女']];
            })->required()->col(12),
            Elm::input('ticket', '准考证')->required()->col(12),
            Elm::input('mobile', '手机号')->required()->col(12),
            Elm::radio('department_type', '科类')->options(function (){
                return collect(['1'=>'不分文理','2'=>'文科','3'=>'理科','4'=>'综合改革'])->transform(function ($item,$key){
                    return ['value'=>(string)$key,'label'=>$item];
                })->values()->toArray();
            })->required(),
            Elm::cascader('city', '省份')->options(function (){
                    return list_to_tree($this->systemAreasDao->DaoLevel(2)->select(['parentcode','code as value','title as label'])->get(),pid:'parentcode',id:'value');
            })->col(12),

            Elm::checkbox('departments', '选科')->options(function (){
                $CollegeSubjectData = App(CollegeSubjectDao::class)->select(['id as value','name as label'])->get()->toArray();
                return $CollegeSubjectData;
            })->button(true)->col(12),

            Elm::input('score','高考文化分数')->required()->col(12),
            Elm::input('art_score','美术统考分数')->required()->col(12),
            Elm::input('comprehensive_score','综合分')->required()->col(12),
            Elm::input('comprehensive_ranking','综合分排名')->required()->col(12),
            Elm::input('chinese_score','语文分数')->required(),
            Elm::input('foreign_id','外语语种')->required()->col(12),
            Elm::input('foreign_score','外语分数')->required()->col(12),
            Elm::number('qualified_subject_num','统考合格科数')->min(0)->max(6)->required(),

            Elm::input('school_test_score','校考成绩')->required()->col(12),
            Elm::input('school_test_ranking','校考成绩')->required()->col(12),
            Elm::input('school_test_name','校考画室')->required(),
            Elm::cascader('intention_school', '意向学校')->options(function (){
                return list_to_tree($this->systemAreasDao->DaoLevel(2)->select(['parentcode','code as value','title as label'])->get(),pid:'parentcode',id:'value');
            })->prop('multiple',true)->col(8),
            Elm::cascader('intention_subject', '意向专业')->options(function (){
                return list_to_tree($this->systemAreasDao->DaoLevel(2)->select(['parentcode','code as value','title as label'])->get(),pid:'parentcode',id:'value');
            })->prop('multiple',true)->col(8),
            Elm::cascader('intention_city', '意向城市')->options(function (){
                return list_to_tree($this->systemAreasDao->DaoLevel(2)->select(['parentcode','code as value','title as label'])->get(),pid:'parentcode',id:'value');
            })->prop('multiple',true)->col(8),

            Elm::textarea('character', '性格特点'),
            Elm::textarea('interest', '兴趣爱好'),

        ]);
        $lists = $form->setTitle(is_null($id) ? '新增任务' : '编辑任务')->formData($formData->toArray());
        _SUCCESS(formToData($lists));
    }

    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists(){
        $params = $this->getValidatorData();
        $data = $this->collegeTaskDao->lists($params);
        _GetLastSql(1);
        _SUCCESS($data);
    }

    /**
     * @PostApi(path="add")
     * @return void
     */
    public function add(){
        $params = $this->request->post();
        $params = collect($params);
        $this->collegeTaskDao->add($params);
        _SUCCESS();
    }

    /**
     * @PostApi(path="edit")
     */
    public function edit(){
        $params = $this->request->post();
        $params = collect($params);
        $this->collegeTaskDao->edit($params->get('id'),$params->except('id'));
        _SUCCESS();
    }
}