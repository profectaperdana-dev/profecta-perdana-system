<header class="main-nav">

    <div class="sidebar-user text-center pb-1"><a class="setting-primary" href="{{ url('/profiles') }}"><i
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
                <ul class="text-center">
                    <li>
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
                            <h6>Master Data</h6>
                        </div>
                    </li>

                    {{-- ! master product   --}}
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('product_materials') ||
                        request()->is('products') ||
                        request()->is('trade_in') ||
                        request()->is('product_sub_materials') ||
                        request()->is('product_sub_types') ||
                        request()->is('product_uoms')) active @endif"
                            href="javascript:void(0)"><i data-feather="box"></i><span>Master Product</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('product_materials') ||
                                request()->is('products') ||
                                request()->is('product_sub_materials') ||
                                request()->is('product_sub_types') ||
                                request()->is('trade_in') ||
                                request()->is('product_uoms')) block @else none @endif ">
                            <li>
                                <a href="{{ url('/product_uoms') }}"
                                    class="{{ request()->is('product_uoms') ? 'active' : '' }}">Product Uom</a>
                            </li>
                            <li>
                                <a href="{{ url('/product_materials') }}"
                                    class="{{ request()->is('product_materials') ? 'active' : '' }}">Product
                                    Material</a>
                            </li>
                            <li>
                                <a href="{{ url('/product_sub_materials') }}"
                                    class="{{ request()->is('product_sub_materials') ? 'active' : '' }}">Product Sub
                                    Material</a>
                            </li>
                            <li>
                                <a href="{{ url('/product_sub_types') }}"
                                    class="{{ request()->is('product_sub_types') ? 'active' : '' }}">Product Type
                                    Material</a>
                            </li>
                            <li>
                                <a href="{{ url('/products') }}"
                                    class="{{ request()->is('products') ? 'active' : '' }}">Create Product</a>
                            </li>
                            <li>
                                <a href="{{ url('/trade_in') }}"
                                    class="{{ request()->is('trade_in') ? 'active' : '' }}">Product Trade In</a>
                            </li>

                        </ul>
                    </li>
                    {{-- ! end master product --}}

                    {{-- ! master PPN --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('value_added_tax') ? 'active' : '' }}"
                            href="{{ url('/value_added_tax') }}"><i data-feather="paperclip"></i><span>Master
                                PPN</span></a>
                    </li>
                    {{-- ! end master PPN --}}

                    {{-- ! master stock --}}
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('stocks') ||
                        request()->is('second_product') ||
                        request()->is('stock_c01') ||
                        request()->is('stock_c02') ||
                        request()->is('stock_c03') ||
                        request()->is('stock_ss01') ||
                        request()->is('stock_supplier')) active @endif"
                            href="javascript:void(0)"><i data-feather="server"></i><span>Master Stock
                            </span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('stocks') ||
                                request()->is('second_product') ||
                                request()->is('stock_c01') ||
                                request()->is('stock_c02') ||
                                request()->is('stock_c03') ||
                                request()->is('stock_ss01') ||
                                request()->is('stock_supplier')) block @else none @endif ">
                            <li>
                                <a href="{{ url('/stocks') }}"
                                    class="{{ request()->is('stocks') ? 'active' : '' }}">Stock Profecta Perdana
                                </a>
                            </li>
                            <li><a href="{{ url('/second_product') }}"
                                    class="{{ request()->is('second_product') ? 'active' : '' }}">Stock Second Product
                                </a>
                            </li>
                            <li><a href="{{ url('/stock_c01') }}"
                                    class="{{ request()->is('stock_c01') ? 'active' : '' }}">Stock C-01
                                </a>
                            </li>
                            <li><a href="{{ url('/stock_c02') }}"
                                    class="{{ request()->is('stock_c02') ? 'active' : '' }}">Stock C-02
                                </a>
                            </li>
                            <li><a href="{{ url('/stock_c03') }}"
                                    class="{{ request()->is('stock_c03') ? 'active' : '' }}">Stock C-03
                                </a>
                            </li>
                            <li><a href="{{ url('/stock_ss01') }}"
                                    class="{{ request()->is('stock_ss01') ? 'active' : '' }}">Stock SS-01
                                </a>
                            </li>
                            <li><a href="{{ url('/stock_supplier') }}"
                                    class="{{ request()->is('stock_supplier') ? 'active' : '' }}">Stock Supplier
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- ! end master stock --}}

                    {{-- ! master areas --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('customer_areas') ? 'active' : '' }}"
                            href="{{ url('/customer_areas') }}"><i data-feather="map"></i><span>Master
                                Area</span></a>
                    </li>
                    {{-- ! end master areas --}}

                    {{-- ! master warehouse --}}
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('warehouse_types') || request()->is('warehouses')) active @endif"
                            href="javascript:void(0)"><i data-feather="archive"></i><span>Master Warehouse</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('warehouse_types') || request()->is('warehouses')) block @else none @endif ">
                            <li><a href="{{ url('/warehouse_types') }}"
                                    class="{{ request()->is('warehouse_types') ? 'active' : '' }}">Warehouse Type</a>
                            </li>
                            <li><a href="{{ url('/warehouses') }}"
                                    class="{{ request()->is('warehouses') ? 'active' : '' }}">Create Warehouse</a>
                            </li>
                        </ul>
                    </li>
                    {{-- ! end master warehouse --}}

                    {{-- ! master customer --}}
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('customers') || request()->is('customer_categories')) active @endif"
                            href="javascript:void(0)"><i data-feather="user-check"></i><span>Master Customer</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('customers') || request()->is('customer_categories')) block @else none @endif ">
                            <li><a href="{{ url('/customer_categories') }}"
                                    class="{{ request()->is('customer_categories') ? 'active' : '' }}">Customer
                                    Category</a></li>
                            <li><a href="{{ url('/customers') }}"
                                    class="{{ request()->is('customers') ? 'active' : '' }}">Create Customer</a></li>
                        </ul>
                    </li>
                    {{-- ! end master customer --}}

                    {{-- ! master discount --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('discounts') ? 'active' : '' }}"
                            href="{{ url('/discounts') }}"><i data-feather="percent"></i><span>Master
                                Discount</span></a>
                    </li>
                    {{-- ! end master discount --}}

                    {{-- ! master account --}}
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('users') || request()->is('roles') || request()->is('jobs')) active @endif"
                            href="javascript:void(0)"><i data-feather="users"></i><span>Master Account</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('users') || request()->is('roles') || request()->is('jobs')) block @else none @endif ">
                            <!--<li><a href="{{ url('/roles') }}"-->
                            <!--        class="{{ request()->is('roles') ? 'active' : '' }}">Account Role</a>-->
                            <!--</li>-->
                            <li><a href="{{ url('/jobs') }}"
                                    class="{{ request()->is('jobs') ? 'active' : '' }}">Account Job</a>
                            </li>
                            <li><a href="{{ url('/users') }}"
                                    class="{{ request()->is('users') ? 'active' : '' }}">Create Account</a></li>
                        </ul>
                    </li>
                    {{-- ! end master account --}}

                    {{-- ! master supplier --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('supliers') ? 'active' : '' }}"
                            href="{{ url('/supliers') }}"><i data-feather="battery"></i><span>Master
                                Vendor</span></a>
                    </li>
                    {{-- ! end master supplier --}}

                    {{-- ! master kendaraan --}}
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('') || request()->is('motorcycle') || request()->is('motorcycle_type')) active @endif"
                            href="javascript:void(0)"><i data-feather="airplay"></i><span>Master Vehicle</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('cars') ||
                                request()->is('cars_type') ||
                                request()->is('motorcycle') ||
                                request()->is('motorcycle_type')) block @else none @endif ">
                            <li><a href="{{ url('cars/') }}"
                                    class="{{ request()->is('cars') ? 'active' : '' }}">Create Car
                                    Brand</a>
                            </li>
                            <li><a href="{{ url('cars_type/') }}"
                                    class="{{ request()->is('cars_type') ? 'active' : '' }}">Create Car
                                    Type</a>
                            </li>
                            <li><a href="{{ url('/motorcycle') }}"
                                    class="{{ request()->is('motorcycle') ? 'active' : '' }}">Create Motorcycle
                                    Brand</a>
                            </li>
                            <li><a href="{{ url('/motorcycle_type') }}"
                                    class="{{ request()->is('motorcycle_type') ? 'active' : '' }}">Create Motorcycle
                                    Type</a>
                            </li>
                        </ul>
                    </li>
                    {{-- ! end master kendaraan --}}

                    {{-- ! master accounting --}}
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('account_sub_type') || request()->is('account') || request()->is('account_sub')) active @endif"
                            href="javascript:void(0)"><i data-feather="book-open"></i><span>Master
                                Accounting</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('account_sub_type') || request()->is('account') || request()->is('account_sub')) block @else none @endif ">
                            <li><a href="{{ url('/account') }}"
                                    class="{{ request()->is('account') ? 'active' : '' }}">Create Account</a>
                            </li>
                            <li><a href="{{ url('/account_sub') }}"
                                    class="{{ request()->is('account_sub') ? 'active' : '' }}">Account Sub</a>
                            </li>
                            <li><a href="{{ url('/account_sub_type') }}"
                                    class="{{ request()->is('account_sub_type') ? 'active' : '' }}">Account Sub
                                    Type</a></li>
                        </ul>
                    </li>
                    {{-- ! end master accounting --}}

                    {{-- ! master Asset --}}
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('asset') || request()->is('asset_category')) active @endif"
                            href="javascript:void(0)"><i data-feather="scissors"></i><span>Master
                                Asset</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('asset') || request()->is('asset_category')) block @else none @endif ">
                            <li><a href="{{ url('/asset_category') }}"
                                    class="{{ request()->is('asset_category') ? 'active' : '' }}">Asset Category</a>
                            </li>
                            <li><a href="{{ url('/asset') }}"
                                    class="{{ request()->is('asset') ? 'active' : '' }}">Create Asset</a>
                            </li>
                        </ul>
                    </li>
                    {{-- ! end master Asset --}}

                    {{-- ! master employee --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('employee') ? 'active' : '' }}"
                            href="{{ url('/employee') }}"><i data-feather="eye"></i><span>Master
                                Employee</span></a>
                    </li>
                    {{-- ! end master employee --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('authorization') ? 'active' : '' }}"
                            href="{{ url('/authorization') }}"><i data-feather="paperclip"></i><span>Master
                                Authorization</span></a>
                    </li>

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
                            <h6>Report Accounting</h6>
                        </div>
                    </li>

                    {{-- !  accounting --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('expenses/create') ? 'active' : '' }}"
                            href="{{ url('/expenses/create') }}"><i data-feather="upload-cloud"></i><span>Input
                                Expenses
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('jurnal') ? 'active' : '' }}"
                            href="{{ url('/jurnal') }}"><i data-feather="calendar"></i><span>Journal
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('profit_loss') ? 'active' : '' }}"
                            href="{{ url('/profit_loss') }}"><i data-feather="dollar-sign"></i><span>Profit & Loss
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('depreciation') ? 'active' : '' }}"
                            href="{{ url('/depreciation') }}"><i data-feather="trending-down"></i><span>
                                Depreciation
                            </span></a>
                    </li>
                    {{-- ! end accounting --}}

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Candidate Employee</h6>
                        </div>
                    </li>

                    {{-- ! candidate employee --}}
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('prospective_employees') ? 'active' : '' }}"
                            href="{{ url('/prospective_employees/') }}"><i data-feather="cast"></i><span>Form
                                Candidate
                            </span></a>
                    </li>
                    {{-- ! end candidate employee --}}

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Second Product Sales</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('retail_second_products/create') ? 'active' : '' }}"
                            href="{{ url('/retail_second_products/create') }}"><i
                                data-feather="edit"></i><span>Create Order
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('retail_second_products') ? 'active' : '' }}"
                            href="{{ url('/retail_second_products') }}"><i data-feather="folder"></i><span>Invoicing
                            </span></a>
                    </li>
                    <li class="sidebar-main-title">
                        <div>
                            <h6>Retail Sales</h6>
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
                            <h6>Trade In Transaction</h6>
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
                            <h6>Purchase Transaction</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('purchase_orders/create') ? 'active' : '' }}"
                            href="{{ url('/purchase_orders/create') }}"><i
                                data-feather="shopping-bag"></i><span>Create
                                Purchase Order
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('purchase_orders') ? 'active' : '' }}"
                            href="{{ url('/purchase_orders') }}"><i data-feather="bookmark"></i><span>PO Need
                                Approve
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
                                data-feather="folder-plus"></i><span>Product Receival
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('purchase_orders/manage_payment') ? 'active' : '' }}"
                            href="{{ url('/purchase_orders/manage_payment') }}"><i
                                data-feather="credit-card"></i><span>Manage
                                Payment
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('return_purchase') ? 'active' : '' }}"
                            href="{{ url('/return_purchase') }}"><i data-feather="rotate-cw"></i><span>Return
                                Purchase
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
                                Payment
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('invoice') ? 'active' : '' }}"
                            href="{{ url('/invoice') }}"><i data-feather="folder"></i></i><span>Invoicing
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('return') ? 'active' : '' }}"
                            href="{{ url('/return') }}"><i data-feather="rotate-ccw"></i></i><span>Return Invoice
                            </span></a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Stock Mutation</h6>
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
                        <a class="nav-link menu-title link-nav {{ request()->is('stock_mutation/approval') ? 'active' : '' }}"
                            href="{{ url('/stock_mutation/approval') }}"><i data-feather="edit"></i><span>Approve
                                Stock
                                Mutation
                            </span>
                        </a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('stock_mutation') ? 'active' : '' }}"
                            href="{{ url('/stock_mutation') }}"><i data-feather="clipboard"></i><span>Stock
                                Mutation List
                            </span>
                        </a>
                    </li>

                    <li class="sidebar-main-title">
                        <div>
                            <h6>Claims & Informations</h6>
                        </div>
                    </li>
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('claim/create') || request()->is('claim_tyre/create')) active @endif"
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
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('claim') || request()->is('claim_tyre')) active @endif"
                            href="javascript:void(0)"><i data-feather="clipboard"></i><span>Prior Checking
                                List</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('claim') || request()->is('claim_tyre')) block @else none @endif ">
                            <li><a href="{{ url('/claim') }}"
                                    class="{{ request()->is('claim') ? 'active' : '' }}">Prior Checking Accu</a>
                            </li>
                            <li><a href="{{ url('/claim_tyre') }}"
                                    class="{{ request()->is('claim_tyre') ? 'active' : '' }}">
                                    Prior Checking Tyre</a>
                            </li>
                        </ul>
                    </li>
                    {{-- <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('claim') ? 'active' : '' }}"
                            href="{{ url('/claim') }}"><i data-feather="clipboard"></i><span>Early Checking List
                            </span></a>
                    </li> --}}
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('history_claim') || request()->is('')) active @endif"
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
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('report_sales') ||
                                request()->is('report_retail') ||
                                request()->is('report_employee') ||
                                request()->is('report_stock') ||
                                request()->is('report_purchase') ||
                                request()->is('report_trade_in') ||
                                request()->is('report_claim') ||
                                request()->is('report_receivable') ||
                                request()->is('report_debt') ||
                                request()->is('report_vendor') ||
                                request()->is('report_return_invoice') ||
                                request()->is('report_return_purchases')) active @endif"
                            href="javascript:void(0)"><i data-feather="book-open"></i><span>Report</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('report_sales') ||
                                request()->is('report_retail') ||
                                request()->is('report_employee') ||
                                request()->is('report_stock') ||
                                request()->is('report_trade_in') ||
                                request()->is('report_purchase') ||
                                request()->is('report_receivable') ||
                                request()->is('report_debt') ||
                                request()->is('report_vendor') ||
                                request()->is('report_claim')) block @else none @endif ">
                            <li><a href="{{ url('/report_stock') }}"
                                    class="{{ request()->is('report_stock') ? 'active' : '' }}">Report Stock</a>
                            </li>
                            <li><a href="{{ url('/report_sales') }}"
                                    class="{{ request()->is('report_sales') ? 'active' : '' }}">Report Invoice
                                    Non-Retail</a>
                            </li>
                            <li><a href="{{ url('/report_retail') }}"
                                    class="{{ request()->is('report_retail') ? 'active' : '' }}">Report Invoice
                                    Retail</a>
                            </li>
                            <li><a href="{{ url('/report_purchase') }}"
                                    class="{{ request()->is('report_purchase') ? 'active' : '' }}">Report Purchase
                                </a></li>
                            <li><a href="{{ url('/report_receivable') }}"
                                    class="{{ request()->is('report_receivable') ? 'active' : '' }}">Report
                                    Receivable</a>
                            </li>
                            <li><a href="{{ url('/report_debt') }}"
                                    class="{{ request()->is('report_debt') ? 'active' : '' }}">Report
                                    Debt</a>
                            </li>
                            <li><a href="{{ url('/report_claim') }}"
                                    class="{{ request()->is('report_claim') ? 'active' : '' }}">Report Claim</a>
                            </li>
                            <li><a href="{{ url('/report_mutation') }}"
                                    class="{{ request()->is('report_mutation') ? 'active' : '' }}">Report
                                    Mutation</a>
                            </li>
                            <li><a href="{{ url('/report_return_invoice') }}"
                                    class="{{ request()->is('report_return_invoice') ? 'active' : '' }}">Report
                                    Return
                                    Invoice</a>
                            </li>
                            <li><a href="{{ url('/report_return_purchases') }}"
                                    class="{{ request()->is('report_return_purchases') ? 'active' : '' }}">Report
                                    Return
                                    Purchases</a>
                            </li>
                            <li><a href="{{ url('/report_trade_in') }}"
                                    class="{{ request()->is('report_trade_in') ? 'active' : '' }}">Report
                                    Trade-In
                                </a>
                            </li>
                            <li><a href="{{ url('/report_vendor') }}"
                                    class="{{ request()->is('report_vendor') ? 'active' : '' }}">Report
                                    Vendor
                                </a>
                            </li>
                            <li><a href="{{ url('/report_employee') }}"
                                    class="{{ request()->is('report_employee') ? 'active' : '' }}">Report
                                    Employee
                                </a>
                            </li>
                        </ul>

                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('analytics') ? 'active' : '' }}"
                            href="{{ url('/analytics') }}"><i data-feather="activity"></i></i><span>Salesman
                                Analysis
                            </span></a>
                    </li>
                    <li class="dropdown"><a
                            class="nav-link menu-title @if (request()->is('file_invoice') || request()->is('file_do') || request()->is('file_po')) active @endif"
                            href="javascript:void(0)"><i data-feather="archive"></i><span>File Archive</span></a>
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
