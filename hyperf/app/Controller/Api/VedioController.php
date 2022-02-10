<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/7
 * Time: 6:48
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Model\Admin\Vedio;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\PostApi;

/**
 * Class BannerController
 * @ApiController(prefix="api/vedio")
 *
 */
class VedioController extends AbstractController
{
    /**
     * @Inject()
     * @var Vedio
     */
    protected $vedioModel;

    /**
     * @PostApi(path="lists")
     */
    public function lists()
    {
        $type = $this->request->input('type',0);
        $lists = $this->vedioModel->with(['imagePath','vedioPath'])->where(function($query)use($type){
        })->orderBy('create_time','desc')->get();
        succ($lists);
    }

}