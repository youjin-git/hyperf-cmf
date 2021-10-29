<?php
require 'container.php';
require 'file.php';

use App\Constants\ErrorCode;
use App\Exception\YjException;
use Hyperf\DbConnection\Db;
use Hyperf\Paginator\LengthAwarePaginator;

if (!function_exists('p')) {
    function p($data)
    {
        if (method_exists($data, 'toArray')) {
            dump($data->toArray());
        } else {
            dump($data);
        }
    }
}

function getErrorMsg($msg = '未知错误')
{
    return \Hyperf\Utils\Context::get('error_msg', $msg);
}

function isJson($msg)
{
    if (!is_string($msg)) {
        return false;
    }
    return is_array(json_decode($msg, true));
}

function _SetNotPage($isPage = false)
{
    return \Hyperf\Utils\Context::set(\App\Constants\Context::ISPAGE, $isPage);
}

function err(string $msg = '未知错误', int $code = ErrorCode::FAIL, $data = [])
{
    $msg = getErrorMsg($msg);
    if (isJson($msg)) {
        $msg = json_decode($msg, true)['message'] ?? $msg;
    }

    throw  new YjException($data, $code, $msg);
}

function succ($data = [])
{
    throw new YjException($data, ErrorCode::CODE_SUCC, 'success');
}

function _SUCCESS($data = [])
{
    throw new YjException($data, ErrorCode::CODE_SUCC, 'success');
}


function systemConfig($keys)
{
    return make(\App\Model\Admin\ConfigValue::class)->_get($keys);
}


if (!function_exists('formToData')) {
    function formToData(\FormBuilder\Form $form): array
    {
        $rule = $form->formRule();
        $action = $form->getAction();
        $method = $form->getMethod();
        $title = $form->getTitle();
        $config = (object)$form->formConfig();
        return compact('rule', 'action', 'method', 'title', 'config');
    }
}

if (!function_exists('filter_emoji')) {

    // 过滤掉emoji表情
    function filter_emoji($str)
    {
        $str = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }
}

function to1062($value, $action = 62)
{
    if ($action == 62) {
        return to62($value + 10000);
    } else {
        return to10($value) - 10000;
    }
}

function to10($str)
{
    $from = 62;
    $str = strval($str);
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($str);
    $dec = 0;
    for ($i = 0; $i < $len; $i++) {
        $pos = strpos($dict, $str[$i]);
        $dec = bcadd(bcmul(bcpow($from, $len - $i - 1), $pos), $dec);
    }
    return $dec;
}

function to62($num)
{
    $to = 62;
    $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $ret = '';
    do {
        $ret = $dict[bcmod($num, $to)] . $ret; //bcmod取得高精确度数字的余数。
        $num = bcdiv($num, $to);  //bcdiv将二个高精确度数字相除。
    } while ($num > 0);
    return $ret;
}

function sendMail($sendMail, $subject, $content)
{
    // 引入PHPMailer的核心文件
//    require_once("PHPMailer/class.phpmailer.php");
//    require_once("PHPMailer/class.smtp.php");
    $mail_config = config("mail_config");
    p($mail_config);
    // 实例化PHPMailer核心类
    $mail = new  PHPMailer();
    // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
    $mail->SMTPDebug = 1;
    // 使用smtp鉴权方式发送邮件
    $mail->isSMTP();
    // smtp需要鉴权 这个必须是true
    $mail->SMTPAuth = true;
    // 链接qq域名邮箱的服务器地址
    $mail->Host = $mail_config["smtp_address"];
    // 设置使用ssl加密方式登录鉴权
    $mail->SMTPSecure = 'ssl';
    // 设置ssl连接smtp服务器的远程服务器端口号
    $mail->Port = 465;
    // 设置发送的邮件的编码
    $mail->CharSet = 'UTF-8';
    // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
    $mail->FromName = $mail_config["nick"];
    // smtp登录的账号 QQ邮箱即可
    $mail->Username = $mail_config["user_name"];
    // smtp登录的密码 使用生成的授权码
    $mail->Password = $mail_config["pass_word"];
    // 设置发件人邮箱地址 同登录账号
    $mail->From = $mail_config["from_mail"];
    // 邮件正文是否为html编码 注意此处是一个方法
    $mail->isHTML(true);
    // 设置收件人邮箱地址
    $mail->addAddress($sendMail);
    // 添加多个收件人 则多次调用方法即可
//    $mail->addAddress('87654321@163.com');
    // 添加该邮件的主题
    $mail->Subject = $subject;
    // 添加邮件正文
    $mail->Body = $content;
    // 为该邮件添加附件
//    $mail->addAttachment('./example.pdf');
    // 发送邮件 返回状态
    $status = $mail->send();
    writeLog("send_mail_re", $status, "send_mail");
    return $status;
}

/**生成指定 len长度的随机字符串
 * @param $length
 * @return string|null
 */
function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";//大小写字母以及数字
    $max = strlen($strPol) - 1;

    for ($i = 0; $i < $length; $i++) {
        $str .= $strPol[rand(0, $max)];
    }
    return $str;
}

