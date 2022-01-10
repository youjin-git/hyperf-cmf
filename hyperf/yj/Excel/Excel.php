<?php

namespace Yj\Excel;

use Hyperf\Di\Annotation\Inject;

class Excel
{

    /**
     * @Inject()
     * @var Reader
     */
    private  $reader;

    public function queueImport($import,$filePath,string $disk = null,string $readerType = null){
        return $this->import($import,$filePath,$disk,$readerType);
    }

    public function import($import, $filePath, string $disk = null, string $readerType = null){
        $readerType = 'Xlsx';
        $this->reader->read($import,$filePath,$readerType,$disk);
        return $this;
    }
}