<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2020 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------
namespace App\Controller\Api\Product;

use App\Controller\AbstractController;
use App\Service\Product\ProductService;
use Hyperf\Apidog\Annotation\ApiController;
use Hyperf\Apidog\Annotation\FormData;
use Hyperf\Apidog\Annotation\PostApi;
use Hyperf\Di\Annotation\Inject;

/**
 * @ApiController(tag="spu",prefix="api/Product/spu",description="")
 */
class Spu extends AbstractController
{

    /**
     * @Inject()
     * @var ProductService
     */
    protected $productService;

    public function lst()
    {
        [$page, $limit] = $this->getPage();
        $where = $this->request->params([
            'keyword', 'cate_id', 'order', 'price_on', 'price_off', 'brand_id', 'pid','mer_cate_id','product_type','action'
        ]);
        $where['is_gift_bag'] = 0;
        $where['order'] = $where['order'] ? $where['order'] : 'rank';
        $data = $this->repository->getApiSearch($where, $page, $limit, $this->userInfo);
        return app('json')->success($data);
    }


    public function merProductLst($id)
    {
        [$page, $limit] = $this->getPage();
        $where = $this->request->params([
            'keyword', 'cate_id','order', 'price_on', 'price_off', 'brand_id', 'pid','mer_cate_id','product_type','action'
        ]);
        $where['mer_id'] = $id;
        $where['is_gift_bag'] = 0;
        $where['order'] = $where['order'] ? $where['order'] : 'sort';
        $data = $this->repository->getApiSearch($where, $page, $limit, $this->userInfo);
        return app('json')->success($data);
    }

    /**
     * @PostApi(path="recommend", description="推荐")
     */
    public function recommend()
    {
        $list = $this->productService->list([],['id','desc'],['merchant']);
        succ($list);
    }

    public function hot($type)
    {
        [$page, $limit] = $this->getPage();
        $where['hot_type'] = $type;
        $where['is_gift_bag'] = 0;
        $where['order'] = 'star';
        $data = $this->repository->getApiSearch($where, $page, $limit,null);
        return app('json')->success($data);
    }

    public function bag()
    {
        [$page, $limit] = $this->getPage();
        $where['is_gift_bag'] = 1;
        $where['order'] = 'rank';
        $data = $this->repository->getApiSearch($where, $page, $limit,null);
        return app('json')->success($data);
    }

    public function bagRecommend()
    {
        [$page, $limit] = $this->getPage();
        $where['is_gift_bag'] = 1;
        $where['best'] = 'is_best';
        $data = $this->repository->getApiSearch($where, $page, $limit,null);
        return app('json')->success($data);
    }

    public function activeCategory($type)
    {
        $data = $this->repository->getActiveCaategory($type);
        return app('json')->success($data);
    }
}
