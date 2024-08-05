<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use App\Models\CustomerCategoriesModel;
use App\Models\CustomerModel;
use App\Models\DirectSalesModel;
use App\Models\DiscountModel;
use App\Models\ReturnModel;
use App\Models\ReturnRetailModel;
use App\Models\SalesOrderModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use function App\Helpers\checkOverDue;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $increment = CustomerModel::oldest()->get();
        // $n = 0;
        // foreach ($increment as $value) {
        //     $exl = $value->code_cust;
        //     if ($exl[1] == 1) {
        //         ++$n;
        //         $length = 4;
        //         $cust_number_id = str_pad($n, $length, '0', STR_PAD_LEFT);
        //         $value->code_cust = '01' . $cust_number_id;
        //         dd($value->code_cust);
        //     }
        // }

        $all_customer = CustomerModel::leftJoin('customer_categories', 'customers.category_cust_id', '=', 'customer_categories.id')
            ->leftJoin('customer_areas', 'customers.area_cust_id', '=', 'customer_areas.id')
            ->where('customers.isapprove', 1)
            ->select('customers.*', 'customer_areas.area_name', 'customer_categories.category_name')
            ->oldest('customers.name_cust')
            ->get();

        $data = [
            "title" => 'Data Customers',
            "customers" => $all_customer
        ];

        // checkOverDue();

        return view('customers.index', $data);
    }

    public function select()
    {
        $data = CustomerModel::oldest('name_cust')->get();

        return response()->json($data);
    }
    public function create(Request $request)
    {
        $all_customer_categories = CustomerCategoriesModel::oldest('category_name')->get();
        $all_customer_areas = CustomerAreaModel::oldest('area_name')->get();
        $empty_customer = new CustomerModel();

        $data = [
            'title' => 'Data Customer',
            'customer_categories' => $all_customer_categories,
            'customer_areas' => $all_customer_areas,
            'customer' => $empty_customer

        ];
        return view('customers.create', $data);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $validated_data = $request->validate([
                'name_cust' => 'required',
                'id_card_number' => 'required',
                'phone_cust' => 'required',
                'office_number' => 'required',
                'province' => 'required',
                'city' => 'required',
                'district' => 'required',
                'village' => 'required',
                'address_cust' => 'required',
                'npwp' => 'required',
                'email_cust' => 'required',
                'category_cust_id' => 'required|numeric',
                'area_cust_id' => 'required|numeric',
                'credit_limit' => 'required',
                'label' => 'required',
                'due_date' => 'required|numeric',
                'coordinate' => 'required',
                'reference_image' => 'image|mimes:jpg,png,jpeg|max:2048'
            ], [
                'coordinate.required' => 'Generate Location Button must be clicked'
            ]);

            //create create by
            $validated_data['created_by'] = Auth::user()->id;
            $validated_data['isapprove'] = 0;
            $validated_data['status'] = 0;

            //Create Customer Code
            // $area_code = CustomerAreaModel::where('id', $validated_data['area_cust_id'])->firstOrFail()->area_code;
            // $length = 4;
            // $last_cust_area = CustomerModel::where('area_cust_id', $validated_data['area_cust_id'])->latest()->first();
            // if ($last_cust_area == null) {
            //     $id = 1;
            // } else {
            //     $code = substr($last_cust_area->code_cust, 3);
            //     $id = intval($code) + 1;
            // }
            // $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
            $validated_data['code_cust'] = '-';
            // dd($validated_data['code_cust']);

            //Get Province, City, District, Village
            $province_name = $this->getNameProvince($validated_data['province']);
            $validated_data['province'] = ucwords(strtolower($province_name));
            $city_name = $this->getNameCity($validated_data['city']);
            $validated_data['city'] = ucwords(strtolower($city_name));
            $district_name = $this->getNameDistrict($validated_data['district']);
            $validated_data['district'] = ucwords(strtolower($district_name));

            //Process Image
            $file_ref = $request->file('reference_image');
            if ($file_ref) {
                $name_file_ref = time() . '_' . $file_ref->getClientOriginalName();
                $file_ref->move(public_path('images/customers'), $name_file_ref);
            } else {
                $name_file_ref = "blank";
            }
            $validated_data['reference_image'] = $name_file_ref;

            $file_id = $request->file('id_card_image');
            if ($file_id) {
                $directory = 'images/customers/ktp';
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                $name_file_id = time() . '_' . $file_id->getClientOriginalName();
                $file_id->move(public_path($directory), $name_file_id);
            } else {
                $name_file_id = "blank";
            }
            $validated_data['id_card_image'] = $name_file_id;

            $file_npwp = $request->file('npwp_image');
            if ($file_npwp) {
                $directory = 'images/customers/npwp';
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                $name_file_npwp = time() . '_' . $file_npwp->getClientOriginalName();
                $file_npwp->move(public_path($directory), $name_file_npwp);
            } else {
                $name_file_npwp = "blank";
            }
            $validated_data['npwp_image'] = $name_file_npwp;

            $file_selfie = $request->file('selfie_image');
            if ($file_selfie) {
                $directory = 'images/customers/selfie';
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                $name_file_selfie = time() . '_' . $file_selfie->getClientOriginalName();
                $file_selfie->move(public_path($directory), $name_file_selfie);
            } else {
                $name_file_selfie = "blank";
            }
            $validated_data['selfie_image'] = $name_file_selfie;

            CustomerModel::create($validated_data);

            DB::commit();
            return redirect('/customers')->with('success', 'Customer Add Success');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            // return redirect('/customers')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show()
    {

        abort(404);
    }
    public function edit($id)
    {
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }

        $all_customer_categories = CustomerCategoriesModel::oldest('category_name')->get();
        $all_customer_areas = CustomerAreaModel::oldest('area_name')->get();
        $choosed_customer = CustomerModel::where('id', $id)->firstOrFail();

        $data = [
            'title' => 'Data Customer',
            'customer_categories' => $all_customer_categories,
            'customer_areas' => $all_customer_areas,
            'customer' => $choosed_customer

        ];
        return view('customers.edit', $data);
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

        try {
            DB::beginTransaction();
            $validated_data = $request->validate([
                'name_cust' => '',
                'id_card_number' => '',
                'phone_cust' => '',
                'office_number' => '',
                'province' => '',
                'city' => '',
                'district' => '',
                'village' => '',
                'address_cust' => '',
                'npwp' => '',
                'email_cust' => '',
                'category_cust_id' => 'numeric',
                'area_cust_id' => 'numeric',
                'credit_limit' => 'numeric',
                'label' => '',
                'isOverDue' => 'numeric',
                'isOverPlafoned' => 'numeric',
                'due_date' => 'numeric',
                'status' => 'numeric',
                'reference_image' => 'image|mimes:jpg,png,jpeg|max:2048',
                'id_card_image' => 'image|mimes:jpg,png,jpeg|max:2048',
                'npwp_image' => 'image|mimes:jpg,png,jpeg|max:2048',
                'selfie_image' => 'image|mimes:jpg,png,jpeg|max:2048',
                'coordinate_' => ''
            ]);

            $customer_current = CustomerModel::where('id', $id)->firstOrFail();
            // dd($customer_current);
            $customer_current->name_cust = $validated_data['name_cust'];
            $customer_current->phone_cust = $validated_data['phone_cust'];
            $customer_current->office_number = $validated_data['office_number'];
            $customer_current->id_card_number = $validated_data['id_card_number'];
            if ($customer_current->province != $validated_data['province']) {
                $province_name = $this->getNameProvince($validated_data['province']);
                $customer_current->province = ucwords(strtolower($province_name));
                $city_name = $this->getNameCity($validated_data['city']);
                $customer_current->city = ucwords(strtolower($city_name));
                $district_name = $this->getNameDistrict($validated_data['district']);
                $customer_current->district = ucwords(strtolower($district_name));
            }
            $customer_current->village = $validated_data['village'];
            $customer_current->address_cust = $validated_data['address_cust'];
            $customer_current->npwp = $validated_data['npwp'];
            $customer_current->email_cust = $validated_data['email_cust'];
            $customer_current->category_cust_id = $validated_data['category_cust_id'];
            $customer_current->area_cust_id = $validated_data['area_cust_id'];

            //Create Customer Code
            $area_code = CustomerAreaModel::where('id', $validated_data['area_cust_id'])->firstOrFail()->area_code;
            $new_code = $area_code . substr($customer_current->code_cust, 2);
            $customer_current->code_cust = $new_code;

            $customer_current->credit_limit = $validated_data['credit_limit'];
            $customer_current->label = $validated_data['label'];
            $customer_current->due_date = $validated_data['due_date'];
            $customer_current->coordinate = $validated_data['coordinate_'];
            $customer_current->isOverDue = $validated_data['isOverDue'];
            $customer_current->isOverPlafoned = $validated_data['isOverPlafoned'];
            $customer_current->status = $validated_data['status'];

            //process image
            $file = $request->file('reference_image');
            if ($file) {
                $name_file = time() . '_' . $file->getClientOriginalName();
                $path = public_path('images/customers/') . $customer_current->reference_image;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $file->move(public_path('images/customers'), $name_file);
                $customer_current->reference_image = $name_file;
            }

            $file = $request->file('id_card_image');
            if ($file) {
                $name_file = time() . '_' . $file->getClientOriginalName();
                $path = public_path('images/customers/ktp/') . $customer_current->id_card_image;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $file->move(public_path('images/customers/ktp/'), $name_file);
                $customer_current->id_card_image = $name_file;
            }

            $file = $request->file('npwp_image');
            if ($file) {
                $name_file = time() . '_' . $file->getClientOriginalName();
                $path = public_path('images/customers/npwp/') . $customer_current->npwp_image;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $file->move(public_path('images/customers/npwp/'), $name_file);
                $customer_current->npwp_image = $name_file;
            }

            $file = $request->file('selfie_image');
            if ($file) {
                $name_file = time() . '_' . $file->getClientOriginalName();
                $path = public_path('images/customers/selfie/') . $customer_current->selfie_image;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $file->move(public_path('images/customers/selfie/'), $name_file);
                $customer_current->selfie_image = $name_file;
            }
            $customer_current->save();

            DB::commit();
            return redirect('/customers')->with('success', 'Customer Edit Success');
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/customers')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function approval()
    {
        $unapprove_customers = CustomerModel::where('isapprove', 0)->oldest('name_cust')->get();
        $all_customer_categories = CustomerCategoriesModel::oldest('category_name')->get();
        $all_customer_areas = CustomerAreaModel::oldest('area_name')->get();

        $data = [
            "title" => 'Unapproved Customer List',
            "unapproves" => $unapprove_customers,
            'customer_categories' => $all_customer_categories,
            "customer_areas" => $all_customer_areas
        ];

        return view('customers.approval', $data);
    }

    public function approve(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $selected_customer = CustomerModel::where('id', $id)->first();
            $selected_customer->name_cust = $request->name_cust;
            $selected_customer->phone_cust = $request->phone_cust;
            $selected_customer->office_number = $request->office_number;
            $selected_customer->id_card_number = $request->id_card_number;
            if ($selected_customer->province != $request->province) {
                $province_name = $this->getNameProvince($request->province);
                $selected_customer->province = ucwords(strtolower($province_name));
                $city_name = $this->getNameCity($request->city);
                $selected_customer->city = ucwords(strtolower($city_name));
                $district_name = $this->getNameDistrict($request->district);
                $selected_customer->district = ucwords(strtolower($district_name));
            }
            $selected_customer->village = $request->village;
            $selected_customer->address_cust = $request->address_cust;
            $selected_customer->npwp = $request->npwp;
            $selected_customer->email_cust = $request->email_cust;
            $selected_customer->category_cust_id = $request->category_cust_id;
            $selected_customer->area_cust_id = $request->area_cust_id;

            //Create Customer Code
            $area_code = CustomerAreaModel::where('id', $request->area_cust_id)->firstOrFail()->area_code;
            $length = 4;
            $last_cust_area = CustomerModel::where('area_cust_id', $request->area_cust_id)->where('isapprove', 1)->latest()->first();
            if ($last_cust_area == null) {
                $id = 1;
            } else {
                $code = substr($last_cust_area->code_cust, 3);
                $id = intval($code) + 1;
            }
            $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
            // $area_code = CustomerAreaModel::where('id', $validated_data['area_cust_id'])->firstOrFail()->area_code;
            // $new_code = $area_code . substr($customer_current->code_cust, 2);
            $selected_customer->code_cust =  $area_code . $cust_number_id;


            // $area_code = CustomerAreaModel::where('id', $request->area_cust_id)->firstOrFail()->area_code;
            // $new_code = $area_code . substr($selected_customer->code_cust, 2);
            // $selected_customer->code_cust = $new_code;

            $selected_customer->credit_limit = $request->credit_limit;
            $selected_customer->label = $request->label;
            $selected_customer->due_date = $request->due_date;
            $selected_customer->coordinate = $request->coordinate_;
            $selected_customer->isapprove = 1;
            $selected_customer->status = 1;

            //process image
            $file_reference = $request->file('reference_image');
            if ($file_reference != null && $file_reference != "") {
                // dd($file_reference);
                $name_file_reference = time() . '_' . $file_reference->getClientOriginalName();
                $path_reference = public_path('images/customers/') . $selected_customer->reference_image;
                if (File::exists($path_reference)) {
                    File::delete($path_reference);
                }
                $file_reference->move(public_path('images/customers'), $name_file_reference);
                $selected_customer->reference_image = $name_file_reference;
            }

            $file_id = $request->file('id_card_image');
            if ($file_id != null && $file_id != "") {
                $name_file_id = time() . '_' . $file_id->getClientOriginalName();
                $path_id = public_path('images/customers/ktp/') . $selected_customer->id_card_image;
                if (File::exists($path_id)) {
                    File::delete($path_id);
                }
                $file_id->move(public_path('images/customers/ktp/'), $name_file_id);
                $selected_customer->id_card_image = $name_file_id;
            }

            $file_npwp = $request->file('npwp_image');
            if ($file_npwp != null && $file_npwp != "") {
                $name_file_npwp = time() . '_' . $file_npwp->getClientOriginalName();
                $path_npwp = public_path('images/customers/npwp/') . $selected_customer->npwp_image;
                if (File::exists($path_npwp)) {
                    File::delete($path_npwp);
                }
                $file_npwp->move(public_path('images/customers/npwp/'), $name_file_npwp);
                $selected_customer->npwp_image = $name_file_npwp;
            }

            $file_selfie = $request->file('selfie_image');
            if ($file_selfie != null && $file_selfie != "") {
                $name_file_selfie = time() . '_' . $file_selfie->getClientOriginalName();
                $path_selfie = public_path('images/customers/selfie/') . $selected_customer->selfie_image;
                if (File::exists($path_selfie)) {
                    File::delete($path_selfie);
                }
                $file_selfie->move(public_path('images/customers/selfie/'), $name_file_selfie);
                $selected_customer->selfie_image = $name_file_selfie;
            }

            $selected_customer->save();

            DB::commit();
            return redirect('/customer/approval')->with('success', 'Customer Approval Success!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/customer/approval')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        try {
            DB::beginTransaction();
            $customer_current = CustomerModel::where('id', $id)->firstOrFail();
            $discount_current = DiscountModel::where('customer_id', $customer_current->id)->delete();
            $path = public_path('images/customers/') . $customer_current->reference_image;
            if (File::exists($path)) {
                File::delete($path);
            }
            $customer_current->delete();

            DB::commit();
            return redirect('/customers')->with('error', 'Customer Delete Success');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/customers')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function getProvince()
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/provinces.json');
        $getProvinces = $getAPI->json();
        return response()->json($getProvinces);
    }

    public function getNameProvince($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/province/' . $id . '.json');
        $getProvinces = $getAPI->json();
        // dd($getProvinces['name']);
        return $getProvinces['name'];
        // dd($getProvinces['name']);
    }

    public function getCity($province_id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/regencies/' . $province_id . '.json');
        $getCities = $getAPI->json();
        return response()->json($getCities);
    }

    public function getNameCity($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/regency/' . $id . '.json');
        $getCities = $getAPI->json();
        return $getCities['name'];
    }

    public function getDistrict($city_id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/districts/' . $city_id . '.json');
        $getDistricts = $getAPI->json();
        return response()->json($getDistricts);
    }

    public function getNameDistrict($id)
    {
        $getAPI = Http::get('https://preposterous-cat.github.io/api-wilayah-indonesia/static/api/district/' . $id . '.json');
        $getDistricts = $getAPI->json();
        return $getDistricts['name'];
    }

    public function getTotalCredit($id)
    {
        $SODebts = SalesOrderModel::where('customers_id', $id)
            ->where('isverified', 1)
            ->where('isPaid', 0)
            ->get();

        $retailDebts = DirectSalesModel::where('cust_name', $id)->where('isPaid', 0)->where('isapproved', 1)->get();
        if ($retailDebts == null || sizeof($retailDebts) <= 0) {
            $check_cust = CustomerModel::where('id', $id)->first();
            if ($check_cust->name_cust == 'Direct Other Customer (Palembang)' || $check_cust->name_cust == 'Direct Other Customer (Jambi)') {
                if ($check_cust->areaBy->area_name == "Sumatera Selatan") {
                    $retailDebts = DirectSalesModel::where('warehouse_id', 1)->where('cust_name', 'NOT REGEXP', '^[0-9]+$')
                        ->where('isPaid', 0)->where('isapproved', 1)->get();
                } elseif ($check_cust->areaBy->area_name == 'Jambi') {
                    $retailDebts = DirectSalesModel::where('warehouse_id', 8)->where('cust_name', 'NOT REGEXP', '^[0-9]+$')
                        ->where('isPaid', 0)->where('isapproved', 1)->get();
                }
            }
        }


        $total_credit = 0;
        $total_return = 0;
        if ($SODebts) {
            foreach ($SODebts as $SODebt) {
                $total_credit = $total_credit + $SODebt->total_after_ppn;
                $selected_return = ReturnModel::where('sales_order_id', $SODebt->id)->sum('total');
                $total_return += $selected_return;
            }
        }


        $total_credit_ds = 0;
        $total_return_ds = 0;
        if ($retailDebts) {
            foreach ($retailDebts as $retail) {
                $total_credit_ds = $total_credit_ds + $retail->total_incl;
                $selected_return_ds = ReturnRetailModel::where('retail_id', $retail->id)->sum('total');
                $total_return_ds += $selected_return_ds;
            }
        }


        $final_total = ($total_credit - $total_return) + ($total_credit_ds - $total_return_ds);
        return response()->json($final_total);
    }
}
