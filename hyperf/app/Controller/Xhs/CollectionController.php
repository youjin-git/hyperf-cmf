<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Controller\Xhs;


use App\Middleware\CheckLoginMiddleware;
use App\Model\Admin\ConfigValue;
use App\Model\User;
use App\Model\Waste;
use App\Model\Xhs\XhsTalent;
use App\Model\Xhs\XhsTalentNotes;
use App\Request\RegiterRequest;
use App\Request\WasteRequest;
use App\Service\CollectionService;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\GetApi;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Cache\Cache;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Context;
use App\Controller\AbstractController;
use App\Middleware\CheckAdminMiddleware;
use Psr\SimpleCache\CacheInterface;
use QL\QueryList;
use QL\Ext\Chrome;
use Nesk\Puphpeteer\Puppeteer;

/**
 * User: 尤金
 * Date: 2021/4/21
 * @ApiController(tag="采集",prefix="xhs/collection",description="")
 */
class CollectionController extends AbstractController
{
    /**
     * @Inject()
     * @var CacheInterface
     */
    protected $cache;

    /**
     * @Inject()
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @Inject()
     * @var CollectionService
     */
    protected $collectSerivce;

    /**
     * @Inject()
     * @var XhsTalent
     */
    protected $xhsTalentModel;

    /**
     * @Inject()
     * @var XhsTalentNotes
     */
    protected $xhsTalentNotesModel;
    /**
     * @PostApi(path="start", description="获取用户信息")
     * @FormData(key="id|Id", rule="required")
     */
        public function start(){
            $data = $this->getValidatorData();
            $talentId =  $data['id'];
            $info = $this->xhsTalentModel->where('id',$talentId)->first();
            $ip = $this->collectSerivce->getIp();
            $path = $info->url;
            $client = $this->clientFactory->create(['verify'=>false]);
            $url ="http://139.198.171.152/base_info?path={$path}";
            $html = $client->get($url)->getBody()->getContents();
            dd($html);

            $List = QueryList::html($html);
            $title = $List->find('title')->text();
            if($title == '滑块验证'){
               err('出现滑块验证',301,['url'=>$path]);
            }

            preg_match('/window.__INITIAL_SSR_STATE__=(.*)<\/script>/',$html,$res);

            $res = str_replace('undefined',"\"\"",$res[1]);
            $res = json_decode($res,true);

            if($res['status'] == 0){
                $userDetail = $res['Main']['userDetail'];
                $notesDetail = $res['Main']['notesDetail'];


                $talentInfo = [
                  'fans'=>$userDetail['fans'],
                  'gender'=>$userDetail['gender'],
                  'nickname'=>$userDetail['nickname'],
                  'location'=>$userDetail['location'],
                  'notes'=>$userDetail['notes'],
                  'desc'=>$userDetail['desc'],
                  'image'=>$userDetail['image'],
                  'liked'=>$userDetail['liked'],
                  'follows'=>$userDetail['follows'],
                ];

                $talent = $this->xhsTalentModel->where('id',$talentId)->update($talentInfo);

                $talentInfoNotes = [];
                foreach($notesDetail as $v){
                    $talentInfoNote = [
                        [
                        'note_id'=>$v['id'],
                        'talent_id'=>$talentId,
                        ],
                        [
                        'title'=>$v['title'],
                        'likes'=>$v['likes'],
                        'time'=>$v['time'],
                        ]
                    ];
                    $talentInfoNotes[] = $talentInfoNote;
                }
                dump($talentInfoNotes);
                foreach($talentInfoNotes as $v){
                    $this->xhsTalentNotesModel->updateOrCreate($v[0],$v[1]);
                }
                succ();
            }
            err();


//            $talent = [
//                'fans'=>
//            ];



            die;
            $List = QueryList::html($html);

            $title = $List->find('title')->text();
            dump($title);

            $data  = $List->find('.card-info')->children('.info-number')->texts();
    //        dump($data);
            $nickname =  $List->find('.name-detail')->text();
            $script = $List->find('script')->texts();


//            dump($script);

            $json = ltrim($script[count($script)-1],'window.__INITIAL_STATE__=');
            $json = str_replace('undefined',"\"\"",$json);
            dd($json);


//            preg_match_all('/albumDetail":\[\{"id":"(.*)","desc/',$script[8],$data);


            //获得个人专辑的链接
            $path = "https://www.xiaohongshu.com/board/{$data[1][0]}";
            $url ="http://39.105.46.139:9509?path={$path}";
            $html = $client->get($url)->getBody()->getContents();
            $List = QueryList::html($html);
            $data = $List->find('script')->texts();
            $json = ltrim($data[count($data)-1],'window.__INITIAL_SSR_STATE__=');
            $json = str_replace('undefined',"\"\"",$json);

            succ(json_decode($json,true));

            //        dd($script);






            dd($nickname);


        }

    public function decodeUnicode($str)
    {

        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', function ($matches) {
            return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");
        }, $str);
    }

    public function filterEmoji($str)
    {
        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);

        return $str;
    }
    public function curl_get_https($url)
    {
        $path = file_get_contents($url);
        return $path;
    }


}
