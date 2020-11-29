<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Admin extends Model
{
    use SoftDeletes;

    protected $table = 'admins';

    public function roles()
    {
        return $this->belongsToMany('App\Model\Role', 'admin_role', 'admin_id', 'role_id' )
            ->withTimestamps();
    }
}
