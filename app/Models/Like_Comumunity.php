<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like_Comumunity extends Model
{
    protected $fillable = [
        'user_id',
        'post__comumunity_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function post__comumunity()
    {
        return $this->belongsTo(Post_Comumunity::class);
    }

}