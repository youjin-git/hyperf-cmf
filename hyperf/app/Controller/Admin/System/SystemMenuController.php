<?php


namespace App\Controller\Admin\System;


use App\Controller\AbstractController;
use App\Dao\System\SystemMenuDao;
use App\Middleware\CheckAdminMiddleware;
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
        $data = $data->toArray();
        $data = list_to_tree($data);
        _SUCCESS($data);
    }
}