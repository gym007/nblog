<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';

    static $tree = [];

    /**
     * @return string 返回所有的分类并组装成html目录树（复选框）
     */
    public static function allCategories()
    {
        if ($categories = self::select('id', 'pid', 'cate_title as title')->orderBy('id')->get()) {
            // 转化成树状数组
            $categories = $categories->toArray();
            foreach ($categories as &$category) {
                $category['spread'] = true;
                $category['checked'] = false;
            }
            $categories = p2s($categories);
        } else {
            $categories = [];
        }

        return $categories;
    }

    /**
     * 返回目录树（表格形式）
     * @return array
     */
    public static function allCategoriesTable()
    {
        if ($categories = self::select('id', 'pid', 'cate_title as name', 'status')->orderBy('id')->get()) {
            // 转化成树状数组
            $categories = $categories->toArray();
            $categories = p2s($categories);
        } else {
            $categories = [];
        }

        return $categories;
    }

    public static function categories2lists()
    {
        if ($categories = self::select('id', 'pid', 'cate_title as name', 'status')->orderBy('id')->get()) {
            // 转化成树状数组
            $categories = $categories->toArray();
            $indexArray = [];
            foreach ($categories as $category) {
                $indexArray[$category['id']] = $category;
            }
            $categories = $indexArray;
        } else {
            $categories = [];
        }
    }

    public static function treeForSelect($href = false)
    {
        if ($categories = self::select('id', 'pid', 'cate_title as name')->orderBy('id')->get()) {
            $categories = $categories->toArray();
            foreach ($categories as &$category) {
                $category['open'] = true;
                $category['checked'] = true;
                $category['spread'] = true;
                if ($href) {
                    $category['href'] = '/article?cate_id=' . $category['id'];
                }
            }
            // 转化成树状数组
            $categories = p2s($categories);
        } else {
            $categories = [];
        }


        $data = [
            [
                'id' => 1,
                'name' => 'zzz',
                'open' => true,
                'children' => [
                    [
                        'id' => 2,
                        'name' => '1',
                        'open' => false,
                        'checked' => true
                    ],
                    [
                        'id' => 3,
                        'name' => '2',
                        'open' => false,
                        'checked' => true
                    ],
                    [
                        'id' => 17,
                        'name' => '3z',
                        'open' => false,
                        'checked' => true
                    ]
                ],
                'checked' => true
            ],
            [
                'id' => 4,
                'name' => '评论',
                'open' => false,
                'children' => [
                    [
                        'id' => 5,
                        'name' => '留言列表',
                        'open' => false,
                        'checked' => false
                    ],
                    [
                        'id' => 6,
                        'name' => '发表留言',
                        'open' => false,
                        'checked' => false
                    ],
                    [
                        'id' => 333,
                        'name' => '233333',
                        'open' => false,
                        'checked' => false
                    ]
                ],
                'checked' => false
            ],
            [
                'id' => 10,
                'name' => '权限管理',
                'open' => false,
                'children' => [
                    [
                        'id' => 8,
                        'name' => '用户列表',
                        'open' => false,
                        'children' => [
                            [
                                'id' => 40,
                                'name' => '添加用户',
                                'open' => false,
                                'url' => null,
                                'title' => '40',
                                'checked' => false,
                                'level' => 2,
                                'check_Child_State' => 0,
                                'check_Focus' => false,
                                'checkedOld' => false,
                                'dropInner' => false,
                                'drag' => false,
                                'parent' => false
                            ],
                            [
                                'id' => 41,
                                'name' => '编辑用户',
                                'open' => false,
                                'checked' => false
                            ],
                            [
                                'id' => 42,
                                'name' => '删除用户',
                                'open' => false,
                                'checked' => false
                            ]
                        ],
                        'checked' => false
                    ],
                    [
                        'id' => 11,
                        'name' => '角色列表',
                        'open' => false,
                        'checked' => false
                    ],
                    [
                        'id' => 13,
                        'name' => '所有权限',
                        'open' => false,
                        'children' => [
                            [
                                'id' => 34,
                                'name' => '添加权限',
                                'open' => false,
                                'checked' => false
                            ],
                            [
                                'id' => 37,
                                'name' => '编辑权限',
                                'open' => false,
                                'checked' => false
                            ],
                            [
                                'id' => 38,
                                'name' => '删除权限',
                                'open' => false,
                                'checked' => false
                            ]
                        ],
                        'checked' => false
                    ],
                    [
                        'id' => 15,
                        'name' => '操作日志',
                        'open' => false,
                        'checked' => false
                    ]
                ],
                'checked' => false
            ]
        ];
        return $categories;
    }

    public static function sonsTree2List($pid)
    {
        if ($categories = self::select('id', 'pid', 'status', 'cate_title')->orderBy('id')->get()) {
            $categories = $categories->toArray();
            $categories = p2s($categories, $pid);
            $rootIds = [];
            foreach ($categories as $category) {
                $rootIds[] = $category['id'];
            }
            $list = tree2list($categories, $rootIds);
            return $list;
        }
        return [];
    }

}
