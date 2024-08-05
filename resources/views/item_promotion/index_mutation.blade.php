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
                    <h3 class="font-weight-bold">{{ $title }}</h3>

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
                            @if ($area_user->count() > 1)
                                <div class="col-lg-4 col-12">
                                    <label class="col-form-label text-end">Area</label>
                                    <div class="input-group">
                                        <select name="area" id="area" multiple class="col-sm-12 selectMulti">
                                            @foreach ($area as $ar)
                                                <option value="{{ $ar->id }}">
                                                    {{ $ar->area_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif

                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-primary form-control text-white" name="filter"
                                        id="filter">Filter</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-2">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button class="btn btn-warning form-control text-white" name="refresh"
                                        id="refresh">Reset</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-sm table-striped text-capitalize" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>No</th>
                                        <th>Mutation Number</th>
                                        <th>Mutation Date</th>
                                        <th>From Warehouse</th>
                                        <th>To Warehouse</th>
                                        <th>Remark</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('.datepicker-here').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
                });

                $('.selectMulti').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                document.querySelector('input[name="to_date"]').value = parseDate(new Date());

                function parseDate(date) {
                    let now = date;
                    // Format the date as "dd-mm-yyyy"
                    let day = now.getDate().toString().padStart(2, '0');
                    let month = (now.getMonth() + 1).toString().padStart(2, '0');
                    let year = now.getFullYear();
                    let formattedDate = `${day}-${month}-${year}`;
                    return formattedDate;
                }

                load_data();

                function load_data(from_date = '', to_date = '', area = '') {
                    $('#example1').DataTable({
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        dom: 'lpftrip',
                        pageLength: -1,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/material-promotion/mutation') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                area: area
                            }
                        },
                        columns: [{

                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center fw-bold",
                                orderable: false,
                                searchable: false
                            },
                            {

                                data: 'action',
                                name: 'action',
                                orderable: true,
                                searchable: true
                            },
                            {
                                className: 'text-nowrap',
                                data: 'mutation_date',
                                name: 'mutation_date'

                            },
                            {
                                className: 'text-nowrap',
                                data: 'from',
                                name: 'from'

                            },
                            {
                                className: 'text-nowrap',
                                data: 'to',
                                name: 'to'

                            },
                            {
                                className: 'text-nowrap',
                                data: 'remark',
                                name: 'remark',
                            },
                            {
                                className: 'text-nowrap',
                                data: 'created_by',
                                name: 'created_by',
                            },

                        ],
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
                    var area = $('#area').val();
                    if (from_date != '' && to_date != '' || area != '') {
                        $('#example1').DataTable().destroy();
                        load_data(from_date, to_date, area);
                    } else {
                        alert('Both Date is required');
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
            $(document).ready(function() {
                $(document).on("click", ".modal-btn2", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");

                    // $(document).on("click", ".modal-btn2", function() {

                    let modal_id = $(this).attr('data-bs-target');
                    let mutation_id = $(modal_id).find('.mutation_id').val();
                    let warehouse_from = $(modal_id).find('.from_warehouse').val();
                    let warehouse_to = $(modal_id).find('.to_warehouse').val();
                    let product_type = $(modal_id).find('.product_type').val();

                    $('form').submit(function(e) {
                        var form = $(this);
                        var button = form.find('button[type="submit"]');
                        if (form[0].checkValidity()) {
                            button.prop('disabled', true);
                            $(this).find('.spinner-border').removeClass('d-none');
                            $(this).find('span:not(.spinner-border)').addClass('d-none');
                            $(this).off('click');
                        }
                    });
                    //Get Customer ID
                    $(modal_id).find(".uoms").select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                    });

                    if (product_type == 'Common') {
                        $(modal_id).find(".productM").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                            ajax: {
                                type: "GET",
                                url: "/stock_mutation/select",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        fw: warehouse_from
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.nama_sub_material +
                                                    " " + item.type_name + " " +
                                                    item
                                                    .nama_barang,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    } else {
                        $(".productM").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: "100%",
                            ajax: {
                                type: "GET",
                                url: "/stock_mutation/selectSecond",
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                        fw: warehouse_from
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.nama_barang,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    }


                    $(modal_id).on('change', '.productM', function() {
                        let product_id = $(this).val();

                        if (product_type == 'Common') {
                            $.ajax({
                                context: this,
                                type: "GET",
                                url: "/stock_mutation/getQtyDetail",
                                data: {
                                    _token: csrf,
                                    fw: warehouse_from,
                                    p: product_id
                                },
                                dataType: "json",
                                success: function(data) {
                                    if (product_id == "") {
                                        $(this).parent().siblings().find('.from-stock')
                                            .attr('hidden',
                                                true);
                                    } else {
                                        $(this).parent().siblings().find('.from-stock')
                                            .attr('hidden',
                                                false);
                                        $(this).parent().siblings().find('.from-stock')
                                            .html(
                                                'Stock Total: ' + data);
                                    }

                                },
                            });
                        } else {
                            $.ajax({
                                context: this,
                                type: "GET",
                                url: "/stock_mutation/getSecondProductQty",
                                data: {
                                    _token: csrf,
                                    fw: warehouse_from,
                                    p: product_id
                                },
                                dataType: "json",
                                success: function(data) {
                                    if (product_id == "") {
                                        $(this).parent().siblings().find('.from-stock')
                                            .attr('hidden',
                                                true);
                                    } else {
                                        $(this).parent().siblings().find('.from-stock')
                                            .attr('hidden',
                                                false);
                                        $(this).parent().siblings().find('.from-stock')
                                            .html(
                                                'Stock Total: ' + data);
                                    }

                                },
                            });
                        }
                    });

                    //Get Customer ID
                    let x = $(modal_id)
                        .find('.modal-body')
                        .find('#formMutation')
                        .children('.form-group')
                        .last()
                        .find('.loop')
                        .val();

                    $(modal_id).on("click", "#addM", function() {
                        ++x;
                        let form =
                            '<div class="form-group row rounded pt-2 mb-3" style="background-color: #f0e194">' +
                            '<div class="form-group col-12 col-lg-7">' +
                            "<label>Product</label>" +
                            '<select multiple name="mutationFields[' +
                            x +
                            '][product_id]" class="form-control productM" required>' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-9 col-lg-3 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control" required name="mutationFields[' +
                            x +
                            '][qty]">' +
                            '<small class="from-stock" hidden>Stock Total: 0</small>' +
                            '</div>' +
                            '<div class="col-3 col-lg-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white remMutation text-center" style="border:none; background-color:red">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find("#formMutation").append(form);

                        if (product_type == 'Common') {
                            $(modal_id).find(".productM").select2({
                                dropdownParent: modal_id,
                                placeholder: 'Select an option',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: '100%',
                                ajax: {
                                    type: "GET",
                                    url: "/stock_mutation/select",
                                    data: function(params) {
                                        return {
                                            _token: csrf,
                                            q: params.term, // search term
                                            fw: warehouse_from
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
                                                        item.type_name +
                                                        " " + item
                                                        .nama_barang,
                                                    id: item.id,
                                                }, ];
                                            }),
                                        };
                                    },
                                },
                            });
                        } else {
                            $(".productM").select2({
                                dropdownParent: modal_id,
                                placeholder: 'Select an option',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: "100%",
                                ajax: {
                                    type: "GET",
                                    url: "/stock_mutation/selectSecond",
                                    data: function(params) {
                                        return {
                                            _token: csrf,
                                            q: params.term, // search term
                                            fw: warehouse_from
                                        };
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return [{
                                                    text: item.nama_barang,
                                                    id: item.id,
                                                }, ];
                                            }),
                                        };
                                    },
                                },
                            });
                        }
                    });

                    $(modal_id).on("click", ".remMutation", function() {
                        $(this).closest(".row").remove();
                    });


                    $(modal_id).on('hidden.bs.modal', function() {
                        $(modal_id).unbind();
                    });
                });
            });
        </script>
    @endpush
@endsection
