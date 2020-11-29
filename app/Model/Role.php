<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $table = 'roles';

    public function menus()
    {
        return $this->belongsToMany('App\Model\Menu', 'role_menu', 'role_id', 'menu_id' )
            ->withTimestamps()->using('App\Model\RoleMenu');
        // ->using('App\Model\ArticleTag');
    }
}
