<?php

namespace Yj\Excel;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Yj\Excel\Concerns\ToCollection;
use Yj\Excel\Files\TemporaryFileFactory;

class Sheet
{

    private Worksheet $worksheet;

    public function __construct(Worksheet $worksheet)
    {
        $this->worksheet = $worksheet;
        $this->chunkSize            = 100;
        $this->temporaryFileFactory = app(TemporaryFileFactory::class);
    }

    public static function make(Spreadsheet $spreadsheet,$index){
        if(is_numeric($index)){
            return self::Byindex($spreadsheet,$index);
        }
    }

    public static function Byindex(Spreadsheet $spreadsheet,$index){
        return new static($spreadsheet->getSheet($index));
    }

    /**
     * @param $sheetImport
     * @return int
     */
    public function getStartRow($sheetImport): int
    {

        return 1;
    }

    public function toCollection($import,int $startRow = null, $nullValue = null,$calculateFormulas = false, $formatData = false){
        $rows = $this->toArray($import, $startRow, $nullValue, $calculateFormulas, $formatData);
        return collect($rows);
    }

    public function disconnect()
    {
        $this->worksheet->disconnectCells();
        unset($this->worksheet);
    }

    public function toArray($import,int $startRow = null, $nullValue = null,$calculateFormulas = false, $formatData = false){
        if ($startRow > $this->worksheet->getHighestRow()) {
            return [];
        }

//        dd($this->worksheet->getHighestRow());
        $endRow = null;
        $headingRow = [];
        $rows = [];
        $endColumn=null;
        foreach ($this->worksheet->getRowIterator($startRow, $endRow) as $index => $row) {
                $row = new \Yj\Excel\Row($row,$headingRow);
                $row = $row->toArray($nullValue, $calculateFormulas, $formatData, $endColumn);
                $rows[] = $row;
        }
//        dump($cells);
        return $rows;
    }

    public function import($import, int $startRow = 1){
        if ($import instanceof ToCollection) {
            $rows =  $this->toCollection($import, $startRow, null);
            $import->collection($rows);
        }
    }

}