<?php

namespace App\Controller\Test;



use App\Controller\AbstractController;
use App\Model\Admin\Menu;
use App\Model\Domain;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\GetApi;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="test")
 */
class testController extends AbstractController
{

    /**
     * @Inject()
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @Inject()
     * @var Menu
     */
    protected $menu;


    /**
     * @GetApi(path="test")
     */
    #[

    ]
    public function test()
    {


        dump(22);
        dump($params = $this->getValidatorData());

        _SUCCESS(11);
//        $path = 'exp004.xlsx';
//        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
//        $objPHPExcel = $reader->load(config('file.storage.local.root') . '/' . $path);
//        $sheet = $objPHPExcel->getSheet(0);   //excel中的第一张sheet
//        $highestRow = $sheet->getHighestRow();       // 取得总行数
//        $client = $this->clientFactory->create(['verify' => false]);
//        for ($j = 2; $j <= $highestRow; $j++) {
//            $url = $objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue();
//            Db::table('domain')->insert(['domain' => $url]);
//        }
    }

}