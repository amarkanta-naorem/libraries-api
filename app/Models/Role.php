<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name','slug','description'];

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->using(RoleUser::class)
                    ->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class)
                    ->using(PermissionRole::class)
                    ->withTimestamps();
    }
}
