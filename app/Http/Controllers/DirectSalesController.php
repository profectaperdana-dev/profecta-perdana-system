<?php

namespace App\Http\Controllers;

use App\Models\CarBrandModel;
use App\Models\DistrictModel;
use App\Models\MotorBrandModel;
use App\Models\ProductModel;
use App\Models\SubMaterialModel;
use App\Models\ValueAddedTaxModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectSalesController extends Controller
{
    public function index()
    {
    }

    public function create()
    {
        $retail_products = ProductModel::with(['stockBy', 'materials', 'sub_materials', 'sub_types', 'uoms'])
            ->whereIn('shown', ['all', 'retail'])
            ->whereHas('stockBy', function ($query) {
                $query->where('warehouses_id', Auth::user()->warehouse_id);
            })->get();
        $car_brands = CarBrandModel::with('typeBy')->oldest('car_brand')->get();
        $motor_brands = MotorBrandModel::with('typeBy')->oldest('name_brand')->get();
        $sub_materials = SubMaterialModel::all();
        $ppn = ValueAddedTaxModel::first()->ppn / 100;
        $districts = DistrictModel::all();

        $data = [
            'title' => 'Create Retail Order',
            'retail_products' => $retail_products,
            'car_brands' => $car_brands,
            'motor_brands' => $motor_brands,
            'sub_materials' => $sub_materials,
            'ppn' => $ppn,
            'districts' => $districts
        ];

        return view('direct_sales.create', $data);
    }

    public function store(Request $request)
    {
        dd($request->all());
    }

    public function select()
    {
        $sub_materials = [];
        if (request()->has('q')) {
            $search = request()->q;
            $sub_materials = DistrictModel::where('district_name', 'LIKE', "%$search%")
                ->get();
        } else {
            $sub_materials = DistrictModel::get();
        }
        return response()->json($sub_materials);
    }
}