function fileUpload($file, $name, $id)
{
    if (empty($id)) {
        return ["status" => false, "msg" => "tem_id is null"];
    }
    $path = explode("/app", __DIR__)[0];//获取项目路径
    //return ["status" => false, "msg" => $path];
    if (empty($path)) {
        return ["status" => false, "msg" => "upload error 101"];
    }
    $info = config("fileUp.tem")[$id];
    //判断文件类型
//    var_dump($info);
//    var_dump($file->getMimeType());
//    var_dump($info['type']);
    if (!in_array($file->getMimeType(), $info['type'])) {
        return ["status" => false, "msg" => "file type error"];
    }
    $arr = explode(".", $file->getClientFilename());
    $suffix = end($arr);//获取上传文件后缀名
//    var_dump($file);
    //移动文件到指定位置
    if (strpos($suffix, "file") == 0) {
        $file_path = $path . $info['path'] . $name . "." . $suffix;
    } else {
        $file_path = $path . $info['path'] . $name . "." . explode("/", $file->getMimeType())[1];
    }
    if (!is_dir($path . $info['path'])) {
        mkdir($path . $info['path'], 0777, true);
    }
    $file->moveTo($file_path);
    $path = "/static/" . explode("/static/", $file_path)[1];
    if ($file->isMoved()) {
        return ["status" => true, "msg" => "", "real_path" => $file_path, "path" => $path];
    } else {
        return ["status" => false, "msg" => "upload error 102 "];
    }
}

function http_curl($url, $data = null, $headers = array())
{
    try {
        $curl = curl_init();
        if (count($headers) >= 1) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    } catch (Exception $e) {
        return false;
    }
}


/**判断 $params 中 key 为 $$val_arr 中的值是否存在空值
 * @param $params
 * @param $val_arr
 */
function paramIsEmpty($params, $val_arr)
{
    foreach ($val_arr as $k => $v) {
        if (!isset($params[$v]) && empty($params[$v])) {
            return true;
        }
    }
    return false;
}


/**
 * 生成随机数
 *
 * @param chars 种子
 * @param num   生成个数
 */
function generateShortUuid($chars, $num)
{
    $len = count($chars);
    $uuid = randomUUID();
    $sb = "";
    $uuid = str_replace("-", "", $uuid);
    for ($i = 0; $i < $num; $i++) {
        $str = substr($uuid, $i * 4, 4);
        $x = intval($str, 10);
        $sb .= $chars[$x % $len];
    }
    return $sb;
}

function randomUUID()
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid = substr($chars, 0, 8) . '-';
    $uuid .= substr($chars, 8, 4) . '-';
    $uuid .= substr($chars, 12, 4) . '-';
    $uuid .= substr($chars, 16, 4) . '-';
    $uuid .= substr($chars, 20, 12);
    return $uuid;
}

function float_number($number)
{
    $length = strlen($number);  //数字长度
    if ($length > 8) { //亿单位
        $str = substr_replace(strstr($number, substr($number, -7), ' '), '.', -1, 0) . "亿";
    } elseif ($length > 4) { //万单位
        //截取前俩为
        $str = substr_replace(strstr($number, substr($number, -3), ' '), '.', -1, 0) . "万";
    } else {
        return $number;
    }
    return $str;
}


function list_to_tree($array, $val = 0, $id = 'id', $pid = 'pid', $child = 'children')
{
    if (empty($array)) {
        return [];
    }
    $tree = [];
    foreach ($array as $key => $v) {
        $tree[$v[$id]] = &$array[$key];
    }
    $tree1 = [];
    foreach ($array as $key => $v) {
        if ($v[$pid] == $val) {
            $tree1[] = &$array[$key];
        } else {
            if (isset($tree[$v[$pid]])) {
                $tree[$v[$pid]][$child][] = &$array[$key];
            }
        }
    }
    return $tree1;
}


if (!function_exists('systemGroupData')) {
    /**
     * 获取总后台组合数据配置
     *
     * @param string $key
     * @param int|null $page
     * @param int|null $limit
     * @return LengthAwarePaginator
     */
    function systemGroupData(string $key, ?int $page = null, ?int $limit = 10)
    {

        $make = di()->get(\App\Service\System\GroupDataService::class);
        return $make->groupData($key, $page, $limit);
    }
}

if (!function_exists('_GetLastSql')) {
    /**
     * Notes: 打印最后一条sql
     * Users: zwc
     * Date: 20201-3-17
     */
    function _GetLastSql()
    {
        $sqls = Db::getQueryLog();
        foreach ($sqls as &$sql) {
            foreach ($sql['bindings'] as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $sql['bindings'][$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else {
                    if (is_string($binding)) {
                        $sql['bindings'][$i] = "'$binding'";
                    }
                }
            }
            $query = str_replace(array('%', '?'), array('%%', '%s'), $sql['query']);
            $query = vsprintf($query, $sql['bindings']);
            $sql['_query'] = $query;
        }
        dump(array_column($sqls, '_query'));
    }
}

if (!function_exists('formatCascaderData')) {
    function formatCascaderData(&$options, $name, $baseLevel = 0, $pidName = 'pid', $pid = 0, $level = 0, $data = []): array
    {
        $_options = $options;
        foreach ($_options as $k => $option) {
            if ($option[$pidName] == $pid) {
                $value = ['value' => $k, 'label' => $option[$name]];
                unset($options[$k]);
                $value['children'] = formatCascaderData($options, $name, $baseLevel, $pidName, $k, $level + 1);
                if (!count($value['children'])) unset($value['children']);
                $data[] = $value;
            }
        }
        return $data;
    }
}