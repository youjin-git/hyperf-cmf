<?php

namespace Yj\Excel\Files;

class LocalTemporaryFile extends TemporaryFile
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @param  string  $filePath
     */
    public function __construct(string $filePath)
    {

        touch($filePath);
        dump($filePath,realpath($filePath),111);
        $this->filePath = realpath($filePath);
    }

    public function copyFrom($filePath, string $disk = null): TemporaryFile
    {
        dump('copyFrom');
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

    /**
     * @return string
     */
    public function getLocalPath(): string
    {
        return $this->filePath;
    }

    /**
     * @param @param string|resource $contents
     */
    public function put($contents)
    {
        file_put_contents($this->filePath, $contents);
    }

    /**
     * @return TemporaryFile
     */
    public function sync(): TemporaryFile
    {
        return $this;
    }

}