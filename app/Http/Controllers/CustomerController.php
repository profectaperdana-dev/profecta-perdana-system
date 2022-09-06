<?php

namespace App\Http\Controllers;

use App\Models\CustomerAreaModel;
use App\Models\CustomerCategoriesModel;
use App\Models\CustomerModel;
use App\Models\SalesOrderModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $all_customer = CustomerModel::leftJoin('customer_categories', 'customers.category_cust_id', '=', 'customer_categories.id')
            ->leftJoin('customer_areas', 'customers.area_cust_id', '=', 'customer_areas.id')
            ->select('customers.*', 'customer_areas.area_name', 'customer_categories.category_name')
            ->latest()
            ->get();

        $data = [
            "title" => 'Data Customers',
            "customers" => $all_customer
        ];

        checkOverDue();

        return view('customers.index', $data);
    }

    public function create(Request $request)
    {
        $all_customer_categories = CustomerCategoriesModel::all();
        $all_customer_areas = CustomerAreaModel::all();
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
            'credit_limit' => 'required|numeric',
            'label' => 'required',
            'due_date' => 'required|numeric',
            'coordinate' => 'required',
            'status' => 'required|numeric',
            'reference_image' => 'image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'coordinate.required' => 'Generate Location Button must be clicked'
        ]);

        //create create by
        $validated_data['created_by'] = Auth::user()->id;

        //Create Customer Code
        $area_code = CustomerAreaModel::where('id', $validated_data['area_cust_id'])->firstOrFail()->area_code;
        $length = 4;
        $id = intval(CustomerModel::where('code_cust', 'LIKE', "%$area_code%")->max('id')) + 1;
        $cust_number_id = str_pad($id, $length, '0', STR_PAD_LEFT);
        $validated_data['code_cust'] = $area_code . $cust_number_id;

        //Get Province, City, District, Village
        $province_name = $this->getNameProvince($validated_data['province']);
        $validated_data['province'] = ucwords(strtolower($province_name));
        $city_name = $this->getNameCity($validated_data['city']);
        $validated_data['city'] = ucwords(strtolower($city_name));
        $district_name = $this->getNameDistrict($validated_data['district']);
        $validated_data['district'] = ucwords(strtolower($district_name));

        //Process Image
        $file = $request->file('reference_image');
        if ($file) {
            $name_file = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/customers'), $name_file);
        } else {
            $name_file = "blank";
        }
        $validated_data['reference_image'] = $name_file;
        CustomerModel::create($validated_data);

        return redirect('/customers')->with('success', 'Customer Add Success');
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
    public function edit(CustomerModel $customer)
    {
        if (!Gate::allows('level1') && !Gate::allows('level2') || !Gate::allows('superadmin')) {
            abort(403);
        }
        $all_customer_categories = CustomerCategoriesModel::all();
        $all_customer_areas = CustomerAreaModel::all();
        $choosed_customer = CustomerModel::where('code_cust', $customer->code_cust)->firstOrFail();

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
    public function update(Request $request, CustomerModel $customer)
    {
        if (!Gate::allows('level1') && !Gate::allows('level2') || !Gate::allows('superadmin')) {
            abort(403);
        }
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
            'credit_limit' => 'required|numeric',
            'label' => 'required',
            'isOverDue' => 'required|numeric',
            'isOverPlafoned' => 'required|numeric',
            'due_date' => 'required|numeric',
            'status' => 'required|numeric',
            'reference_image' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $customer_current = CustomerModel::where('code_cust', $customer->code_cust)->firstOrFail();
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
        $customer_current->credit_limit = $validated_data['credit_limit'];
        $customer_current->label = $validated_data['label'];
        $customer_current->due_date = $validated_data['due_date'];
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
        $customer_current->save();

        return redirect('/customers')->with('success', 'Customer Edit Success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerModel $customer)
    {
        if (!Gate::allows('level1') || !Gate::allows('superadmin')) {
            abort(403);
        }
        $customer_current = CustomerModel::where('code_cust', $customer->code_cust)->firstOrFail();
        $path = public_path('images/customers/') . $customer_current->reference_image;
        if (File::exists($path)) {
            File::delete($path);
        }
        $customer_current->delete();
        return redirect('/customers')->with('error', 'Customer Delete Success');
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
            ->where('payment_method', 3)
            ->where('isPaid', 0)
            ->get();

        $total_credit = 0;
        foreach ($SODebts as $SODebt) {
            $total_credit = $total_credit + $SODebt->total_after_ppn;
        }
        return response()->json($total_credit);
    }
}
