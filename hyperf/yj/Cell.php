<?php

namespace Yj;

class Cell
{
    private \PhpOffice\PhpSpreadsheet\Cell\Cell $cell;

    public function __construct(\PhpOffice\PhpSpreadsheet\Cell\Cell $cell)
    {
        $this->cell = $cell;
    }

    public function getValue($nullValue = null,$calculateFormulas = false, $formatData = true){
        $value = $nullValue;
        if($this->cell->getValue() !== null){
            $value = $this->cell->getValue();
        }
        return $value;
    }
}