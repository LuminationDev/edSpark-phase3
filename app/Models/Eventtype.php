<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventtype extends Model
{
    use HasFactory;

     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'event_types';
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
        'event_type_name',
        'event_type_value'
    ];
}
