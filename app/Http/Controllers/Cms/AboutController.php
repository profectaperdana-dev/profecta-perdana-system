<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cms\AboutModel;
use App\Models\Cms\AboutJourneyModel;
use App\Models\Cms\AboutProvideModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Mockery\Undefined;

class AboutController extends Controller
{
    public function index(Request $request)
    {
        $about = AboutModel::with(['provideBy', 'journeyBy'])->first();
        $data = [
            'title' => 'Manage About Content',
            'about' => $about,
        ];

        return view('cms.abouts.index', $data);
    }

    public function store(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'img1' => 'mimes:jpg,png,jpeg|max:2048',
        //     'img2' => 'mimes:jpg,png,jpeg|max:2048',
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 422,
        //         'message' => $validator->errors(),
        //     ]);
        // }

        // dd($request->all());
        try {
            DB::beginTransaction();
            $current_about = AboutModel::first();

            if ($current_about == null) {
                $current_about = new AboutModel();
            }

            $current_about->header_about = $request->title;
            $current_about->description_about = $request->description;
            $current_about->header_history = $request->title_history;
            $current_about->description_history = $request->description_history;

            $img1 = $request->img1;
            $img2 = $request->img2;

            $directory = 'images/cms/abouts';
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            //image preview
            if ($img1 && $img1 != 'undefined') {
                $name_img1 = time() . '_1.' . $img1->extension();
                $img1->move(public_path($directory), $name_img1);
                $current_about->image_1 = $name_img1;
            }

            if ($img2 && $img2 != 'undefined') {
                $name_img2 = time() . '_2.' . $img2->extension();
                $img2->move(public_path($directory), $name_img2);
                $current_about->image_2 = $name_img2;
            }

            $current_about->save();

            $provide_list = AboutProvideModel::where('provides_id', $current_about->id)->get();
            $idn = 0;
            $imageList = $provide_list->pluck('image')->toArray();

            foreach ($request->soprovided as $item) {
                $new_provide = new AboutProvideModel();
                $new_provide->provides_id = $current_about->id;
                $new_provide->title = $item['title'];

                $image = $request->file('img_provide' . $idn); // Menggunakan file() untuk mendapatkan file yang diunggah.

                $directory = 'images/cms/abouts';
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }

                if ($image && $image != 'undefined') {
                    // dd('masuk');
                    $name_image = time() . $idn . '_3.' . $image->extension();
                    $image->move(public_path($directory), $name_image);
                    $new_provide->image = $name_image;
                } else {
                    $new_provide->image = $imageList[$idn];
                }

                $new_provide->save();
                $idn++;
            }

            if ($provide_list) {
                foreach ($provide_list as $value) {
                    $value->delete();
                }
            }

            $journey = AboutJourneyModel::where('journeys_id', $current_about->id)->get();
            if ($journey) {
                $journey = AboutJourneyModel::where('journeys_id', $current_about->id)->delete();
            }
            foreach ($request->soFields as $item) {
                $journey = new AboutJourneyModel();
                $journey->journeys_id = $current_about->id;
                $journey->year = $item['year'];
                $journey->title = $item['title'];
                $journey->description = $item['description'];
                $journey->save();
            }

            DB::commit();
            //return response
            return response()->json([
                'status' => 200,
                'message' => 'Adding  success!',
                'data' => $current_about,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            return redirect('/retail')->with('error', $e->getMessage() . '. Please call your Most Valuable IT Team.');
        }
    }

    public function api_getabout()
    {
        $about = AboutModel::with(['provideBy', 'journeyBy'])->first();
        return response()->json([
            'status' => 200,
            'data' => $about,
        ]);
    }
}
