<?php


namespace App\Controller\Admin\System;


use App\Controller\AbstractController;
use App\Dao\System\SystemMenuDao;
use App\Middleware\CheckAdminMiddleware;
use App\Request\Admin\System\SystemMenuAddRequest;
use App\Request\Admin\System\SystemMenuEditRequest;
use App\Service\System\SystemMenuService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\PostApi;


/**
 * User: 尤金
 * @Middleware(CheckAdminMiddleware::class)
 * @ApiController(prefix="/admin/menu")
 */
class SystemMenuController extends AbstractController
{
    /**
     * @Inject()
     * @var SystemMenuService
     */
    public $systemMenuService;

    /**
     * @PostApi(path="lists")
     * @return void
     */
    public function lists()
    {
        $params = $this->request->all();
        $data = $this->systemMenuService->page(false)->list($params);
        $data = list_to_tree($data->toArray());
        _SUCCESS($data);
    }

    /**
     * @PostApi(path="add")
     * @param SystemMenuAddRequest $request
     * @return void
     */
    public function add(SystemMenuAddRequest $request)
    {
        $params = $request->collection();
        $params->concat([
            'title' => '默认',
        ]);
        $this->systemMenuService->add($params);
        _SUCCESS();
    }

    /**
     * @PostApi(path="edit")
     * @param SystemMenuEditRequest $request
     * @return void
     */
    public function edit(SystemMenuEditRequest $request)
    {
        $params = $request->collection();
        tap($this->systemMenuService->edit($params->get('id'), $params->except('id')), function ($data) {
            $data ? _SUCCESS() : _Error();
        });
    }

    /**
     * @PostApi(path="set-position",description="设置位置")
     * @FormData(key="target_menu_id",rule="required")
     * @FormData(key="menu_id",rule="required")
     * @FormData(key="types",rule="required")
     * @return void
     */
    public function setPosition(){
        $params = $this->getValidatorData();
        $this->systemMenuService->setPosition($params->get('menu_id'),$params->get('target_menu_id'),$params->get('types'));
        _SUCCESS();
    }
}