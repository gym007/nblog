<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

// use

class Menu extends Model
{
    use SoftDeletes;

    protected $table = 'menus';

    /**
     * 组成树形数据
     * @return array
     */
    public static function treeForSelect()
    {
        if ($menus = self::select('id', 'pid', 'name', 'is_menu')->orderBy('id')->get()) {
            $menus = $menus->toArray();
            foreach ($menus as &$menu) {
                $menu['open'] = true;
                $menu['checked'] = true;
                $menu['spread'] = true;
            }
            // 转化成树状数组
            $menus = p2s($menus);
        } else {
            $menus = [];
        }
        return $menus;
    }

    public static function treeForMenu()
    {
        if ($menus = self::select('id', 'pid', 'name', 'url')->where('is_menu', 1)->orderBy('id')->get()) {
            $menus = $menus->toArray();
            $arr = [];
            foreach ($menus as $key => $menu) {
                $menu['icon'] = '&#xe705;';
                $arr[] = $menu;
            }
            $menus = $arr;
            // 转化成树状数组
            $menus = p2s($menus);
        } else {
            $menus = [];
        }

        return $menus;
    }

    public static function treeForMenuForAdmin($adminId)
    {
        $sql = <<<SQL
SELECT
m.id, m.pid, m.name, m.url, m.is_menu, m.route
FROM
menus m ,admins a, admin_role ar, role_menu rm 
WHERE
m.id = rm.menu_id and ar.role_id = rm.role_id and ar.admin_id = a.id and a.id = ?
SQL;

        if ($menus = DB::select($sql, [$adminId])) {
            $arr = [];
            $permissions = [];
            foreach ($menus as $key => $menu) {
                if ($menu->is_menu > 0) {
                    // 菜单记录
                    $arr[] = [
                        'id' => $menu->id,
                        'pid' => $menu->pid,
                        'name' => $menu->name,
                        'url' => $menu->url,
                        'icon' => '&#xe705;',
                    ];
                }

                $permissions[] = $menu->route;
            }
            $menus = $arr;
            // 转化成树状数组
            $menus = p2s($menus);
        } else {
            $permissions = [];
            $menus = [];
        }

        $data = [
            'menus' => $menus,
            'permissions' => $permissions,
        ];

        return $data;
    }



}
