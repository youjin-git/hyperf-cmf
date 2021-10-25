<?php

if (!function_exists('getFilePath')) {
        function getFilePath($id){
              return  di()->get(\App\Model\Admin\File::class)->getFullPath($id);
        }
}
