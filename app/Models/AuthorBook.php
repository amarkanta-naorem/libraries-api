<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class AuthorBook extends Pivot
{
    protected $table = 'author_book';

    protected $fillable = ['author_id', 'book_id', 'sort_order', 'role'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
