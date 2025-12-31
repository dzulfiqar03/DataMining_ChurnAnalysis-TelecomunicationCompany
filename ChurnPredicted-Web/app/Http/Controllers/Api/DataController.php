<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DataController extends Controller
{
    public function store(Request $request)
    {
        cache()->put('cluster_data', $request->cluster_summary, now()->addMinutes(60));
        cache()->put('name', $request->name, now()->addMinutes(60));
        cache()->put('tenure', $request->tenure, now()->addMinutes(60));
        cache()->put('online_security', $request->online_security, now()->addMinutes(60));
        cache()->put('tech_support', $request->tech_support, now()->addMinutes(60));
        cache()->put('cluster', $request->cluster, now()->addMinutes(60));
        cache()->put('prediction', $request->predict, now()->addMinutes(60));
        cache()->put('probability_no_churn', $request->prob_nochurn, now()->addMinutes(60));
        cache()->put('probability_churn', $request->prob_churn, now()->addMinutes(60));

        return response()->json(['status' => 'ok']);
    }
    
}
