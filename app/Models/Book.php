<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'number_book',
        'publisher',
        'cover',
        'publication_year',
        'category_id',
        'stock'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }
    
}