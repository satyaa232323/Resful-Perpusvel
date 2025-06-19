<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post_Comumunity extends Model
{
    protected $fillable = [
        'user_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment_Comumunity::class);
    }

    public function images()
    {
        return $this->hasMany(Image_Post_Comumunity::class, 'post__comumunity_id');
    }

    public function videos()
    {
        return $this->hasMany(Video_Post_Comumunity::class, 'post__comumunity_id');
    }

    public function likes()
    {
        return $this->hasMany(Like_Comumunity::class, 'post__comumunity_id');
    }
}
