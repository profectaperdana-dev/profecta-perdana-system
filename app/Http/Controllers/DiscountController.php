<?php

namespace App\Http\Controllers;

use App\Events\RealTimeMessage;
use App\Models\CustomerModel;
use App\Models\DiscountModel;
use App\Models\ProductModel;
use App\Models\SubTypeModel;
use Illuminate\Contracts\Auth\Access\Gate as AccessGate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

use function PHPUnit\Framework\isEmpty;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $all_customers = CustomerModel::where('status', 1)->oldest('name_cust')->get();
        $discounts = DiscountModel::get()->unique('customer_id');
        $customer_ids = $discounts->pluck('customer_id');
        $customers_with_disc = CustomerModel::whereIn('id', $customer_ids)->oldest('name_cust')->get();
        // dd($customers_with_disc);
        // foreach ($all_customers as $c) {
        //     dd($c->haveDiscounts);
        // }
        $types = SubTypeModel::get()->sortBy(function ($q) {
            return [
                $q->sub_materials->nama_sub_material,
                $q->type_name
            ];
        });

        $data = [
            'title' => 'Create Discount',
            'customers' => $all_customers,
            'customers_with_disc' => $customers_with_disc,
            'types' => $types
        ];

        return view('discounts.index', $data);
    }
    public function updateInline($id)
    {
        $uom = request()->i;
        $uomS = str_replace(',', '.', $uom);
        $model = DiscountModel::where('id', $id)->first();
        $model->discount = $uomS;
        $model->save();
        return response()->json(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function select($customer_id, $product_id)
    {
        try {
            $discount = [];
            $sub_type = ProductModel::select('id_sub_type')->where('id', $product_id)->first();
            $discount = DiscountModel::select("id", "discount")
                ->where('customer_id', $customer_id)
                ->where('product_id', $sub_type->id_sub_type)
                ->first();

            return response()->json($discount);
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function create()
    {
        abort(404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validate_data = $request->validate([
            "customer_id" => "required|numeric",
            "discountFields.*.product_id" => "required|numeric",
            "discountFields.*.discount" => "required"
        ]);

        try {
            DB::beginTransaction();

            $message_duplicate = "";
            $issaved = false;
            foreach ($request->discountFields as $key => $value) {
                $model = new DiscountModel();
                $model->customer_id = $request->get('customer_id');
                $model->product_id = $value['product_id'];
                $doubled_disc = str_replace(",", ".", $value['discount']);
                $model->discount = $doubled_disc;
                $model->created_by = Auth::user()->id;

                $check_duplicate = DiscountModel::where('customer_id', $model->customer_id)
                    ->where('product_id', $model->product_id)
                    ->count();

                if ($check_duplicate > 0) {
                    $message_duplicate = "You enter duplication of products. Please recheck the discount you set.";
                    continue;
                } else {
                    $issaved = $model->save();
                }
            }
            if (empty($message_duplicate) && $issaved == true) {

                DB::commit();
                return redirect('/discounts')->with('success', 'Add Discount Success');
            } elseif (!empty($message_duplicate) && $issaved == true) {

                DB::commit();
                return redirect('/discounts')->with('success', 'Some of Discounts add maybe Success! ' . $message_duplicate);
            } else {

                DB::rollback();
                return redirect('/discounts')->with('error', 'Add Discount Fail! Maybe the discount already set or check your input again');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/discounts')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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

        // dd($request->all());
        $validate_data = $request->validate([
            "editFields.*.product_id" => "required",
            "editFields.*.discount" => "required"
        ]);

        try {
            DB::beginTransaction();
            foreach ($request->editFields as $edit) {
                $current_discount = DiscountModel::where('customer_id', $id)->where('product_id', $edit['product_id'])->first();
                if ($current_discount == null) {
                    $discount_data = new DiscountModel();
                    $discount_data->product_id = $edit['product_id'];
                    $discount_data->customer_id = $id;
                    $doubled_disc = str_replace(",", ".", $edit['discount']);
                    $discount_data->discount = $doubled_disc;
                    $discount_data->save();
                } else {
                    $discount_data = $current_discount;
                    $doubled_disc = str_replace(",", ".", $edit['discount']);
                    $discount_data->discount = $doubled_disc;
                    $discount_data->save();
                }
            }

            DB::commit();
            return redirect('/discounts')->with('success', 'Discount Edit Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/discounts')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
            DiscountModel::where('id', $id)->delete();

            DB::commit();
            return redirect('/discounts')->with('error', 'Discount Delete Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/discounts')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
