<?php


namespace App\Controller\Admin\System;


use App\Constants\GroupType;
use App\Controller\AbstractController;
use App\Dao\System\GroupDao;
use App\Form\Elm;
use App\Middleware\CheckAdminMiddleware;
use App\Service\System\GroupService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use think\facade\Route;

/**
 * User: 尤金
 * @Middleware(CheckAdminMiddleware::class)
 * @ApiController(tag="",prefix="admin/group",description="")
 */
class GroupController extends AbstractController
{
    /**
     * @Inject()
     * @var GroupService
     */
    protected $groupService;

    /**
     * @PostApi(path="list", description="获取用户信息")
     */
    public function list()
    {
        $params = $this->request->all();
        $data = $this->groupService->list($params);
        succ($data);
    }

    /**
     * @PostApi(path="detail", description="获取用户信息")
     * @FormData(key="id|Id", rule="required")
     */
    public function detail()
    {
        $params = $this->getValidatorData();
        $data = $this->groupService->first($params['id']);
        succ($data);
    }

    /**
     * @PostApi(path="form", description="获取用户信息")
     */
    public function form()
    {

        $id = $this->request->input('id');
        if ($id) {
            $formData = $this->groupService->first($id)->toArray();
        } else {
            $formData = [];
        }
        $form = Elm::createForm($id ? 'admin/group/edit' : 'admin/group/add');
        $form->setRule([
            Elm::input('name', '组合数据名称')->required(),
            Elm::input('key', '组合数据key')->required(),
            Elm::input('info', '组合数据说明'),
            Elm::number('sort', '排序', 0),
            Elm::group('fields', '字段')->rules([
                Elm::select('type', '类型')->required()->options(function () {
                    $options = [];
                    foreach (GroupDao::TYPES as $value => $label) {
                        $options[] = compact('value', 'label');
                    }
                    return $options;
                }),
                Elm::input('name', '字段名称'),
                Elm::input('field', '字段key'),
                Elm::textarea('param', '参数'),
            ]),
            Elm::hidden('id', $id),
        ]);
        $form = $form->setTitle(is_null($id) ? '添加组合数据' : '编辑组合数据')->formData($formData);
        succ(formToData($form));
    }

    /**
     * @PostApi(path="update", description="获取用户信息")
     */
    public function update()
    {

    }

    /**
     * @PostApi(path="add", description="获取用户信息")
     */
    public function add()
    {
        $params = $this->request->all();
        $res = $this->groupService->create($params);
        $res ? succ() : err();
    }

    /**
     * @PostApi(path="edit", description="获取用户信息")
     */
    public function edit()
    {
        $params = $this->request->all();
        $res = $this->groupService->update($params['id'], $params);
        $res ? succ() : err();
    }
}