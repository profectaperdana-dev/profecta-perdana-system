<div class="page-main-header">
    <div class="main-header-right row m-0">
        <div class="main-header-left">
            <div class="logo-wrapper"><a href="index.html"><img class="img-fluid" src="{{ asset('images/logo.png') }}"
                        alt=""></a></div>
            <div class="dark-logo-wrapper"><a href="index.html"><img class="img-fluid"
                        src="{{ asset('assets') }}/images/logo/dark-logo.png" alt=""></a></div>
            <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center"
                    id="sidebar-toggle"></i></div>
        </div>
        <div class="left-menu-header col">
        </div>
        <div class="nav-right col pull-right right-menu p-0">
            <ul class="nav-menus">


                <li class="onhover-dropdown">
                    <div class="notification-box">
                        @can('isVerificator')
                            @if (count($notif) > 0)
                                <i class="fa-2x far fa-bell fa-shake"></i>
                                <span class="dot-animated"></span>
                            @else
                                <i class="bell fa-2x far fa-bell"></i>
                            @endif
                        @elsecan('isFinance')
                            @if (count($notif) > 0)
                                <i class="fa-2x far fa-bell fa-shake"></i>
                                <span class="dot-animated"></span>
                            @else
                                <i class="bell fa-2x far fa-bell"></i>
                            @endif
                        @elsecan('isSuperAdmin')
                            @if (count($notif) > 0)
                                <i class="fa-2x far fa-bell fa-shake"></i>
                                <span class="dot-animated"></span>
                            @else
                                <i class="bell fa-2x far fa-bell"></i>
                            @endif
                        @else
                            <i class="bell fa-2x far fa-bell"></i>
                        @endcan
                    </div>
                    <ul class="notification-dropdown onhover-show-div">
                        <li>
                            <p class="f-w-700 mb-0">You have
                                Notifications<span class="notifCount pull-right badge badge-primary badge-pill">
                                    {{ count($notif) }}
                                </span></p>
                        </li>
                        <div class="notifContainer">
                            @foreach ($notif as $notifData)
                                <li class="notif-primary">
                                    <div class="media"><span class="notification-bg bg-light-primary"><i
                                                class="fa fa-envelope"> </i></span>
                                        <div class="media-body">
                                            <a href="{{ url('read_notif/' . $notifData->id) }}">
                                                <p>{{ $notifData->message }}</p><span>10 minutes ago</span>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </div>
                    </ul>
                </li>
                <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i
                            data-feather="maximize"></i></a></li>

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
