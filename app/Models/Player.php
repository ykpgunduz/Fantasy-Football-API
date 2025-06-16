<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'team',
        'position',
        'age',
        'height',
        'weight',
        'nationality',
        'api_id'
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getHeightFormattedAttribute()
    {
        return $this->height ? $this->height . ' cm' : '-';
    }

    public function getWeightFormattedAttribute()
    {
        return $this->weight ? $this->weight . ' kg' : '-';
    }
}
