<header class="main-nav">
    <div class="sidebar-user text-center"><a class="setting-primary" href="{{ url('/profiles') }}"><i
                data-feather="settings"></i></a>
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
            {{ Auth::user()->warehouseBy->warehouses }}
        </p>

    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
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
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('home') ? 'active' : '' }}"
                            href="{{ url('/home') }}"><i data-feather="home"></i><span>Dashboard</span></a>
                    </li>
                    {{-- <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('customers/create') ? 'active' : '' }}"
                            href="{{ url('/customers/create') }}"><i data-feather="user-check"></i><span>Create
                                Customers
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('customers') ? 'active' : '' }}"
                            href="{{ url('/customers') }}"><i data-feather="user-check"></i><span>All Customers
                            </span></a>
                    </li> --}}
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Claims & Informations</h6>
                        </div>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('claim/create') || request()->is('claim_tyre/create')) active @endif"
                            href="javascript:void(0)"><i data-feather="edit"></i><span>Create Claim</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('claim/create') || request()->is('claim_tyre/create')) block @else none @endif ">
                            <li><a href="{{ url('/claim/create') }}"
                                    class="{{ request()->is('claim/create') ? 'active' : '' }}">Accu Claim</a>
                            </li>
                            <li><a href="{{ url('/claim_tyre/create') }}"
                                    class="{{ request()->is('claim_tyre/create') ? 'active' : '' }}">Tyre
                                    Claim</a>
                            </li>


                        </ul>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('claim') || request()->is('claim_tyre')) active @endif"
                            href="javascript:void(0)"><i data-feather="clipboard"></i><span>Early Checking
                                List</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('claim') || request()->is('claim_tyre')) block @else none @endif ">
                            <li><a href="{{ url('/claim') }}"
                                    class="{{ request()->is('claim') ? 'active' : '' }}">Early Checking Accu</a>
                            </li>
                            <li><a href="{{ url('/claim_tyre') }}"
                                    class="{{ request()->is('claim_tyre') ? 'active' : '' }}">
                                    Early Checking Tyre</a>
                            </li>
                        </ul>
                    </li>
                    {{-- <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('claim') ? 'active' : '' }}"
                            href="{{ url('/claim') }}"><i data-feather="clipboard"></i><span>Early Checking List
                            </span></a>
                    </li> --}}
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('history_claim') || request()->is('')) active @endif"
                            href="javascript:void(0)"><i data-feather="folder"></i><span>Claim Completed</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('history_claim') || request()->is('')) block @else none @endif ">
                            <li><a href="{{ url('/history_claim') }}"
                                    class="{{ request()->is('history_claim') ? 'active' : '' }}">Completed Accu
                                    Claim</a>
                            </li>
                            <li><a href="{{ url('/') }}" class="{{ request()->is('') ? 'active' : '' }}">
                                    Completed Tyre Claim</a>
                            </li>
                        </ul>
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

                            <li><a href="{{ url('/report_claim') }}"
                                    class="{{ request()->is('report_claim') ? 'active' : '' }}">Report Claim</a>
                            </li>

                        </ul>

                    </li>

                    {{-- <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('history_claim') ? 'active' : '' }}"
                            href="{{ url('/history_claim') }}"><i data-feather="folder"></i><span>History Claims

                            </span></a>
                    </li> --}}

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
