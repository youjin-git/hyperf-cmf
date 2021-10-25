<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller\Util;

use App\Controller\AbstractController;
use App\Model\Admin\File;
use App\Model\Form;
use App\Model\FormData;
use App\Model\Order;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use function Symfony\Component\String\s;


/**
 * @AutoController()
 */
class ExcelController extends AbstractController
{
 
    /**
     * @Inject()
     * @var File
     */
    protected $fileModel;

    protected function makeWhere($where)
    {
        $whereArr = [];
        return $whereArr;
    }

    public function download()
    {
//        $id = $this->request->post('id');
        $params = $this->request->inputs(['status']);
        dump($params);

        $where = $this->makeWhere($params);
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $line = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $title = [
            'name' => '姓名',
            'education' => '学历',
            'id_card' => '身份证',
            'household' => '户籍性质',
            'nation' => '民族',
            'provice' => '户籍地址',
            'id_card_positive_picture_id' => '身份证正面',
            'id_card_back_picture_id' => '身份证背面'
        ];
        $ress = $this->orderModel->where($where)->with(['insured', 'orderDetail'])->get();

        succ($ress);
        foreach ($ress as $kk => $res) {
            $sheet->setactivesheetindex($kk);
            $i = 0;
            foreach ($title as $k => $vv) {
                if (in_array($k, ['id_card_positive_picture_id', 'id_card_back_picture_id'])) {
                    $this->imgSheet($line[$i], 2, $spreadsheet, $sheet, $this->fileModel->where('id', $res['insured'][$k])->value('path'));
                } else {
                    $sheet->setCellValue($line[$i] . '2', (string)$res['insured'][$k]);
                }
                $i++;
            }
            $i = 0;
            foreach ($title as $k => $v) {
                $sheet->setCellValue($line[$i] . '1', $v);
                $sheet->getColumnDimension($line[$i])->setWidth(50);
                $i++;
            }

            $titles = [
                'month' => '月份',
                'price' => '总费用',
                'social_security_base_price' => '社保基数',
                'advance_payment_price' => '社保总费率',
                'fund_base_price' => '公积金基数',
                'fund_rate' => '公积金总费率',
            ];
            $i = 0;
            foreach ($titles as $kk => $vv) {
                $sheet->setCellValue($line[$i] . '3', $vv);
                $i++;
            }


            foreach ($res['order_detail'] as $k => $v) {
                $i = 0;
                foreach ($titles as $kk => $vv) {
                    dump($line[$i] . ($k + 3), (string)$v[$kk]);
                    $sheet->setCellValue($line[$i] . ($k + 4), (string)$v[$kk]);
                    $i++;
                }
            }
            $sheet->getRowDimension(1)->setRowHeight(20);
        }
        $name = $res['insured']['name'];
        $writer = new Xlsx($spreadsheet);
        $path = config('file.storage.local.root') . 'excel/' . $name . '.xlsx';
        $writer->save($path);
        $spreadsheet->disconnectWorksheets();
        succ($path);
    }

    public function url()
    {
        $url = $this->request->query('url');
        return $this->response->download($url);
    }

    public function imgSheet($cell, $num, $spreadsheet, $sheet, $img)
    {
        if (!empty($img)) {
            //判断文件是否存在
            $source = BASE_PATH . "/public" . $img;
//            $source = BASE_PATH . "/public" . "/storage/callback_image/2020-11-09/27/20201109095106_478379_27_in.jpg";
            if (file_exists($source)) {
                $drawing[$num] = new Drawing();
                $drawing[$num]->setName('img');
                $drawing[$num]->setDescription('img');

                $drawing[$num]->setPath($source);
                $spreadsheet->getActiveSheet()->getRowDimension($num)->setRowHeight(80);
                $drawing[$num]->setWidth(80);
                $drawing[$num]->setHeight(80);
                $drawing[$num]->setCoordinates($cell . $num);
                $drawing[$num]->setOffsetX(0);
                $drawing[$num]->setOffsetY(0);
                $drawing[$num]->setWorksheet($spreadsheet->getActiveSheet());
            } else {
                $sheet->setCellValue($cell . $num, '');
            }
        } else {
            $sheet->setCellValue($cell . $num, '');
        }
    }


}
