<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PredictedSendService;
use Illuminate\Http\Request;

class DataSenderController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct(
        protected PredictedSendService $predictedSendService
    ) {}
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => [10, 20, 30, 40, 50],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei']
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
        $send = $this->predictedSendService->create($request->all());

        return $send;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
