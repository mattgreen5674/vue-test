<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MattTestController extends Controller
{
    public function getSomeData()
    {
        $data = [
            ['id' => 1, 'name' => 'test1', 'type' => 'active'],
            ['id' => 2, 'name' => 'test2', 'type' => 'inactive'],
            ['id' => 3, 'name' => 'test3', 'type' => 'active'],
            ['id' => 4, 'name' => 'test4', 'type' => 'inactive'],
        ];

        return response()->json($data);
    }

    public function sendSomeData(Request $request)
    {
        $data = collect($request->all())->map(fn($item) => collect($item)->toArray())->filter()->toArray();
        return response()->json($data);
        //return response()->json(['test' => 'testing']);
    }

    public function sendFormData(Request $request)
    {
        $data = collect($request->all())->map(fn($item) => collect($item)->toArray())->filter()->toArray();
        return response()->json($data);
        //return response()->json(['test' => 'testing']);
    }
}
