<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomePageModel extends Model
{
    use HasFactory;
    protected $connection = 'cms_mysql';
    protected $table = 'home_pages';

    public function bannerBy()
    {
        return $this->hasMany(HomePageBannerModel::class, 'banner_id', 'id');
    }

    public function benefitBy()
    {
        return $this->hasMany(HomePageBenefitModel::class, 'benefit_id', 'id');
    }

    public function reviewBy()
    {
        return $this->hasMany(HomePageReviewModel::class, 'review_id', 'id');
    }
}
