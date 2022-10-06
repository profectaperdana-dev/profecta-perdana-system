<?php

namespace App\Http\Controllers;

use App\Models\StockMutationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StockMutationController extends Controller
{
    public function index(Request $request)
    {
        if (
            !Gate::allows('isSuperAdmin') && !Gate::allows('isWarehouseKeeper')
        ) {
            abort(403);
        }

        // get kode area
        // dd($request->all());
        if ($request->ajax()) {
            if (!empty($request->from_date)) {
                $mutation = StockMutationModel::with('stockMutationDetailBy', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->whereBetween('mutation_date', array($request->from_date, $request->to_date))
                    ->latest()
                    ->get();
            } else {
                $mutation = StockMutationModel::with('stockMutationDetailBy', 'fromWarehouse', 'toWarehouse', 'createdBy')
                    ->latest()
                    ->get();
            }
            return datatables()->of($mutation)
                ->editColumn('mutation_date', function ($data) {
                    return date('d-M-Y', strtotime($data->mutation_date));
                })
                ->editColumn('from', function (StockMutationModel $stockMutationModel) {
                    return $stockMutationModel->fromWarehouse->warehouses;
                })
                ->editColumn('to', function (StockMutationModel $stockMutationModel) {
                    return $stockMutationModel->toWarehouse->warehouses;
                })
                ->editColumn('created_by', function (StockMutationModel $stockMutationModel) {
                    return $stockMutationModel->createdBy->name;
                })
                ->addIndexColumn() //memberikan penomoran
                ->addColumn('action', function ($mutation) {
                    return view('stock_mutations._option', compact('mutation'))->render();
                })
                ->rawColumns(['action'])
                // ->rawColumns()
                ->addIndexColumn()
                ->make(true);
        }

        $data = [
            'title' => 'All Stock Mutations in Profecta Perdana'
        ];
        return view('stock_mutations.index', $data);
    }
}
