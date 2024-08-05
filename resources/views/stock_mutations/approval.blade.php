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
                            <table id="example" class="table table-sm text-nowrap table-striped text-capitalize"
                                style="width:100%">
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
                                    @foreach ($mutations as $mutation)
                                        <tr>
                                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                                            <td class="text-center"><a class="fw-bold text-success modal-btn2"
                                                    href="#" data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#approveData{{ $mutation->id }}">{{ $mutation->mutation_number }}
                                            </td>
                                            <td class="text-center">{{ date('d F Y', strtotime($mutation->mutation_date)) }}
                                            </td>
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
                                            <input type="hidden" name="product_type" class="product-type"
                                                value="{{ $mutation->product_type }}">
                                            <div class="col-md-6 form-group mr-5">
                                                <label>From Warehouse</label>
                                                <select name="from" required multiple
                                                    class="form-control materials from_warehouse {{ $errors->first('from') ? ' is-invalid' : '' }}">
                                                    @foreach ($warehouses as $warehouse)
                                                        <option value="{{ $warehouse->id }}"
                                                            @if ($mutation->from == $warehouse->id) selected @endif>
                                                            {{ $warehouse->warehouses }}
                                                        </option>
                                                    @endforeach

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
                                                <select name="to" id="" required multiple
                                                    class="form-control uoms {{ $errors->first('to') ? ' is-invalid' : '' }}">
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


                                            <div class="col-md-12 form-group mr-5">
                                                <label>Remarks</label>
                                                <textarea class="form-control" name="remark" id="" cols="30" rows="2" required>{{ $mutation->remark }}</textarea>
                                            </div>
                                        </div>
                                        {{-- <input type="hidden" id="mutation_id" value="{{ $mutation->id }}">
                                        <input type="hidden" id="from_warehouse" value="{{ $mutation->from }}">
                                        <input type="hidden" id="to_warehouse" value="{{ $mutation->to }}"> --}}
                                        <div class="" id="formMutation">
                                            @foreach ($mutation->stockMutationDetailBy as $item)
                                                <input type="hidden" class="loop" value="{{ $loop->index }}">
                                                <div class="row rounded pt-2 mb-3" style="background-color: #f0e194">
                                                    @if ($mutation->product_type == 'Common')
                                                        <div class="form-group col-12 col-lg-5">
                                                            <label>Product</label>
                                                            <select multiple
                                                                name="mutationFields[{{ $loop->index }}][product_id]"
                                                                class="form-control productM" required>
                                                                <option value="{{ $item->product_id }}" selected>
                                                                    {{ $item->productBy->sub_materials->nama_sub_material . ' ' . $item->productBy->sub_types->type_name . ' ' . $item->productBy->nama_barang }}
                                                                </option>
                                                            </select>
                                                            @error('mutationFields[{{ $loop->index }}][product_id]')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    @else
                                                        <div class="form-group col-12 col-lg-7">
                                                            <label>Product</label>
                                                            <select multiple
                                                                name="mutationFields[{{ $loop->index }}][product_id]"
                                                                class="form-control productM" required>
                                                                <option value="{{ $item->product_id }}" selected>
                                                                    {{ $item->productSecondBy->name_product_trade_in }}
                                                                </option>
                                                            </select>
                                                            @error('mutationFields[{{ $loop->index }}][product_id]')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    @endif

                                                    <div class="col-9 col-lg-2 form-group">
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

                                                    <div class="col-9 col-lg-3 form-group">
                                                        <label>Note</label>
                                                        <input type="text" required class="form-control"
                                                            name="mutationFields[{{ $loop->index }}][note]"
                                                            value="{{ $item->note }}" id="">

                                                        @error('mutationFields[{{ $loop->index }}][note]')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>

                                                    @if ($loop->index == 0)
                                                        <div class="col-3 col-lg-2 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a id="addM" href="javascript:void(0)"
                                                                class="form-control text-white text-center"
                                                                style="border:none; background-color:#276e61">+</a>
                                                        </div>
                                                    @else
                                                        <div class="col-3 col-lg-1 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a id="addM" href="javascript:void(0)"
                                                                class="form-control text-white text-center"
                                                                style="border:none; background-color:#276e61">+</a>
                                                        </div>
                                                        <div class="col-3 col-lg-1 form-group">
                                                            <label for="">&nbsp;</label>
                                                            <a id="" href="javascript:void(0)"
                                                                class="form-control remMutation text-white text-center"
                                                                style="border:none; background-color:#d94f5c">-</a>
                                                        </div>
                                                    @endif

                                                    @if ($mutation->product_type == 'Common')
                                                        <div class="parentDot" data-index="{{ $loop->index }}"
                                                            data-product={{ $item->product_id }}
                                                            @if (!$item->productBy->materials->nama_material == 'Tyre') hidden @endif>
                                                            @foreach ($item->mutationDotBy as $code)
                                                                <div class="row " data-index-dot={{ $loop->index }}>
                                                                    <div class="col-4 col-lg-3 form-group">
                                                                        <label for="">DOT</label>
                                                                        <select
                                                                            name="mutationFields[{{ $loop->parent->index }}][{{ $loop->index }}][Dot]"
                                                                            id="" class="form-control dotProduct"
                                                                            multiple>
                                                                            @foreach ($item->getDot($mutation->from) as $dots)
                                                                                <option value="{{ $dots->id }}"
                                                                                    @if ($dots->id == $code->dot) selected @endif>
                                                                                    {{ $dots->dot }} -
                                                                                    [{{ $dots->qty }} Pcs]</option>
                                                                            @endforeach

                                                                        </select>
                                                                    </div>
                                                                    <div class="col-4 col-lg-2 form-group">
                                                                        <label for="">Qty</label>
                                                                        <input type="text"
                                                                            name="mutationFields[{{ $loop->parent->index }}][{{ $loop->index }}][qtyDot]"
                                                                            class="form-control text-center qtyDot"
                                                                            id="inputGroup-sizing-sm"
                                                                            value="{{ $code->qty }}" placeholder="Qty"
                                                                            aria-label="Qty">

                                                                    </div>
                                                                    @if ($loop->index == 0)
                                                                        <div class="col-4 col-lg-1 form-group">
                                                                            <label for="">&nbsp;</label>
                                                                            <a href="javascript:void(0)"
                                                                                class="form-control text-white text-center addDot"
                                                                                style="border:none; background-color:green">+</a>
                                                                        </div>
                                                                    @else
                                                                        <div class="col-2 col-lg-1 form-group">
                                                                            <label for="">&nbsp;</label>
                                                                            <a href="javascript:void(0)"
                                                                                class="form-control text-white text-center addDot"
                                                                                style="border:none; background-color:green">+</a>
                                                                        </div>
                                                                        <div class="col-2 col-lg-1 form-group">
                                                                            <label for="">&nbsp;</label>
                                                                            <a href="javascript:void(0)"
                                                                                class="form-control text-white text-center remDot"
                                                                                style="border:none; background-color:red">-</a>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-info" type="button"
                                                data-bs-dismiss="modal">Close</button>
                                            <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                data-original-title="test" data-bs-target="#reject{{ $mutation->id }}"
                                                data-bs-dismiss="modal">Reject</button>
                                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                                    aria-hidden="true"></span>
                                                <span class="sr-only">Loading...</span>
                                                Approve
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="modal" id="reject{{ $mutation->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Reject {{ $mutation->mutation_number }}</h6>
                        {{-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> --}}
                    </div>
                    <div class="modal-body">
                        Are you sure you want to reject this mutation?
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#approveData{{ $mutation->id }}"
                            data-bs-dismiss="modal">Back
                        </a>
                        <a type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</a>
                        <a type="button" href="{{ url('stock_mutation/' . $mutation->id . '/reject_mutation') }}"
                            class="btn btn-delete btn-primary">Yes, reject</a>
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
                var t = $('#example').DataTable({
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
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0
                    }, {
                        searchable: false,
                        orderable: false,
                        targets: 1,
                    }, ],
                });

                t.on('order.dt search.dt', function() {
                    let i = 1;

                    t.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();

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
                    let product_type = $(modal_id).find('.product-type').val();

                    //Get Customer ID
                    $(modal_id).find(".uoms, .materials").select2({
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

                        $(modal_id).find(".dotProduct").select2({
                            dropdownParent: modal_id,
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                        });

                        $(document).off("click", ".addDot");
                        $(document).on("click", ".addDot", function() {
                            let xdot = $(this).closest('.row').attr('data-index-dot');
                            let index_product = $(this).closest('.row').parent().attr('data-index');
                            // console.log(index_product);
                            // console.log(xdot);
                            ++xdot;
                            let form_dot = `<div class="row">
                                <div class="col-3 form-group">
                                    <label for="">DOT</label>
                                    <select name="mutationFields[${index_product}][${xdot}][Dot]" id="" class="form-control dotProduct"
                                        multiple></select>
                                </div>
                                <div class="col-2 form-group">
                                    <label for="">Qty</label>
                                    <input type="text" name="mutationFields[${index_product}][${xdot}][qtyDot]"
                                        class="form-control text-center qtyDot" id="inputGroup-sizing-sm" value="0"
                                        placeholder="Qty" aria-label="Qty">

                                </div>
                                <div class="col-1 form-group">
                                    <label for="">&nbsp;</label>
                                    <a href="javascript:void(0)" class="form-control text-white text-center addDot"
                                        style="border:none; background-color:green">+</a>
                                </div>
                                <div class="col-1 form-group">
                                    <label for="">&nbsp;</label>
                                    <a href="javascript:void(0)" class="form-control text-white text-center remDot"
                                        style="border:none; background-color:red">-</a>
                                </div>
                            </div>`;
                            // console.log(parentDot.html());
                            $(this).closest('.row').parent().append(form_dot);

                            let selected_product = $(this).closest('.row').parent().attr(
                                'data-product');

                            $(this).closest('.row').parent().find(".dotProduct").select2({
                                dropdownParent: modal_id,
                                placeholder: 'Select an option',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: '100%',
                                ajax: {
                                    context: this,
                                    type: "GET",
                                    url: "/tyre_dot/selectDot",
                                    data: {
                                        _token: csrf,
                                        p: selected_product,
                                        w: warehouse_from
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(
                                                item) {
                                                return [{
                                                    text: item
                                                        .dot +
                                                        ' - [ ' +
                                                        item
                                                        .qty +
                                                        ' pcs]',
                                                    id: item.id,
                                                }, ];
                                            }),
                                        };
                                    },
                                },
                            }).on("select2:select", function() {
                                $(".dotProduct").attr('readonly', true);
                            });

                        });

                        $(document).on("click", ".remDot", function() {
                            $(this).closest(".row").remove();
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
                            let parentDot = $(this).parent().siblings('.parentDot');
                            // parentDot.find(".dotProduct").select2('destroy');
                            // parentDot.find(".dotProduct").val([]).trigger("change");
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
                                                'Stock : ' + data);
                                    }

                                },
                            });

                            $.ajax({
                                context: this,
                                type: "GET",
                                url: "/stock_mutation/checkMaterial",
                                data: {
                                    _token: csrf,
                                    p: product_id
                                },
                                dataType: "json",
                                success: function(data) {
                                    if (data) {
                                        $(this).parent().siblings('.parentDot').attr(
                                            'hidden',
                                            false);
                                    } else {
                                        $(this).parent().siblings('.parentDot').attr(
                                            'hidden',
                                            true);
                                    }

                                },
                            });


                            // console.log(parentDot.html());

                            parentDot.find(".dotProduct").select2({
                                dropdownParent: modal_id,
                                placeholder: 'Select an option',
                                allowClear: true,
                                maximumSelectionLength: 1,
                                width: '100%',
                                ajax: {
                                    context: this,
                                    type: "GET",
                                    url: "/tyre_dot/selectDot",
                                    data: {
                                        _token: csrf,
                                        p: product_id,
                                        w: warehouse_from
                                    },
                                    dataType: "json",
                                    delay: 250,
                                    processResults: function(data) {
                                        return {
                                            results: $.map(data, function(item) {
                                                return [{
                                                    text: item.dot +
                                                        ' - [ ' +
                                                        item.qty +
                                                        ' pcs]',
                                                    id: item.id,
                                                }, ];
                                            }),
                                        };
                                    },
                                },
                            }).on("select2:select", function() {
                                $(".dotProduct").attr('readonly', true);
                            });

                            //     $(document).off("click", ".addDot");
                            //     $(document).on("click", ".addDot", function() {
                            //         // console.log(index_product);
                            //         let xdot = $(this).closest('.row').attr('data-index-dot');
                            //         let index_product = $(this).closest('.row').parent().attr(
                            //             'data-index');

                            //         ++xdot;
                            //         let form_dot = `<div class="row">
                    //     <div class="col-3 form-group">
                    //         <label for="">DOT</label>
                    //         <select name="mutationFields[${xdot}][${index_product}][Dot]" required id="" class="form-control dotProduct"
                    //             multiple></select>
                    //     </div>
                    //     <div class="col-2 form-group">
                    //         <label for="">Qty</label>
                    //         <input type="text" required name="mutationFields[${xdot}][${index_product}][qtyDot]"
                    //             class="form-control text-center qtyDot" id="inputGroup-sizing-sm" value="0"
                    //             placeholder="Qty" aria-label="Qty">

                    //     </div>
                    //     <div class="col-1 form-group">
                    //         <label for="">&nbsp;</label>
                    //         <a href="javascript:void(0)" class="form-control text-white text-center addDot"
                    //             style="border:none; background-color:green">+</a>
                    //     </div>
                    //     <div class="col-1 form-group">
                    //         <label for="">&nbsp;</label>
                    //         <a href="javascript:void(0)" class="form-control text-white text-center remDot"
                    //             style="border:none; background-color:red">-</a>
                    //     </div>
                    // </div>`;
                            //         // console.log(parentDot.html());
                            //         $(this).closest('.row').parent().append(form_dot);

                            //         parentDot.find(".dotProduct").select2({
                            //             placeholder: 'Select an option',
                            //             allowClear: false,
                            //             maximumSelectionLength: 1,
                            //             width: '100%',
                            //             ajax: {
                            //                 context: this,
                            //                 type: "GET",
                            //                 url: "/tyre_dot/selectDot",
                            //                 data: {
                            //                     _token: csrf,
                            //                     p: product_id,
                            //                     w: warehouse_from
                            //                 },
                            //                 dataType: "json",
                            //                 delay: 250,
                            //                 processResults: function(data) {
                            //                     return {
                            //                         results: $.map(data, function(
                            //                             item) {
                            //                             return [{
                            //                                 text: item
                            //                                     .dot +
                            //                                     ' - [ ' +
                            //                                     item
                            //                                     .qty +
                            //                                     ' pcs]',
                            //                                 id: item.id,
                            //                             }, ];
                            //                         }),
                            //                     };
                            //                 },
                            //             },
                            //         }).on("select2:select", function() {
                            //             $(".dotProduct").attr('readonly', true);
                            //         });

                            //     });

                            $(document).on("click", ".remDot", function() {
                                $(this).closest(".row").remove();
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
                                                'Stock : ' + data);
                                    }

                                },
                            });
                        }

                    });

                    $(modal_id).on('change', '.from_warehouse', function() {
                        warehouse_from = $(this).val();
                        $(modal_id).find(".productM").empty().trigger('change');
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
                            '<div class="form-group col-12 col-lg-5">' +
                            "<label>Product</label>" +
                            '<select multiple name="mutationFields[' +
                            x +
                            '][product_id]" class="form-control productM" required>' +

                            '</select>' +
                            '</div>' +
                            '<div class="col-9 col-lg-2 form-group">' +
                            '<label> Qty </label> ' +
                            '<input class="form-control" required name="mutationFields[' +
                            x +
                            '][qty]">' +
                            '<small class="from-stock" hidden>Stock : 0</small>' +
                            '</div>' +
                            '<div class="col-9 col-lg-3 form-group">' +
                            '<label>Note</label>' +
                            '<input type="text" class="form-control"' +
                            'name="mutationFields[' + x + '][note]"' +
                            'id="">' +
                            '</div>' +
                            '<div class="col-3 col-lg-1 form-group">' +
                            '<label for="">&nbsp;</label>' +
                            '<a id="addM" href="javascript:void(0)"' +
                            'class="form-control text-white text-center"' +
                            'style="border:none; background-color:#276e61">+</a>' +
                            '</div>' +
                            '<div class="col-3 col-lg-1 form-group">' +
                            '<label for=""> &nbsp; </label>' +
                            '<a href="javascript:void(0)" class="form-control text-white remMutation text-center" style="border:none; background-color:#d94f5c">' +
                            '- </a> ' +
                            '</div>' +
                            `<div class="parentDot" data-index="${x}" hidden>
                                <div class="row ">
                                    <div class="col-3 form-group">
                                        <label for="">DOT</label>
                                        <select name="mutationFields[${x}][0][Dot]" id="" class="form-control dotProduct"
                                            multiple></select>
                                    </div>
                                    <div class="col-2 form-group">
                                        <label for="">Qty</label>
                                        <input type="text" name="mutationFields[${x}][0][qtyDot]"
                                            class="form-control text-center qtyDot" id="inputGroup-sizing-sm" value="0"
                                            placeholder="Qty" aria-label="Qty">
    
                                    </div>
                                    <div class="col-1 form-group">
                                        <label for="">&nbsp;</label>
                                        <a href="javascript:void(0)" class="form-control text-white text-center addDot"
                                            style="border:none; background-color:green">+</a>
                                    </div>
                                </div>
                            </div>` +
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
