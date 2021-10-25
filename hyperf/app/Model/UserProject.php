<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class UserProject extends Model
{
    use Snowflake;
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;
    protected $table = 'user_project';

    /**
     * @Inject()
     * @var Project
     */
    protected $project;

    /**
     * @Inject()
     * @var User
     */
    protected $user;
    protected $fillable = [
        'project_id',
        'uid',
    ];

    protected $statusFormat = [
        -2=>'结束',
        -1=>'审核拒绝',
        0 => '审核中',
        1 => '进行中',
    ];

    protected $appends = ['create_time_format','picture_format','status_format','project_info','phone'];

    public function getIdAttribute($value)
    {
        return (string)$value;
    }

    public function getCreateTimeFormatAttribute()
    {
        return  $this->getAttribute('create_time');
    }
    public function getProjectInfoAttribute(){
            return $this->project->where('id',$this->getAttribute('project_id'))->first();
    }
    public function getPhoneAttribute(){
        return $this->user->where('id',$this->getAttribute('uid'))->value('phone');
    }


    public function getPictureFormatAttribute(){
        return  getFilePath($this->getAttribute('picture'));
    }

    public function getStatusFormatAttribute(){
        return $this->statusFormat[$this->getAttribute('status')];

    }

}
