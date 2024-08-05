@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')

        <style>
            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="font-weight-bold">{{ $title }}

                    </h3>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">
                        {{ $title }}
                </h6> --}}
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-12 xl-100">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5></h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group row col-12">
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date" data-value="{{ date('d-m-Y') }}"
                                        name="from_date" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="to_date" data-value="{{ date('d-m-Y') }}"
                                        name="to_date" autocomplete="on">
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary text-white form-control" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning text-white form-control" name="refresh"
                                        id="refresh">Refresh</button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="ppn" id="ppn" value="{{ $ppn }}">
                        <div class="table-responsive">
                            <table id="example1" class="table table-sm table-striped" style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th>No</th>
                                        <th></th>
                                        <th>Order Number</th>
                                        <th>Date</th>
                                        <th>Customer</th>
                                        <th>Total (Incl. PPN)</th>
                                        <th>Payment</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-center">Total</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!--<div class="modal fade" id="#detailDirect1673" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">-->
    <!--  <div class="modal-dialog">-->
    <!--    <div class="modal-content">-->
    <!--      <div class="modal-header">-->
    <!--        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>-->
    <!--        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
    <!--      </div>-->
    <!--      <div class="modal-body">-->
    <!--        ...-->
    <!--      </div>-->
    <!--      <div class="modal-footer">-->
    <!--        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
    <!--        <button type="button" class="btn btn-primary">Save changes</button>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
    <!--</div>-->

    {{-- <input type="text" hidden value="{{ $ }}"> --}}
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                //set date
                $('.datepicker-here').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
                });

                $(document).on('click', '.btn-delete', function() {
                    $(this).addClass('disabled');
                });

                $(document).on('submit', 'form', function(e) {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);
                        // e.preventDefault(); // prevent form submission
                    }
                });
                
                // Mendapatkan query string dari URL
                let queryString = window.location.search;
                
                // Parse query string ke dalam objek
                let queryParams = new URLSearchParams(queryString);
                
                // Mendapatkan nilai dari parameter "filter"
                let filterValue = queryParams.get("filter");

                function parseDate(date) {
                    let now = date;
                    // Format the date as "dd-mm-yyyy"
                    let day = now.getDate().toString().padStart(2, '0');
                    let month = (now.getMonth() + 1).toString().padStart(2, '0');
                    let year = now.getFullYear();
                    let formattedDate = `${day}-${month}-${year}`;
                    return formattedDate;
                }
                // Get the current date


                 if(filterValue == 'this_month'){
                    // Buat objek Date untuk tanggal saat ini
                    var currentDate = new Date();
                    
                    // Untuk mendapatkan awal bulan, atur tanggal ke 1
                    var firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                    
                    // Untuk mendapatkan akhir bulan ini, atur tanggal ke 0 (hari sebelum tanggal 1 bulan ini)
                    var lastDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
                    
                    // Format tanggal dalam bentuk string (dd/mm/yyyy)
                    var startDateString = (firstDayOfMonth.getDate() < 10 ? '0' : '') + firstDayOfMonth.getDate() + '-' + ((firstDayOfMonth.getMonth() + 1) < 10 ? '0' : '') + (firstDayOfMonth.getMonth() + 1) + '-' + firstDayOfMonth.getFullYear();
                    var endDateString = (lastDayOfMonth.getDate() < 10 ? '0' : '') + lastDayOfMonth.getDate() + '-' + ((lastDayOfMonth.getMonth() + 1) < 10 ? '0' : '') + (lastDayOfMonth.getMonth() + 1) + '-' + lastDayOfMonth.getFullYear();
                    
                    document.querySelector('input[name="from_date"]').value = startDateString;
                    document.querySelector('input[name="to_date"]').value = endDateString;
                }else{
                    document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                    document.querySelector('input[name="to_date"]').value = parseDate(new Date());
                }
                
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                const format = (d) => {
                    return `
                            <div style="margin-left:2px;" class="row col-lg-3 card shadow">
                                    <table class="table fw-bold" style="border:0;" border="0">
                                        <tr>
                                            <td>Remark</td>
                                            <td>:</td>
                                            <td>${d.remark}</td>
                                        </tr>
                                        <tr>
                                            <td>Status mail</td>
                                            <td>:</td>
                                            <td>${d.status_mail}</td>
                                        </tr>
                                        <tr>
                                            <td>Created by</td>
                                            <td>:</td>
                                            <td>${d.created_by}</td>
                                        </tr>
                                    </table>
                            </div>
                        `;
                };
                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#example1').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "bPaginate": true, // disable pagination
                        "bLengthChange": true, // disable show entries dropdown
                        "searching": true,
                        lengthMenu:[10,25,50,-1],
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        paging:true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/retail') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                filter: filterValue
                            }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center fw-bold",
                                orderable: false,
                                searchable: false
                            }, {
                                data: null,
                                orderable: false,
                                searchable: false,
                                className: 'details-control',
                                defaultContent: '<i data-feather="plus"></i>'
                            }, {
                                data: 'action',
                                name: 'action',
                                orderable: true,
                                searchable: true
                            }, {
                                className: "text-center text-nowrap",
                                data: 'order_date',
                                name: 'order_date'

                            },

                            {
                                "className": "text-nowrap",
                                data: 'cust_name',
                                name: 'cust_name',
                                searchable: true,
                                search: function(searchTerm, cellData) {
                                    console.log(searchTerm);
                                    return cellData.toLowerCase().includes(searchTerm.toLowerCase());
                                }

                            }, {
                                className: 'text-end',
                                data: 'total_incl',
                                name: 'total_incl'
                            }, {
                                className: "text-center text-nowrap",
                                data: 'payment_method',
                                name: 'payment_method'

                            }, {
                                className: "text-center text-nowrap",
                                data: 'isPaid',
                                name: 'isPaid'
                            },




                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();
                            var allData = api.rows({ search: 'applied' }).data().toArray().flat();
                            console.log(display);
                            // PPN
                            var visibleData = api.column(5).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    // let raw2 = raw1[0].split(".");
                                    raw2 = raw1.join('');
                                    // raw2 = raw2 + '.' + raw1[1];
                                    totalPPN += parseInt(raw2);
                                }
                            });


                            $(api.column(5).footer()).html(totalPPN.toLocaleString('EN', {
                                // style: 'currency',
                                // currency: 'IDR',
                                // minimumFractionDigits: 0,

                            }));
                        },
                        initComplete: function() {
                            var table = $('#example1').DataTable();
                            $(document).find('#example1 tbody').off().on('click', 'td.details-control',
                                function() {
                                    var tr = $(this).closest('tr');
                                    var row = table.row(tr);

                                    if (row.child.isShown()) {
                                        // This row is already open - close it
                                        row.child.hide();
                                        tr.removeClass('shown');
                                    } else {
                                        // Open this row
                                        row.child(format(row.data())).show();
                                        tr.addClass('shown');
                                    }
                                });
                        },
                        drawCallback: function(settings) {
                            // Kode yang akan dijalankan setelah DataTable selesai dikerjakan
                            $('#thisModal').html('');
                            $('.currentModal').each(function(){
                                let currentModal = $(this).html();
                                $(this).html('');
                                $('#thisModal').append(currentModal);
                            });
                            
                            // console.log($('#currentModal').html());
                            // Lakukan tindakan lain yang Anda inginkan di sini
                        },

                    });
                }
                
                //Click modal
                // $(document).on('click', '.code', function () {
                //     var data_id = $(this).attr('data-id');
                    
                //     var myModal = new bootstrap.Modal($('#detailDirect1673'), {
                //         keyboard: false
                //     });
                    
                //     myModal.show();
                // });
                

                // $('#detailDirect1673').on('shown.bs.modal', function (e) {
                //     // Lakukan permintaan AJAX untuk memuat data modal
                //     let data_id = $(this).attr('data-id');
                //     $.ajax({
                //         url: '/retail/modal/endpoint',
                //         method: 'GET',
                //         data: {
                //                 id_: data_id
                //             },
                //         success: function (data) {
                //             alert('dapat data id:');
                //         },
                //         error: function (error) {
                //             console.error('Error fetching modal data:', error);
                //         }
                //     });
                // });
                
                
                
                $('#filter').click(function() {


                    function formatDate(date) {
                        // Split the date string into day, month, and year components
                        let dateParts = date.split('-');

                        // Create a new Date object using the year, month, and day components
                        let dateObject = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);

                        // Format the date as "yyyy-mm-dd"
                        let year = dateObject.getFullYear();
                        let month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
                        let day = dateObject.getDate().toString().padStart(2, '0');
                        let formattedDate = `${year}-${month}-${day}`;

                        return formattedDate;
                    }

                    var from_date = formatDate($('#from_date').val());
                    var to_date = formatDate($('#to_date').val());
                    if (from_date != '' && to_date != '') {
                        $('#example1').DataTable().destroy();
                        load_data(from_date, to_date);
                    } else {
                        $.notify({
                            title: 'Warning !',
                            message: 'Please Select Start Date & End Date'
                        }, {
                            type: 'warning',
                            allow_dismiss: true,
                            newest_on_top: true,
                            mouse_over: true,
                            showProgressbar: false,
                            spacing: 10,
                            timer: 3000,
                            placement: {
                                from: 'top',
                                align: 'right'
                            },
                            offset: {
                                x: 30,
                                y: 30
                            },
                            delay: 1000,
                            z_index: 3000,
                            animate: {
                                enter: 'animated swing',
                                exit: 'animated swing'
                            }
                        });
                    }
                });

                $('#refresh').click(function() {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#example1').DataTable().destroy();
                    load_data();
                });


            });
        </script>
        <script>
            "use strict";
            $(document).ready(function() {
                $(document).on("click", ".modalRetail", function(event) {

                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    let modal_id = $(this).attr('data-bs-target');

                    let warehouse = $('#warehouse').val();

                    $(modal_id).find('.select2, .productRetail').select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });

                    $(modal_id).find('#addRetail').unbind('click');

                    $(modal_id).find(".productRetail").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/retail/selectProductAll",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                    w: warehouse
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_sub_material + " " +
                                                item.type_name + " " + item
                                                .nama_barang,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    $(modal_id).find('#cust').change(function() {
                        if ($(this).val() == 'other_cust') {
                            $('.manual-cust').attr('hidden', false);
                            $('.phone').attr('readonly', false);
                            $('.id_card').attr('readonly', false);
                            $('.email_add').attr('readonly', false);
                            $('.plate').attr('readonly', false);
                            $('.vehicle').attr('hidden', false);
                            $('.geo').attr('hidden', false);
                            $('.province').attr('readonly', false);
                            $('.city').attr('readonly', false);
                            $('.district').attr('readonly', false);
                            $('.address').attr('readonly', false);

                            $(modal_id).find(".province").select2({
                                width: "100%",
                                dropdownParent: modal_id,
                                placeholder: "Select Customer Province",
                                minimumResultsForSearch: -1,
                                sorter: data => data.sort((a, b) => a.text.localeCompare(b
                                    .text)),
                                ajax: {
                                    type: "GET",
                                    url: "/customers/getProvince",
                                    data: function(params) {
                                        return {
                                            _token: csrf,
                                            q: params.term, // search term
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return [{
                                                    text: item.name,
                                                    id: item.id,
                                                }, ];
                                            }),
                                        };
                                    },
                                },
                            });

                            $(modal_id).find('.province').change(function() {
                                let province_value = $(modal_id).find('.province').val();

                                $(modal_id).find(".city").select2({
                                    width: "100%",
                                    dropdownParent: modal_id,
                                    minimumResultsForSearch: -1,
                                    placeholder: "Select Customer City",
                                    sorter: data => data.sort((a, b) => a.text
                                        .localeCompare(b
                                            .text)),
                                    ajax: {
                                        type: "GET",
                                        url: "/customers/getCity/" + province_value,
                                        data: function(params) {
                                            return {
                                                _token: csrf,
                                                q: params.term, // search term
                                            };
                                        },
                                        dataType: "json",
                                        delay: 250,
                                        processResults: function(data) {
                                            return {
                                                results: $.map(data, function(
                                                    item) {
                                                    return [{
                                                        text: item
                                                            .name,
                                                        id: item.id,
                                                    }, ];
                                                }),
                                            };
                                        },
                                    },
                                });
                            });

                            $(modal_id).find('.city').change(function() {
                                let city_value = $(modal_id).find('.city').val();

                                $(modal_id).find(".district").select2({
                                    width: "100%",
                                    dropdownParent: modal_id,
                                    minimumResultsForSearch: -1,
                                    placeholder: "Select Customer District",
                                    sorter: data => data.sort((a, b) => a.text
                                        .localeCompare(b
                                            .text)),
                                    ajax: {
                                        type: "GET",
                                        url: "/customers/getDistrict/" + city_value,
                                        data: function(params) {
                                            return {
                                                _token: csrf,
                                                q: params.term, // search term
                                            };
                                        },
                                        dataType: "json",
                                        delay: 250,
                                        processResults: function(data) {
                                            return {
                                                results: $.map(data, function(
                                                    item) {
                                                    return [{
                                                        text: item
                                                            .name,
                                                        id: item.id,
                                                    }, ];
                                                }),
                                            };
                                        },
                                    },
                                });
                            });
                        } else {
                            $('.manual-cust').attr('hidden', true);
                            $('.phone').attr('readonly', true);
                            $('.id_card').attr('readonly', true);
                            $('.email_add').attr('readonly', true);
                            $('.plate').attr('readonly', true);
                            $('.vehicle').attr('hidden', true);
                            $('.geo').attr('hidden', true);
                            $('.province').attr('readonly', true);
                            $('.city').attr('readonly', true);
                            $('.district').attr('readonly', true);
                            $('.address').attr('readonly', true);
                        }
                    });

                    let vehicle = $(modal_id).find('#vehicle').val();
                    if (vehicle == "Car") {
                        $(modal_id).find('#car').attr('hidden', false);
                        $(modal_id).find('#motor').attr('hidden', true);
                        $(modal_id).find('#other').attr('hidden', true);
                    } else if (vehicle == "Motocycle") {
                        $(modal_id).find('#car').attr('hidden', true);
                        $(modal_id).find('#motor').attr('hidden', false);
                        $(modal_id).find('#other').attr('hidden', true);
                    } else {
                        $(modal_id).find('#car').attr('hidden', true);
                        $(modal_id).find('#motor').attr('hidden', true);
                        $(modal_id).find('#other').attr('hidden', false);
                    }

                    //Choose Vehicle
                    $(modal_id).find('#vehicle').change(function() {
                        if ($(this).val() == "Car") {
                            $(modal_id).find('#car').attr('hidden', false);
                            $(modal_id).find('#motor').attr('hidden', true);
                            $(modal_id).find('#other').attr('hidden', true);
                        } else if ($(this).val() == "Motocycle") {
                            $(modal_id).find('#car').attr('hidden', true);
                            $(modal_id).find('#motor').attr('hidden', false);
                            $(modal_id).find('#other').attr('hidden', true);
                        } else {
                            $(modal_id).find('#car').attr('hidden', true);
                            $(modal_id).find('#motor').attr('hidden', true);
                            $(modal_id).find('#other').attr('hidden', false);
                        }
                    });

                    $(modal_id).find(".car-brand").change(function() {
                        //clear select
                        $(modal_id).find(".car-type").empty();
                        //set id
                        let car_brand = $(this).val();
                        if (car_brand) {
                            $(modal_id).find(".car-type").select2({
                                width: "100%",
                                dropdownParent: modal_id,
                                ajax: {
                                    type: "GET",
                                    url: "/car_brand/select/" + car_brand,
                                    data: function(params) {
                                        return {
                                            _token: csrf,
                                            q: params.term, // search term
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: [{
                                                text: 'Other',
                                                id: 'other_car'
                                            }].concat($.map(data, function(item) {
                                                return {
                                                    text: item.car_type,
                                                    id: item.car_type,
                                                };
                                            })),
                                        };
                                    },
                                },
                            });
                        } else {
                            $(modal_id).find(".car-type").empty();
                        }
                    });

                    $(modal_id).find(".motor-brand").change(function() {
                        //clear select
                        $(modal_id).find(".motor-type").empty();
                        //set id
                        let motor_brand = $(this).val();
                        if (motor_brand) {
                            $(modal_id).find(".motor-type").select2({
                                width: "100%",
                                dropdownParent: modal_id,
                                ajax: {
                                    type: "GET",
                                    url: "/motocycle_brand/select/" + motor_brand,
                                    data: function(params) {
                                        return {
                                            _token: csrf,
                                            q: params.term, // search term
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: [{
                                                text: 'Other',
                                                id: 'other_motor'
                                            }].concat($.map(data, function(item) {
                                                return {
                                                    text: item.name_type,
                                                    id: item.name_type,
                                                };
                                            })),
                                        };
                                    },
                                },
                            });
                        } else {
                            $(modal_id).find(".motor-type").empty();
                        }
                    });

                    $(modal_id).find(".car-type").change(function() {
                        if ($(this).val() == 'other_car') {
                            $(modal_id).find(".other-car").attr('hidden', false);
                        } else {
                            $(modal_id).find(".other-car").attr('hidden', true);
                            $(modal_id).find(".other_car_input").val(null);
                        }
                    });

                    $(modal_id).find(".motor-type").change(function() {
                        if ($(this).val() == 'other_motor') {
                            $(modal_id).find(".other-motor").attr('hidden', false);
                        } else {
                            $(modal_id).find(".other-motor").attr('hidden', true);
                            $(modal_id).find(".other_motor_input").val(null);
                        }
                    });

                    let cust = $(modal_id).find('#cust').val();
                    if (cust == 'other_cust') {
                        $(modal_id).find(".province").select2({
                            width: "100%",
                            dropdownParent: modal_id,
                            placeholder: "Select Customer Province",
                            minimumResultsForSearch: -1,
                            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                            ajax: {
                                type: "GET",
                                url: "/customers/getProvince",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.name,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });

                        $(modal_id).find('.province').change(function() {
                            let province_value = $(modal_id).find('.province').val();

                            $(modal_id).find(".city").select2({
                                width: "100%",
                                dropdownParent: modal_id,
                                minimumResultsForSearch: -1,
                                placeholder: "Select Customer City",
                                sorter: data => data.sort((a, b) => a.text.localeCompare(b
                                    .text)),
                                ajax: {
                                    type: "GET",
                                    url: "/customers/getCity/" + province_value,
                                    data: function(params) {
                                        return {
                                            _token: csrf,
                                            q: params.term, // search term
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return [{
                                                    text: item.name,
                                                    id: item.id,
                                                }, ];
                                            }),
                                        };
                                    },
                                },
                            });
                        });

                        $(modal_id).find('.city').change(function() {
                            let city_value = $(modal_id).find('.city').val();

                            $(modal_id).find(".district").select2({
                                width: "100%",
                                dropdownParent: modal_id,
                                minimumResultsForSearch: -1,
                                placeholder: "Select Customer District",
                                sorter: data => data.sort((a, b) => a.text.localeCompare(b
                                    .text)),
                                ajax: {
                                    type: "GET",
                                    url: "/customers/getDistrict/" + city_value,
                                    data: function(params) {
                                        return {
                                            _token: csrf,
                                            q: params.term, // search term
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return [{
                                                    text: item.name,
                                                    id: item.id,
                                                }, ];
                                            }),
                                        };
                                    },
                                },
                            });
                        });
                    }

                    $(modal_id).on('change', '.qty-cart', function() {
                        let first_code = ``;
                        let first_code_list = $(this).closest('.form-group').siblings('.series-code')
                            .find(
                                '.first-code').each(
                                function() {
                                    first_code +=
                                        ` <div class="col-6 col-lg-3 form-group first-code">` + $(this)
                                        .html() + `</div>`;
                                });
                        let item_index = $(this).closest('.form-group').siblings('.series-code').find(
                            '.form-group').last().find('.item-index').val();
                        let loop_index = $(this).closest('.form-group').siblings('.series-code').find(
                            '.first-code').last().find('.loop-index').val();
                        let loop_total = $(this).closest('.form-group').siblings('.series-code')
                            .find('.form-group').each(function() {
                                return;
                            });

                        let count = $(this).val();

                        if (count >= first_code_list.length) {
                            $(this).closest('.form-group').siblings('.series-code').html('');
                            let element_series_code = ``;
                            for (let index = first_code_list.length; index < count; index++) {
                                loop_index++;
                                element_series_code += ` <div class="col-6 col-lg-3 form-group">
                                <input type="text" class="form-control" required placeholder="Series Code"
                                    name="retails[${item_index}][${parseInt(loop_index)}][product_code]" id="">
                                <input type="hidden" class="item-index" value="${item_index}" id="">
                                <input type="hidden" class="loop-index" value="${parseInt(loop_index)}" id="">
                            </div>`;

                            }
                            let appending_elements = first_code + element_series_code;
                            $(this).closest('.form-group').siblings('.series-code').append(
                                appending_elements);
                        } else if (count == 0) {
                            $(this).closest('.form-group').siblings('.series-code').html('');
                        } else {
                            let first_code_ = ``;
                            let first_code_list_ = $(this).closest('.form-group').siblings(
                                    '.series-code')
                                .find('.form-group').slice((parseInt(loop_total.length)) - parseInt(
                                    count));
                            console.log(first_code_list_.html());
                            $(this).closest('.form-group').siblings('.series-code').html('');
                            $(this).closest('.form-group').siblings('.series-code').append(
                                first_code_list_);
                        }
                    });

                    $('.diskon').on('keyup', function() {
                        var selection = window.getSelection().toString();
                        if (selection !== '') {
                            return;
                        }
                        // When the arrow keys are pressed, abort.
                        if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                            return;
                        }
                        var $this = $(this);
                        // Get the value.
                        var input = $this.val();
                        var input = input.replace(/[\D\s\._\-]+/g, "");
                        input = input ? parseInt(input, 10) : 0;
                        $this.val(function() {
                            return (input === 0) ? "" : input.toLocaleString("id-ID");
                        });
                        $this.next().val(input);

                    });
                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('#formRetail')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();

                    $(modal_id).find('.addRetail').click(function() {
                        ++x;
                        const form =
                            '<div class="mx-auto py-2 form-group rounded row" style="background-color: #f0e194">' +
                            '<input type="hidden" class="loop" value="' + x + '">' +
                            '<div class="col-3 col-lg-1 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white remSo-edit text-center" style="border:none; background-color:red">' +
                            "- </a> " +
                            "</div>" +
                            '<div class="form-group col-12 col-lg-5">' +
                            "<label>Product</label>" +
                            '<select name="retails[' +
                            x +
                            '][product_id]" class="form-control productRetail" required>' +
                            '<option value="">Choose Product</option> ' +
                            "</select>" +
                            "</div>" +
                            '<div class="col-6 col-lg-2 form-group">' +
                            "<label> Qty </label> " +
                            '<input type="number" class="form-control qty-cart" required name="retails[' +
                            x +
                            '][qty]">' +
                            '<small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>' +
                            "</div> " +
                            '<div class="col-4 col-lg-2 form-group">' +
                            "<label>Disc (%)</label>" +
                            '<input type="text" value="0" class="form-control" name="retails[' +
                            x +
                            '][discount]" id="">' +
                            "</div>" +
                            '<div class="col-5 col-lg-2 form-group">' +
                            "<label>Disc (Rp)</label>" +
                            '<input type="text" value="0" class="form-control diskon" >' +
                            '<input type="hidden" class="form-control" name="retails[' +
                            x +
                            '][discount_rp]" id="">' +
                            "</div>" +
                            '<div class="row form-group series-code">' +
                            "</div>" +
                            " </div>";
                        $(modal_id).find(".formRetail").append(form);
                        $('.diskon').on('keyup', function() {
                            var selection = window.getSelection().toString();
                            if (selection !== '') {
                                return;
                            }
                            // When the arrow keys are pressed, abort.
                            if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                                return;
                            }
                            var $this = $(this);
                            // Get the value.
                            var input = $this.val();
                            var input = input.replace(/[\D\s\._\-]+/g, "");
                            input = input ? parseInt(input, 10) : 0;
                            $this.val(function() {
                                return (input === 0) ? "" : input.toLocaleString(
                                    "id-ID");
                            });
                            $this.next().val(input);

                        });
                        $(modal_id).find(".productRetail").select2({
                            width: "100%",
                            dropdownParent: modal_id,
                            ajax: {
                                context: this,
                                type: "GET",
                                url: "/retail/selectProductAll",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        w: warehouse
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item
                                                    .nama_sub_material +
                                                    " " +
                                                    item.type_name + " " +
                                                    item
                                                    .nama_barang,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });

                        $(modal_id).find('.qty-cart').on('change', function() {
                            $(this).closest('.form-group').siblings('.series-code').html('');
                            let qty_in_add = $(this).val();
                            let element_series_code_in_add = ``;
                            for (let index = 0; index < qty_in_add; index++) {
                                element_series_code_in_add += `
                                <div class="col-6 col-lg-3 form-group first-code">
                                    <input type="text" class="form-control" placeholder="Series Code" required
                                    name="retails[${x}][${index}][product_code]"
                                     id="">
                                    <input type="hidden" class="item-index"
                                    value="${x}" id="">
                                    <input type="hidden" class="loop-index"
                                    value="${index}" id="">
                                </div>`;
                            }
                            $(this).closest('.form-group').siblings('.series-code').append(
                                element_series_code_in_add);
                        });
                    });

                    //remove Sales Order fields
                    $(modal_id).on("click", ".remSo-edit", function() {
                        $(this).closest(".row").remove();
                    });
                    // $(modal_id).on('hidden.bs.modal', function() {
                    //     $(document).unbind('modal');
                    // });

                });
            });
        </script>
        <script>
            $(document).on("click", ".copy_code", function() {
                var code = $(this).closest('td').find('.code').text();
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(code).select();
                document.execCommand("copy");
                $temp.remove();
                $.notify({
                    title: 'Success !',
                    message: 'Code ' + code + ' Copied'
                }, {
                    type: 'success',
                    allow_dismiss: true,
                    newest_on_top: true,
                    mouse_over: true,
                    showProgressbar: false,
                    spacing: 10,
                    timer: 3000,
                    placement: {
                        from: 'top',
                        align: 'right'
                    },
                    offset: {
                        x: 30,
                        y: 30
                    },
                    delay: 1000,
                    z_index: 10000,
                    animate: {
                        enter: 'animated swing',
                        exit: 'animated swing'
                    }
                });
                // alert('Code copied : ' + code);
            });
        </script>
    @endpush
@endsection
