<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventformat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_formats';
    public function events()
    {
        return $this->hasMany(Event::class);
    }
    /**
     * The attributes that are mass assignable
     *
     * @var array
     */
    protected $fillable = [
        'event_format_name',
        'event_format_value'
    ];
}
