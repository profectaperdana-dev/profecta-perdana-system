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
    <!-- Container-fluid starts-->
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
                            <p>Welcome to the viho Family! we are glad that you are visite this dashboard. we will
                                be happy
                                to help you grow your business.</p>
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
            @can('isSales')
                <div class="col-sm-12 col-xl-6 col-lg-6">
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
                <div class="col-sm-12 col-xl-6 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body"><span class="m-0">Sales (IDR)</span>
                                    <h4 class="mb-0 counter">{{ number_format($so_total) }}</h4><i class="icon-bg"
                                        data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
            <div class="col-xl-12 xl-100 box-col-12">
                <div class="card">
                    <div class="cal-date-widget card-body">
                        <div class="row">

                            <div class="col-xl-12 col-xs-12 col-md-12 col-sm-12">
                                <div class="cal-datepicker">
                                    <div class="datepicker-here " data-language="en"> </div>
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
        <script src="{{ asset('assets') }}/js/dashboard/default.js"></script>
        <script src="{{ asset('assets') }}/js/notify/index.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.en.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.custom.js"></script>
    @endpush
@endsection
