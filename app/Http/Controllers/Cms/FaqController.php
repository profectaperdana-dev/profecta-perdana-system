<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\FaqModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    public function index()
    {
        $get_all_faq = FaqModel::oldest('sort_number')->get();

        $data = [
            'title' => "Manage FAQ Content",
            'all_faq' => $get_all_faq
        ];

        return view('cms.faqs.index', $data);
    }

    public function store(Request $request)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }

        $next_order = FaqModel::all()->count() + 1;

        $faq = new FaqModel();
        $faq->sort_number = $next_order;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->created_by = Auth::user()->id;
        $faq->save();

        return response()->json([
            'status' => 200,
            'message' => 'Adding FAQ success!',
            'data' => $faq
        ]);
    }

    public function edit(Request $request, $id)
    {
        // return response()->json($request->all());
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }
        $faq = FaqModel::where('id', $id)->first();
        $faq->sort_number = $request->sort_number;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();

        return response()->json([
            'status' => 200,
            'message' => 'Editing FAQ success!'
        ]);
    }

    public function delete($id)
    {
        $faq = FaqModel::where('id', $id)->first();
        $faq->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleting FAQ success!'
        ]);
    }

    public function api_getfaq()
    {
        $faq = FaqModel::oldest('sort_number')->get();

        return response()->json([
            'status' => 200,
            'data' => $faq
        ]);
    }
}
