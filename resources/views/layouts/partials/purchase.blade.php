    {{-- ! Purchase retail today --}}
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/all_purchase_orders') }}">-->
    <!--            <div class="bg-primary b-r-4 card-body">-->
    <!--                <div class="media static-top-widget">-->
    <!--                    <div class="align-self-center text-center"><i data-feather="shopping-cart"></i></div>-->
    <!--                    <div class="media-body"><span class="m-0">Purchase Today</span>-->
    <!--                        <h4 class="mb-0"></h4><i class="icon-bg"-->
    <!--                            data-feather="shopping-cart"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->
    <!--{{-- ! Purchase this month --}}-->
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/all_purchase_orders') }}">-->
    <!--            <div class="bg-primary b-r-4 card-body">-->
    <!--                <div class="media static-top-widget">-->
    <!--                    <div class="align-self-center text-center"><i data-feather="shopping-cart"></i></div>-->
    <!--                    <div class="media-body"><span class="m-0">Purchase This Month</span>-->
    <!--                        <h4 class="mb-0"></h4><i class="icon-bg"-->
    <!--                            data-feather="shopping-cart"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->
    <!--{{-- ! Purchase this year --}}-->
    <!--<div class="col-sm-12 col-xl-4 col-lg-4">-->
    <!--    <div class="card o-hidden border-0"><a href="{{ url('/all_purchase_orders') }}">-->
    <!--            <div class="bg-primary b-r-4 card-body">-->
    <!--                <div class="media static-top-widget">-->
    <!--                    <div class="align-self-center text-center"><i data-feather="shopping-cart"></i></div>-->
    <!--                    <div class="media-body"><span class="m-0">Purchase This Year</span>-->
    <!--                        <h4 class="mb-0">}</h4><i class="icon-bg"-->
    <!--                            data-feather="shopping-cart"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--    </div>-->
    <!--    </a>-->
    <!--</div>-->


    
    <div class="card">
    <div class="card-header pb-0">
        <h5>Purchase overview by vendor</h5>
    </div>
    <div class="card-body">
        <div class="owl-carousel owl-theme p-lg-5" id="owl-carousel-2e">
            @foreach ($purchase_overview as $purchase)
                <div class="item">
                    <div class="col-sm-12">
                        <div class="card height-equal">
                            <div class="card-header pb-0">
                                <h6>{{ $purchase->supplierBy->nama_supplier }}</h6>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between align-items-center"
                                        style="background-color: transparent !important">
                                        <span><i class="fa fa-circle" aria-hidden="true"></i>
                                            Today (Rp):</span>

                                        <span class="text-dark">{{ number_format($purchase->total_sum_today) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"
                                        style="background-color: transparent !important">
                                        <span><i class="fa fa-circle" aria-hidden="true"></i>
                                            This month (Rp):</span>

                                        <span class="text-dark">{{ number_format($purchase->total_sum_month) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center"
                                        style="background-color: transparent !important">
                                        <span><i class="fa fa-circle" aria-hidden="true"></i>
                                            This year (Rp):</span>

                                        <span class="text-dark">{{ number_format($purchase->total_sum_year) }}</span>
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
