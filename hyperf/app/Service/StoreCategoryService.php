<?php

namespace App\Service;

use App\Model\Community;
use App\Model\CommunityLike;
use App\Model\CommunityReport;
use App\Model\Product\Category;
use App\Model\StoreCategory;
use App\Model\User;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use App\Constants\ErrorCode;


class StoreCategoryService extends Service
{
    /**
     * @Inject()
     * @var Category
     */
    protected $productCategory;

    public function getList()
    {

    }

    public function getTreeList()
    {
        $list = $this->productCategory->with('picture')->get()->toArray();
        $list = list_to_tree($list);
        return $list;
    }


}