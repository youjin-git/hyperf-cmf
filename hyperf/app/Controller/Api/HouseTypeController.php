<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/7
 * Time: 23:22
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use Hyperf\DbConnection\Db;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;

/**
 * Class HouseTypeController
 * @ApiController(prefix="api/house_type")
 */
class HouseTypeController extends AbstractController
{
    /**
     * @PostApi(path="lists")
     */
    public function lists()
    {
        $data = Db::table('house_type')->get();
        _SUCCESS($data);
    }
}