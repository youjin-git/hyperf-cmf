<?php


namespace App\Model\Merchant;


use App\Model\Model;
use Hyperf\Database\Model\SoftDeletes;

class Merchant extends Model
{

    use SoftDeletes;

    protected $table = 'merchant';



}