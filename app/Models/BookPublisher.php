<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BookPublisher extends Pivot
{
    protected $table = 'book_publisher';

    protected $fillable = ['book_id', 'publisher_id'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
