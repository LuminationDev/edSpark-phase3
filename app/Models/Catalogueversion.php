<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogueversion extends Model
{
    use HasFactory;

    protected $table = 'catalogue_versions';
    protected $primaryKey = 'version';

    protected $fillable = [
        'is_active'
    ];

    protected static function boot()
    {
        parent::boot();
        // subscribe to any updates and set all to inactive when one is set to active
        static::updating(function ($updating) {
            if ($updating->is_active) {
                static::where('version', '<>', $updating->version)
                    ->where('is_active', '=', true)
                    ->update(['is_active' => false]);
            }
        });
    }


}
