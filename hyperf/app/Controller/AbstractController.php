<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Constants\ErrorCode;
use FormBuilder\Driver\CustomComponent;
use FormBuilder\Factory\Elm;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    public function __construct()
    {
        Db::enableQueryLog();
    }

    public function getUid()
    {
        return Context::get('uid') ?: err('token is wrong', ErrorCode::TOEKN_INVALID);
    }

    protected function getValidatorData($fielid = '')
    {
        if ($fielid) {
            return Context::get('validator.data')[$fielid];
        }
        return Context::get('validator.data');
    }


    public function baseCreate()
    {
        $id = $this->request->input('id');
        if ($id) {
            $formData = $this->bannerModel->where('id', $id)->first()->toArray();
        } else {
            $formData = [];
        }
        $form = Elm::createForm('admin/banner/add');
        $type = 'YjUpload';
        $span = new CustomComponent($type);
////            $span->action( $this->configValueModel->_get('site_url').'/util/file/upload');
        $span->props(['action' => systemConfig('site_url') . '/util/file/upload', 'name' => '上传图片']);
        $component = $span->field('picture');
        $form->setRule([
            \App\Form\Elm::wangeditor(),
            Elm::input('title', '标题')->required('标题必填'),
            $component,
            Elm::input('link', '链接')->required('跳转必填'),
            Elm::hidden('id', $id)
        ]);
        $form = $form->setTitle($id ? '编辑banner' : '添加banner')->formData($formData);
        succ(formToData($form));
    }

    protected function getRoute($action)
    {
        return $this->route . '/' . $action;
    }
}
