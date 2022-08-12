<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Profile ";
        $id =  Auth::user()->id;
        $data = User::where('users.id',  $id)
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->join('warehouses', 'users.warehouse_id', '=', 'warehouses.id')
            ->select('users.*', 'roles.name AS role_name', 'warehouses.warehouses AS warehouse_name')
            ->first();
        // dd(Auth::user()->id);
        return view('profiles.index', compact('data', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePhoto(Request $request, $id)
    {
        $request->validate([
            'photo_profile' => 'required',

        ]);
        $model = User::find($id);

        if ($model->photo_profile == null) {
            $file = $request->photo_profile;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("foto_profile/", $nama_file);
            $model->photo_profile = $nama_file;
            $model->save();
            return redirect('profiles')->with('info', 'Changes Photo Profile is Success');
        } else {
            unlink('foto_profile/' . $request->get('url_lama'));
            $file = $request->photo_profile;
            $nama_file = time() . '.' . $file->getClientOriginalExtension();
            $file->move("foto_profile/", $nama_file);
            $model->photo_profile = $nama_file;
            $model->save();
            return redirect('profiles')->with('info', 'Changes Photo Profile is Success');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
