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

use App\Form\Elm;
use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\Menu;
use App\Model\Product\Product;
use App\Model\Product\ProductContent;
use App\Model\Project;
use App\Model\User;
use App\Model\UserAuthentication;
use App\Model\UserBill;
use App\Model\UserProject;
use App\Service\AdminService;
use App\Service\Product\ProductService;
use FormBuilder\Driver\CustomComponent;

use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Annotation\AutoController;
use App\Controller\AbstractController;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use App\Middleware\CheckAdminMiddleware;

/**
 * @AutoController()
 * @Middleware(CheckAdminMiddleware::class)
 */
class ProductController extends AbstractController
{
    /**
     * @Inject()
     * @var Project
     */
    protected $projectModel;
    /**
     * @Inject()
     * @var UserBill
     */
    protected $userBillModel;
    /**
     * @Inject()
     * @var UserAuthentication
     */
    protected $userAuthenticationModel;
    /**
     * @Inject()
     * @var UserProject
     */
    protected $userProjectModel;

    /**
     * @Inject()
     * @var ProductService
     */
    protected $productService;
    /**
     * @Inject()
     * @var User
     */
    protected $userModel;

    /**
     * @Inject()
     * @var Product
     */
    protected $productModel;

    /**
     * @Inject()
     * @var ProductContent
     */
    protected $productContentModel;

    public function create()
    {
        $id = $this->request->input('id');
        if ($id) {
            $formData = $this->userProjectModel->where('id', $id)->first()->toArray();
            unset($formData['password']);
        } else {
            $formData = [];
        }
        $form = Elm::createForm($id ? '/admin/project/edit' : '/admin/project/add');

        $form->setRule([
            Elm::hidden('id', $id),
            Elm::input('name', '项目名称')->required('项目名称必填'),
            Elm::YjUpload()->field('picture'),
            Elm::input('price', '返现金额'),
            Elm::tinymce()->field('content')->title('内容'),
        ]);

        $lists = $form->setTitle($id ? '编辑' : '添加' . '项目')->formData($formData);
        succ(formToData($lists));
    }


    public function add()
    {
        $params = $this->request->all();
        $res = $this->productService->add($params);

        $res ? succ() : err();
    }


    public function detail()
    {
        $id = $this->request->input('id');
        $detail = $this->productService->detail(compact('id'));
        succ($detail);

    }

    public function edit()
    {
        $params = $this->request->all();
        $this->productService->edit($params['id'], $params) ? succ() : err();
    }

    public function lists()
    {
        $lists = $this->productService->list();
        succ($lists);
    }

    public function change_status()
    {
        $id = $this->request->input('id');
        $status = $this->request->input('status');
        $this->userBillModel->where('id', $id)->update(['status' => $status]) ? succ() : err();
    }

    public function delete()
    {
        $id = $this->request->input('id');
        $this->userModel->where('id', $id)->delete() ? succ() : err();

    }


}
