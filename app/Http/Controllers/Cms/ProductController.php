<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\ProductModel as CmsProductModel;
use App\Models\ProductModel;
use App\Models\SubMaterialModel;
use App\Models\SubTypeModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $sub_material = SubMaterialModel::oldest('nama_sub_material')->get();
        $get_product = SubTypeModel::with(['sub_materials', 'cmsProductBy'])
            ->select("type_name", "sub_material_id", "id")
            ->where("sub_material_id", $sub_material->first()->id)
            ->get()
            ->sortBy(function ($query) {
                return $query->sub_materials->nama_sub_material . ' ' . $query->type_name;
            });

        // dd($get_product);
        $data = [
            'title' => "Manage Product Content",
            'all_sub_material' => $sub_material,
            'filtered_products' => $get_product
        ];

        return view('cms.products.index', $data);
    }

    public function store(Request $request)
    {
        $get_product = CmsProductModel::where('product_id', $request->product_id)->first();

        if (!$get_product) {
            $get_product = new CmsProductModel();
        }

        $get_product->product_id = $request->product_id;
        $get_product->additional_desc = $request->additional_desc;

        $img = $request->photo;
        if ($img && $img != 'undefined') {
            $directory = 'images/cms/products';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            //delete old picture
            $path = public_path('images/cms/products/') . $get_product->photo;
            if (File::exists($path)) {
                File::delete($path);
            }

            $name_img = time() . '.' . $img->extension();
            $img->move(public_path($directory), $name_img);

            $get_product->photo = $name_img;
        }


        $get_product->save();

        return response()->json([
            'status' => 200,
            'message' => 'Changing product description success!',
            'data' => $get_product
        ]);
    }

    public function api_getsubmaterial()
    {
        $sub_material = SubMaterialModel::oldest('nama_sub_material')->whereNotIn('id', [12,8, 9])->get();

        return response()->json([
            'status' => 200,
            'data' => $sub_material
        ]);
    }

    public function api_filterbysubmaterial($id)
    {
        $get_product_by_sub_material = SubTypeModel::with(['sub_materials', 'cmsProductBy'])
            ->select("type_name", "sub_material_id", "id")
            ->where("sub_material_id", $id)
            ->get()
            ->sortBy(function ($query) {
                return $query->sub_materials->nama_sub_material . ' ' . $query->type_name;
            });
        return response()->json([
            'status' => 200,
            'data' => $get_product_by_sub_material->values()
        ]);
    }

    public function api_search($text)
    {
        $search_result = SubTypeModel::with(['sub_materials', 'cmsProductBy'])
            ->select("type_name", "sub_material_id", "id")
            ->get()
            ->map(function ($item) {
                $item->custom_attribute = $item->sub_materials->nama_sub_material . ' ' . $item->type_name;
                return $item;
            })
            // ->pluck('custom_attribute')
            ->filter(function ($item) use ($text) {
                return preg_match("/$text/i", $item->custom_attribute);
            })
            ->sortBy('custom_attribute');

        return response()->json([
            'status' => 200,
            'data' => $search_result->values()
        ]);
    }
}
