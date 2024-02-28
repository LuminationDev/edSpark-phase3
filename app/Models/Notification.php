<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = "user_notifications";

    /**
     * The attributes that are mass assignable
     *
     */
    protected $fillable = [
        'data',
        'type',
        'read_at',
    ];
    public function notifiable()
    {
        return $this->morphTo();
    }
}
