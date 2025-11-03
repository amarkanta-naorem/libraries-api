<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookCategory extends Pivot
{
    protected $table = 'book_category';

    protected $fillable = ['book_id', 'category_id', 'sort_order'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
