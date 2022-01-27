<?php
namespace Yj\Form;

use FormBuilder\Driver\CustomComponent;

class Elm extends \FormBuilder\Factory\Elm{

    public static function YjUpload(){
        $type = 'yjUpload';
        $component = new CustomComponent($type);
////            $span->action( $this->configValueModel->_get('site_url').'/util/file/upload');
        $component->props(['action'=>systemConfig('site_url').'/util/file/upload','name'=>'上传图片']);
        return $component;
    }

    public static function YjRadio(){
//        return \FormBuilder\Factory\Elm::radio();
//        Elm::radio('type', '公告类型')->options(function(){
//            $options = [];
//            foreach(['公告通知'] as $k=>$v){
//                $options[] = Elm::option($k+1, $v);
//            }
//            return $options;
//        })->required('公告类型必填'),
    }

    public static function Ueditor(){
        $type = 'Ueditor';
        $component = new CustomComponent($type);
        return $component;
    }


    public static function tinymce()
    {
        $type = 'tinymce';
        $component = new CustomComponent($type);
        return $component;
    }

    public static function wangeditor()
    {
        $type = 'wangeditor';
        $component = new CustomComponent($type);
        return $component;
    }


}

