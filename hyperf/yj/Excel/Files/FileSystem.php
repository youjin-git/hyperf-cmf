<?php

namespace Yj\Excel\Files;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Filesystem\FilesystemFactory;

class FileSystem
{

    /**
     * @Inject()
     * @var FilesystemFactory
     */
    private  $filesystem;


   /**
     * @param $disk
     * @param $diskOptions
     * @return \League\Flysystem\Filesystem
     */
    public function disk($disk,$diskOptions=[]){
        return  $this->filesystem->get('local');
    }


}