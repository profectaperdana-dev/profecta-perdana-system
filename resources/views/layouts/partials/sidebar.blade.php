<header class="main-nav">

    <div class="sidebar-user text-center pb-1"><a class="setting-primary" href="{{ url('/profiles') }}"><i
                data-feather="settings"></i></a>

        {{-- ! informasi profile --}}
        @if (Auth::user()->employeeBy->photo == 'blank')
            <img class="img-90 rounded-circle" src="{{ asset('images/blank.png') }}" alt="">
        @else
            <img class="img-90 rounded-circle"
                src="{{ url('public/images/employees/' . Auth::user()->employeeBy->photo) }}" alt=""
                style="width:100%;height:90px;object-fit:cover;object-position: 50% 50%;image-rendering:smooth;filter:blur(0.4px)">
        @endif
        <div class="badge-bottom"></div><a href="{{ url('/profiles') }}">
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6>
        </a>
        <p class="mb-0 font-roboto text-capitalize">
            {{ Auth::user()->roleBy->name }} <br><br></p>
        {{-- ! end informasi profile --}}

    </div>

    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                {{-- <ul class="text-center">
                    <li>
                        <form class="form-inline search-form">
                            <div class="search-bg">
                                <input class="form-control-plaintext" placeholder="Search here....." id="searchLink">
                            </div>
                        </form>
                    </li>
                </ul> --}}

                <ul class="nav-menu custom-scrollbar pt-4">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i>
                        </div>
                    </li>
                    {{-- ! dashboard --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('home') ? 'active' : '' }}"
                            href="{{ url('/home') }}"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>
                    {{-- ! end dashboard --}}

                    @foreach (Auth::user()->getMasterSection() as $ms)
                        {{-- Master Section --}}

                        <li class="sidebar-main-title">
                            <div>
                                <h6>{{ $ms }}</h6>
                            </div>
                        </li>

                        @php
                            $getSection = Auth::user()->getSection();
                            if ($ms == 'Master') {
                                $getSection = Auth::user()
                                    ->getSection()
                                    ->sortBy('section');
                            }
                            
                        @endphp

                        {{-- End Master Section --}}

                        {{-- Section  --}}
                        @foreach ($getSection as $sc)
                            @if ($sc->master_section == $ms)
                                @php
                                    // $authBy = Auth::user()->userAuthBy->sortBy('order_sort');
                                    $authBy = Auth::user()->userAuthBy->sortBy(function ($item) {
                                        return $item->authBy->order_sort;
                                    });
                                @endphp
                                <li class="dropdown"><a
                                        class="nav-link menu-title
                                        @foreach ($authBy as $item)
                                            @if ($sc->section == $item->authBy->section)
                                                @if (request()->is($item->authBy->url)) active @else @continue @endif
                                            @endif @endforeach"
                                        href="javascript:void(0)"><span>{{ $sc->section }}</span></a>
                                    <ul class="nav-submenu menu-content"
                                        style="display:@foreach ($authBy as $item)
                                            @if ($sc->section == $item->authBy->section)
                                                @if (request()->is($item->authBy->url)) block @endif
                                            @else @continue
                                            @endif @endforeach">
                                        @foreach ($authBy as $item)
                                            @if ($sc->section == $item->authBy->section)
                                                <li>
                                                    <a href="{{ url('/' . $item->authBy->url) }}"
                                                        class="{{ request()->is($item->authBy->url) ? 'active' : '' }}">
                                                        {{ $item->authBy->menu_name }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    @endforeach
                    {{-- End Section --}}


                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i>
            </div>
        </div>
    </nav>
</header>
