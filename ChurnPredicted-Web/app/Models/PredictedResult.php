<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PredictedResult extends Model
{
    protected $table = 'predicted_results';
    protected $fillable = [
        'id_nama',
        'cluster',
        'prediction',
        'probability_no_churn',
        'probability_churn'
    ];

    public function predicted()
    {
        $this->belongsTo(PredictedResult::class, 'id_nama', 'id');
    }
}
