<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\PortfolioModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class PortfolioController extends Controller
{
    public function index()
    {
        $get_all_portfolio = PortfolioModel::latest()->get();
        $data = [
            'title' => 'Manage Portfolio Content',
            'all_portfolio' => $get_all_portfolio,
        ];

        return view('cms.portfolio.index', $data);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'img' => 'image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ]);
        }

        $portfolio = new PortfolioModel();

        $img = $request->img;
        if ($img) {
            $directory = 'images/cms/portfolios';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $name_img = time() . '.' . $img->extension();
            $img->move(public_path($directory), $name_img);
        } else {
            $name_img = 'blank';
        }
        $portfolio->img = $name_img;

        $portfolio->title = $request->title;
        $portfolio->description = $request->description;
        $portfolio->created_at = Auth::user()->id;
        $portfolio->save();

        return response()->json([
            'status' => 200,
            'message' => 'Adding portfolio success!',
            'data' => $portfolio,
        ]);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors(),
            ]);
        }
        $portfolio = PortfolioModel::where('id', $id)->first();
        $portfolio->title = $request->title;
        $portfolio->description = $request->description;

        $portfolio->save();

        return response()->json([
            'status' => 200,
            'message' => 'Editing portfolio success!',
            'data' => $portfolio,
        ]);
    }

    public function delete($id)
    {
        $portfolio = PortfolioModel::where('id', $id)->first();
        $path = public_path('images/cms/portfolios/') . $portfolio->img;
        if (File::exists($path)) {
            File::delete($path);
        }
        $portfolio->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleting Portfolio success!',
        ]);
    }

    public function api_getportfolio()
    {
        $get_all_portfolio = PortfolioModel::latest()->take(9)->get();

        return response()->json([
            'status' => 200,
            'data' => $get_all_portfolio,
        ]);
    }
}
