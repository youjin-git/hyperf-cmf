<?php

namespace Yj\Excel\Files;

class RemoteTemporaryFile
{

    /**
     * @var string
     */
    private $disk;

    /**
     * @var Disk|null
     */
    private $diskInstance;

    /**
     * @var string
     */
    private $filename;

    /**
     * @var LocalTemporaryFile
     */
    private $localTemporaryFile;

    public function __construct($disk,$filename,LocalTemporaryFile $localTemporaryFile)
    {
        $this->disk               = $disk;
        $this->filename           = $filename;
        $this->localTemporaryFile = $localTemporaryFile;
        $this->disk()->put($filename,'');
    }


    /**
     * @return \League\Flysystem\Filesystem|Disk
     */
    public function disk()
    {
        return $this->diskInstance ?: $this->diskInstance = app(FileSystem::class)->disk($this->disk);
    }

    public function copyFrom($filePath, string $disk = null): TemporaryFile
    {
       if ($disk === null && realpath($filePath) !== false) {
            $readStream = fopen($filePath, 'rb');
        } else {
            $readStream = app(FileSystem::class)->disk($disk)->readStream($filePath);
        }

        $this->put($readStream);

        if (is_resource($readStream)) {
            fclose($readStream);
        }

        return $this->sync();
    }

    public function put($contents){
        $this->disk()->put($this->filename, $contents);
    }

    /**
     * @return TemporaryFile
     */
    public function sync(): TemporaryFile
    {
        if (!$this->localTemporaryFile->exists()) {
            touch($this->localTemporaryFile->getLocalPath());
        }

        $this->disk()->copy(
            $this,
            $this->localTemporaryFile->getLocalPath()
        );

        return $this;
    }
}