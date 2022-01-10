<?php

namespace Yj\Excel;


use Yj\Cell;

class Row
{
    private \PhpOffice\PhpSpreadsheet\Worksheet\Row $row;
    private array $headingRow;

    public function __construct(\PhpOffice\PhpSpreadsheet\Worksheet\Row $row, $headingRow = [])
    {
        $this->row = $row;
        $this->headingRow = $headingRow;
    }

    public function toArray($nullValue = null, $calculateFormulas = false, $formatData = true, ?string $endColumn = null){
         $cells = [];
         $i = 0;
         foreach ($this->row->getCellIterator('A',$endColumn) as $cell){
             $value =  (new Cell($cell))->getValue($nullValue,$calculateFormulas, $formatData);
             if (isset($this->headingRow[$i])) {
                 $cells[$this->headingRow[$i]] = $value;
             } else {
                 $cells[] = $value;
             }
             $i++;
         }
        return $cells;
    }


}