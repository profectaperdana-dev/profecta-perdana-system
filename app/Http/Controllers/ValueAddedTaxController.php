<?php

namespace App\Http\Controllers;

use App\Models\ValueAddedTaxModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ValueAddedTaxController extends Controller
{
    public function index()
    {
        $all_taxes = ValueAddedTaxModel::all();
        $data = [
            'title' => 'Master Value-added Tax (PPN)',
            'taxes' => $all_taxes
        ];

        return view('value_added_taxes.index', $data);
    }

    public function update(Request $request, $id)
    {
        if (!Gate::allows('level1') && !Gate::allows('level2')) {
            abort(403);
        }
        $validated_data = $request->validate([
            'ppn' => 'required',
        ]);

        $current_user = ValueAddedTaxModel::where('id', $id)->first();
        $current_user->ppn = $validated_data['ppn'];
        $current_user->save();

        return redirect('/value_added_tax')->with('success', 'Value-added Tax Change Success!');
    }
}
