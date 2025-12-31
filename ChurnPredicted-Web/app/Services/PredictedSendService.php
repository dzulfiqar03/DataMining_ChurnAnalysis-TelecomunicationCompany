<?php

namespace App\Services;

use App\Models\Predicted;
use App\Models\PredictedResult;

class PredictedSendService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function showAll()
    {

        $data = Predicted::with('predicted_result')->get();
        return $data;
    }

    public function create(array $args)
    {

        $predicted =   Predicted::create([
            'name' => $args['name'],
            'tenure' => $args['tenure'],
            'online_security' => $args['online_security'],
            'tech_support' => $args['tech_support']
        ]);

        PredictedResult::create([
            'id_nama' => $predicted->id,
            'cluster' => $args['cluster'],
            'prediction' => $args['predict'],
            'probability_no_churn' => $args['prob_nochurn'],
            'probability_churn' => $args['prob_churn']
        ]);

        return response()->json([
            'status' => 'Success'
        ]);
    }
}
