<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partnerprofile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'partner_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'partner_id',
        'user_id',
        'content',
        'introduction',
        'motto',
        'cover_image',
        'logo',
        'status',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the partner associated with the profile.
     */
    public function partner()
    {
        return $this->belongsTo('App\Models\Partner', 'partner_id');
    }

    /**
     * Get the user associated with the profile.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
