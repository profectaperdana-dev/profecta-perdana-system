<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\Cms\AreaModel;
use App\Models\Cms\ContactModel;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $all_areas = AreaModel::oldest('name')->get();

        $data = [
            'title' => "Manage Contact Content",
            'all_areas' => $all_areas
        ];
        return view('cms.contacts.index', $data);
    }

    public function get_data_by_area()
    {
        $contact = ContactModel::where('area_id', request()->area_id)->first();

        return response()->json([
            'status' => 200,
            'data' => $contact
        ]);
    }

    public function store(Request $request)
    {
        $current_contact = ContactModel::where('area_id', $request->area_id)->first();

        if ($current_contact == null) {
            $current_contact = new ContactModel();
        }

        $current_contact->area_id = $request->area_id;
        $current_contact->phone_1 = $request->phone_1;
        $current_contact->phone_2 = $request->phone_2;
        $current_contact->email = $request->email;
        $current_contact->address = $request->address;
        $current_contact->embedded_maps = $request->embedded_maps;
        $current_contact->shopee_url = $request->shopee_url;
        $current_contact->tokopedia_url = $request->tokopedia_url;
        $current_contact->instagram_url = $request->instagram_url;
        $current_contact->facebook_url = $request->facebook_url;
        $current_contact->tiktok_url = $request->tiktok_url;
        $current_contact->save();

        return response()->json([
            'status' => 200,
            'message' => 'Adding contact success!',
            'data' => $current_contact
        ]);
    }

    public function api_getcontact()
    {
        $contact = ContactModel::where('area_id', 1)->first();
        return response()->json([
            'status' => 200,
            'data' => $contact
        ]);
    }

    public function api_filterbyarea($id)
    {
        $contact = ContactModel::where('area_id', $id)->first();
        return response()->json([
            'status' => 200,
            'data' => $contact
        ]);
    }

    public function api_areaandphone()
    {
        $contact = ContactModel::with(['areaBy'])->select('area_id', 'phone_1', 'phone_2')->get()->sortBy(function ($item) {
            return $item->areaBy->name;
        });
        return response()->json([
            'status' => 200,
            'data' => $contact->values()
        ]);
    }
}
