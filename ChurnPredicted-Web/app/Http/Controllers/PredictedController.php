<?php

namespace App\Http\Controllers;

use App\Models\Predicted;
use App\Services\PredictedSendService;
use Illuminate\Http\Request;

class PredictedController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(
        protected PredictedSendService $predictedSendService
    ) {}
    public function index()
    {
        $title = "Home";
        $dataPredict = $this->predictedSendService->showAll();

        return view('main')->with([
            'title' => $title,
            'dataPredict' => $dataPredict,
            'clusters' => cache('cluster_data'),
            'tenure' => cache('tenure'),
            'online_security' => cache('online_security'),
            'tech_support' => cache('tech_support'),
            'cluster' => cache('cluster'),
            'prediction' => cache('prediction'),
            'probability_no_churn' => cache('probability_no_churn'),
            'probability_churn' => cache('probability_churn'),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Predicted $predicted)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Predicted $predicted)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Predicted $predicted)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Predicted $predicted)
    {
        //
    }
}
