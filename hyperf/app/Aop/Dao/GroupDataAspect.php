<?php


namespace App\Aop\Dao;


use App\Aop\BaseAspect;
use App\Aop\ProceedingJoinPoint;
use App\Dao\System\GroupDataDao;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Annotation\Aspect;

/**
 * @Aspect(priority=0)
 */
class GroupDataAspect extends BaseAspect
{
    /**
     * @Inject()
     * @var GroupDataDao
     */
    protected $groupDataDao;

    public $classes = [
        GroupDataDao::class . '::form',
    ];

    function beforeHandle(\Hyperf\Di\Aop\ProceedingJoinPoint $proceedingJoinPoint)
    {
        $id = $this->getArgument('id');
        if ($id) {
            $data = $this->groupDataDao->DaoWhere(['id' => $id])->first()->toArray();
            $value = $data['value'];
            unset($data['value']);
            $data += $value;
            $this->setArgument('formData', $data);
        }
    }

    function afterHandle($result)
    {
        return formToData($result);
    }

}