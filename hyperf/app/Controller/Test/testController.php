<?php


namespace App\Controller\Test;


use App\Controller\AbstractController;
use App\Model\Domain;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\HttpServer\Annotation\Middleware;

/**
 * @ApiController(tag="test",prefix="test",description="111111")
 */
class testController extends AbstractController
{

    /**
     * @Inject()
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @GetApi(path="test", description="获取用户信息")
     */
    public function test()
    {
        $path = 'exp004.xlsx';
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $objPHPExcel = $reader->load(config('file.storage.local.root') . '/' . $path);
        $sheet = $objPHPExcel->getSheet(0);   //excel中的第一张sheet
        $highestRow = $sheet->getHighestRow();       // 取得总行数
        $client = $this->clientFactory->create(['verify' => false]);
        for ($j = 2; $j <= $highestRow; $j++) {
            $url = $objPHPExcel->getActiveSheet()->getCell("A" . $j)->getValue();
            Db::table('domain')->insert(['domain' => $url]);
        }
    }

    /**
     * @Inject()
     * @var Domain
     */
    protected $DomainModel;

    /**
     * @GetApi(path="collent", description="获取用户信息")
     */
    public function collent()
    {
        $num = 100;
        while ($num--) {
            try {
                $res = $this->DomainModel
                    ->orderBy('times')
                    ->whereNull('domain_title')
                    ->inRandomOrder()
                    ->first();
                $url = $res->domain;
                $id = $res->id;
                $url = 'http://' . $url;
                dump($url);
                $client = $this->clientFactory->create(['verify' => false, 'timeout' => 5]);
                $data = $client->get($url)->getBody()->getContents();
                preg_match("/<title>(.*)<\/title>/i", $data, $title);
                dump($title = htmlentities($title[1]));

                $res->domain_title = $title;
                $res->save();

            } catch (\Exception $e) {
                dump($e);
                Db::table('domain')->where('id', $id)->increment('times');
            }
        }
    }

}