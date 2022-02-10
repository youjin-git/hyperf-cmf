<?php
/**
 * Created by PhpStorm.
 * User: zwc
 * Date: 2022/2/7
 * Time: 13:46
 */

namespace App\Controller\Api;


use App\Controller\AbstractController;
use App\Model\Admin\ConfigValue;
use App\Model\System\SystemConfig;
use Hyperf\Di\Annotation\Inject;
use Yj\Apidog\Annotation\ApiController;
use Yj\Apidog\Annotation\FormData;
use Yj\Apidog\Annotation\PostApi;

/**
 * Class ConfigController
 * @ApiController(prefix="api/config")
 */
class ConfigController extends AbstractController
{

    /**
     * @Inject()
     * @var ConfigValue
     */
    protected $configValue;

    /**
     * @PostApi(path="get")
     * @FormData(key="key",rule="required")
     */
    public function get(){
        $params = $this->getValidatorData();
        $data  = systemConfig($params->get('key'));
        _GetLastSql();
        dump($data);
//        $data = $this->configValue->whereIn('key',$params->get('key'))->pluck('key','value');
//        _GetLastSql();
        _SUCCESS($data);
    }
}