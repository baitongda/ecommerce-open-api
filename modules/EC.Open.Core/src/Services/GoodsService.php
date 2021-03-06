<?php

/*
 * This file is part of ibrand/EC-Open-Core.
 *
 * (c) iBrand <https://www.ibrand.cc>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace iBrand\EC\Open\Core\Services;

use iBrand\Component\Category\RepositoryContract as CategoryRepository;
use iBrand\Component\Product\Repositories\GoodsRepository;
use Illuminate\Support\Facades\DB;

class GoodsService
{
    private $categoryRepository;
    private $goodsRepository;

    public function __construct(CategoryRepository $categoryRepository, GoodsRepository $goodsRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->goodsRepository = $goodsRepository;
    }

    public function getGoodsByCategoryId($categoryId)
    {
        $categoryIds = $this->categoryRepository->getSubIdsById($categoryId);
        $goodsCategoryTable = config('ibrand.app.database.prefix', 'ibrand_').'goods_category';
        $categoryGoodsIds = DB::table($goodsCategoryTable)->whereIn('category_id', $categoryIds)->select('goods_id')->distinct()->get()
            ->pluck('goods_id')->toArray();

        return $this->goodsRepository->findWhereIn('id', $categoryGoodsIds);
    }
}
