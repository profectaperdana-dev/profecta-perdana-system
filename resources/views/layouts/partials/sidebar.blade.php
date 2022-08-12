<header class="main-nav">
    <div class="sidebar-user text-center"><a class="setting-primary" href="{{ url('/profiles') }}"><i
                data-feather="settings"></i></a>
        @if (Auth::user()->photo_profile == null)
            <img class="img-90 rounded-circle" src="{{ asset('images/blank.png') }}" alt="">
        @else
            <img class="img-90 rounded-circle" src="{{ asset('foto_profile/' . Auth::user()->photo_profile) }}"
                alt="">
        @endif
        <div class="badge-bottom"></div><a href="user-profile.html">
            <h6 class="mt-3 f-14 f-w-600">{{ Auth::user()->name }}</h6>
        </a>
        <p class="mb-0 font-roboto text-capitalize">
            {{ Auth::user()->roleBy->name }} | {{ Auth::user()->warehouseBy->warehouses }}</p>

    </div>
    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar">
                    <li class="back-btn">
                        <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2"
                                aria-hidden="true"></i></div>
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
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Master Data </h6>
                        </div>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('product_materials') ||
                        request()->is('products') ||
                        request()->is('product_sub_materials') ||
                        request()->is('product_sub_types') ||
                        request()->is('product_uoms')) active @endif"
                            href="javascript:void(0)"><i data-feather="box"></i><span>Master Products</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('product_materials') ||
                                request()->is('products') ||
                                request()->is('product_sub_materials') ||
                                request()->is('product_sub_types') ||
                                request()->is('product_uoms')) block @else none @endif ">
                            <li>
                                <a href="{{ url('/products') }}"
                                    class="{{ request()->is('products') ? 'active' : '' }}">Products</a>
                            </li>
                            <li>
                                <a href="{{ url('/stocks') }}"
                                    class="{{ request()->is('stocks') ? 'active' : '' }}">Products Stocks</a>
                            </li>
                            <li>
                                <a href="{{ url('/product_materials') }}"
                                    class="{{ request()->is('product_materials') ? 'active' : '' }}">Products
                                    Materials</a>
                            </li>
                            <li>
                                <a href="{{ url('/product_sub_materials') }}"
                                    class="{{ request()->is('product_sub_materials') ? 'active' : '' }}">Products Sub
                                    Materials</a>
                            </li>
                            <li>
                                <a href="{{ url('/product_sub_types') }}"
                                    class="{{ request()->is('product_sub_types') ? 'active' : '' }}">Products Type
                                    Materials</a>
                            </li>
                            <li>
                                <a href="{{ url('/product_uoms') }}"
                                    class="{{ request()->is('product_uoms') ? 'active' : '' }}">Products Uoms</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('customers') || request()->is('customer_categories') || request()->is('customer_areas')) active @endif"
                            href="javascript:void(0)"><i data-feather="user-check"></i><span>Master Customers</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('customers') || request()->is('customer_categories') || request()->is('customer_areas')) block @else none @endif ">
                            <li><a href="{{ url('/customers') }}"
                                    class="{{ request()->is('customers') ? 'active' : '' }}">Customers</a></li>
                            <li><a href="{{ url('/customer_categories') }}"
                                    class="{{ request()->is('customer_categories') ? 'active' : '' }}">Customer
                                    Categories</a></li>
                            <li><a href="{{ url('/customer_areas') }}"
                                    class="{{ request()->is('customer_areas') ? 'active' : '' }}">Customer Areas</a>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('users') || request()->is('roles')) active @endif"
                            href="javascript:void(0)"><i data-feather="users"></i><span>Master Accounts</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('users') || request()->is('roles')) block @else none @endif ">
                            <li><a href="{{ url('/users') }}"
                                    class="{{ request()->is('users') ? 'active' : '' }}">Accounts</a></li>
                            <li><a href="{{ url('/roles') }}"
                                    class="{{ request()->is('roles') ? 'active' : '' }}">Accounts Role</a></li>

                        </ul>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('home') ? 'active' : '' }}"
                            href="{{ url('/home') }}"><i data-feather="percent"></i><span>Master Diskon</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('warehouses') ? 'active' : '' }}"
                            href="{{ url('/warehouses') }}"><i data-feather="server"></i><span>Master
                                Warehouses</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('supliers') ? 'active' : '' }}"
                            href="{{ url('/supliers') }}"><i data-feather="battery"></i><span>Master
                                Suppliers</span></a>
                    </li>

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
