<?php

namespace App\Providers;

use App\Models\AssetModel;
use App\Models\AuthorizationModel;
use App\Models\NotificationsModel;
use App\Models\UserAuthorizationModel;
use App\Models\VacationModel;
use App\View\Composers\NotificationComposer as ComposersNotificationComposer;
use Carbon\Carbon;
use App\Views\Composers\NotificationComposer;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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


        // ! set number format to rupiah
        Blade::directive('currency', function ($expression) {
            return "Rp. <?php echo number_format($expression,0,',','.'); ?>";
        });

        // ! Notification / verifikasi / approval / etc
        view()->composer('*', function ($view) {

            // ! cek auth
            if (Auth::user() != null) {
                $auth = AuthorizationModel::all();
                $user_auth = UserAuthorizationModel::with('authBy')->where('user_id', Auth::user()->id)->whereHas('authBy', function ($query) {
                    $query->where('url', request()->path());
                })->count();

                if ($auth->contains('url', request()->path())) {
                    if ($user_auth <= 0) {
                        return redirect()->back()->send();
                    }
                }
            }

            // ! update status maintenance asset
            $asset = AssetModel::all();
            foreach ($asset as $row) {

                $now = date('Y-m-d');
                $earlier = new DateTime($now);
                $later = new DateTime($row->next_service);
                $abs_diff = $later->diff($earlier)->format('%a'); //selisih tanggal
                // 1 =  belum selesai service
                // 0 =  selesai service
                if ($row->next_service > $now) { //jika tanggal service lebih besar dari tanggal sekarang
                    if ($abs_diff < 8) { //jika selisih tanggal kurang dari 8 hari
                        $row->status = 1;
                    }
                }
                $row->save();
            }
            // ! data notif
            $cek_user_verificator = UserAuthorizationModel::with('userBy')->where('auth_id', 43)->where('user_id', @auth()->user()->id)->first();
            $cek_user_approve_so = UserAuthorizationModel::with('userBy')->where('auth_id', 44)->where('user_id', @auth()->user()->id)->first();
            $cek_user_approve_po = UserAuthorizationModel::with('userBy')->where('auth_id', 39)->where('user_id', @auth()->user()->id)->first();
            $cek_user_approve_leave = UserAuthorizationModel::with('userBy')->where('auth_id', 1006)->where('user_id', @auth()->user()->id)->first();
            
            
            $cek_approve_receive_return_direct = UserAuthorizationModel::with('userBy')->where('auth_id', 1071)->where('user_id', @auth()->user()->id)->first();
            $cek_approve_receive_return_indirect = UserAuthorizationModel::with('userBy')->where('auth_id', 1070)->where('user_id', @auth()->user()->id)->first();
            $cek_approve_receive_return_ = UserAuthorizationModel::with('userBy')->where('auth_id', 1068)->where('user_id', @auth()->user()->id)->first();
            $cek_approve_receive_return = UserAuthorizationModel::with('userBy')->where('auth_id', 1069)->where('user_id', @auth()->user()->id)->first();




            // dd($cek_user_approve_po);
            // dd($cek_user_verificator);
            $notif = NotificationsModel::where('status', '0')
                ->whereIn('job_id', [@$cek_approve_receive_return_direct->auth_id,@$cek_approve_receive_return->auth_id,@$cek_approve_receive_return_indirect->auth_id,@$cek_approve_receive_return_->auth_id,@$cek_approve_receive_return->auth_id, @$cek_user_verificator->auth_id, @$cek_user_approve_so->auth_id, @$cek_user_approve_po->auth_id])
                ->latest()
                ->get();
                // dd($notif);

            // ! cek verifikasi
            $verificate_so = UserAuthorizationModel::with('userBy')->where('auth_id', 43)->where('user_id', @auth()->user()->id)->count();

            // ! cek approve sales order
            $approve_so = UserAuthorizationModel::with('userBy')->where('auth_id', 44)->where('user_id', @auth()->user()->id)->count();

            // ! cek approve purchase order
            $approve_po = UserAuthorizationModel::with('userBy')->where('auth_id', 39)->where('user_id', @auth()->user()->id)->count();
            $approve_leave = UserAuthorizationModel::with('userBy')->where('auth_id', 1006)->where('user_id', @auth()->user()->id)->count();
                        $receiver_return = UserAuthorizationModel::with('userBy')->where('auth_id', 1070)->where('user_id', @auth()->user()->id)->count();

            // dd($approve_po);
            // ! parse data ke view
            $data_app_service = [
                'notif' => $notif,
                'verificate_so' => $verificate_so,
                'approve_so' => $approve_so,
                'approve_po' => $approve_po,
                'approve_leave' => $approve_leave,
                'cek_user_verificator' => $cek_user_verificator->auth_id ?? 0,
                'cek_user_approve_so' => $cek_user_approve_so->auth_id ?? 0,
                'cek_user_approve_po' => $cek_user_approve_po->auth_id ?? 0,
                'cek_user_approve_leave' => $cek_user_approve_leave->auth_id ?? 0,
                'receiver_return' => $receiver_return->auth_id ?? 0,
                
                'cek_approve_receive_return_direct' => $cek_approve_receive_return_direct->auth_id ?? 0,
                'cek_approve_receive_return_indirect' => $cek_approve_receive_return_indirect->auth_id ?? 0,
                'cek_approve_receive_return_' => $cek_approve_receive_return_->auth_id ?? 0,
                'cek_approve_receive_return' => $cek_approve_receive_return->auth_id ?? 0,

            ];
            View::share($data_app_service);
        });
    }
}
