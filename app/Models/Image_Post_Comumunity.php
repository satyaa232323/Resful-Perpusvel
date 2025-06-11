<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image_Post_Comumunity extends Model
{
    protected $fillable = [
        'post__comumunity_id',
        'image',
        'user_id',   
    ];

    public function post__comumunity()
    {
        return $this->belongsTo(Post_Comumunity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}