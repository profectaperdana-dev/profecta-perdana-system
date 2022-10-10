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
            {{ Auth::user()->roleBy->name }} <br> {{ Auth::user()->jobBy->job_name }} at

            {{ Auth::user()->warehouseBy->warehouses }}</p>

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
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Master Data</h6>
                        </div>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('product_materials') ||
                        request()->is('products') ||
                        request()->is('second_product') ||
                        request()->is('stocks') ||
                        request()->is('product_sub_materials') ||
                        request()->is('product_sub_types') ||
                        request()->is('product_uoms')) active @endif"
                            href="javascript:void(0)"><i data-feather="box"></i><span>Master Products</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('product_materials') ||
                                request()->is('products') ||
                                request()->is('second_product') ||
                                request()->is('stocks') ||
                                request()->is('product_sub_materials') ||
                                request()->is('product_sub_types') ||
                                request()->is('product_uoms')) block @else none @endif ">
                            <li>
                                <a href="{{ url('/product_uoms') }}"
                                    class="{{ request()->is('product_uoms') ? 'active' : '' }}">Products Uoms</a>
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
                                <a href="{{ url('/products') }}"
                                    class="{{ request()->is('products') ? 'active' : '' }}">Products</a>
                            </li>
                            <li>
                                <a href="{{ url('/stocks') }}"
                                    class="{{ request()->is('stocks') ? 'active' : '' }}">Products
                                    Stocks</a>
                            </li>
                            <li>
                                <a href="{{ url('/second_product') }}"
                                    class="{{ request()->is('second_product') ? 'active' : '' }}">Second Products</a>
                            </li>


                        </ul>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('customers') || request()->is('customer_categories') || request()->is('customer_areas')) active @endif"
                            href="javascript:void(0)"><i data-feather="user-check"></i><span>Master Customers</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('customers') || request()->is('customer_categories') || request()->is('customer_areas')) block @else none @endif ">
                            <li><a href="{{ url('/customer_categories') }}"
                                    class="{{ request()->is('customer_categories') ? 'active' : '' }}">Customer
                                    Categories</a></li>
                            <li><a href="{{ url('/customer_areas') }}"
                                    class="{{ request()->is('customer_areas') ? 'active' : '' }}">Customer Areas</a>
                            </li>
                            <li><a href="{{ url('/customers') }}"
                                    class="{{ request()->is('customers') ? 'active' : '' }}">Customers</a></li>
                        </ul>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('users') || request()->is('roles') || request()->is('jobs')) active @endif"
                            href="javascript:void(0)"><i data-feather="users"></i><span>Master Accounts</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('users') || request()->is('roles') || request()->is('jobs')) block @else none @endif ">
                            <li><a href="{{ url('/roles') }}"
                                    class="{{ request()->is('roles') ? 'active' : '' }}">Accounts Role</a>
                            </li>
                            <li><a href="{{ url('/jobs') }}"
                                    class="{{ request()->is('jobs') ? 'active' : '' }}">Accounts Job</a>
                            </li>
                            <li><a href="{{ url('/users') }}"
                                    class="{{ request()->is('users') ? 'active' : '' }}">Accounts</a></li>

                        </ul>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('discounts') ? 'active' : '' }}"
                            href="{{ url('/discounts') }}"><i data-feather="percent"></i><span>Master
                                Discount</span></a>
                    </li>

                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('warehouse_types') || request()->is('warehouses')) active @endif"
                            href="javascript:void(0)"><i data-feather="server"></i><span>Master Warehouse</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('warehouse_types') || request()->is('warehouses')) block @else none @endif ">
                            <li><a href="{{ url('/warehouse_types') }}"
                                    class="{{ request()->is('warehouse_types') ? 'active' : '' }}">Type Warehouse</a>
                            </li>
                            <li><a href="{{ url('/warehouses') }}"
                                    class="{{ request()->is('warehouses') ? 'active' : '' }}">Warehouses</a>
                            </li>


                        </ul>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('supliers') ? 'active' : '' }}"
                            href="{{ url('/supliers') }}"><i data-feather="battery"></i><span>Master
                                Suppliers</span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Information</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('check_stock') ? 'active' : '' }}"
                            href="{{ url('/check_stock') }}"><i data-feather="inbox"></i><span>Check
                                Stock
                            </span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Transaction Purchase</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('purchase_orders/create') ? 'active' : '' }}"
                            href="{{ url('/purchase_orders/create') }}"><i data-feather="shopping-bag"></i><span>Create
                                Purchase Order
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('purchase_orders') ? 'active' : '' }}"
                            href="{{ url('/purchase_orders') }}"><i data-feather="bookmark"></i><span>PO Need Approve
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('all_purchase_orders') ? 'active' : '' }}"
                            href="{{ url('/all_purchase_orders') }}"><i data-feather="folder"></i></i><span>All
                                Purchase Order
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('purchase_orders/receiving') ? 'active' : '' }}"
                            href="{{ url('/purchase_orders/receiving') }}"><i
                                data-feather="folder-plus"></i><span>Receiving PO
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('return_purchase') ? 'active' : '' }}"
                            href="{{ url('/return_purchase') }}"><i data-feather="rotate-cw"></i><span>Return
                                Purchases
                            </span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Transaction Sales</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('sales_order') ? 'active' : '' }}"
                            href="{{ url('/sales_order') }}"><i data-feather="shopping-cart"></i><span>Create Sales
                                Order
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('recent_sales_order') ? 'active' : '' }}"
                            href="{{ url('/recent_sales_order') }}"><i data-feather="star"></i><span>SO Need Verify
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('need_approval') ? 'active' : '' }}"
                            href="{{ url('/need_approval') }}"><i data-feather="toggle-right"></i><span>SO
                                Need Approve
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('invoice/manage_payment') ? 'active' : '' }}"
                            href="{{ url('/invoice/manage_payment') }}"><i
                                data-feather="credit-card"></i><span>Manage
                                Payments
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('invoice') ? 'active' : '' }}"
                            href="{{ url('/invoice') }}"><i data-feather="folder"></i></i><span>All Invoice
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('return') ? 'active' : '' }}"
                            href="{{ url('/return') }}"><i data-feather="rotate-ccw"></i></i><span>Return Invoice
                            </span></a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Stock Mutations</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('stock_mutation/create') ? 'active' : '' }}"
                            href="{{ url('/stock_mutation/create') }}"><i data-feather="edit"></i><span>Create Stock
                                Mutation
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('stock_mutation') ? 'active' : '' }}"
                            href="{{ url('/stock_mutation') }}"><i data-feather="clipboard"></i><span>Stock
                                Mutations List
                            </span>
                        </a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Claims & Informations</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('claim/create') ? 'active' : '' }}"
                            href="{{ url('/claim/create') }}"><i data-feather="edit"></i><span>Create Claim
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('claim') ? 'active' : '' }}"
                            href="{{ url('/claim') }}"><i data-feather="clipboard"></i><span>Claim List
                            </span></a>
                    </li>


                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('history_claim') ? 'active' : '' }}"
                            href="{{ url('/history_claim') }}"><i data-feather="folder"></i><span>History Claims

                            </span></a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Analysis Report & Files</h6>
                        </div>
                    </li>
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('report_sales') ||
                                request()->is('report_purchase') ||
                                request()->is('report_claim') ||
                                request()->is('report_return_invoice') ||
                                request()->is('report_return_purchases')) active @endif"
                            href="javascript:void(0)"><i data-feather="book-open"></i><span>Report</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('report_sales') || request()->is('report_purchase') || request()->is('report_claim')) block @else none @endif ">
                            <li><a href="{{ url('/report_sales') }}"
                                    class="{{ request()->is('report_sales') ? 'active' : '' }}">Report Invoice</a>
                            </li>
                            <li><a href="{{ url('/report_purchase') }}"
                                    class="{{ request()->is('report_purchase') ? 'active' : '' }}">Report Purchase
                                </a></li>
                            <li><a href="{{ url('/report_claim') }}"
                                    class="{{ request()->is('report_claim') ? 'active' : '' }}">Report Claim</a>
                            </li>
                            <li><a href="{{ url('/report_return_invoice') }}"
                                    class="{{ request()->is('report_return_invoice') ? 'active' : '' }}">Report Return
                                    Invoice</a>
                            </li>
                            <li><a href="{{ url('/report_return_purchases') }}"
                                    class="{{ request()->is('report_return_purchases') ? 'active' : '' }}">Report
                                    Return
                                    Purchases</a>
                            </li>
                        </ul>

                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('analytics') ? 'active' : '' }}"
                            href="{{ url('/analytics') }}"><i data-feather="activity"></i></i><span>Analysis
                            </span></a>
                    </li>
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('file_invoice') || request()->is('file_do') || request()->is('file_po')) active @endif"
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
                            <li><a href="{{ url('/file_po') }}"
                                    class="{{ request()->is('file_po') ? 'active' : '' }}">File
                                    Purchase
                                    Order</a>
                            </li>

                        </ul>

                    </li>

                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
