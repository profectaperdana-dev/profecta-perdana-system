<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\AreaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{

    public function store(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }

        $area = new AreaModel();
        $area->name = $request->name;
        $area->save();

        return response()->json([
            'status' => 200,
            'message' => 'Adding area success!',
            'data' => $area
        ]);
    }

    public function edit(Request $request, $id)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }
        $area = AreaModel::where('id', $id)->first();
        $area->name = $request->name;

        $area->save();

        return response()->json([
            'status' => 200,
            'message' => 'Editing area success!',
            'data' => $area
        ]);
    }

    public function delete($id)
    {
        $area = AreaModel::where('id', $id)->first();
        $area->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleting area success!'
        ]);
    }

    public function api_getarea()
    {
        $area = AreaModel::oldest()->get();

        return response()->json([
            'status' => 200,
            'data' => $area
        ]);
    }
}
