<?php

namespace Yj\Excel\Files;


use Hyperf\Utils\Str;

class TemporaryFileFactory extends TemporaryFile
{
    /**
     * @var string|null
     */
    private $temporaryPath;

    /**
     * @var string|null
     */
    private $temporaryDisk;

    /**
     * @param  string|null  $temporaryPath
     * @param  string|null  $temporaryDisk
     */
    public function __construct(string $temporaryPath = null, string $temporaryDisk = null)
    {
        $this->temporaryPath = $temporaryPath??'temporaryPath';
        $this->temporaryDisk = $temporaryDisk??'temporaryDisk';
    }


    public function makeRemote($fileExtension = null){
        $filename = $this->generateFilename($fileExtension);
        $this->makeLocal($filename);
        return new RemoteTemporaryFile('','RemoteTemporaryExcel\\'.$filename,$this->makeLocal($filename));
    }

    /**
     * @param $fileExtension
     * @return LocalTemporaryFile|RemoteTemporaryFile
     */
    public function make($fileExtension = null){
        if (null !== $this->temporaryDisk) {
            return $this->makeRemote($fileExtension);
        }
        return $this->makeLocal(null, $fileExtension);
    }


    public function makeLocal(string $fileName = null, string $fileExtension = null){
        if (!file_exists($this->temporaryPath) && !mkdir($concurrentDirectory = $this->temporaryPath) && !is_dir($concurrentDirectory)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
        }


        return new LocalTemporaryFile(
            $this->temporaryPath . DIRECTORY_SEPARATOR . ($fileName ?: $this->generateFilename($fileExtension))
        );
    }

    /**
     * @param  string|null  $fileExtension
     * @return string
     */
    private function generateFilename(string $fileExtension = null): string
    {
        return 'laravel-excel-' . Str::random(32) . ($fileExtension ? '.' . $fileExtension : '');
    }


}