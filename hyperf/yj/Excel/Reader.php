<?php

namespace Yj\Excel;

use Hyperf\Utils\Collection;
use Yj\Excel\Concerns\WithChunkReading;
use Yj\Excel\Factories\ReaderFactory;
use Yj\Excel\Files\TemporaryFileFactory;

class Reader
{
    private TemporaryFileFactory $temporaryFileFactory;

    public function __construct(TemporaryFileFactory $temporaryFileFactory)
    {
        $this->temporaryFileFactory = $temporaryFileFactory;
    }


    public function read($import,$filePath,string $readerType = null, string $disk = null){

        $this->reader = $this->getReader($import, $filePath, $readerType, $disk);

        $this->loadSpreadsheet($import);

        $sheets             = new Collection();
        $sheetsToDisconnect = [];
        foreach ($this->sheetImports as $index => $sheetImport) {
            if ($sheet = $this->getSheet($import, $sheetImport, $index)) {
                $sheet->import($sheetImport,$sheet->getStartRow($sheetImport));
//                $sheets->put($index, $sheet->toCollection($sheetImport, $sheet->getStartRow($sheetImport), null, $calculatesFormulas=false, $formatData=false));
                $sheetsToDisconnect[] = $sheet;
            }
        }
        foreach ($sheetsToDisconnect as $sheet) {
            $sheet->disconnect();
        }


        return $this;
    }

    protected function getSheet($import, $sheetImport, $index){
        return Sheet::make($this->spreadsheet, $index);
    }
    /**
     * @param  object  $import
     */
    public function loadSpreadsheet($import)
    {

        $this->readSpreadsheet();

        // When no multiple sheets, use the main import object
        // for each loaded sheet in the spreadsheet

        $this->sheetImports = array_fill(0, $this->spreadsheet->getSheetCount(), $import);


//        $this->beforeImport($import);
    }

    public function readSpreadsheet()
    {
        $this->spreadsheet = $this->reader->load(
            $this->currentFile->getLocalPath()
        );
    }

    public function getReader($import,$filePath,string $readerType = null, string $disk = null){

        $shouldQueue = $import instanceof ShouldQueue;
        if ($shouldQueue && !$import instanceof WithChunkReading) {
//            throw new InvalidArgumentException('ShouldQueue is only supported in combination with WithChunkReading.');
        }

       # if ($import instanceof WithEvents) {
       #     $this->registerListeners($import->registerEvents());
       # }

        $fileExtension   = pathinfo($filePath, PATHINFO_EXTENSION);


        $temporaryFile = $this->temporaryFileFactory->makeLocal($fileExtension);

        $this->currentFile = $temporaryFile->copyFrom(
            $filePath,
            $disk
        );


        return ReaderFactory::make(
            $import,
            $this->currentFile,
            $readerType
        );

//        $this->temporaryFileFactory->make($fileExtension);


    }


}