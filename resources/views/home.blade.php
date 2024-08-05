@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/date-picker.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
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
        {{-- ! card welcome back --}}
        <div class="col-xl-12 xl-100 box-col-12">
            <div class="row">
                <div class="col-12 col-lg-12">
                    <div class="card profile-greeting">
                        <div class="card-header">
                            <div class="header-top">
                                <div class="setting-list bg-primary position-unset">
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center p-t-0">
                            <h3 class="font-light">Hello Welcome back, {{ Auth::user()->name }} !!</h3>
                            <h3 class="font-light">GOOD IS NOT GOOD ENOUGH WHEN BETTER IS EXPECTED</h3>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- ! card informatio --}}
            @include('layouts.partials.purchase')

            @include('layouts.partials.card_information')
            {{-- ! PO & Return --}}
            {{-- ! maintenance info --}}
            @include('layouts.partials.maintenance_information')

            {{-- ! PO & Return --}}

            {{-- ! AR & AP --}}
            @include('layouts.partials.debt_receivable')

            {{-- ! Chart Sales --}}
            <!--@include('layouts.partials.chart_sales')-->

        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets') }}/js/custom-card/custom-card.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.en.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.custom.js"></script>
        <script src="{{ asset('assets') }}/js/notify/bootstrap-notify.min.js"></script>
        <!--<script src="{{ asset('assets') }}/js/vector-map/jquery-jvectormap-2.0.2.min.js"></script>-->
        <!--<script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-world-mill-en.js"></script>-->
        <!--<script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-us-aea-en.js"></script>-->
        <!--<script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-uk-mill-en.js"></script>-->
        <!--<script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-au-mill.js"></script>-->
        <!--<script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js"></script>-->
        <!--<script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-in-mill.js"></script>-->
        <!--<script src="{{ asset('assets') }}/js/vector-map/map/jquery-jvectormap-asia-mill.js"></script>-->
        <script src="{{ asset('assets') }}/js/dashboard/default.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.en.js"></script>
        <script src="{{ asset('assets') }}/js/datepicker/date-picker/datepicker.custom.js"></script>
        <script>
            $(document).ready(function() {
                $('.maintenance').DataTable({
                    "searching": false,
                    "paging": false,
                    "info": false,
                    "ordering": false,
                });
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                const format_rec = (d) => {
                    return `
                            <div style="margin-left:2px;" class="row col-lg-3 card shadow">
                                    <table class="table fw-bold" style="border:0;" border="0">
                                        <tr>
                                            <td>Remark</td>
                                            <td>:</td>
                                            <td>${d.remark}</td>
                                        </tr>
                                    </table>
                            </div>
                        `;
                };
                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#dataTable1').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,

                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('/report_receivable') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [{
                                data: null,
                                orderable: false,
                                searchable: false,
                                className: 'details-control',
                                defaultContent: '<i data-feather="plus"></i>'
                            },
                            {
                                className: 'text-center fw-bold',
                                data: 'order_number',
                                name: 'order_number'
                            },
                            {
                                className: 'text-center',
                                data: 'order_date',
                                name: 'order_date'
                            },
                            {
                                className: 'text-center',
                                data: 'due_date',
                                name: 'due_date'
                            },
                            {
                                className: 'text-center',
                                data: 'top',
                                name: 'top'
                            },
                            {
                                className: 'text-center',
                                data: 'customer',
                                name: 'customer'
                            },
                            {
                                "className": "text-end",
                                data: 'receivable',
                                name: 'receivable'
                            },
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api(),
                                data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function(i) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column(6)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b.replace(/\./g, ''));
                                }, 0);


                            // Update footer
                            $(api.column(6).footer()).html(
                                total.toLocaleString()
                            );
                        },
                        initComplete: function() {
                            var table = $('#dataTable1').DataTable();
                            $(document).find('#dataTable1 tbody').off().on('click', 'td.details-control',
                                function() {
                                    var tr = $(this).closest('tr');
                                    var row = table.row(tr);

                                    if (row.child.isShown()) {
                                        // This row is already open - close it
                                        row.child.hide();
                                        tr.removeClass('shown');
                                    } else {
                                        // Open this row
                                        row.child(format_rec(row.data())).show();
                                        tr.addClass('shown');
                                    }
                                });
                        },
                        order: [],
                        dom: 'Bfrtip',
                        "searching": false
                    });
                }
            });
        </script>
        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                load_data();

                // const format_debt = (d) => {
                //     return `
                //             <div style="margin-left:2px;" class="row col-lg-3 card shadow">
                //                     <table class="table fw-bold" style="border:0;" border="0">
                //                         <tr>
                //                             <td>TOP</td>
                //                             <td>:</td>
                //                             <td>${d.top}</td>
                //                         </tr>
                //                         <tr>
                //                             <td>Remark</td>
                //                             <td>:</td>
                //                             <td>${d.remark}</td>
                //                         </tr>
                //                     </table>
                //             </div>
                //         `;
                // };

                function load_data(from_date = '', to_date = '') {
                    $('#dataTable').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,

                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('/report_debt') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [
                            // {
                            //     data: null,
                            //     orderable: false,
                            //     searchable: false,
                            //     className: 'details-control',
                            //     defaultContent: '<i data-feather="plus"></i>'
                            // },
                            {
                                className: 'text-center fw-bold',
                                data: 'order_number',
                                name: 'order_number'
                            },
                            {
                                className: 'text-center',

                                data: 'order_date',
                                name: 'order_date'
                            },
                            {
                                className: 'text-center',

                                data: 'due_date',
                                name: 'due_date'
                            },
                            {
                                className: 'text-center',
                                data: 'supplier_id',
                                name: 'supplier_id'
                            },
                            {
                                "className": "text-end",
                                data: 'debt',
                                name: 'debt'
                            },
                            {
                                className: 'text-center',
                                data: 'remark',
                                name: 'remark'
                            },
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api(),
                                data;

                            // Remove the formatting to get integer data for summation
                            var intVal = function(i) {
                                return typeof i === 'string' ?
                                    i.replace(/[\$,]/g, '') * 1 :
                                    typeof i === 'number' ?
                                    i : 0;
                            };

                            // Total over all pages
                            total = api
                                .column(5)
                                .data()
                                .reduce(function(a, b) {
                                    return intVal(a) + intVal(b.replace(/\./g, ''));
                                }, 0);


                            // Update footer
                            $(api.column(5).footer()).html(
                                total.toLocaleString()
                            );
                        },
                        initComplete: function() {
                            var table = $('#dataTable').DataTable();
                            $(document).find('#dataTable tbody').off().on('click', 'td.details-control',
                                function() {
                                    var tr = $(this).closest('tr');
                                    var row = table.row(tr);

                                    if (row.child.isShown()) {
                                        // This row is already open - close it
                                        row.child.hide();
                                        tr.removeClass('shown');
                                    } else {
                                        // Open this row
                                        row.child(format_debt(row.data())).show();
                                        tr.addClass('shown');
                                    }
                                });
                        },

                        order: [],
                        dom: 'Bfrtip',
                    });
                }
            });
        </script>
        <script>
            $(function() {
                var cData = JSON.parse(`<?= $chart_data ?>`);
                var bData = JSON.parse(`<?= $chart_profit ?>`);
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
                            data: bData.data,
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
                    labels: bData.label,
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
                                return value.toLocaleString();
                            },
                        },
                    }, ],
                    tooltip: {
                        shared: true,
                        intersect: false,
                        y: {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return "Rp " + y.toLocaleString();
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

                setTimeout(() => {
                    var chart = new ApexCharts(document.querySelector("#chart-dash-2-line"), options);
                    chart.render();
                    $('.main-nav').show();
                }, 2100);
            });
            'use strict';
            var notify = $.notify('<i class="fa fa-bell-o"></i><strong>Loading</strong> page Do not close this page...', {
                type: 'theme',
                allow_dismiss: true,
                delay: 2000,
                showProgressbar: true,
                timer: 300
            });

            setTimeout(function() {
                notify.update('message', '<i class="fa fa-bell-o"></i><strong>Loading</strong> Please Wait');
            }, 1000);
        </script>
        <script>
            $(document).ready(function() {
                // $('#purchase').DataTable({
                //     "language": {
                //         "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                //     },
                //     "lengthChange": false,
                //     "paging": false,
                //     "bPaginate": false, // disable pagination
                //     "bLengthChange": false, // disable show entries dropdown
                //     "searching": true,
                //     "ordering": true,
                //     "info": false,
                //     "autoWidth": false,

                //     processing: true,
                //     serverSide: true,
                //     pageLength: -1,
                //     ajax: {
                //         url: "{{ url('/purchase_home') }}",

                //     },
                //     columns: [{
                //             className: 'text-center fw-bold',
                //             data: 'order_number',
                //             name: 'order_number'
                //         },
                //         {
                //             className: 'text-center',

                //             data: 'order_date',
                //             name: 'order_date'
                //         },
                //         {
                //             className: 'text-center',

                //             data: 'vendor',
                //             name: 'vendor'
                //         },
                //         {
                //             className: 'text-center',
                //             data: 'warehouse',
                //             name: 'warehouse'
                //         },
                //         {
                //             "className": "text-end",
                //             data: 'total',
                //             name: 'total'
                //         },
                //         {
                //             "className": "text-end",
                //             data: 'receive',
                //             name: 'receive'
                //         },
                //         {
                //             "className": "text-end",
                //             data: 'settlement',
                //             name: 'settlement'
                //         },
                //     ],
                //     footerCallback: function(row, data, start, end, display) {
                //         var api = this.api(),
                //             data;

                //         // Remove the formatting to get integer data for summation
                //         var intVal = function(i) {
                //             return typeof i === 'string' ?
                //                 i.replace(/[\$,]/g, '') * 1 :
                //                 typeof i === 'number' ?
                //                 i : 0;
                //         };

                //         // Total over all pages
                //         total = api
                //             .column(5)
                //             .data()
                //             .reduce(function(a, b) {
                //                 return intVal(a) + intVal(b.replace(/\./g, ''));
                //             }, 0);


                //         // Update footer
                //         $(api.column(4).footer()).html(
                //             total.toLocaleString()
                //         );
                //     },


                //     order: [],
                //     dom: 'Bfrtip',
                // });

            });

            $(document).ready(function() {
                // $('#return').DataTable({
                //     "language": {
                //         "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                //     },
                //     "lengthChange": false,
                //     "paging": false,
                //     "bPaginate": false, // disable pagination
                //     "bLengthChange": false, // disable show entries dropdown
                //     "searching": true,
                //     "ordering": true,
                //     "info": false,
                //     "autoWidth": false,

                //     processing: true,
                //     serverSide: true,
                //     pageLength: -1,
                //     ajax: {
                //         url: "{{ url('/return_home') }}",

                //     },
                //     columns: [{
                //             className: 'text-center fw-bold',
                //             data: 'return_date',
                //             name: 'return_date'
                //         },
                //         {
                //             className: 'text-center',

                //             data: 'return_number',
                //             name: 'return_number'
                //         },
                //         {
                //             className: 'text-center',

                //             data: 'retail_id',
                //             name: 'retail_id'
                //         },
                //         {
                //             className: 'text-center',
                //             data: 'total',
                //             name: 'total'
                //         },
                //         {
                //             "className": "text-end",
                //             data: 'return_reason',
                //             name: 'return_reason'
                //         },
                //         {
                //             "className": "text-end",
                //             data: 'created_by',
                //             name: 'created_by'
                //         },

                //     ],
                //     footerCallback: function(row, data, start, end, display) {
                //         var api = this.api(),
                //             data;

                //         // Remove the formatting to get integer data for summation
                //         var intVal = function(i) {
                //             return typeof i === 'string' ?
                //                 i.replace(/[\$,]/g, '') * 1 :
                //                 typeof i === 'number' ?
                //                 i : 0;
                //         };

                //         // Total over all pages
                //         total = api
                //             .column(5)
                //             .data()
                //             .reduce(function(a, b) {
                //                 return intVal(a) + intVal(b.replace(/\./g, ''));
                //             }, 0);


                //         // Update footer
                //         $(api.column(3).footer()).html(
                //             total.toLocaleString()
                //         );
                //     },
                //     // initComplete: function() {
                //     //     var table = $('#return').DataTable();
                //     //     $(document).find('#return tbody').off().on('click', 'td.details-control',
                //     //         function() {
                //     //             var tr = $(this).closest('tr');
                //     //             var row = table.row(tr);

                //     //             if (row.child.isShown()) {
                //     //                 // This row is already open - close it
                //     //                 row.child.hide();
                //     //                 tr.removeClass('shown');
                //     //             } else {
                //     //                 // Open this row
                //     //                 row.child(format_debt(row.data())).show();
                //     //                 tr.addClass('shown');
                //     //             }
                //     //         });
                //     // },

                //     order: [],
                //     dom: 'Bfrtip',
                // });

            });
        </script>
    @endpush
@endsection
