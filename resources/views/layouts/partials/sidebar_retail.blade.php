<header class="main-nav">

    <div class="sidebar-user text-center"><a class="setting-primary" href="{{ url('/profiles') }}"><i
                data-feather="settings"></i></a>

        {{-- ! informasi profile --}}
        @if (Auth::user()->employeeBy->photo == 'blank')
            <img class="img-90 rounded-circle" src="{{ asset('images/blank.png') }}" alt="">
        @else
            <img class="img-90 rounded-circle" src="{{ asset('images/employees/' . Auth::user()->employeeBy->photo) }}"
                alt=""
                style="width:100%;height:90px;object-fit:cover;object-position: 50% 50%;image-rendering:smooth;filter:blur(0.4px)">
        @endif
        <div class="badge-bottom"></div><a href="user-profile.html">
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6>
        </a>
        <p class="mb-0 font-roboto text-capitalize">
            {{ Auth::user()->roleBy->name }} <br> {{ Auth::user()->jobBy->job_name }} at

            {{ Auth::user()->warehouseBy->warehouses }}</p>
        {{-- ! end informasi profile --}}

    </div>

    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul>
                    <li class="ms-3">
                        <form class="form-inline search-form">
                            <div class="search-bg">
                                <input class="form-control-plaintext" placeholder="Search here....." id="searchLink">
                            </div>
                        </form>
                    </li>
                </ul>

                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i>
                        </div>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Starter </h6>
                        </div>
                    </li>

                    {{-- ! dashboard --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('home') ? 'active' : '' }}"
                            href="{{ url('/home') }}"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>
                    {{-- ! end dashboard --}}

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Manage Data</h6>
                        </div>
                    </li>
                    {{-- ! end master supplier --}}

                    {{-- ! master kendaraan --}}
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('') || request()->is('motorcycle') || request()->is('motorcycle_type')) active @endif"
                            href="javascript:void(0)"><i data-feather="airplay"></i><span>Manage Vehicle</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('cars') ||
                                request()->is('cars_type') ||
                                request()->is('motorcycle') ||
                                request()->is('motorcycle_type')) block @else none @endif ">
                            <li><a href="{{ url('cars/') }}" class="{{ request()->is('cars') ? 'active' : '' }}">Car
                                    Brand</a>
                            </li>
                            <li><a href="{{ url('cars_type/') }}"
                                    class="{{ request()->is('cars_type') ? 'active' : '' }}">Car
                                    Type</a>
                            </li>
                            <li><a href="{{ url('/motorcycle') }}"
                                    class="{{ request()->is('motorcycle') ? 'active' : '' }}">Motorcycle Brand</a>
                            </li>
                            <li><a href="{{ url('/motorcycle_type') }}"
                                    class="{{ request()->is('motorcycle_type') ? 'active' : '' }}">Motorcycle Type</a>
                            </li>
                        </ul>
                    </li>
                    {{-- ! end master kendaraan --}}


                    <li class="sidebar-main-title">
                        <div>
                            <h6>Information Stock</h6>
                        </div>
                    </li>

                    {{-- ! informasi stok --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('check_stock') ? 'active' : '' }}"
                            href="{{ url('/check_stock') }}"><i data-feather="inbox"></i><span>Check
                                Stock Now
                            </span></a>
                    </li>
                    {{-- ! end informasi stok --}}


                    <li class="sidebar-main-title">
                        <div>
                            <h6>Sales Seconds Product</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('retail_second_products/create') ? 'active' : '' }}"
                            href="{{ url('/retail_second_products/create') }}"><i data-feather="edit"></i><span>Create
                                Order
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('retail_second_products') ? 'active' : '' }}"
                            href="{{ url('/retail_second_products') }}"><i data-feather="folder"></i><span>Invoicing
                            </span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Sales Retail</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('retail/create') ? 'active' : '' }}"
                            href="{{ url('/retail/create') }}"><i data-feather="edit"></i><span>Create Order
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('retail') ? 'active' : '' }}"
                            href="{{ url('/retail') }}"><i data-feather="folder"></i><span>Invoicing
                            </span></a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Transaction Trade-In</h6>
                        </div>
                    </li>

                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('create/trade_in') ? 'active' : '' }}"
                            href="{{ url('/create/trade_in') }}"><i data-feather="edit"></i><span>Create
                                Trade-In
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('trade_invoice') ? 'active' : '' }}"
                            href="{{ url('/trade_invoice') }}"><i data-feather="folder"></i><span>Trade-In
                                Invoicing
                            </span>
                        </a>
                    </li>


                    <li class="sidebar-main-title">
                        <div>
                            <h6>Analysis Report & Files</h6>
                        </div>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('report_sales') ||
                        request()->is('report_purchase') ||
                        request()->is('report_trade_in') ||
                        request()->is('report_claim') ||
                        request()->is('report_return_invoice') ||
                        request()->is('report_return_purchases')) active @endif"
                            href="javascript:void(0)"><i data-feather="book-open"></i><span>Report</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('report_sales') ||
                                request()->is('report_trade_in') ||
                                request()->is('report_purchase') ||
                                request()->is('report_claim')) block @else none @endif ">
                            <li><a href="{{ url('/report_sales') }}"
                                    class="{{ request()->is('report_sales') ? 'active' : '' }}">Report Invoice</a>
                            </li>

                            <li><a href="{{ url('/report_return_invoice') }}"
                                    class="{{ request()->is('report_return_invoice') ? 'active' : '' }}">Report
                                    Return
                                    Invoice</a>
                            </li>

                            <li><a href="{{ url('/report_trade_in') }}"
                                    class="{{ request()->is('report_trade_in') ? 'active' : '' }}">Report
                                    Trade-In
                                </a>
                            </li>
                        </ul>

                    </li>

                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('file_invoice') || request()->is('file_do') || request()->is('file_po')) active @endif"
                            href="javascript:void(0)"><i data-feather="archive"></i><span>Files Arsip</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('file_invoice') || request()->is('file_do') || request()->is('file_po')) block @else none @endif ">
                            <li><a href="{{ url('/file_invoice') }}"
                                    class="{{ request()->is('file_invoice') ? 'active' : '' }}">File Invoice</a></li>
                            <li><a href="{{ url('/file_do') }}"
                                    class="{{ request()->is('file_do') ? 'active' : '' }}">File
                                    Delivery
                                    Order
                                </a></li>

                        </ul>

                    </li>

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
