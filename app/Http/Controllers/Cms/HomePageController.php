<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cms\HomePageModel;
use App\Models\Cms\HomePageBannerModel;
use App\Models\Cms\HomePageBenefitModel;
use App\Models\Cms\HomePageReviewModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class HomePageController extends Controller
{
    public function index(Request $request)
    {
        $homepage = HomePageModel::with(['bannerBy', 'benefitBy', 'reviewBy'])->first();
        $data = [
            'title' => 'Manage HomePage Content',
            'homepage' => $homepage,
        ];

        return view('cms.homepages.index', $data);
    }

    public function store(Request $request)
    {
        $current_homepage = HomePageModel::first();

        if ($current_homepage == null) {
            $current_homepage = new HomePageModel();
        }

        $current_homepage->title = $request->title;
        $current_homepage->description = $request->description;
        $current_homepage->costumer_total = $request->total_costumer;
        $current_homepage->sales_total = $request->tota_sales;
        $current_homepage->established = $request->total_established;

        $img_input_2 = $request->img_input_2;

        $directory = 'images/cms/homepages';
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        //image preview

        if ($img_input_2 && $img_input_2 != 'undefined') {
            $name_image = time() . '_2.' . $img_input_2->extension();
            $img_input_2->move(public_path($directory), $name_image);
            $current_homepage->img = $name_image;
        }

        $current_homepage->save();

        //Banner
        $image_temp = [];
        $banner_list = HomePageBannerModel::where('banner_id', $current_homepage->id)->get();
        if ($banner_list->isNotEmpty()) {
            foreach ($banner_list as $item) {
                array_push($image_temp, $item->banner);
            }

            $banner_list = HomePageBannerModel::where('banner_id', $current_homepage->id)->delete();
        }
        $m = 0;
        foreach ($request->sobanner as $index => $item) {

            $banner_list = new HomePageBannerModel();
            $banner_list->banner_id = $current_homepage->id;
            if (isset($item['title_banner'])) {
                $banner_list->title_banner = $item['title_banner'];
            } else {
                $banner_list->title_banner = 'Default Title';
            }
            if (isset($item['caption'])) {
                $banner_list->caption = $item['caption'];
            } else {
                $banner_list->caption = 'Default Caption';
            }
            if (isset($item['img1'])) {
                $banner = $item['img1'];
                //image preview
                if ($banner && $banner != 'undefined') {
                    $name_img = time() . $m . '.' . $banner->extension();
                    $banner->move(public_path($directory), $name_img);
                    $banner_list->banner = $name_img;
                }
            } else {
                $banner_list->banner = $image_temp[$m];
            }

            $banner_list->save();
            $m++;
        } //else{
        //     Log::error("Missing 'title_banner' or 'caption' keys in sobanner array: " . json_encode($item));
        // }

        //Benefit
        $benefit = HomePageBenefitModel::where('benefit_id', $current_homepage->id)->get();
        if ($benefit) {
            $benefit = HomePageBenefitModel::where('benefit_id', $current_homepage->id)->delete();
        }
        foreach ($request->sobenefit as $item) {
            $benefit = new HomePageBenefitModel();
            $benefit->benefit_id = $current_homepage->id;
            $benefit->title_benefit = $item['title_benefit'];
            $benefit->save();
        }

        //Review
        $review = HomePageReviewModel::where('review_id', $current_homepage->id)->get();
        if ($review) {
            $review = HomePageReviewModel::where('review_id', $current_homepage->id)->delete();
        }
        foreach ($request->soreview as $item) {
            $review = new HomePageReviewModel();
            $review->review_id = $current_homepage->id;
            $review->text_review = $item['text_review'];
            $review->author = $item['author'];
            $review->save();
        }

        //return response
        return response()->json([
            'status' => 200,
            'message' => 'Adding  success!',
            'data' => $current_homepage,
        ]);
    }

    public function api_gethomepage()
    {
        $homepage = HomePageModel::with(['bannerBy', 'benefitBy', 'reviewBy'])->first();
        return response()->json([
            'status' => 200,
            'data' => $homepage,
        ]);
    }
}
