<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAndArticle extends Model
{
    public function postsAndArticles() {
        return $this->hasMany(PostAndArticle::class);
    }
}
