<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'role_name',
        'role_value'
    ];

    public function permissions()
    {
        // return $this->belongsToMany('App\Models\Permission', 'permission_role', 'role_id', 'permission_id');
        return $this->belongsToMany(Permission::class);
    }
}
