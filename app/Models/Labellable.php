<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Labellable extends Model
{
    protected $table = 'labellables';
    protected $fillable = ['label_id', 'labellable_id', 'labellable_type'];

    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    public function labellable()
    {
        return $this->morphTo();
    }
}