<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements HasName
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'display_name',
        'email',
        'password',
        'status',
        'remember_token',
        'token',
        'isFirstTimeVisit'
    ];

    public function canAccessFilament(): bool {
        return str_ends_with($this->email, 'edspark.sa.gov.au') && $this->hasVerifiedEmail();
    }

    public function getFilamentName(): string
    {
        return "{$this->full_name}";
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function usertype()
    {
        return $this->belongsTo(Usertype::class);
    }

    public function hasRole($roleName){
        $role = Role::where('role_name', $roleName)->first();
        return User::where('role_id', $role->id)->get();
    }

    // public function hasPermissions($roleName){
    //     $role = Role::where('role_name', $roleName)->first();
    //     $permissions = $role->permissions;
    //     return $permissions;
    // }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function partner()
    {
        return $this->hasOne(Partner::class);
    }
}
