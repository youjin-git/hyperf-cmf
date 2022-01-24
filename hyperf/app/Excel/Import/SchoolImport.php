<?php


namespace App\Excel\Import;


use App\Dao\College\CollegeSchoolDao;
use App\Model\Admin\File;
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

    /**
     * @Inject()
     * @var CollegeSchoolDao
     */
    public $collegeSchoolDao;

    public function collection(Collection $collection)
    {

//        dump($collection);
        $collection->each(function ($item){

           $this->collegeSchoolDao->firstOrCreate([
               'code'=>$item[0],
           ],['title'=>$item[1]]);

        });
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
//        $file = getFilePath($params->get('id'));
        $file = App(File::class)->where('id',$params->get('id'))->value('path');

        App(Excel::class)->queueImport($this,($file));

    }


    public function getFileFactory(){
        return $this->filesFactory->get('local');
    }


    public function file(){
//        $this->getFileFactory()->get()
    }


}