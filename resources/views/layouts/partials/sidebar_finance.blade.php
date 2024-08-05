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

                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('discounts') ? 'active' : '' }}"
                            href="{{ url('/discounts') }}"><i data-feather="percent"></i><span>Manage
                                Discount</span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('value_added_tax') ? 'active' : '' }}"
                            href="{{ url('/value_added_tax') }}"><i data-feather="percent"></i><span>Manage
                                Value-added Tax</span></a>
                    </li>
                    <li class="dropdown"><a class="nav-link menu-title @if (request()->is('account_sub_type') || request()->is('account') || request()->is('account_sub')) active @endif"
                            href="javascript:void(0)"><i data-feather="book-open"></i><span>Manage
                                Accounting</span></a>
                        <ul class="nav-submenu menu-content"
                            style="display: @if (request()->is('account_sub_type') || request()->is('account') || request()->is('account_sub')) block @else none @endif ">
                            <li><a href="{{ url('/account') }}"
                                    class="{{ request()->is('account') ? 'active' : '' }}">Accounts</a>
                            </li>
                            <li><a href="{{ url('/account_sub') }}"
                                    class="{{ request()->is('account_sub') ? 'active' : '' }}">Accounts Sub</a>
                            </li>
                            <li><a href="{{ url('/account_sub_type') }}"
                                    class="{{ request()->is('account_sub_type') ? 'active' : '' }}">Accounts Sub
                                    Type</a></li>

                        </ul>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('customers/create') ? 'active' : '' }}"
                            href="{{ url('/customers/create') }}"><i data-feather="user-check"></i><span>Create
                                Customers
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('customers') ? 'active' : '' }}"
                            href="{{ url('/customers') }}"><i data-feather="user-check"></i><span>All Customers
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
                        <a class="nav-link menu-title link-nav {{ request()->is('need_approval') ? 'active' : '' }}"
                            href="{{ url('/need_approval') }}"><i data-feather="toggle-right"></i><span>SO
                                Need Approve
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('invoice/manage_payment') ? 'active' : '' }}"
                            href="{{ url('/invoice/manage_payment') }}"><i data-feather="credit-card"></i><span>Manage
                                Payments
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
                            <h6>Report Accounting</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('expenses/create') ? 'active' : '' }}"
                            href="{{ url('/expenses/create') }}"><i data-feather="inbox"></i><span>Input Expenses
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('jurnal') ? 'active' : '' }}"
                            href="{{ url('/jurnal') }}"><i data-feather="inbox"></i><span>Journal
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('profit_loss') ? 'active' : '' }}"
                            href="{{ url('/profit_loss') }}"><i data-feather="inbox"></i><span>Profit & Loss
                            </span></a>
                    </li>
                    <li>
                        <a class="nav-link menu-title link-nav {{ request()->is('depreciation') ? 'active' : '' }}"
                            href="{{ url('/depreciation') }}"><i data-feather="inbox"></i><span>
                                Depreciation
                            </span></a>
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
                            <h6>Reports & Files</h6>
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
                            <li><a href="{{ url('/report_purchase') }}"
                                    class="{{ request()->is('report_purchase') ? 'active' : '' }}">Report Purchase
                                </a></li>
                            <li><a href="{{ url('/report_claim') }}"
                                    class="{{ request()->is('report_claim') ? 'active' : '' }}">Report Claim</a>
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
