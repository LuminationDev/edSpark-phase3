<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;

class Schoolmoderation extends Model
{
    use HasFactory, HasTags;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_profiles';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'site_id',
        'name',
        'content_blocks',
        'logo',
        'cover_image',
        'tech_used',
        'pedagogical_approaches',
        'tech_landscape',
        'is_featured',
        'status',
        'created_at',
        'updated_at',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class,'site_id', 'site_id');
    }
}
