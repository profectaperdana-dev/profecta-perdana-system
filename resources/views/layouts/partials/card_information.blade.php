{{-- ! count of product --}}
<div class="row">
    <div class="col-lg-6 col-sm-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Indirect Sales Overview</h5>
            </div>
            <div class="card-body">
                <div class="owl-carousel owl-theme p-lg-5" id="owl-carousel-2a">
                    @foreach ($sales_indirect_overview as $sales)
                        <div class="item">
                            <div class="col-sm-12">
                                <div class="card height-equal">
                                    <div class="card-header pb-0">
                                        <h6>{{ $sales->warehouseBy->warehouses }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    <a class="link-success" href="{{ url('/invoice') }}">Today (Rp):</a></span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_today) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    <a class="link-success" href="{{ url('/invoice?filter=this_month') }}">This month (Rp):</a></span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_month) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    This year (Rp):</span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_year) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Direct Sales Overview</h5>
            </div>
            <div class="card-body">
                <div class="owl-carousel owl-theme p-lg-5" id="owl-carousel-2b">
                    @foreach ($sales_direct_overview as $sales)
                        <div class="item">
                            <div class="col-sm-12">
                                <div class="card height-equal">
                                    <div class="card-header pb-0">
                                        <h6>{{ $sales->warehouseBy->warehouses }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    <a href="{{ url('/retail') }}">Today (Rp):</a></span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_today) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    <a href="{{ url('/retail?filter=this_month') }}">This month (Rp):</a></span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_month) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    This year (Rp):</span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_year) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 col-sm-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Indirect Sales Return Overview</h5>
            </div>
            <div class="card-body">
                <div class="owl-carousel owl-theme p-lg-5" id="owl-carousel-2c">
                    @foreach ($return_sales_indirect_overview as $sales)
                        <div class="item">
                            <div class="col-sm-12">
                                <div class="card height-equal">
                                    <div class="card-header pb-0">
                                        <h6>{{$sales->warehouses}}</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    <a class="link-success" href="{{ url('/return') }}">Today (Rp):</a></span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_today) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    <a class="link-success" href="{{ url('/return?filter=this_month') }}">This month (Rp):</a></span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_month) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    This year (Rp):</span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_year) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5>Direct Sales Return Overview</h5>
            </div>
            <div class="card-body">
                <div class="owl-carousel owl-theme p-lg-5" id="owl-carousel-2d">
                    @foreach ($return_sales_direct_overview as $sales)
                        <div class="item">
                            <div class="col-sm-12">
                                <div class="card height-equal">
                                    <div class="card-header pb-0">
                                        <h6>{{$sales->warehouses}}</h6>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    <a href="{{ url('/return_retail') }}">Today (Rp):</a></span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_today) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    <a href="{{ url('/return_retail?filter=this_month') }}">This month (Rp):</a></span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_month) }}</span>
                                            </li>
                                            <li class="list-group-item d-flex justify-content-between align-items-center"
                                                style="background-color: transparent !important">
                                                <span><i class="fa fa-circle" aria-hidden="true"></i>
                                                    This year (Rp):</span>
        
                                                <span class="text-dark">{{ number_format($sales->total_sum_year) }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-sm-12 col-xl-4 col-lg-4">
    <a href="{{ url('/products') }}">
        <div class="card o-hidden border-0">
            <div class="bg-primary b-r-4 card-body">

                <div class="media static-top-widget">

                    <div class="align-self-center text-center"><i data-feather="box"></i></div>
                    <div class="media-body"><span class="m-0">Product</span>
                        <h4 class="mb-0 ">{{number_format($count_product)}}</h4><i class="icon-bg" data-feather="box"></i>
                    </div>

                </div>

            </div>
        </div>
    </a>
</div>

{{-- ! count of customer --}}
<div class="col-sm-12 col-xl-4 col-lg-4">
    <a href="{{ url('/customers') }}">
        <div class="card o-hidden border-0">
            <div class="bg-primary b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="user-plus"></i></div>
                    <div class="media-body"><span class="m-0">Customer</span>
                        <h4 class="mb-0">{{number_format($count_customer)}}</h4><i class="icon-bg"
                            data-feather="user-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- ! count of warehouse --}}
<div class="col-sm-12 col-xl-4 col-lg-4">
    <a href="{{ url('/warehouses') }}">
        <div class="card o-hidden border-0">
            <div class="bg-primary b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="archive"></i></div>
                    <div class="media-body"><span class="m-0">Warehouse</span>
                        <h4 class="mb-0 ">{{number_format($count_warehouse)}}</h4><i class="icon-bg"
                            data-feather="archive"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- ! count of users --}}
