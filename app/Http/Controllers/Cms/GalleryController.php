<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\GalleryCategoryModel;
use App\Models\Cms\GalleryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    public function index()
    {
        $get_all_galleries = GalleryModel::latest()->get();
        $get_all_categories = GalleryCategoryModel::oldest('name')->get();
        $data = [
            'title' => "Manage Gallery Content",
            'all_galleries' => $get_all_galleries,
            'all_categories' => $get_all_categories
        ];

        return view('cms.galleries.index', $data);
    }

    public function store(Request $request)
    {
        // return response()->json($request->img->getClientOriginalName());
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'img' => 'image|mimes:jpg,png,jpeg|max:2048',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }

        $gallery = new GalleryModel();

        $img = $request->img;
        if ($img) {
            $directory = 'images/cms/galleries';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            $name_img = time() . '.' . $img->extension();
            $img->move(public_path($directory), $name_img);
        } else {
            $name_img = "blank";
        }
        $gallery->img = $name_img;

        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->category_id = $request->category_id;
        $gallery->created_by = Auth::user()->id;
        $gallery->save();

        $current_data = GalleryModel::with('categoryBy')->where('id', $gallery->id)->first();

        return response()->json([
            'status' => 200,
            'message' => 'Adding gallery success!',
            'data' => $current_data
        ]);
    }

    public function edit(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'category_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => $validator->errors()
            ]);
        }
        $gallery = GalleryModel::where('id', $id)->first();
        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->category_id = $request->category_id;

        $gallery->save();

        $category_name = GalleryCategoryModel::where('id', $gallery->category_id)->first();

        return response()->json([
            'status' => 200,
            'message' => 'Editing gallery success!',
            'data' => $gallery,
            'category_name' => $category_name
        ]);
    }

    public function delete($id)
    {
        $gallery = GalleryModel::where('id', $id)->first();
        $path = public_path('images/cms/galleries/') . $gallery->img;
        if (File::exists($path)) {
            File::delete($path);
        }
        $gallery->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleting Gallery success!'
        ]);
    }

    public function store_category(Request $request)
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

        $category = new GalleryCategoryModel();
        $category->name = $request->name;
        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'Adding gallery category success!',
            'data' => $category
        ]);
    }

    public function edit_category(Request $request, $id)
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
        $category = GalleryCategoryModel::where('id', $id)->first();
        $category->name = $request->name;

        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'Editing gallery category success!',
            'data' => $category
        ]);
    }

    public function delete_category($id)
    {
        $category = GalleryCategoryModel::where('id', $id)->first();
        $category->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Deleting Gallery Category success!'
        ]);
    }

    //API SECTION START

    public function api_getcategory()
    {
        $category = GalleryCategoryModel::oldest('name')->get();
        return response()->json([
            'status' => 200,
            'data' => $category
        ]);
    }

    public function api_getgallery()
    {
        $gallery = GalleryModel::latest()->get();
        return response()->json([
            'status' => 200,
            'data' => $gallery
        ]);
    }

    public function api_filterbycategory($id)
    {

        $filtered_gallery = GalleryModel::where('category_id', $id)->latest()->get();
        if ($id == -1) {
            $filtered_gallery = GalleryModel::latest()->get();
        }

        return response()->json([
            'status' => 200,
            'data' => $filtered_gallery
        ]);
    }

    //API SECTION END
}
