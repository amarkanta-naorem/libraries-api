<?php

namespace App\Http\Resources\Book;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $thumbnailUuid = null;

        if (!empty($this->thumbnail)) {
            $filename = pathinfo(basename($this->thumbnail), PATHINFO_FILENAME);
            $thumbnailUuid = $filename;
        }

        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'isbn' => $this->isbn,
            'name' => $this->name,
            'slug' => $this->slug,
            'thumbnail' => $thumbnailUuid,
            'edition' => $this->edition,
            'format' => $this->format,
            'language' => $this->language,
        ];
    }
}
