<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table='labels';
    protected $fillable = ['value', 'description', 'type'];

//    public function labellables()
//    {
//        return $this->hasMany(Labellable::class);
//    }

    public function advices()
    {
        return $this->morphedByMany(Advice::class, 'labellable', 'labellables');
    }

    public function events()
    {
        return $this->morphedByMany(Event::class, 'labellable', 'labellables');
    }

    public function softwares()
    {
        return $this->morphedByMany(Software::class, 'labellable', 'labellables');
    }

    public function hardwares()
    {
        return $this->morphedByMany(Hardware::class, 'labellable', 'labellables');
    }
}
