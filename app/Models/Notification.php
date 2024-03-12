<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    /**
     * The table associated with the model
     *
     * @var string
     */
    protected $table = "notifications";

    /**
     * The attributes that are mass assignable
     *
     */
    protected $fillable = [
        'data',
        'type',
        'read_at',
        'notifiable_type',
        'notifiable_id'
    ];
    public function notifiable()
    {
        return $this->morphTo();
    }
}
