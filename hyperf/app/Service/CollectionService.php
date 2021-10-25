<?php


namespace App\Service;


use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\Database\Model\Builder;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Guzzle\ClientFactory;

class CollectionService extends Service
{
    public function getModel()
    {
        // TODO: Implement getModel() method.
    }

    /**
     * @Inject()
     * @var ClientFactory
     */
    protected $clientFactory;

    /**
     * @Cacheable(prefix="ip", ttl=10, listener="ip-update")
     */
    public function getIp(){
        $getIpUrl = 'http://webapi.http.zhimacangku.com/getip?num=1&type=2&pro=&city=0&yys=0&port=1&time=1&ts=0&ys=0&cs=0&lb=1&sb=0&pb=4&mr=1&regions=';
        $setWhite = 'https://wapi.http.linkudp.com/index/index/save_white?neek=33817&appkey=7072619cf43c6883df3064848ef4ebb3&white=39.105.46.139';

        $client =$this->clientFactory->create(['verify'=>false]);

        $res = $client->request('GET', $setWhite);

        $res = $client->request('GET', $getIpUrl)->getBody()->getContents();
        $data = json_decode($res,true)['data'][0];
        $url = $data['ip'].':'.$data['port'];
        return $url;
    }
}