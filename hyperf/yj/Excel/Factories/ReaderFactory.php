<?php

namespace Yj\Excel\Factories;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Yj\Excel\Files\TemporaryFile;

class ReaderFactory
{
    public static function make($import,TemporaryFile $file,$readerType){
        dump($readerType);
        $reader = IOFactory::createReader(
            $readerType
        );
        return $reader;
    }
}