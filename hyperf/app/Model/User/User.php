<?php


namespace App\Model\User;


use App\Dao\User\UserAccountDao;
use App\Model\File;
use App\Model\Merchant\Merchant;
use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;
use Hyperf\Snowflake\Concern\Snowflake;

class User extends Model
{
    use Snowflake;
    use SoftDeletes;

    protected $table = 'user';

    protected $appends = [
        'images'
    ];
    protected $fillable = [
        'image_id',
        'images_id',
        'cate_id',
        'brand_id',
        'name',
        'info',
    ];

    public function getIdAttribute($value)
    {
        return (string)$value;
    }

    public function merchant()
    {
        return $this->hasOne(Merchant::class, 'id', 'mer_id');
    }

    public function productDownload()
    {
        return $this->hasOne(ProductDownload::class, 'product_id', 'id');
    }


    public function getImagesAttribute()
    {
        return $this->checkAttributes('images_id', function ($value) {
            return getFilePath(explode(',', $value));
        });

    }

    public function image()
    {
        return $this->hasOne(File::class, 'id', 'image_id');
    }

    public function productContent()
    {
        return $this->hasOne(ProductContent::class, 'product_id', 'id');
    }

    public function userAccount()
    {
        return $this->hasMany(UserAccount::class, 'user_id', 'id');
    }


}