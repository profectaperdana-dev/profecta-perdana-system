<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class CustomerAreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_customer_areas = CustomerAreaModel::all();
        $data = [
            'title' => 'Master Area',
            'customer_areas' => $all_customer_areas
        ];

        return view('customer_areas.index', $data);
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
            'area_name' => 'required',
            'area_code' => 'required|unique:customer_areas|max:3'
        ]);
        try {
            DB::beginTransaction();
            $validated_data['created_by'] = Auth::user()->id;

            CustomerAreaModel::create($validated_data);

            DB::commit();
            return redirect('/customer_areas')->with('success', 'Customer Area Add Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/customer_areas')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            'area_name_edit' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $customer_area = CustomerAreaModel::where('id', $id)->firstOrFail();
            $customer_area->area_name = $validateData['area_name_edit'];
            $customer_area->save();

            DB::commit();
            return redirect('/customer_areas')->with('success', 'Customer Areas Edit Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/customer_areas')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            CustomerAreaModel::where('id', $id)->delete();

            DB::commit();
            return redirect('/customer_areas')->with('error', 'Customer Areas Delete Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/customer_areas')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