<div class="col-sm-12 col-xl-4 col-lg-4">
    <a href="{{ url('/users') }}">
        <div class="card o-hidden border-0">
            <div class="bg-primary b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="user"></i></div>
                    <div class="media-body"><span class="m-0">User System</span>
                        <h4 class="mb-0">{{number_format($count_user)}}</h4><i class="icon-bg" data-feather="user"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- ! count of employee --}}
<div class="col-sm-12 col-xl-4 col-lg-4">
    <a href="{{ url('/employee') }}">
        <div class="card o-hidden border-0">
            <div class="bg-primary b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="users"></i></div>
                    <div class="media-body"><span class="m-0">Employee</span>
                        <h4 class="mb-0 ">{{number_format($count_employee)}}</h4><i class="icon-bg" data-feather="users"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- ! count of vendor --}}
<div class="col-sm-12 col-xl-4 col-lg-4">
    <a href="{{ url('/supliers') }}">
        <div class="card o-hidden border-0">
            <div class="bg-primary b-r-4 card-body">
                <div class="media static-top-widget">
                    <div class="align-self-center text-center"><i data-feather="truck"></i></div>
                    <div class="media-body"><span class="m-0">Vendor</span>
                        <h4 class="mb-0 ">{{number_format($count_suppliers)}}</h4><i class="icon-bg"
                            data-feather="truck"></i>
                    </div>
                </div>
            </div>
        </div>
    </a>
</div>

{{-- ! sales to day --}}
<!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
<!--    <div class="card o-hidden border-0"><a href="{{ url('/invoice') }}">-->
<!--            <div class="bg-primary b-r-4 card-body">-->
<!--                <div class="media static-top-widget">-->
<!--                    <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>-->
<!--                    <div class="media-body"><span class="m-0">Sale Today (Non Retail)</span>-->
<!--                            data-feather="dollar-sign"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--    </div>-->
<!--    </a>-->
<!--</div>-->

{{-- ! sales this month --}}
<!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
<!--    <div class="card o-hidden border-0"><a href="{{ url('/invoice?filter=this_month') }}">-->
<!--            <div class="bg-primary b-r-4 card-body">-->
<!--                <div class="media static-top-widget">-->
<!--                    <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>-->
<!--                    <div class="media-body"><span class="m-0">Sales This Month (Non Retail)</span>-->
<!--                            data-feather="dollar-sign"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--    </div>-->
<!--    </a>-->
<!--</div>-->
{{-- ! sales this year --}}
<!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
<!--    <div class="card o-hidden border-0"><a href="{{ url('/invoice') }}">-->
<!--            <div class="bg-primary b-r-4 card-body">-->
<!--                <div class="media static-top-widget">-->
<!--                    <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>-->
<!--                    <div class="media-body"><span class="m-0">Sales This Year (Non Retail)</span>-->
<!--                            data-feather="dollar-sign"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--    </div>-->
<!--    </a>-->
<!--</div>-->

{{-- ! sales retail today --}}
<!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
<!--    <div class="card o-hidden border-0"><a href="{{ url('/retail') }}">-->
<!--            <div class="bg-primary b-r-4 card-body">-->
<!--                <div class="media static-top-widget">-->
<!--                    <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>-->
<!--                    <div class="media-body"><span class="m-0">Sale Today (Retail)</span>-->
<!--                            data-feather="dollar-sign"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--    </div>-->
<!--    </a>-->
<!--</div>-->

