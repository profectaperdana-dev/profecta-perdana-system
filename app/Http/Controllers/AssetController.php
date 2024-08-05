<?php

namespace App\Http\Controllers;

use App\Models\AssetCategoryModel;
use App\Models\AssetModel;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $asset = AssetModel::with('createdBy', 'categoryBy')
                ->oldest('asset_name')
                ->get();

            return datatables()->of($asset)
                ->editColumn('qr', function ($data) {
                    return QrCode::size(100)->generate(url('asset/information/' . $data->id));
                })
                ->editColumn('created_by', function ($data) {
                    return $data->createdBy->name;
                })

                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($asset) {
                    return view('assets._option', compact('asset'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $all_assets = AssetModel::with('createdBy')->latest()->get();
        $data = [
            'title' => 'Master Asset',
            'assets' => $all_assets
        ];

        return view('assets.index', $data);
    }


    public function information($id)
    {

        $data = AssetModel::where('id', $id)->first();
        // $data = [
        //     'assets' => $data
        // ];
        // dd($data);
        return view('assets.asset_info', compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $all_categories = AssetCategoryModel::oldest('name')->get();
        $data = [
            'title' => 'Create Asset',
            'categories' => $all_categories
        ];
        return view('assets.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd(request()->all());
        //* validate
        $request->validate([
            "asset_category" => "required",
            "asset_name" => "required",
            "amount" => "required|numeric",
            "lifetime" => "required|numeric",
            // "acquisition_year" => "required",
            "acquisition_cost" => "required|numeric",
            // "range" => "required",
            // "service_date" => "required",
            // "next_service" => "required"
        ]);

        try {
            DB::beginTransaction();

            $model = new AssetModel();

            //Create Code
            $selected_category = AssetCategoryModel::where('id', $request->asset_category)->first();
            $code = $selected_category->code;
            $count_asset = AssetModel::where('asset_code', 'LIKE', "%$code%")->max('id');
            $model->asset_code = $code . ($count_asset + 1);
            $model->range = $request->range;


            $model->service_date = $request->service_date;
            if ($model->service_date != '') {
                $next = explode('/', $request->next_service);
                $next_service = $next[2] . '-' . $next[1] . '-' . $next[0];
                // dd($next_service);
                $model->next_service = $next_service;
                $now = date('Y-m-d');
                $earlier = new DateTime($now);
                $later = new DateTime($model->next_service);
                $abs_diff = $later->diff($earlier)->format('%a'); //3
                if ($model->next_service > $now) {
                    if ($abs_diff > 7) {
                        $model->status = 'Maintenance Is Complete';
                    } else {
                        $model->status = 'Un Maintenance';
                    }
                }
            }



            $model->category_id = $request->asset_category;
            $model->asset_name = $request->asset_name;
            $model->amount = $request->amount;
            $model->lifetime = $request->lifetime;
            $model->acquisition_year = $request->acquisition_year;
            $model->acquisition_cost = $request->acquisition_cost;
            $model->created_by = Auth::user()->id;

            // $image = QrCode::format('png')
            //     ->merge(public_path('images/1644463030.png'), 0.5, true)
            //     ->size(500)
            //     ->errorCorrection('H')
            //     ->generate('A simple example of QR code!');
            // $model->qr_code = $output_file;
            $saved = $model->save();

            if ($saved) {

                DB::commit();
                return redirect('/asset')->with('success', 'Add Asset Success!');
            } else {

                DB::rollback();
                return redirect('/asset')->with('error', 'Add Asset Fail!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/asset')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        abort(404);
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
        //* validate
        $request->validate([
            "asset_name" => "required",
            "amount" => "required|numeric",
            "lifetime" => "required|numeric",
            "acquisition_year" => "required",
            "acquisition_cost" => "required|numeric"
        ]);

        try {
            DB::beginTransaction();
            $model = AssetModel::where('id', $id)->first();
            $model->asset_name = $request->asset_name;
            $model->amount = $request->amount;
            $model->lifetime = $request->lifetime;
            $model->acquisition_year = $request->acquisition_year;
            $model->acquisition_cost = $request->acquisition_cost;
            $saved = $model->save();

            if ($saved) {

                DB::commit();
                return redirect('/asset')->with('success', 'Update Asset Success!');
            } else {

                DB::rollback();
                return redirect('/asset')->with('error', 'Update Asset Fail!');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/asset')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
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
        try {
            DB::beginTransaction();
            $data = AssetModel::find($id);
            $saved = $data->delete();
            if ($saved) {

                DB::commit();
                return redirect('/asset')->with('success', 'Data has been deleted');
            } else {

                DB::rollback();
                return redirect('/asset')->with('error', 'Data failed to delete');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return redirect('/asset')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }
}
