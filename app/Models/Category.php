<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'parent_id','name','slug','description',
        'created_by','updated_by','deleted_by',
    ];

    protected $dates = ['deleted_at'];

    protected static function boot() {
        parent::boot();
        static::creating(function($model)  {
            $user = Auth::user();
            if ($user) {
                $model->created_by = $user->id;
                $model->updated_by = $user->id;
            }
        });
        static::updating(function($model) {
            $user = Auth::user();
            if ($user) {
                $model->updated_by = $user->id;
            }
            $model->updated_at = Carbon::now();
        });
        static::deleting(function($model) {
            $user = Auth::user();
            if ($user) {
                $model->deleted_by = $user->id;
                $model->save();
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function books()
    {
        return $this->belongsToMany(Book::class)
                    ->using(BookCategory::class)
                    ->withPivot('sort_order')
                    ->withTimestamps();
    }
}
