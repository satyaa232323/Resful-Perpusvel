<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Bookresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
        'title' => $this->title,
        'author' => $this->author,
        'number_book'  => $this->number_book,
        'publisher'  => $this->publisher,
        'cover' => asset('storage/' . $this->cover), 
        'publication_year'  => $this->publication_year,
        'category_id'  => $this->category_id,
        'stock' => $this->stock, 
        'slug' => $this->slug,
       ];
    }
}