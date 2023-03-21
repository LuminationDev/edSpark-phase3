<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'permission_name',
        'permission_value'
    ];

    public function roles()
    {
        // return $this->belongsToMany('App\Models\Role', 'permission_role', 'role_id', 'permission_id');
        return $this->belongsToMany(Role::class);
    }
}
