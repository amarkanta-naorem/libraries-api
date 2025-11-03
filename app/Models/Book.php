<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Book extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id', 'isbn', 'name', 'slug',
        'edition', 'format', 'language',
        'created_by', 'updated_by', 'deleted_by',
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
        return $this->belongsTo(Book::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Book::class, 'parent_id');
    }

    public function authors()
    {
        return $this->belongsToMany(Author::class)
            ->using(AuthorBook::class)
            ->withPivot('sort_order', 'role')
            ->withTimestamps();
    }

    public function publishers()
    {
        return $this->belongsToMany(Publisher::class)
            ->using(BookPublisher::class)
            ->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->using(BookCategory::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)
            ->using(BookTag::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }
}