{{-- ! sales this month --}}
<!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
<!--    <div class="card o-hidden border-0"><a href="{{ url('/retail?filter=this_month') }}">-->
<!--            <div class="bg-primary b-r-4 card-body">-->
<!--                <div class="media static-top-widget">-->
<!--                    <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>-->
<!--                    <div class="media-body"><span class="m-0">Sales This Month (Retail)</span>-->
<!--                            data-feather="dollar-sign"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--    </div>-->
<!--    </a>-->
<!--</div>-->
{{-- ! sales this year --}}
<!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
<!--    <div class="card o-hidden border-0"><a href="{{ url('/retail') }}">-->
<!--            <div class="bg-primary b-r-4 card-body">-->
<!--                <div class="media static-top-widget">-->
<!--                    <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>-->
<!--                    <div class="media-body"><span class="m-0">Sales This Year (Retail)</span>-->
<!--                            data-feather="dollar-sign"></i>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--    </div>-->
<!--    </a>-->
<!--</div>-->

    <!--{{-- ! Retail retail today --}}-->
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/return_retail') }}">-->
    <!--        <div class="bg-primary b-r-4 card-body">-->
    <!--            <div class="media static-top-widget">-->
    <!--                <div class="align-self-center text-center"><i data-feather="rotate-ccw"></i></div>-->
    <!--                <div class="media-body"><span class="m-0">Return Direct Today</span>-->
    <!--                    <h4 class="mb-0"></h4><i class="icon-bg"-->
    <!--                        data-feather="rotate-ccw"></i>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->
    <!--{{-- ! Retail this month --}}-->
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/return_retail?filter=this_month') }}">-->
    <!--        <div class="bg-primary b-r-4 card-body">-->
    <!--            <div class="media static-top-widget">-->
    <!--                <div class="align-self-center text-center"><i data-feather="rotate-ccw"></i></div>-->
    <!--                <div class="media-body"><span class="m-0">Return Direct This Month</span>-->
    <!--                    <h4 class="mb-0"></h4><i class="icon-bg"-->
    <!--                        data-feather="rotate-ccw"></i>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->
    <!--{{-- ! Retail this year --}}-->
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/return_retail') }}">-->
    <!--        <div class="bg-primary b-r-4 card-body">-->
    <!--            <div class="media static-top-widget">-->
    <!--                <div class="align-self-center text-center"><i data-feather="rotate-ccw"></i></div>-->
    <!--                <div class="media-body"><span class="m-0">Return Direct This Year</span>-->
    <!--                    <h4 class="mb-0"></h4><i class="icon-bg"-->
    <!--                        data-feather="rotate-ccw"></i>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->

    <!--{{-- ! Non Retail retail today --}}-->
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/return') }}">-->
    <!--            <div class="bg-primary b-r-4 card-body">-->
    <!--                <div class="media static-top-widget">-->
    <!--                    <div class="align-self-center text-center"><i data-feather="rotate-ccw"></i></div>-->
    <!--                    <div class="media-body"><span class="m-0">Return Indirect Today</span>-->
    <!--                        <h4 class="mb-0"></h4><i class="icon-bg"-->
    <!--                            data-feather="rotate-ccw"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->
    <!--{{-- ! Non Retail this month --}}-->
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/return?filter=this_month') }}">-->
    <!--            <div class="bg-primary b-r-4 card-body">-->
    <!--                <div class="media static-top-widget">-->
    <!--                    <div class="align-self-center text-center"><i data-feather="rotate-ccw"></i></div>-->
    <!--                    <div class="media-body"><span class="m-0">Return Indirect This Month</span>-->
    <!--                        <h4 class="mb-0"></h4><i class="icon-bg"-->
    <!--                            data-feather="rotate-ccw"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->
    <!--{{-- ! Non Retail this year --}}-->
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/return') }}">-->
    <!--            <div class="bg-primary b-r-4 card-body">-->
    <!--                <div class="media static-top-widget">-->
    <!--                    <div class="align-self-center text-center"><i data-feather="rotate-ccw"></i></div>-->
    <!--                    <div class="media-body"><span class="m-0">Return Indirect This Year</span>-->
    <!--                        <h4 class="mb-0"></h4><i class="icon-bg"-->
    <!--                            data-feather="rotate-ccw"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->
