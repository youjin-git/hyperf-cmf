<?php

namespace App\Controller\Admin\Product;

use App\Form\Elm;
use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\Menu;
use App\Model\Project;
use App\Model\User;
use App\Model\UserAuthentication;
use App\Model\UserBill;
use App\Model\UserProject;
use App\Service\AdminService;
use App\Service\Product\ProductCategoryService;
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
class CategoryController extends AbstractController
{
    /**
     * @Inject()
     * @var ProductCategoryService
     */
    protected $productCategoryService;


    public function form()
    {
        $id = $this->request->input('id');
        $formData = [];
        if ($id) {
            $formData = $this->productCategoryService->view($id)->toArray();
            $formData['pid'] = (string)$formData['pid'];
        }
        $form = Elm::createForm($id ? 'admin/product/category/edit' : 'admin/product/category/add');
        $form->setRule([
            Elm::cascader('pid', '上级分类')->options(function () use ($id) {
                $menus = $this->productCategoryService->getTreeFormat(function ($query) {
                    $query->where('level', '<', 2);
                });
                if ($id && isset($menus[$id])) unset($menus[$id]);

                array_unshift($menus, ['label' => '顶级分类', 'value' => '0']);
                return $menus;
            })->props(['props' => ['checkStrictly' => true, 'emitPath' => false]])
                ->filterable(true)
                ->validate([]),
            Elm::input('cate_name', '分类名称')->required(),
            Elm::YjUpload()->field('picture_id'),
            Elm::switches('is_show', '是否显示', 1)->activeValue(1)->inactiveValue(0)->inactiveText('关闭')->activeText('开启'),
            Elm::number('sort', '排序', 0),
            Elm::hidden('id', $id),
        ]);
        $form = $form->setTitle(is_null($id) ? '添加分类' : '编辑分类')->formData($formData);
        succ(formToData($form));
    }

    public function lists()
    {

        $lists = $this->productCategoryService->listsByTree();
        succ($lists);
    }


    public function getTree()
    {
        $lists = $this->productCategoryService->getTreeFormat();
        succ($lists);
    }

    /**
     * 用户添加
     *
     */
    public function add()
    {
        $params = $this->request->all();

        $res = $this->productCategoryService->add($params);
        $res ? succ() : err();
    }

    public function edit()
    {
        $id = $this->request->input('id');
        $data = $this->request->all();
        $this->productCategoryService->edit($id, $data) ? succ() : err();
    }

    public function delete()
    {
        $id = $this->request->input('id') ?: err('id is empty');
        $this->productCategoryService->delete($id) ? succ() : err();
    }


}
