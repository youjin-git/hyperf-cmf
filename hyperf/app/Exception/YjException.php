<?php
namespace App\Exception;

use App\Constants\ErrorCode;
use Hyperf\Server\Exception\ServerException;


class YjException extends  ServerException
{

    public function __construct($data=[],int $code=0,$message='')
    {
            switch($code){
                case ErrorCode::CODE_SUCC:
                    $data = self::succ_format($data);
                    break;
                case ErrorCode::FAIL:
                default:
                    $data = self::err_format($message,$code,$data);
                 break;
            }

           parent::__construct(json_encode($data),$code);
    }

    public function getMsg(){
        return json_decode($this->message,true)['message'];
    }
    public static function err_format($message = '', $status = ErrorCode::FAIL,$data=[])
    {
         return compact('status','message','data');
    }

    public static function succ_format($data = [], $message = 'SUCC')
    {
         $status = ErrorCode::CODE_SUCC;
         return compact('status','data','message');
    }

}