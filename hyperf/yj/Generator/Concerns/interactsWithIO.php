<?php


namespace Yj\Generator\Concerns;


trait interactsWithIO
{
    protected $input;

    public function argument($key = null)
    {
        if (is_null($key)) {
            return $this->input->getArguments();
        }
        return $this->input->getArgument($key);
    }


}