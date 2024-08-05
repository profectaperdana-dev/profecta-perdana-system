<?php

namespace App\Http\Controllers;

use App\Models\CustomerCategoriesModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CustomerCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_customer_categories = CustomerCategoriesModel::all();
        $data = [
            'title' => " Customer Category",
            'customer_categories' => $all_customer_categories
        ];

        return view('customer_categories.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'category_name' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $validated_data['created_by'] = Auth::user()->id;

            CustomerCategoriesModel::create($validated_data);

            DB::commit();
            return redirect('/customer_categories')->with('success', 'Customer Category Add Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/customer_categories')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $validateData = $request->validate([
            'category_name_edit' => 'required'
        ]);

        try {
            DB::beginTransaction();
            $customer_category = CustomerCategoriesModel::where('id', $id)->firstOrFail();
            $customer_category->category_name = $validateData['category_name_edit'];
            $customer_category->save();

            DB::commit();
            return redirect('/customer_categories')->with('success', 'Customer Categories Edit Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/customer_categories')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!Gate::allows('level1')) {
            abort(403);
        }
        try {
            DB::beginTransaction();
            CustomerCategoriesModel::where('id', $id)->delete();

            DB::commit();
            return redirect('/customer_categories')->with('error', 'Customer Categories Delete Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/customer_categories')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
