<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment_Comumunity extends Model
{
    protected $fillable = [
        'user_id',
        'community_id',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function community()
    {
        return $this->belongsTo(related: Post_Comumunity::class);
    }
}