<?php


namespace Yj\Apidog\Annotation;

use Hyperf\HttpServer\Annotation\Mapping;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class GetApi extends Mapping
{
    public $path;

    public $summary;

    public $description;

    public $deprecated;

    public $methods = ['GET'];

    public function __construct($value = null)
    {
        parent::__construct($value);
        if (is_array($value)) {
            foreach ($value as $key => $val) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $val;
                }
            }
        }
    }
}