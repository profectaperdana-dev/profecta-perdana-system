@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">
                        You can approve stock mutations.
                    </h6>
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
                        <div class="table-responsive">
                            <table id="example1" class="table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="2%">action</th>
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
                                    @foreach ($mutations as $mutation)
                                        <tr>
                                            <td class="text-center"><a class="btn btn-primary btn-sm modal-btn2"
                                                    href="#" data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#approveData{{ $mutation->id }}">Approve
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $mutation->mutation_number }}</td>
                                            <td>{{ date('d-M-Y', strtotime($mutation->mutation_date)) }}</td>
                                            <td>{{ $mutation->fromWarehouse->warehouses }}</td>
                                            <td>{{ $mutation->toWarehouse->warehouses }}</td>
                                            <td>{{ $mutation->remark }}</td>
                                            <td>{{ $mutation->createdBy->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($mutations as $mutation)
        <div class="modal fade" id="approveData{{ $mutation->id }}" data-bs-keyboard="false"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Approve Mutation
                            :
                            {{ $mutation->mutation_number }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ url('stock_mutation/' . $mutation->id . '/approve_mutation') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-6 form-group mr-5">
                                                <label>From Warehouse</label>
                                                <select name="from" required
                                                    class="form-control materials from_warehouse {{ $errors->first('from') ? ' is-invalid' : '' }}">
                                                    @can('isSuperAdmin')
                                                        <option value="" selected>-Choose Warehouse From-</option>
                                                        @foreach ($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}"
                                                                @if ($mutation->from == $warehouse->id) selected @endif>
                                                                {{ $warehouse->warehouses }}
                                                            </option>
                                                        @endforeach
                                                    @elsecan('isWarehouseKeeper')
                                                        <option value="{{ Auth::user()->warehouse_id }}" selected>
                                                            {{ Auth::user()->warehouseBy->warehouses }}
                                                        </option>
                                                    @endcan

                                                </select>
                                                @error('from')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label>
                                                    To Warehouse</label>
                                                <select name="to" id="" required
                                                    class="form-control uoms {{ $errors->first('to') ? ' is-invalid' : '' }}">
                                                    <option value="" selected>-Choose Warehouse To -</option>
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}"
                                                            @if ($mutation->to == $warehouse->id) selected @endif>
                                                            {{ $warehouse->warehouses }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('to')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12 form-group mr-5">
                                                <label>Remarks</label>
                                                <textarea class="form-control" name="remark" id="" cols="30" rows="5" required>{{ $mutation->remark }}</textarea>
                                            </div>
                                        </div>
                                        {{-- <input type="hidden" id="mutation_id" value="{{ $mutation->id }}">
                                        <input type="hidden" id="from_warehouse" value="{{ $mutation->from }}">
                                        <input type="hidden" id="to_warehouse" value="{{ $mutation->to }}"> --}}
                                        <div class="row" id="formMutation">
                                            @foreach ($mutation->stockMutationDetailBy as $item)
                                                <input type="hidden" class="loop" value="{{ $loop->index }}">
                                                <div class="row">
                                                    <div class="form-group col-7">
                                                        <label>Product</label>
                                                        <select name="mutationFields[{{ $loop->index }}][product_id]"
                                                            class="form-control productM" required>
                                                            <option value="">Choose Product</option>
                                                            <option value="{{ $item->product_id }}" selected>
                                                                {{ $item->productBy->nama_barang . ' (' . $item->productBy->sub_materials->nama_sub_material . ', ' . $item->productBy->sub_types->type_name . ')' }}
                                                            </option>
                                                        </select>
                                                        @error('mutationFields[{{ $loop->index }}][product_id]')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                    <div class="col-3 col-md-3 form-group">
                                                        <label>Qty</label>
                                                        <input type="number" class="form-control" required
                                                            name="mutationFields[{{ $loop->index }}][qty]"
                                                            value="{{ $item->qty }}" id="">
                                                        <small class="from-stock" hidden>Stock : 0</small>

                                                        @error('mutationFields[{{ $loop->index }}][qty]')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    @if ($loop->index == 0)
                                                        <div class="col-2 col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a id="addM" href="javascript:void(0)"
                                                                class="form-control text-white text-center"
                                                                style="border:none; background-color:green">+</a>
                                                        </div>
                                                    @else
                                                        <div class="col-2 col-md-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a id="" href="javascript:void(0)"
                                                                class="form-control remMutation text-white text-center"
                                                                style="border:none; background-color:red">-</a>
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="form-group">

                                            <button type="reset" class="btn btn-warning">Reset</button>
                                            <button type="submit" class="btn btn-primary">Approve</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach
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

                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#example1').DataTable({

                        dom: 'Bfrtip',
                        lengthMenu: [
                            [10, 25, 50, -1],
                            ['10 rows', '25 rows', '50 rows', 'Show All']
                        ],
                        buttons: ['pageLength',
                            {
                                title: 'Data Stock Mutations',
                                messageTop: '<h5>{{ $title }} ({{ date('l H:i A, d F Y ') }})</h5><br>',
                                messageBottom: '<strong style="color:red;">*Please select only the type of column needed when printing so that the print is neater</strong>',
                                extend: 'print',
                                customize: function(win) {
                                    $(win.document.body)
                                        .css('font-size', '10pt')
                                        .prepend(
                                            '<img src="{{ asset('images/logo.png') }}" style="position:absolute; top:300; left:150; bottom:; opacity: 0.2;"/>'
                                        );
                                    $(win.document.body)
                                        .find('thead')
                                        .css('background-color', 'rgba(211,225,222,255)')
                                        .css('font-size', '8pt')
                                    $(win.document.body)
                                        .find('tbody')
                                        .css('background-color', 'rgba(211,225,222,255)')
                                        .css('font-size', '8pt')
                                    $(win.document.body)
                                        .find('table')
                                        .css('width', '100%')
                                },
                                orientation: 'landscape',
                                pageSize: 'legal',
                                exportOptions: {
                                    columns: ':visible'
                                },
                            },
                            {
                                extend: 'excel',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            },
                            'colvis'
                        ],

                    });
                }
                $('#filter').click(function() {
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    if (from_date != '' && to_date != '') {
                        $('#example1').DataTable().destroy();
                        load_data(from_date, to_date);
                    } else {
                        alert('Both Date is required');
                    }
                });

                $('#refresh').click(function() {
                    $('#from_date').val('');
                    $('#to_date').val('');
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
                    let mutation_id = $(modal_id).find('#mutation_id').val();
                    let warehouse_from = $(modal_id).find('.from_warehouse').val();
                    let warehouse_to = $(modal_id).find('#to_warehouse').val();

                    //Get Customer ID
                    $(modal_id).find(".uoms").select2({
                        width: "100%",
                    });

                    $(modal_id).find(".productM").select2({
                        width: "100%",
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
                                            text: item.nama_barang +
                                                " (" +
                                                item.type_name +
                                                ", " +
                                                item.nama_sub_material +
                                                ")",
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    $(modal_id).on('change', '.productM', function() {
                        let product_id = $(this).val();

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
                                    $(this).parent().siblings().find('.from-stock').html(
                                        'Stock : ' + data);
                                }

                            },
                        });
                    });

                    $(modal_id).on('change', '.from_warehouse', function() {
                        warehouse_from = $(this).val();
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
                            '<div class="form-group row">' +
                            '<div class="form-group col-7">' +
                            "<label>Product</label>" +
                            '<select name="mutationFields[' +
                            x +
                            '][product_id]" class="form-control productM" required>' +
                            '<option value=""> Choose Product </option> ' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-3 col-md-3 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control" required name="mutationFields[' +
                            x +
                            '][qty]">' +
                            '<small class="from-stock" hidden>Stock : 0</small>' +
                            '</div>' +
                            '<div class="col-2 col-md-2 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a class="form-control text-white remMutation text-center" style="border:none; background-color:red">' +
                            '- </a> ' +
                            '</div>' +
                            ' </div>';
                        $(modal_id).find("#formMutation").append(form);

                        $(modal_id).find(".productM").select2({
                            width: "100%",
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
                                                text: item.nama_barang +
                                                    " (" +
                                                    item.type_name +
                                                    ", " +
                                                    item.nama_sub_material +
                                                    ")",
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
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
