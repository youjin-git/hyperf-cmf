<?php
declare (strict_types=1);
namespace App\Model;

use App\Model\Model;
use Hyperf\Database\Model\Events\Saving;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Hyperf\Database\Model\SoftDeletes;

class UserAuthentication extends Model
{
    use SoftDeletes;
    /**
     * @Inject()
     * @var Redis
     */
    protected $redis;

    protected $table = 'user_authentication';
    protected $fillable = [
        'id_card',
        'uid',
        'wx_picture_id',
        'zfb_picture_id',
        'status',
        'real_name',
    ];

    protected $typeFormat = [
        1=>'项目返现',
        2=>'提现',
        3=>'后台审核拒绝',
    ];

    protected $statusFormat = [
        -1=>'审核拒绝',
        0 => '审核中',
        1 => '审核通过',
    ];

    protected $appends = ['type_format','wx_picture','zfb_picture','status_format'];

    public function getTypeFormatAttribute(){
        return $this->typeFormat[$this->getAttribute('type')]??'';
    }

    public function user(){
            return $this->hasOne(User::class, 'id', 'uid');
    }

    public function getStatusFormatAttribute(){
        return $this->statusFormat[$this->getAttribute('status')];
    }

    public function getWxPictureAttribute(){
        return getFilePath($this->getAttribute('wx_picture_id'));
    }

    public function getZfbPictureAttribute(){
        return getFilePath($this->getAttribute('zfb_picture_id'));
    }




}
