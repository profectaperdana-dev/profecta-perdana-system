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

                {{-- CHART SALESMAN --}}
                <div class="col-xl-12 box-col-12 des-xl-100">
                    <div class="row">
                        <div class="col-xl-6 box-col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="header-top d-sm-flex justify-content-between align-items-center">
                                        <h5>sales chart by salesman</h5><br>
                                    </div>
                                    <div class="form-group row col-12">
                                        <div class="col-3">
                                            <label class="col-form-label">Start Date</label>
                                            <div class="input-group">
                                                <input class="form-control digits" type="date" data-language="en"
                                                    placeholder="Start" name="from_date" id="from_date">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <label class="col-form-label">End Date</label>
                                            <div class="input-group">
                                                <input class="form-control digits" type="date" data-language="en"
                                                    placeholder="Start" name="to_date" id="to_date">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <label class="col-form-label">&nbsp;</label>
                                            <div class="input-group">
                                                <button class="btn btn-primary" name="filter" id="filter"><i
                                                        class="fa fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <label class="col-form-label">&nbsp;</label>
                                            <div class="input-group">
                                                <a class="btn btn-warning" href="{{ url('/analytics') }}"><i
                                                        class="fa fa-refresh"></i></a>
                                            </div>
                                        </div>
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

                    </div>
                </div>
                {{-- END CHART SALESMAN --}}
                <div class="col-xl-12 box-col-12 des-xl-100">
                    <div class="row">

                        <div class="col-xl-6 box-col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="form-group row col-12">
                                        <div class="col-4">
                                            <label class="col-form-label text-end">Start Date</label>
                                            <div class="input-group">
                                                <input class="form-control digits" type="date" data-language="en"
                                                    placeholder="Start" name="from_date" id="from_date">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <label class="col-form-label text-end">End Date</label>
                                            <div class="input-group">
                                                <input class="form-control digits" type="date" data-language="en"
                                                    placeholder="Start" name="to_date" id="to_date">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <label class="col-form-label text-end">&nbsp;</label>
                                            <div class="input-group">
                                                <button class="btn btn-primary" name="filter"
                                                    id="filter">Filter</button>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <label class="col-form-label text-end">&nbsp;</label>
                                            <div class="input-group">
                                                <a class="btn btn-warning" href="{{ url('/analytics') }}">Refresh</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="header-top d-sm-flex justify-content-between align-items-center">
                                        <h5>sales chart by Product</h5><br>

                                    </div>
                                </div>

                                <div class="card-body chart-block p-0">
                                    {{-- <div id="chart-dash-2-line"></div> --}}
                                    <div class="chart-container">
                                        <div class="row">
                                            <div class="col-12">
                                                <div id="chart-dash-1-line"></div>
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
        @include('analysis.chart.chartproduct')
        @include('analysis.chart.chartsales')
    @endpush
@endsection
