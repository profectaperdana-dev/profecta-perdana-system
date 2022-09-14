@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/date-picker.css">
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>

                </div>

            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="col-xl-12 xl-100 box-col-12">
            <div class="row">
                <div class="col-xl-12 col-md-12 box-col-12 des-xl-50">
                    <div class="card profile-greeting">
                        <div class="card-header">
                            <div class="header-top">
                                <div class="setting-list bg-primary position-unset">

                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center p-t-0">
                            <h3 class="font-light">Wellcome Back, {{ Auth::user()->name }} !!</h3>
                            <p>Welcome to the profecta perdana system! we are glad that you are visite this dashboard. we
                                will
                                be happy
                                to help you grow your work.</p>
                        </div>
                        <div class="confetti">
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>
                            <div class="confetti-piece"></div>

                        </div>
                    </div>
                </div>

            </div>

        </div>

        <div class="row">
            @can('isWarehouseKeeper')
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="box"></i></div>
                                <div class="media-body"><span class="m-0">Total Type Product</span>
                                    <h4 class="mb-0 counter">{{ $produk }}</h4><i class="icon-bg" data-feather="box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="box"></i></div>
                                <div class="media-body"><span class="m-0">PO Need Validation</span>
                                    <h4 class="mb-0 counter">{{ $po_val }}</h4><i class="icon-bg" data-feather="box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="box"></i></div>
                                <div class="media-body"><span class="m-0">Total PO</span>
                                    <h4 class="mb-0 counter">{{ $po }}</h4><i class="icon-bg" data-feather="box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('isSales')
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="award"></i></div>
                                <div class="media-body"><span class="m-0">Sales Goals</span>
                                    <h4 class="mb-0 counter">{{ $so_by }}</h4><i class="icon-bg"
                                        data-feather="award"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body"><span class="m-0">Sales Today (IDR)</span>
                                    <h4 class="mb-0 counter">{{ number_format($so_day) }}</h4><i class="icon-bg"
                                        data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body"><span class="m-0">All Sales (IDR)</span>
                                    <h4 class="mb-0 counter">{{ number_format($so_total) }}</h4><i class="icon-bg"
                                        data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('isVerificator')
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="award"></i></div>
                                <div class="media-body"><span class="m-0">Verify Order Today</span>
                                    <h4 class="mb-0 counter">{{ $so_verify }}</h4><i class="icon-bg"
                                        data-feather="award"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="bookmark"></i></div>
                                <div class="media-body"><span class="m-0">Unverified orders
                                        Today</span>
                                    <h4 class="mb-0 counter">{{ $so_no_verif }}</h4><i class="icon-bg"
                                        data-feather="bookmark"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body"><span class="m-0">Sales (IDR)</span>
                                    <h4 class="mb-0 counter">{{ number_format($so_today) }}</h4><i class="icon-bg"
                                        data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('isFinance')
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="award"></i></div>
                                <div class="media-body"><span class="m-0">Approve Today</span>
                                    <h4 class="mb-0 counter">{{ $approve_today }}</h4><i class="icon-bg"
                                        data-feather="award"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body"><span class="m-0"> Sales Today (IDR)</span>
                                    <h4 class="mb-0 counter">{{ number_format($so_today) }}</h4><i class="icon-bg"
                                        data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="user"></i></div>
                                <div class="media-body"><span class="m-0">Customer Over Due Today</span>
                                    <h4 class="mb-0 counter">{{ $over_due }}</h4><i class="icon-bg"
                                        data-feather="user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            @can('isSuperAdmin')
                <div class="col-sm-12 col-xl-3 col-lg-3">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="battery"></i></div>
                                <div class="media-body"><span class="m-0">Total supplier</span>
                                    <h4 class="mb-0 counter">{{ $supplier }}</h4><i class="icon-bg"
                                        data-feather="battery"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-3 col-lg-3">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="user-plus"></i></div>
                                <div class="media-body"><span class="m-0">Total Customer </span>
                                    <h4 class="mb-0 counter">{{ $customer }}</h4><i class="icon-bg"
                                        data-feather="user-plus"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-3 col-lg-3">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="user"></i></div>
                                <div class="media-body"><span class="m-0">Total User</span>
                                    <h4 class="mb-0 counter">{{ $user }}</h4><i class="icon-bg"
                                        data-feather="user"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-3 col-lg-3">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="box"></i></div>
                                <div class="media-body"><span class="m-0">Total Type Product</span>
                                    <h4 class="mb-0 counter">{{ $produk }}</h4><i class="icon-bg"
                                        data-feather="box"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body"><span class="m-0">Sale Today (IDR) </span>
                                    <h4 class="mb-0 counter">{{ number_format($so_today) }}</h4><i class="icon-bg"
                                        data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body"><span class="m-0">Sale This Month (IDR)</span>
                                    <h4 class="mb-0 counter">{{ number_format($month) }}</h4><i class="icon-bg"
                                        data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-xl-4 col-lg-4">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body"><span class="m-0">Sale This Year (IDR)</span>
                                    <h4 class="mb-0 counter">{{ number_format($year) }}</h4><i class="icon-bg"
                                        data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan


            <div class="col-xl-12 box-col-12 des-xl-100">
                <div class="row">

                    <div class="col-xl-8 box-col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="header-top d-sm-flex justify-content-between align-items-center">
                                    <h5>last 7 days sale</h5><br>
                                    <h6>(IDR) {{ number_format($total_income) }}</h6>
                                    {{-- <div class="center-content">
                                        <ul class="week-date">
                                            <li class="font-primary">Today</li>
                                            <li>Month </li>
                                        </ul>
                                    </div> --}}
                                </div>
                            </div>

                            <div class="card-body chart-block p-0">
                                {{-- <div id="chart-dash-2-line"></div> --}}
                                <div class="chart-container">
                                    <div class="row">
                                        <div class="col-12">
                                            <div id="chart-dash-2-line"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 box-col-12">
                        <div class="card">
                            <div class="cal-date-widget card-body">
                                <div class="row">
                                    <div class="col-xl-12 col-xs-12 col-md-12 col-sm-12">
                                        <div class="cal-datepicker">
                                            <div class="datepicker-here" data-language="en"> </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>

        <script src="{{ asset('assets') }}/js/counter/jquery.counterup.min.js"></script>
        <script src="{{ asset('assets') }}/js/counter/counter-custom.js"></script>
        <script src="{{ asset('assets') }}/js/custom-card/custom-card.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.en.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.custom.js"></script>
        <script src="{{ asset('assets') }}/js/counter/jquery.counterup.min.js"></script>

        <script src="{{ asset('assets') }}/js/chart/chartist/chartist.js"></script>
        <script src="{{ asset('assets') }}/js/chart/chartist/chartist-plugin-tooltip.js"></script>
        <script src="{{ asset('assets') }}/js/chart/knob/knob.min.js"></script>
        <script src="{{ asset('assets') }}/js/chart/knob/knob-chart.js"></script>
        <script src="{{ asset('assets') }}/js/chart/apex-chart/apex-chart.js"></script>
        <script src="{{ asset('assets') }}/js/chart/apex-chart/stock-prices.js"></script>
        <script src="{{ asset('assets') }}/js/prism/prism.min.js"></script>
        <script src="{{ asset('assets') }}/js/clipboard/clipboard.min.js"></script>
        <script src="{{ asset('assets') }}/js/counter/jquery.waypoints.min.js"></script>
        <script src="{{ asset('assets') }}/js/counter/jquery.counterup.min.js"></script>
        <script src="{{ asset('assets') }}/js/counter/counter-custom.js"></script>
        <script src="{{ asset('assets') }}/js/custom-card/custom-card.js"></script>
        <script src="{{ asset('assets') }}/js/notify/bootstrap-notify.min.js"></script>
        <script src="{{ asset('assets') }}/js/vector-map/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-world-mill-en.js"></script>
        <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-us-aea-en.js"></script>
        <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-uk-mill-en.js"></script>
        <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-au-mill.js"></script>
        <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js"></script>
        <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-in-mill.js"></script>
        <script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-asia-mill.js"></script>
        {{-- <script src="{{ asset('assets') }}/js/dashboard/default.js"></script> --}}
        <script src="{{ asset('assets') }}/js/notify/index.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.en.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.custom.js"></script>
        <script>
            $(function() {
                var cData = JSON.parse(`<?php echo $data['chart_data']; ?>`);
                var num = cData.data;
                var text = cData.label;
                var options = {
                    series: [{
                            name: '<span class="badge badge-warning">Income</span>',
                            type: 'area',
                            data: num,
                        },
                        {
                            name: '<span class="badge badge-success">Profit</span>',
                            type: 'area',
                            data: [44000000, 55000000, 31000000, 47000000, 31000000, 0, 43000000]
                        }
                    ],

                    chart: {
                        animations: {
                            enabled: true,
                            easing: 'linear',
                            speed: 800,
                            animateGradually: {
                                enabled: true,
                                delay: 150
                            },
                            dynamicAnimation: {
                                enabled: true,
                                speed: 350
                            }
                        },
                        height: 400,
                        type: 'line',
                        background: '',
                        toolbar: {
                            show: true,
                        },

                    },
                    labels: text,
                    stroke: {
                        curve: 'smooth',
                        width: [5, 5],
                        dashArray: [0, 0]

                    },
                    fill: {
                        colors: [vihoAdminConfig.primary, vihoAdminConfig.secondary],
                        type: 'gradient',
                        gradient: {
                            shade: 'light',
                            type: 'vertical',
                            shadeIntensity: 0.4,
                            inverseColors: false,
                            opacityFrom: 0.9,
                            opacityTo: 0.8,
                            stops: [0, 100]
                        }
                    },

                    markers: {
                        size: 5
                    },
                    responsive: [{
                            breakpoint: 991,
                            options: {
                                chart: {
                                    height: 300
                                }
                            }
                        },
                        {
                            breakpoint: 1500,
                            options: {
                                chart: {
                                    height: 325
                                }
                            }
                        }
                    ],
                    yaxis: [{
                        min: 0,
                        tickAmount: 10,
                        labels: {
                            formatter: function(value) {
                                return value.toLocaleString(
                                    'id', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    });
                            },
                        },
                    }, ],
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return "Rp " + y.toLocaleString(
                                        'id', {
                                            minimumFractionDigits: 0,
                                            maximumFractionDigits: 0
                                        });
                                }
                                return y;
                            }
                        }
                    },
                    legend: {
                        show: true,
                    },
                    colors: [vihoAdminConfig.primary, vihoAdminConfig.secondary]
                };
                var chart = new ApexCharts(document.querySelector("#chart-dash-2-line"), options);
                chart.render();
            });
        </script>
    @endpush
@endsection
