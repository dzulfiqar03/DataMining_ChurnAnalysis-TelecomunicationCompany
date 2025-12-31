<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Predicted extends Model
{
    protected $fillable = [
        'id',
        'name',
        'tenure',
        'online_security',
        'tech_support',
    ];

    public function predicted_result()
{

    return $this->hasMany(PredictedResult::class, 'id_nama', 'id');
}
}
