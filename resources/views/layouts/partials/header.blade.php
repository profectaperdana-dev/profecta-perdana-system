<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left text-center">
            <div class="logo-wrapper text-center">
                <a href="javascript:void(0)"><img class="rounded float-start" src="{{ asset('images/logos.png') }}"
                        alt="" style="border:none;width:93px;height:39px;"></a>
            </div>
            <div class="dark-logo-wrapper"><a href="index.html"><img class="javascript:void(0)"
                        src="{{ asset('images/logos.png') }}" alt=""
                        style="border:none;width:93px;height:39px;"></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                    id="sidebar-toggle"></i>
            </div>
        </div>
        <div class="left-menu-header col">

        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">
                <!--<li class="onhover-dropdown">-->
                <!--    <script type="text/javascript">-->
                <!--        function date_time(id) {-->
                <!--            date = new Date;-->
                <!--            year = date.getFullYear();-->
                <!--            month = date.getMonth();-->
                            <!--// months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep',-->
                            <!--//     'Oct', 'Nov', 'Des');-->
                <!--            d = date.getDate();-->
                <!--            day = date.getDay();-->
                            <!--// days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');-->
                <!--            h = date.getHours();-->
                <!--            if (h < 10) {-->
                <!--                h = "0" + h;-->
                <!--            }-->
                <!--            m = date.getMinutes();-->
                <!--            if (m < 10) {-->
                <!--                m = "0" + m;-->
                <!--            }-->
                <!--            s = date.getSeconds();-->
                <!--            if (s < 10) {-->
                <!--                s = "0" + s;-->
                <!--            }-->
                <!--            result = '' + h + ':' + m + ':' + s;-->
                <!--            document.getElementById(id).innerHTML = result;-->
                <!--            setTimeout('date_time("' + id + '");', '1000');-->
                <!--            return true;-->
                <!--        }-->
                <!--    </script>-->

                <!--    <span class="btn btn-light btn-sm" id="date_time"></span>-->
                <!--    <script type="text/javascript">-->
                <!--        window.onload = date_time('date_time');-->
                <!--    </script>-->
                <!--</li>-->
                <li data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample"
                    class="onhover-dropdown">
                    <i class="bell fa-2x fa fa-bell  {{ count($notif) > 0 ? 'text-danger fa-shake' : 'far' }}"> </i>
                </li>
                <li><a id="fullscreen-button" class="text-dark" href="#!"
                        onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>

                <li class="mode"><i data-feather="moon"></i></li>
                <li class="onhover-dropdown p-0">
                    <button class="btn btn-primary-light" type="button"><a href="{{ url('/logout') }}"><i
                                data-feather="log-out"></i>Log out</a></button>
                </li>
            </ul>
        </div>
        <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
    </div>
</div>
<div class="offcanvas  offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"
    data-bs-backdrop="false">
    <div class="offcanvas-header">
        <h6 class="offcanvas-title" id="offcanvasRightLabel">Notifications
        </h6>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-group">
            @php
                $param = [];
                if ($cek_user_verificator > 0) {
                    $param[] = $cek_user_verificator;
                }
                if ($cek_user_approve_so > 0) {
                    $param[] = $cek_user_approve_so;
                }
                
                if ($cek_user_approve_po > 0) {
                    $param[] = $cek_user_approve_po;
                }
                if ($cek_user_approve_leave > 0) {
                    $param[] = $cek_user_approve_leave;
                }
                 if ($cek_approve_receive_return_direct > 0) {
                    $param[] = $cek_approve_receive_return_direct;
                }
                
                if ($cek_approve_receive_return_indirect > 0) {
                    $param[] = $cek_approve_receive_return_indirect;
                }
                 if ($cek_approve_receive_return_ > 0) {
                    $param[] = $cek_approve_receive_return_;
                }
                 if ($cek_approve_receive_return > 0) {
                    $param[] = $cek_approve_receive_return;
                } 
                $array_param = implode(',', $param);
                $url = url('read_all_notif/' . $array_param);
            @endphp
            <input type="hidden" value="{{ $url }}" class="url">
            <li class="no-notif text-end mb-3 {{ count($notif) > 0 ? '' : 'd-none' }}"><a
                    class="btn no-clear rounded btn-sm btn-info btn-notif">Clear Notifications</a>

            </li>
            <li
                class="no-notif list-group-item d-flex justify-content-between align-items-start {{ count($notif) > 0 ? 'd-none' : '' }}">
                <div class="no-notif ms-2 me-auto {{ count($notif) > 0 ? 'd-none' : '' }}">
                    You don't have any notifications.
                </div>
            </li>
            @foreach ($notif as $notifData)
                <li class="notifEach list-group-item d-flex justify-content-between align-items-start mb-2">
                    <div class="ms-2 me-auto">
                        {{ $notifData->message }}.
                    </div>
                    <span class="badge bg-primary rounded-pill">
                        @php
                            $diff = date_diff(new DateTime(), new DateTime($notifData->created_at));
                            $formats = ['%s seconds ago', '%i minutes ago', '%h hours ago', '%a days ago', '%m months ago', '%y years ago'];
                            $index = $diff->format('%s') > 0 && $diff->format('%i') <= 0 ? 0 : ($diff->format('%i') > 0 && $diff->format('%h') <= 0 ? 1 : ($diff->format('%h') > 0 && $diff->format('%a') <= 0 ? 2 : ($diff->format('%a') > 0 && $diff->format('%m') <= 0 ? 3 : ($diff->format('%m') > 0 && $diff->format('%y') <= 0 ? 4 : 5))));
                        @endphp

                        {{ $diff->format($formats[$index]) }}

                    </span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
