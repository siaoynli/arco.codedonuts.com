<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Api\V1\Product;
use App\Models\Api\V1\User;
use Illuminate\Http\Request;


class IndexController extends Controller
{
    public function index()
    {
        return ping();
    }

    public function user()
    {
        return new UserResource(User::find(1));
    }

    /**
     * @Author: lixiaoyun
     * @Email: 120235331@qq.com
     * @Date: 2022/7/27 16:53
     * @Description: 搜索产品demo
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function search(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = 5;
        // 构建查询
        $params = [
            'index' => 'products',
            'type' => '_doc',
            'body' => [
                'from' => ($page - 1) * $perPage, // 通过当前页数与每页数量计算偏移值
                'size' => $perPage,
                'query' => [
                    'bool' => [
                        'filter' => [
                            ['term' => ['on_sale' => true]],
                        ],
                    ],
                ],
            ],
        ];


        if ($search = $request->input('search', '')) {
            $keywords = array_filter(explode(' ', $search));
            $params['body']['query']['bool']['must'] = [];
            // 遍历搜索词数组，分别添加到 must 查询中
            foreach ($keywords as $keyword) {
                $params['body']['query']['bool']['must'][] = [
                    'multi_match' => [
                        'query' => $keyword,
                        'fields' => [
                            'title^2',
                            'long_title^2',
                            'category^2',
                            'description',
                            'summary^2',
                            'skus.title^2',
                            'skus.description',
                            'properties.value',
                        ],
                    ],
                ];
            }
        }


        // 是否有提交 order 参数，如果有就赋值给 $order 变量
        // order 参数用来控制商品的排序规则
        if ($order = $request->input('order', '')) {
            // 是否是以 _asc 或者 _desc 结尾
            if (preg_match('/^(.+)_(asc|desc)$/', $order, $m)) {
                // 如果字符串的开头是这 3 个字符串之一，说明是一个合法的排序值
                if (in_array($m[1], ['price', 'sold_count', 'rating'])) {
                    // 根据传入的排序值来构造排序参数
                    $params['body']['sort'] = [[$m[1] => $m[2]]];
                }
            }
        }


        $result = app('es')->search($params);

        // 通过 collect 函数将返回结果转为集合，并通过集合的 pluck 方法取到返回的商品 ID 数组
        $productIds = collect($result['hits']['hits'])->pluck('_id')->all();
        // 通过 whereIn 方法从数据库中读取商品数据
        return Product::query()
            ->whereIn('id', $productIds)
            // orderByRaw 可以让我们用原生的 SQL 来给查询结果排序
            ->orderByRaw(sprintf("FIND_IN_SET(id, '%s')", join(',', $productIds)))
            ->get();
    }
}
