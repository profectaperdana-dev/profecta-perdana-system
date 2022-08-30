<?php

namespace App\Providers;

use App\Models\NotificationsModel;
use App\View\Composers\NotificationComposer as ComposersNotificationComposer;
use Carbon\Carbon;
use App\Views\Composers\NotificationComposer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });

        //notification

        view()->composer('*', function ($view) {
            $notif = NotificationsModel::where('status', '0')
                ->where('role_id', @auth()->user()->role_id)
                ->latest()
                ->limit(4)
                ->get();

            View::share('notif', $notif);
        });

        // DB::connection()

    }
}
