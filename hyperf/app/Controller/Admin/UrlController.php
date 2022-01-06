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

namespace App\Controller\Admin;

use App\Exception\YjException;
use App\Exception\Handler\AppExceptionHandler;
use App\Model\Admin;
use App\Model\Model;
use App\Model\User;
use Carbon\Carbon;
use FormBuilder\Driver\CustomComponent;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Controller\AbstractController;
use Hyperf\HttpServer\Annotation\Middleware;
use HyperfAdmin\BaseUtils\Constants\ErrorCode;
use HyperfAdmin\BaseUtils\JWT;
use FormBuilder\Factory\Elm;
use App\Middleware\CheckAdminMiddleware;


/**
 * @AutoController()
 */
class UrlController extends AbstractController
{
    public function create(){
        $id = $this->request->input('id');
        if($id){
            $formData = $this->bannerModel->where('id',$id)->first()->toArray();
        }else{
            $formData = [];
        }
        $form =  Elm::createForm('admin/banner/save');
        $form->setRule([
            Elm::input('title', '标题')->required('标题必填'),
            Elm::input('link', '链接')->required('跳转必填'),
            Elm::hidden('id',$id)
        ]);
        $lists = $form->setTitle($id?'编辑banner':'添加banner')->formData($formData);
        succ(formToData($lists));
    }
    public function url()
    {

    }
}
