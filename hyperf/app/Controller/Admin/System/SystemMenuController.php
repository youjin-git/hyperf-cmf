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


/**
 * User: 尤金
 * @Middleware(CheckAdminMiddleware::class)
 * @AutoController()
 */
class SystemMenuController extends AbstractController
{
    /**
     * @Inject()
     * @var SystemMenuService
     */
    public $systemMenuService;

    public function list()
    {
        $params = $this->request->all();
        $data = $this->systemMenuService->page(false)->list($params);
        $data = list_to_tree($data->toArray());
        _SUCCESS($data);
    }

    public function add(SystemMenuAddRequest $request)
    {
        $params = $request->collection();
        $params->concat([
            'title' => '默认',
        ]);
        $this->systemMenuService->add($params);
        _SUCCESS();
    }

    public function edit(SystemMenuEditRequest $request)
    {
        $params = $request->collection();
        tap($this->systemMenuService->edit($params->get('id'), $params->except('id')), function ($data) {
            _GetLastSql();
            $data ? _SUCCESS() : _Error();
        });
    }
}