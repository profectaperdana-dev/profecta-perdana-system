<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\AuthModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

function generateRandomString($length = 15)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = substr(str_shuffle($characters), 0, $length);
    return $randomString;
}

class AuthController extends Controller
{


    public function index()
    {
        $get_current_key = AuthModel::where('user_id', Auth::user()->id)->first();
        $data = [
            'title' => 'Generate Key for Live Chat Admin',
            'key' => $get_current_key
        ];
        // dd(date('Y-m-d H:i:s'));

        return view('cms.live_chats.index', $data);
    }

    public function generate()
    {
        $key = AuthModel::where('user_id', Auth::user()->id)->first();

        if (!$key) {
            $key = new AuthModel();
        }

        $key->user_id = Auth::user()->id;
        $key->name = Auth::user()->name;
        $key->auth_key = generateRandomString();

        // dd($key);

        $key->save();



        return response()->json([
            'status' => 200,
            'message' => 'Generate key success!',
            'data' => $key
        ]);
    }
}
