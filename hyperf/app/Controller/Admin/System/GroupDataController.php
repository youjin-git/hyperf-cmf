<?php


namespace App\Controller\Admin\System;


use App\Controller\AbstractController;
use App\Middleware\CheckAdminMiddleware;
use App\Service\System\GroupDataService;
use App\Service\System\GroupService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Apidog\Annotation\PutApi;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * User: 尤金
 * @Middleware(CheckAdminMiddleware::class)
 * @ApiController(tag="",prefix="admin/group_data",description="")
 */
class GroupDataController extends AbstractController
{
    /**
     * @Inject()
     * @var GroupDataService
     */
    protected $groupDataService;

    /**
     * @Inject()
     * @var GroupService
     */
    protected $groupService;

    /**
     * @PostApi(path="list", description="获取用户信息")
     * @FormData(key="keywords|关键词", rule="")
     */
    public function list()
    {
        $params = $this->request->all();
        $data = $this->groupDataService->list($params);
        succ($data);
    }


    /**
     * @PostApi(path="form", description="获取用户信息")s-
     * @FormData(key="id|id", rule="required")
     * @FormData(key="group_id|group_id", rule="required")
     */
    public function form()
    {
        $params = $this->getValidatorData();
        $data = $this->groupDataService->groupDataDao->form($params['id'], $params['group_id']);
        succ($data);
    }

    /**
     * @PostApi(path="add/{group_id}", description="获取用户信息")
     * @FormData(key="keywords|关键词", rule="")
     */
    public function add()
    {
        $group_id = $this->request->route('group_id');
        $this->groupService->groupDao->where('id', $group_id)->exists() || err('数据组不存在');
        $field = array_column($fieldRule = $this->groupService->groupDao->fields($group_id), 'field');
        $data = $this->request->inputs(['sort', 'status'], [0, 0]);
        $data['value'] = $this->request->inputs($field);
        $data['group_id'] = $group_id;
        $data = $this->groupDataService->add($data, $fieldRule);
        $data ? succ($data) : err();
    }

    /**
     * @PostApi(path="delete", description="获取用户信息")
     * @FormData(key="id|id", rule="")
     */
    public function delete()
    {
        $data = $this->groupDataService->delete($this->getValidatorData('id'));
        $data ? succ() : err();
    }

    /**
     * @PostApi(path="update/{group_id}", description="获取用户信息")
     * @FormData(key="id|id", rule="")
     */
    public function update()
    {
        $group_id = $this->request->route('group_id');
        $this->groupService->groupDao->where('id', $group_id)->exists() || err('数据组不存在');
        $field = array_column($fieldRule = $this->groupService->groupDao->fields($group_id), 'field');
        $data = $this->request->inputs(['sort', 'status'], [0, 0]);
        $data['value'] = $this->request->inputs($field);
        $data['group_id'] = $group_id;
        $data = $this->groupDataService->update($this->getValidatorData('id'), $data, $fieldRule);
        $data ? succ() : err();
    }

}