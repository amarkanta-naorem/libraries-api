<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BookCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Books fetch successfully.',
            'data' => BookResource::collection($this->collection),
            'meta' => [
                'total_books' => $this->collection->count()
            ]
        ];
    }
}
