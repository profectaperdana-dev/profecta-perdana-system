@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        @include('report.style')
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
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basics"
                                class="table display expandable-table table-striped table-sm text-capitalize"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Warehouse</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td class="text-center fw-bold" style="width: 5%">{{ $key + 1 }}</td>
                                            <td>
                                                <a data-bs-toggle="modal" data-bs-target="#modal-{{ $key }}"
                                                    href="#" data-toggle="modal"
                                                    class="modalMatch fw-bold text-nowrap">
                                                    {{ $value->productBy->sub_materials->nama_sub_material }}
                                                    {{ $value->productBy->sub_types->type_name }}
                                                    {{ $value->productBy->nama_barang }}
                                                </a>
                                            </td>
                                            <td>{{ $value->warehouseBy->warehouses }}</td>
                                            </td>
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
     @foreach ($data as $key => $value)
                                <div class="modal" id="modal-{{ $key }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ url('tyre_dot/save_data') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="id_product" value="{{ $value->products_id }}">
                                                <input type="hidden" name="id_warehouse"
                                                    value="{{ $value->warehouses_id }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="staticBackdropLabel">
                                                        Stock DOT
                                                        {{ $value->productBy->sub_materials->nama_sub_material }}
                                                        {{ $value->productBy->sub_types->type_name }}
                                                        {{ $value->productBy->nama_barang }} at
                                                        {{ $value->warehouseBy->warehouses }}
                                                    </h6>
                                                </div>
                                                <div class="modal-body">
                                                    <div>
                                                        <button type="button"
                                                            class="btn addRow btn-sm btn-success">+</button>
                                                    </div>

                                                    <hr>
                                                    <b>Stock in warehouse : <span
                                                            class="stockInWarehouse">{{ $value->stock }}</span>
                                                        <span class="status"></span>
                                                    </b>
                                                    <hr>
                                                    <span class="text-danger">*You can edit data, only
                                                        double click on
                                                        data field</span>
                                                    <table class="table table-sm table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th style="width: 30%">DOT</th>
                                                                <th style="width: 20%">Qty</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="modal-add table-light">
                                                            @php
                                                                $sub_total = 0;
                                                            @endphp
                                                            @foreach ($datas as $dataRow)
                                                                @if ($dataRow->id_product == $value->productBy->id && $dataRow->id_warehouse == $value->warehouseBy->id)
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            @php
                                                                                $dot = $dataRow->dot;
                                                                                $dot = explode('/', $dot);
                                                                                
                                                                            @endphp
                                                                            <div class="input-group">
                                                                                <input type="hidden" class="id"
                                                                                    required value="{{ $dataRow->DOT }}">
                                                                                <input type="text" name=""
                                                                                    required value="{{ $dot[0] }}"
                                                                                    class="form-control text-center clickWeek"
                                                                                    id="inputGroup-sizing-sm"
                                                                                    placeholder="Week" readonly
                                                                                    aria-label="Week">
                                                                                <span class="input-group-text">/</span>
                                                                                <input type="text" name=""
                                                                                    readonly required
                                                                                    value="{{ $dot[1] }}"
                                                                                    class="form-control text-center clickYear"
                                                                                    id="inputGroup-sizing-sm"
                                                                                    placeholder="Year" aria-label="Year">
                                                                            </div>

                                                                        </td>
                                                                        <td class="text-center">
                                                                            <center>
                                                                                <div class="input-group text-center">
                                                                                    <input type="hidden" class="idQty"
                                                                                        value="{{ $dataRow->DOT }}">
                                                                                    <input type="text" required
                                                                                        class="qtyModal_ clickQty form-control text-center"
                                                                                        readonly
                                                                                        value="{{ $dataRow->qty }}">
                                                                                    <input type="hidden" required
                                                                                        class="idRemRow"
                                                                                        value="{{ $dataRow->DOT }}">
                                                                                    <span
                                                                                        class="input-group-text bg-danger text-sm text-white"><a
                                                                                            href="javascript:void(0)"
                                                                                            class="remRow text-white">x</a></span>
                                                                                </div>
                                                                            </center>
                                                                        </td>
                                                                        @php
                                                                            $sub_total += $dataRow->qty;
                                                                        @endphp
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="table-info">
                                                                <td colspan="1" class="text-center">
                                                                    Total
                                                                    DOT
                                                                    Stock
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="text" readonly
                                                                        class="totalDot form-control text-center"
                                                                        value="{{ $sub_total }}">
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary saveBtn">Saves</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach @foreach ($data as $key => $value)
                                <div class="modal" id="modal-{{ $key }}" data-bs-backdrop="static"
                                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <form action="{{ url('tyre_dot/save_data') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('POST')
                                                <input type="hidden" name="id_product" value="{{ $value->products_id }}">
                                                <input type="hidden" name="id_warehouse"
                                                    value="{{ $value->warehouses_id }}">
                                                <div class="modal-header">
                                                    <h6 class="modal-title" id="staticBackdropLabel">
                                                        Stock DOT
                                                        {{ $value->productBy->sub_materials->nama_sub_material }}
                                                        {{ $value->productBy->sub_types->type_name }}
                                                        {{ $value->productBy->nama_barang }} at
                                                        {{ $value->warehouseBy->warehouses }}
                                                    </h6>
                                                </div>
                                                <div class="modal-body">
                                                    <div>
                                                        <button type="button"
                                                            class="btn addRow btn-sm btn-success">+</button>
                                                    </div>

                                                    <hr>
                                                    <b>Stock in warehouse : <span
                                                            class="stockInWarehouse">{{ $value->stock }}</span>
                                                        <span class="status"></span>
                                                    </b>
                                                    <hr>
                                                    <span class="text-danger">*You can edit data, only
                                                        double click on
                                                        data field</span>
                                                    <table class="table table-sm table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr class="text-center">
                                                                <th style="width: 30%">DOT</th>
                                                                <th style="width: 20%">Qty</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="modal-add table-light">
                                                            @php
                                                                $sub_total = 0;
                                                            @endphp
                                                            @foreach ($datas as $dataRow)
                                                                @if ($dataRow->id_product == $value->productBy->id && $dataRow->id_warehouse == $value->warehouseBy->id)
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            @php
                                                                                $dot = $dataRow->dot;
                                                                                $dot = explode('/', $dot);
                                                                                
                                                                            @endphp
                                                                            <div class="input-group">
                                                                                <input type="hidden" class="id"
                                                                                    required value="{{ $dataRow->DOT }}">
                                                                                <input type="text" name=""
                                                                                    required value="{{ $dot[0] }}"
                                                                                    class="form-control text-center clickWeek"
                                                                                    id="inputGroup-sizing-sm"
                                                                                    placeholder="Week" readonly
                                                                                    aria-label="Week">
                                                                                <span class="input-group-text">/</span>
                                                                                <input type="text" name=""
                                                                                    readonly required
                                                                                    value="{{ $dot[1] }}"
                                                                                    class="form-control text-center clickYear"
                                                                                    id="inputGroup-sizing-sm"
                                                                                    placeholder="Year" aria-label="Year">
                                                                            </div>

                                                                        </td>
                                                                        <td class="text-center">
                                                                            <center>
                                                                                <div class="input-group text-center">
                                                                                    <input type="hidden" class="idQty"
                                                                                        value="{{ $dataRow->DOT }}">
                                                                                    <input type="text" required
                                                                                        class="qtyModal_ clickQty form-control text-center"
                                                                                        readonly
                                                                                        value="{{ $dataRow->qty }}">
                                                                                    <input type="hidden" required
                                                                                        class="idRemRow"
                                                                                        value="{{ $dataRow->DOT }}">
                                                                                    <span
                                                                                        class="input-group-text bg-danger text-sm text-white"><a
                                                                                            href="javascript:void(0)"
                                                                                            class="remRow text-white">x</a></span>
                                                                                </div>
                                                                            </center>
                                                                        </td>
                                                                        @php
                                                                            $sub_total += $dataRow->qty;
                                                                        @endphp
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr class="table-info">
                                                                <td colspan="1" class="text-center">
                                                                    Total
                                                                    DOT
                                                                    Stock
                                                                </td>
                                                                <td class="text-center">
                                                                    <input type="text" readonly
                                                                        class="totalDot form-control text-center"
                                                                        value="{{ $sub_total }}">
                                                                </td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary saveBtn">Saves</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(document).ready(function() {

            });
        </script>
        <script>
            $(function() {
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
                $('form').submit(function(e) {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                    if (validator.checkAll() != 0) {
                        $(this).find('button[type="submit"]').prop('disabled', false);
                    }
                });
            });
        </script>
        <script></script>
        <script>
            $(document).ready(function() {
                var csrf = $('meta[name="csrf-token"]').attr("content");

                $('.modalMatch').on('click', function() {
                    let modal_id = $(this).attr('data-bs-target');
                    var stockInWarehouse = $(modal_id).find(".stockInWarehouse").text();
                    var stockDot = $(modal_id).find(".totalDot").val();

                    $(modal_id).find('.remRow').on('click', function() {
                        var rowId = $(this).parent().parent().parent().find('.idRemRow').val();
                        var token = $('meta[name="csrf-token"]').attr('content');
                        var row = $(this).closest('tr');

                        if (confirm("Are you sure you want to delete this row?")) {
                            $.ajax({
                                url: 'tyre_dot/delete/' + rowId,
                                type: 'GET',
                                data: {
                                    // id: rowId,
                                    _token: token,
                                },
                                success: function(response) {
                                    console.log('Row deleted successfully.');
                                    // remove tr
                                    row.remove();
                                    let totalRemRow = 0;
                                    $(modal_id).find('table tbody tr td .qtyModal_').each(
                                        function() {
                                            totalRemRow += parseInt($(this).val());
                                        });
                                    console.log(totalRemRow);
                                    $(modal_id).find('.totalDot').val(totalRemRow);
                                    if (parseInt(stockInWarehouse) == parseInt(
                                            totalRemRow)) {
                                        $(modal_id).find('.status').html('Stock Match');
                                        $(modal_id).find('.status').removeClass(
                                            'badge badge-danger');
                                        $(modal_id).find('.status').addClass(
                                            'badge badge-success');
                                        $(modal_id).find('.addRow').attr('hidden', true);
                                        $(modal_id).find('.saveBtn').attr('hidden', true);
                                    } else {
                                        $(modal_id).find('.status').html(
                                            'Stock Don\'t Match');
                                        $(modal_id).find('.status').removeClass(
                                            'badge badge-success');
                                        $(modal_id).find('.status').addClass(
                                            'badge badge-danger');
                                        $(modal_id).find('.addRow').attr('hidden', false);
                                        $(modal_id).find('.saveBtn').attr('hidden', false);
                                    }


                                },
                                error: function(response) {
                                    console.log('Failed to delete row.');
                                }
                            });
                        }
                    });

                    $(modal_id).on('dblclick', '.clickWeek', function() {
                        let id = $(this).siblings('.id').val();
                        console.log(id);
                        $(this).removeAttr('readonly');
                        var currentValue = $(this).val();
                        console.log(currentValue);
                        $(this).html('<input required type="text" value="' + currentValue + '">');
                        $(this).find('input').focus();
                        $(this).off('focusout');
                        $(this).focusout(function() {
                            $.ajax({
                                type: "GET",
                                context: this,
                                url: "tyre_dot/updateweek/" + id,
                                data: {
                                    _token: csrf,
                                    week: $(this).val(),
                                },
                                dataType: "json",
                                success: function(data) {
                                    $.notify({
                                        title: 'success !',
                                        message: 'Week has been updated'
                                    }, {
                                        type: 'success',
                                        allow_dismiss: true,
                                        newest_on_top: true,
                                        mouse_over: true,
                                        showProgressbar: false,
                                        spacing: 10,
                                        timer: 1000,
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
                                    $(this).attr('readonly', true);
                                    $(this).off('focusout');
                                },
                            });
                        })
                    });
                    $(modal_id).on('dblclick', '.clickYear', function() {
                        let id = $(this).siblings('.id').val();
                        $(this).removeAttr('readonly');
                        var currentValue = $(this).val();
                        $(this).html('<input required type="text" value="' + currentValue + '">');
                        $(this).find('input').focus();
                        $(this).off('focusout');

                        $(this).focusout(function() {
                            $.ajax({
                                type: "GET",
                                context: this,
                                url: "tyre_dot/updateyear/" + id,
                                data: {
                                    _token: csrf,
                                    year: $(this).val(),
                                },
                                dataType: "json",
                                success: function(data) {
                                    $.notify({
                                        title: 'success !',
                                        message: 'Year has been updated'
                                    }, {
                                        type: 'success',
                                        allow_dismiss: true,
                                        newest_on_top: true,
                                        mouse_over: true,
                                        showProgressbar: false,
                                        spacing: 10,
                                        timer: 1000,
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
                                    $(this).attr('readonly', true);
                                    $(this).off('focusout');

                                },
                            });
                        })
                    });
                    $(modal_id).on('dblclick', '.clickQty', function() {

                        let id = $(this).siblings('.idQty').val();
                        // console.log(id);
                        $(this).removeAttr('readonly');
                        var currentValue = $(this).val();
                        $(this).html('<input required type="text" value="' + currentValue + '">');
                        $(this).find('input').focus();
                        // Move the total quantity calculation outside of the focusout event handler
                        $(modal_id).find('table tbody tr td .qtyModal_').on('input', function() {
                            var totalRowQty = 0;
                            $(modal_id).find('table tbody tr td .qtyModal_').each(function() {
                                totalRowQty += parseInt($(this).val());
                            });
                            $(modal_id).find(".totalDot").val(totalRowQty);

                            // Check whether the total quantity exceeds the stock in the warehouse
                            if (totalRowQty > parseInt(stockInWarehouse)) {
                                $(modal_id).find('table tbody tr td .qtyModal_').off(
                                    'focusout');
                            }

                            if (parseInt(stockInWarehouse) == parseInt(totalRowQty)) {
                                $(modal_id).find('.status').html('Stock Match');
                                $(modal_id).find('.status').removeClass('badge badge-danger');
                                $(modal_id).find('.status').addClass('badge badge-success');
                                $(modal_id).find('.addRow').attr('hidden', true);
                                $(modal_id).find('.saveBtn').attr('hidden', true);
                            } else if (parseInt(stockInWarehouse) < parseInt(totalRowQty)) {
                                $(modal_id).find('.status').html('Stock Don\'t Match');
                                $(modal_id).find('.status').removeClass('badge badge-success');
                                $(modal_id).find('.status').addClass('badge badge-danger');
                                $(modal_id).find('.addRow').attr('hidden', true);
                                $(modal_id).find('.saveBtn').attr('hidden', true);
                            } else if (parseInt(stockInWarehouse) > parseInt(totalRowQty)) {
                                $(modal_id).find('.status').html('Stock Don\'t Match');
                                $(modal_id).find('.status').removeClass('badge badge-success');
                                $(modal_id).find('.status').addClass('badge badge-danger');
                                $(modal_id).find('.addRow').attr('hidden', false);
                                $(modal_id).find('.saveBtn').attr('hidden', false);
                            }

                            // focusout off
                            $(this).on('focusout', function() {
                                if (parseInt($(modal_id).find(".totalDot").val()) >
                                    parseInt(stockInWarehouse)) {
                                    $.notify({
                                        title: 'Failed !',
                                        message: 'Total quantity exceeds the stock in the warehouse'
                                    }, {
                                        type: 'danger',
                                        allow_dismiss: true,
                                        newest_on_top: true,
                                        mouse_over: true,
                                        showProgressbar: false,
                                        spacing: 10,
                                        timer: 1500,
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
                                    // set current value to the input
                                    $(this).val(currentValue);
                                    var totalRowQty_ = 0;
                                    $(modal_id).find('table tbody tr td .qtyModal_')
                                        .each(function() {
                                            totalRowQty_ += parseInt($(this).val());
                                        });
                                    $(modal_id).find(".totalDot").val(totalRowQty_);
                                    if (parseInt(stockInWarehouse) == parseInt(
                                            totalRowQty_)) {
                                        $(modal_id).find('.status').html('Stock Match');
                                        $(modal_id).find('.status').removeClass(
                                            'badge badge-danger');
                                        $(modal_id).find('.status').addClass(
                                            'badge badge-success');
                                        $(modal_id).find('.addRow').attr('hidden',
                                            true);
                                        $(modal_id).find('.saveBtn').attr('hidden',
                                            true);
                                    } else if (parseInt(stockInWarehouse) < parseInt(
                                            totalRowQty_)) {
                                        $(modal_id).find('.status').html(
                                            'Stock Don\'t Match');
                                        $(modal_id).find('.status').removeClass(
                                            'badge badge-success');
                                        $(modal_id).find('.status').addClass(
                                            'badge badge-danger');
                                        $(modal_id).find('.addRow').attr('hidden',
                                            true);
                                        $(modal_id).find('.saveBtn').attr('hidden',
                                            true);
                                    } else if (parseInt(stockInWarehouse) > parseInt(
                                            totalRowQty_)) {
                                        $(modal_id).find('.status').html(
                                            'Stock Don\'t Match');
                                        $(modal_id).find('.status').removeClass(
                                            'badge badge-success');
                                        $(modal_id).find('.status').addClass(
                                            'badge badge-danger');
                                        $(modal_id).find('.addRow').attr('hidden',
                                            false);
                                        $(modal_id).find('.saveBtn').attr('hidden',
                                            false);
                                    }
                                    $.ajax({
                                        type: "GET",
                                        context: this,
                                        url: "tyre_dot/updateqty/" + id,
                                        data: {
                                            _token: csrf,
                                            qty: $(this).val(),
                                        },
                                        dataType: "json",
                                        success: function(data) {
                                            $(this).attr('readonly', true);
                                        },
                                    });
                                    $(this).off('focusout');
                                } else {
                                    $.ajax({
                                        type: "GET",
                                        context: this,
                                        url: "tyre_dot/updateqty/" + id,
                                        data: {
                                            _token: csrf,
                                            qty: $(this).val(),
                                        },
                                        dataType: "json",
                                        success: function(data) {
                                            $(this).attr('readonly', true);
                                        },
                                    });
                                }
                            });
                        });

                    });


                    if (parseInt(stockInWarehouse) == parseInt(stockDot)) {
                        $(modal_id).find('.status').html('Stock Match');
                        $(modal_id).find('.status').addClass('badge badge-success');
                        $(modal_id).find('.addRow').attr('hidden', true);
                        $(modal_id).find('.saveBtn').attr('hidden', true);
                    } else if (parseInt(stockInWarehouse) < parseInt(stockDot)) {
                        $(modal_id).find('.status').html('Stock Don\'t Match');
                        $(modal_id).find('.status').addClass('badge badge-danger');
                        $(modal_id).find('.addRow').attr('hidden', false);
                        $(modal_id).find('.saveBtn').attr('hidden', false);
                    } else if (parseInt(stockInWarehouse) > parseInt(stockDot)) {
                        $(modal_id).find('.status').html('Stock Don\'t Match');
                        $(modal_id).find('.status').addClass('badge badge-danger');
                        $(modal_id).find('.addRow').attr('hidden', false);
                        $(modal_id).find('.saveBtn').attr('hidden', false);
                    }
                    $(modal_id).off("click", ".addRow");
                    var x = 0;
                    $(modal_id).on("click", ".addRow", function() {
                        x++;
                        var barang = $(".barang").val();
                        var newRow =
                            `<tr>
                                <td>
                                    <div class="input-group">

                                        <input type="text" required name="field[${x}][week]" class="form-control text-center week" id="inputGroup-sizing-sm" placeholder="Week" aria-label="Week">
                                        <span class="input-group-text">/</span>
                                        <input type="text" required name="field[${x}][year]" class="form-control text-center year" id="inputGroup-sizing-sm" placeholder="Year" aria-label="Year">
                                    </div>
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" required name="field[${x}][qty]" class="form-control text-center qty qtyModal" id="inputGroup-sizing-sm" value="0" placeholder="Qty" aria-label="Qty">
                                        <span class="input-group-text bg-danger"><a   href="javascript:void(0)" style="color:white;" class="delete-row">x</a></span>
                                    </div>
                                </td>
                            </tr> `;
                        if ($(modal_id).find("table tbody tr").length) {
                            $(modal_id).find("table tbody").append(newRow);
                        } else {
                            $(modal_id).find("table tbody").html(newRow);
                        }
                        // mendapatkan elemen input week
                        const weekInput = $(modal_id).find('.week');

                        // menambahkan event listener untuk mendeteksi ketika pengguna mengetik
                        weekInput.on('keyup', (event) => {
                            const value = event.target.value;

                            // jika nilai input week memiliki dua digit
                            if (value.length === 2) {
                                // mendapatkan elemen input year
                                const yearInput = $(modal_id).find('.year');

                                // memindahkan fokus ke input year
                                yearInput.focus();
                            }
                        });
                        // mendapatkan elemen input week
                        const year = $(modal_id).find('.year');

                        // menambahkan event listener untuk mendeteksi ketika pengguna mengetik
                        year.on('keyup', (event) => {
                            const value = event.target.value;

                            // jika nilai input week memiliki dua digit
                            if (value.length === 2) {
                                // mendapatkan elemen input year
                                const qtyInput = $(modal_id).find('.qty');

                                // memindahkan fokus ke input year
                                qtyInput.focus();
                            }
                        });

                        let qtyModal = $(modal_id).find(".qtyModal").text();
                        $(document).on("click", ".delete-row", function() {
                            $(this).closest("tr").remove();
                            let totalRemRowDin = 0;

                            $(modal_id).find('table tbody tr td .qtyModal_').each(

                                function() {
                                    totalRemRowDin += parseInt($(this).val());
                                });
                            $(modal_id).find(".totalDot").val(totalRemRowDin);
                            console.log(parseInt(stockInWarehouse));
                            console.log(parseInt(totalRemRowDin));
                            if (parseInt(stockInWarehouse) != parseInt(totalRemRowDin)) {
                                $(modal_id).find('.status').html('Stock Don\'t Match');
                                $(modal_id).find('.status').removeClass('badge badge-success');
                                $(modal_id).find('.status').addClass('badge badge-danger');
                                $(modal_id).find('.addRow').attr('hidden', false);
                                $(modal_id).find('.saveBtn').attr('hidden', false);
                            } else if (parseInt(stockInWarehouse) == parseInt(totalRemRowDin)) {
                                $(modal_id).find('.status').html('Stock Match');
                                $(modal_id).find('.status').removeClass('badge badge-danger');
                                $(modal_id).find('.status').addClass('badge badge-success');
                                $(modal_id).find('.addRow').attr('hidden', true);
                                $(modal_id).find('.saveBtn').attr('hidden', true);
                            }

                        });

                        $('.multiSelect').select2({
                            placeholder: 'Select an option',
                            allowClear: true,
                            maximumSelectionLength: 1,
                            width: '100%',
                        });

                        $(modal_id).find("table tbody tr td .qtyModal").on('input',
                            function() {
                                // console.log($(this).val());
                                let totalRow = 0;
                                $(modal_id).find('table tbody tr td .qtyModal_').each(
                                    function() {
                                        totalRow += parseInt($(this).val());
                                    });


                                let sum = 0;
                                $(modal_id).find('.qtyModal').each(function() {
                                    sum += parseInt($(this).val());
                                });

                                let total = totalRow + sum;
                                $(modal_id).find('.totalDot').val(total);
                                if (parseInt(total) >= parseInt(stockInWarehouse)) {
                                    // console.log('stock match');
                                    $(modal_id).find('.addRow').attr('hidden', true);
                                } else {
                                    // console.log('stock don\'t match');
                                    $(modal_id).find('.addRow').attr('hidden', false);

                                }
                                if (parseInt(total) == parseInt(stockInWarehouse)) {
                                    $(modal_id).find('.status').html('Stock Match');
                                    $(modal_id).find('.status').removeClass('badge badge-danger');
                                    $(modal_id).find('.status').addClass('badge badge-success');
                                } else {
                                    $(modal_id).find('.status').html('Stock Don\'t Match');
                                    $(modal_id).find('.status').removeClass('badge badge-success');
                                    $(modal_id).find('.status').addClass('badge badge-danger');

                                }
                                if (parseInt(total) > parseInt(stockInWarehouse)) {
                                    // console.log('stock match');
                                    $(modal_id).find('.saveBtn').addClass('disabled');
                                } else {
                                    // console.log('stock don\'t match');
                                    $(modal_id).find('.saveBtn').removeClass('disabled');

                                }
                            });
                    });
                })

            });
        </script>
        <script>
            $(document).ready(function() {
                // Stock
                let csrf = $('meta[name="csrf-token"]').attr("content");
                let y = 0;
                let warehouse = $('.id_warehouse').val();



                $('.id_warehouse').change(function() {
                    warehouse = $(this).val();
                });
                $('.qty').on('input', function() {
                    let qty = 0;
                    let qtyInWarehouse = $('.qtyInWarehouse').val();
                    $('.qty').each(function() {
                        qty += parseInt($(this).val());
                    });
                    if (qty > qtyInWarehouse) {
                        $('.save').addClass('disabled');

                    } else {
                        $('.save').removeClass('disabled');

                    }
                    if (qty >= qtyInWarehouse) {
                        $('.addTyre').each(function() {
                            $(this).addClass('disabled');

                        });

                    } else {
                        // $('.addTyre').removeClass('disabled');
                        $('.addTyre').each(function() {
                            $(this).removeClass('disabled');
                        });
                        // $('.save').removeClass('disabled');

                    }




                });


                $('.multiSelect').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });
                $(".product-tyre").select2({
                    placeholder: 'Select an product',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        type: "GET",
                        url: "/tyre_dot/select",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term,
                                w: warehouse,
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_sub_material + ' ' + item
                                            .type_name + ' ' +
                                            item.nama_barang,
                                        id: item.products_id,
                                        qty_stock: item.stock,
                                    }, ];
                                }),
                            };
                        },
                    },
                }).on("select2:select", function(e) {
                    var item = e.params.data;
                    var stockQty = item.qty_stock;
                    $("#stock-quantity").val(stockQty); // update the value of the input element
                });





                $(document).on("click", ".remTyre", function() {
                    $(this).parents(".form-group").remove();
                });
            });

            $(document).ready(function() {

                var t = $('#basics').DataTable({
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
                    pageLength: -1,
                    columnDefs: [{
                        searchable: false,
                        orderable: false,
                        targets: 0,
                    }, {
                        searchable: true,
                        orderable: true,
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
    @endpush
@endsection
