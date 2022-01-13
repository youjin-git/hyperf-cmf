<?php


namespace App\Controller\Admin\Code;

use App\Controller\Admin\AdminController;
use App\Middleware\CheckAdminMiddleware;
use App\Service\Database\DatabaseService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class CodeController extends AdminController
{
    /**
     * @Inject()
     * @var DatabaseService
     */
    protected $DatabaseService;


    public function lists()
    {

        $data = $this->DatabaseService->getTables();
        _SUCCESS($data);
    }

}