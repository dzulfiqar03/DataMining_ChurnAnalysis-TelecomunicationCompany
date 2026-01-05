<?php

namespace App\GraphQL\Resolver;

use App\Models\Predicted;

class ChurnResolver
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        $getData = Predicted::with('predicted_result')->get();
        return [
            'dataList' => $getData,
        ];
    }
    public function getByID($root, array $args)
    {
        $getId = Predicted::with('predicted_result')->findOrFail($args['id']);
        return $getId;
    }
}
