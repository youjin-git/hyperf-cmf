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
use App\Service\AdminService;
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
class MenuController extends AbstractController
{

    /**
     * @Inject()
     * @var Menu
     */
    protected $menuModel;

    /**
     * @Inject()
     * @var AdminService
     */
    protected $adminServer;

    public function add()
    {
        $data = $this->request->inputs(['pid', 'name', 'icon', 'path', 'sort']);
        $this->menuModel->create($data);
        succ();
    }

    public function edit()
    {
        $data = $this->request->inputs(['pid', 'name', 'icon', 'path', 'sort', 'status']);
        $id = $this->request->input('id');
        $menu = $this->menuModel->where('id', $id)->first();
        $menu->fill($data);
        $menu->save();
        succ();
    }

    public function form()
    {
        $id = $this->request->input('id');
        if ($id) {
            $formData = $this->menuModel->where('id', $id)->first()->toArray();
            $formData['pid'] = (string)$formData['pid'];
        } else {
            $formData = [];
        }
        $form = Elm::createForm($id ? 'admin/menu/edit' : 'admin/menu/add');
        $form->setRule([
            Elm::cascader('pid', '父级分类')->options(function () use ($id) {
                $menus = $this->menuModel->get()->toArray();
                $menus = array_column($menus, null, 'id');
//                    if ($id && isset($menus[$id])) unset($menus[$id]);
                $menus = formatCascaderData($menus, 'name');
                array_unshift($menus, ['label' => '顶级分类', 'value' => '0']);
                return $menus;
            })->props(['props' => ['checkStrictly' => true, 'emitPath' => false], 'type' => 'other']),
            Elm::select('is_menu', '权限类型', 1)->options([
                ['value' => 1, 'label' => '菜单'],
                ['value' => 0, 'label' => '权限'],
            ])->control([
                [
                    'value' => 0,
                    'rule' => [
                        Elm::input('name', '路由名称')->required(),
                        Elm::textarea('params', '参数')->placeholder("路由参数:\r\nkey1:value1\r\nkey2:value2"),
                    ]
                ], [
                    'value' => 1,
                    'rule' => [
                        Elm::switches('is_show', '是否显示', 1)->inactiveValue(0)->activeValue(1)->inactiveText('关闭')->activeText('开启'),
                        Elm::frameInput('icon', '菜单图标', '#/admin/setting/icons?field=icon')->icon('el-icon-circle-plus-outline')->height('338px')->width('700px')->modal(['modal' => false]),
                        Elm::input('name', '菜单名称')->required(),
                    ]
                ]
            ]),
            Elm::input('path', '路由'),
            Elm::number('sort', '排序', 0),
            Elm::hidden('id', $id)
        ]);
        $form = $form->setTitle(is_null($id) ? '添加菜单' : '编辑菜单')->formData($formData);
        succ(formToData($form));
    }

    public function lists()
    {

//        p($this->menuModel);
        //获取权限
        $uid = Context::get('uid');
        $rules = $this->adminServer->getRules($uid);
        dump($rules);
        $lists = $this->menuModel
            ->orderBy('sort')
            ->where(function ($query) use ($rules) {
                if ($rules != 1) {
                    $query->whereIn('menu_id', $rules);
                }
            })
            ->where('status', 1)->get();
        $lists->transform(function ($item) {
            $item->meta = [
                'icon' => $item->icon,
                'title' => $item->title,
                'type' => $item->type,
                'affix' => $item->affix ? true : false,
            ];
            return $item;
        });
        $lists = list_to_tree($lists->toArray(), 0);
        succ($lists);
    }

    public function delete()
    {
        $id = $this->request->input('id');

        $this->menuModel->where('id', $id)->delete();

        succ();

    }

}
