<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookTag extends Pivot
{
    protected $table = 'book_tag';

    protected $fillable = ['book_id', 'tag_id', 'sort_order'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
