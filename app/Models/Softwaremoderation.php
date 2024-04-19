<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Softwaremoderation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'softwares';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'author_id',
        'title',
        'content',
        'excerpt',
        'cover_image',
        'extra_content',
        'how_to_access',
        'status',
        'created_at',
        'updated_at'
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function softwaretype()
    {
        return $this->belongsTo(Softwaretype::class);
    }
}
