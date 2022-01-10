<?php


namespace App\Excel\Import;


use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Collection;
use Hyperf\Utils\Context;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\GetApi;
use Yj\Apidog\Annotation\PostApi;
use Yj\Excel\Concerns\ToCollection;
use Yj\Excel\Excel;

/**
 * @ApiController(prefix="import")
 */
class SchoolImport implements  ToCollection
{

    public function collection(Collection $collection)
    {

//        dump($collection);
//        dump(111);
        // TODO: Implement collect() method.
    }

    /**
     * @Inject()
     * @var \Hyperf\Filesystem\FilesystemFactory
     */
    protected $filesFactory;

    /**
     * @PostApi(path="school")
     * @FormData(key="id",rule="required",default="40")
     */
    public function import(){
        $params = Context::get('validator.data');
        $file = getFilePath($params->get('id'));
        App(Excel::class)->queueImport($this,($file));

    }


    public function getFileFactory(){
        return $this->filesFactory->get('local');
    }


    public function file(){
//        $this->getFileFactory()->get()
    }


}