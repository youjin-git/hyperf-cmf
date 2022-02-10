<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/6
 * Time: 21:41
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Model\Maidian;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;

/**
 * @ApiController(prefix="api/maidian")
 * @package App\Controller\Api
 */
class MaidianController extends AbstractController
{
    /**
     * @Inject()
     * @var Maidian
     */
    protected $maidian;

    /**
     * @PostApi(path="lists")
     */
    public function lists(){
        $data =  $this->maidian->get();
        _SUCCESS($data);
    }
}