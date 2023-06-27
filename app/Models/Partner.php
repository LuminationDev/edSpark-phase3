<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Partner extends Authenticatable
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'partners';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'logo',
        'cover_image',
        'motto',
        'introduction'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
