@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }
        </style>
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
            <div class="col-sm-12">
                <div class="card shadow">

                    <div class="card-body">

                        <div class="row">
                            <div class="col-6 col-lg-6">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button type="button" class="btn text-white btn-primary form-control mb-3 addItem"
                                        data-bs-toggle="modal" data-bs-target="#staticBackdrop">Create New CoA</button>
                                </div>
                            </div>
                            <div class="col-6 col-lg-6">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <button type="button" class="btn text-white btn-primary form-control mb-3 changeSaldo"
                                        data-bs-toggle="modal" data-bs-target="#changeSaldo">Change Saldo</button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="warehouse" id="warehouse" multiple class="form-control selectMulti">
                                        @foreach ($warehouse as $row)
                                            <option @if ($row->id == 1) selected @endif
                                                value="{{ $row->id }}">
                                                {{ $row->warehouses }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Year</label>
                                <div class="input-group">
                                    <select name="year[]" id="year" multiple class="form-control selectMulti">
                                        @php
                                            $currentYear = date('Y');
                                            $startYear = $currentYear - 5;
                                            $years = range($currentYear, $startYear);
                                        @endphp

                                        @foreach ($years as $year)
                                            <option {{ $currentYear == $year ? 'selected' : '' }}
                                                value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
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
                                        id="refresh">Refresh</button>
                                </div>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table border="1" id="dataTable"
                                class="table table-striped row-border order-column table-sm" style="width:100%">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="align-middle" style="width: 5%">#</th>
                                        <th rowspan="2" class="text-center align-middle">Account</th>
                                        <th rowspan="2" class="text-center align-middle">&nbsp;</th>
                                        <th rowspan="1" colspan="2" class="text-center align-middle">Saldo</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center align-middle">Debit</th>
                                        <th class="text-center align-middle">Kredit</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <form class="storeChangeSaldo" action="{{ url('finance/coa/store-saldo') }}" method="post">
        @method('POST')
        @csrf
        <div class="modal fade" id="changeSaldo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">

                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">Change Saldo |
                            @php
                                $lastDayOfLastYear = date('d/m/Y', strtotime('last day of December last year'));
                            @endphp
                            Silahkan masukkan saldo awal per tanggal <span class="text-success">
                                {{ $lastDayOfLastYear }}</span>

                        </h6>
                    </div>
                    <div class="modal-body" style="font-size: 10pt">
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label>Warehouse</label>
                                <select name="warehouse_id" multiple required class="form-control multiple warehouse">
                                    @foreach ($warehouse as $row)
                                        <option value="{{ $row->id }}">{{ $row->warehouses }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="tableSaldo" hidden class="col-12">
                                <table border="1"
                                    class="tableSaldo table table-striped row-border order-column table-sm"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="align-middle" style="width: 5%">#</th>
                                            <th rowspan="2" class="text-center align-middle ">Account</th>
                                            <th rowspan="2" class="text-center align-middle">&nbsp;</th>
                                            <th rowspan="1" colspan="2" class="text-center align-middle">Saldo</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center align-middle">Debit</th>
                                            <th class="text-center align-middle">Kredit</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th id="footerDebit"></th>
                                        <th id="footerKredit"></th>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-warning">Reset Saldo</button>
                        <button type="button" class="btn btn-danger hideModalChangeSaldo" autocomplete="off"
                            data-bs-dismiss="modal">Close</button>
                        <button id="buttonSaldo" type="submit" disabled class="btn btn-primary">
                            Update Saldo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form class="needs-validation addCoaCategory" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">Create CoA Category</h6>
                    </div>
                    <div class="modal-body" style="font-size: 10pt">
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label>Name</label>
                                <input autocomplete="off" name="name" required type="text" class="form-control">
                            </div>
                            <div class="mb-3 col-12">
                                <label>Category</label>
                                <select name="coa_category_id" multiple required class="form-control multiple category">
                                    @foreach ($category as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-12">
                                <label>Code</label>
                                <input autocomplete="off" name="coa_code" required type="text"
                                    class="form-control code">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="">Detail</label>
                                <textarea class="form-control" name="detail" id="" cols="30" rows="1" required>-</textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="">Description</label>
                                <textarea class="form-control" name="description" id="" cols="30" rows="1" required>-</textarea>
                            </div>
                            @foreach ($warehouse as $item)
                                <div class="col-4">
                                    <label for="">Warehouse</label>
                                    <input type="text" class="form-control" value="{{ $item->warehouses }}" readonly>
                                    <input type="hidden" class="form-control" value="{{ $item->id }}"
                                        name="coa_saldo[{{ $loop->index }}][warehouse_id]">
                                </div>
                                <div class="col-8 mb-3">
                                    <label for="">Start Balance</label>
                                    <input type="text" class="form-control start_balance" value="0">
                                    <input type="hidden" class="form-control start_balance_" value="0"
                                        name="coa_saldo[{{ $loop->index }}][start_balance]">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger hideModalAdd" autocomplete="off"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
        <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>


        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                load_data();
                // table coa category
                function load_data(year = '', warehouse = '') {
                    $('#dataTable').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "bPaginate": false,
                        "bLengthChange": false,
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": true,
                        fixedColumns: {
                            leftColumns: 0,
                            rightColumns: 0
                        },
                        scrollY: 400,
                        scrollX: true,
                        scrollCollapse: true,
                        paging: false,
                        "fixedHeader": true,
                        processing: true,
                        serverSide: true,
                        pageLength: -1,
                        ajax: {
                            url: "{{ url('finance/coa') }}",
                            data: {
                                year: year,
                                warehouse: warehouse,
                            }
                        },
                        columns: [{
                                className: 'text-end fw-bold',
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },

                            {
                                className: '',
                                data: 'coa_code',
                                name: 'coa_code',

                            },
                            {
                                className: '',
                                data: 'name',
                                name: 'name',

                            },

                            {
                                className: 'text-end',
                                data: 'debit',
                                name: 'debit',

                            },
                            {
                                className: 'text-end',
                                data: 'kredit',
                                name: 'kredit',

                            },
                        ],
                        "footerCallback": function(row, data, start, end, display) {
                            var api = this.api();

                            // DEBIT
                            var visibleData = api.column(3).nodes().to$().map(
                                function() {
                                    return $(this).text();
                                }).toArray();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    let raw2 = raw1.join('');
                                    totalPPN += parseInt(raw2);
                                }
                            });


                            // KREDIT
                            var visibleData_ = api.column(4).nodes().to$().map(
                                function() {
                                    return $(this).text();
                                }).toArray();
                            var filteredData_ = visibleData_.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN_ = 0;
                            filteredData_.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    let raw2 = raw1.join('');
                                    totalPPN_ += parseInt(raw2);
                                }
                            });


                            $(api.column(3).footer()).html(totalPPN
                                .toLocaleString());
                            $(api.column(4).footer()).html(totalPPN_
                                .toLocaleString());
                            // Tempatkan total di dalam baris footer
                            $(api.column(0).footer()).html('Total:');
                            $(api.column(1).footer()).html('');
                            $(api.column(2).footer()).html('');
                            $(api.column(3).footer()).html(totalPPN
                                .toLocaleString());
                            $(api.column(4).footer()).html(totalPPN_
                                .toLocaleString());
                        },
                    });
                }

                $('#filter').click(function() {
                    let year = $('#year').val();
                    let warehouse = $('#warehouse').val();
                    $('#dataTable').DataTable().destroy();
                    load_data(year, warehouse);
                });
                $('#refresh').click(function() {
                    $('#year').val(null).trigger('change');
                    $('#warehouse').val(null).trigger('change');
                    $('#dataTable').DataTable().destroy();
                    load_data();
                });
                $('.selectMulti').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                //modal change saldo
                $(document).on('click', '.changeSaldo', function() {
                    let modalChangeSaldo = $(this).attr('data-bs-target')
                    $('.multiple').select2({
                        dropdownParent: modalChangeSaldo,
                        placeholder: 'Choose Warehouse',
                        maximumSelectionLength: 1,
                        width: '100%',
                        allowClear: true,
                    });
                    $(modalChangeSaldo).find('.warehouse').on('change', function() {
                        let warehouseId = $(this).val();
                        if (warehouseId.length !== 0) {
                            $('#tableSaldo').removeAttr('hidden');

                            if ($.fn.DataTable.isDataTable('.tableSaldo')) {
                                $('.tableSaldo').DataTable().destroy();
                            }
                            $('.tableSaldo').DataTable({
                                "language": {
                                    "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                                },
                                caption: `sjdshdjshj`,
                                "lengthChange": false,
                                "bPaginate": false,
                                "bLengthChange": false,
                                "searching": false,
                                "ordering": false,
                                "info": false,
                                "autoWidth": true,
                                fixedColumns: {
                                    leftColumns: 0,
                                    rightColumns: 0
                                },
                                scrollY: 400,
                                scrollX: true,
                                scrollCollapse: false,
                                paging: false,
                                "fixedHeader": true,
                                processing: true,
                                serverSide: true,
                                pageLength: -1,
                                ajax: {
                                    url: "{{ url('finance/coa/coa-saldo') }}",
                                    data: {
                                        warehouse: warehouseId,
                                    }
                                },
                                columns: [{
                                        className: 'text-end fw-bold',
                                        data: 'DT_RowIndex',
                                        name: 'DT_RowIndex'
                                    },

                                    {
                                        className: '',
                                        data: 'coa_code',
                                        name: 'coa_code',

                                    },
                                    {
                                        className: '',
                                        data: 'name',
                                        name: 'name',

                                    },

                                    {
                                        className: 'text-end',
                                        data: 'debit',
                                        name: 'debit',

                                    },
                                    {
                                        className: 'text-end',
                                        data: 'kredit',
                                        name: 'kredit',

                                    },
                                ],
                                "footerCallback": function(row, data, start, end, display) {
                                    var api = this.api();

                                    // DEBIT
                                    var visibleData = api.column(3).nodes().to$().map(
                                        function() {
                                            return $(this).find('input').val();
                                        }).toArray();
                                    var filteredData = visibleData.filter(function(data) {
                                        return data.trim() !== '';
                                    });
                                    var totalPPN = 0;
                                    filteredData.forEach(function(data) {
                                        if (data != '') {
                                            let raw1 = data.split(",");
                                            let raw2 = raw1.join('');
                                            totalPPN += parseInt(raw2);
                                        }
                                    });


                                    // KREDIT
                                    var visibleData_ = api.column(4).nodes().to$().map(
                                        function() {
                                            return $(this).find('input').val();
                                        }).toArray();
                                    var filteredData_ = visibleData_.filter(function(data) {
                                        return data.trim() !== '';
                                    });
                                    var totalPPN_ = 0;
                                    filteredData_.forEach(function(data) {
                                        if (data != '') {
                                            let raw1 = data.split(",");
                                            let raw2 = raw1.join('');
                                            totalPPN_ += parseInt(raw2);
                                        }
                                    });

                                    $(api.column(3).footer()).html(totalPPN
                                        .toLocaleString()).addClass('numberTotalDebit');
                                    $(api.column(4).footer()).html(totalPPN_
                                        .toLocaleString()).addClass('numberTotalKredit');

                                    // Tempatkan total di dalam baris footer
                                    $(api.column(0).footer()).html('Total:');
                                    $(api.column(1).footer()).html('');
                                    $(api.column(2).footer()).html('');
                                    $(api.column(3).footer()).html(totalPPN
                                        .toLocaleString());
                                    $(api.column(4).footer()).html(totalPPN_
                                        .toLocaleString());
                                },
                                initComplete: function() {
                                    $(document).find('.numberSaldoDebit').on('input',
                                        function() {
                                            var selection = window.getSelection()
                                                .toString();
                                            if (selection !== '') {
                                                return;
                                            }
                                            // When the arrow keys are pressed, abort.
                                            if ($.inArray(event.keyCode, [38, 40, 37,
                                                    39
                                                ]) !== -1) {
                                                return;
                                            }
                                            var $this = $(this);
                                            // Get the value.
                                            var input = $this.val();
                                            var input = input.replace(/[^0-9]/g, "");
                                            input = input ? parseInt(input, 10) : 0;
                                            $this.val(function() {
                                                return (input === 0) ? "0" :
                                                    input.toLocaleString(
                                                        "en-EN");
                                            });
                                            $this.next().val(input);

                                            // Hitung ulang totalDebit setiap kali ada perubahan
                                            var cekTotalKredit = 0;
                                            var totalDebit = 0;
                                            $('.numberSaldoDebit').each(function() {
                                                // Mengambil nilai input dan menghapus karakter selain angka
                                                var inputValue = $(this).val()
                                                    .replace(/[^0-9]/g, "");

                                                // Mengonversi nilai menjadi angka dan menambahkannya ke total
                                                totalDebit += inputValue ?
                                                    parseInt(inputValue, 10) :
                                                    0;
                                            });

                                            $('.numberTotalDebit').html(totalDebit
                                                .toLocaleString());

                                            $('.numberSaldoKredit').each(function() {
                                                // Mengambil nilai input dan menghapus karakter selain angka
                                                var inputValue = $(this).val()
                                                    .replace(/[^0-9]/g, "");

                                                // Mengonversi nilai menjadi angka dan menambahkannya ke total
                                                cekTotalKredit += inputValue ?
                                                    parseInt(inputValue, 10) :
                                                    0;
                                            });
                                            if (totalDebit == cekTotalKredit) {
                                                $('#buttonSaldo').removeAttr(
                                                    'disabled');
                                            } else {
                                                $('#buttonSaldo').attr('disabled',
                                                    true);
                                            }


                                        });
                                    $(document).find('.numberSaldoKredit').on('input',
                                        function() {
                                            var selection = window.getSelection()
                                                .toString();
                                            if (selection !== '') {
                                                return;
                                            }
                                            // When the arrow keys are pressed, abort.
                                            if ($.inArray(event.keyCode, [38, 40, 37,
                                                    39
                                                ]) !== -1) {
                                                return;
                                            }
                                            var $this = $(this);
                                            // Get the value.
                                            var input = $this.val();
                                            var input = input.replace(/[^0-9]/g, "");
                                            input = input ? parseInt(input, 10) : 0;
                                            $this.val(function() {
                                                return (input === 0) ? "0" :
                                                    input.toLocaleString(
                                                        "en-EN");
                                            });
                                            $this.next().val(input);

                                            // Hitung ulang totalDebit setiap kali ada perubahan
                                            var cekTotalDebit = 0;
                                            var totalKredit = 0;
                                            $('.numberSaldoKredit').each(function() {
                                                // Mengambil nilai input dan menghapus karakter selain angka
                                                var inputValue = $(this).val()
                                                    .replace(/[^0-9]/g, "");

                                                // Mengonversi nilai menjadi angka dan menambahkannya ke total
                                                totalKredit += inputValue ?
                                                    parseInt(inputValue, 10) :
                                                    0;
                                            });
                                            $('.numberSaldoDebit').each(function() {
                                                // Mengambil nilai input dan menghapus karakter selain angka
                                                var inputValue = $(this).val()
                                                    .replace(/[^0-9]/g, "");

                                                // Mengonversi nilai menjadi angka dan menambahkannya ke total
                                                cekTotalDebit += inputValue ?
                                                    parseInt(inputValue, 10) :
                                                    0;
                                            });
                                            if (totalKredit == cekTotalDebit) {
                                                $('#buttonSaldo').removeAttr(
                                                    'disabled');
                                            } else {
                                                $('#buttonSaldo').attr('disabled',
                                                    true);
                                            }

                                            $('.numberTotalKredit').html(totalKredit
                                                .toLocaleString());
                                            // console.log(totalKredit);
                                        });
                                }
                            }).ajax.reload();
                        } else {
                            $('#tableSaldo').attr('hidden', true);
                            // $('.tableSaldo').DataTable().destroy();
                        }
                    })
                })

                // modal add
                $(document).on("click", ".addItem", function(event) {
                    let modal_id = $(this).attr('data-bs-target');
                    const csrf = $('meta[name="csrf-token"]').attr('content');
                    $('.multiple').select2({
                        dropdownParent: modal_id,
                        placeholder: 'Choose Category',
                        maximumSelectionLength: 1,
                        width: '100%',
                        allowClear: true,
                    });
                    $('.start_balance').on('input', function() {
                        let hargaInput = $(this).val().replace(/,/g, '');
                        let hargaPisah = hargaInput.split('.');
                        let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString('en', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: hargaPisah.length > 1 ? hargaPisah[1]
                                .length : 0
                        });
                        $(this).val(hargaFloat);
                        $('.start_balance_').val(hargaInput);
                    });
                    $('.category').on('change', function() {
                        const category_id = $(this).val();
                        $.ajax({
                            context: this,
                            type: "GET",
                            url: "finance/coa/get_category/" + category_id,
                            data: {
                                _token: csrf,
                            },
                            dataType: "json",
                            delay: 250,
                            success: function(data) {
                                $('.code').val(data.code);
                            },
                            error: function(XMLHttpRequest, textStatus, errorThrown) {},
                        });
                    });
                });


                // add coa category
                $(document).on('submit', '.addCoaCategory', function(event) {
                    event.preventDefault();
                    let form_add = new FormData($(this)[0]);
                    let formElement = $(this);
                    let button = formElement.find('button[type="submit"]');
                    button.prop('disabled', true);
                    $.ajax({
                        url: "{{ url('finance/coa/store') }}",
                        type: "POST",
                        dataType: "JSON",
                        data: form_add, // send FormData object as data
                        processData: false, // prevent jQuery from processing the data
                        contentType: false, // prevent jQuery from setting the content type
                        success: function(data) {
                            swal("Success !", data.message, "success", {
                                button: "Close",
                            });
                            button.prop('disabled', false);
                            $('.hideModalAdd').click();
                            $('.dataTable').DataTable().ajax.reload();
                            formElement[0].reset();
                            // validator.reload();
                        },
                        error: function(data) {
                            swal("Success !", data.message, "error", {
                                button: "Close",
                            });
                            button.prop('disabled', false);
                        }
                    });
                });

                // edit coa category
                $(document).on('submit', '.editItemPromotion', function(event) {
                    event.preventDefault();
                    let form_edit = new FormData(this);
                    let formElement = $(this);
                    let button = formElement.find('button[type="submit"]');
                    button.prop('disabled', true);
                    let id = $(this).find('#id').val();
                    $.ajax({
                        url: `{{ url('finance/coa/${id}/update') }}`,
                        type: "POST",
                        dataType: "JSON",
                        data: form_edit,
                        processData: false, // Ensure FormData is not processed
                        contentType: false, // Ensure FormData is not set as content type
                        success: function(data) {
                            swal("Success !", data.message, "success", {
                                button: "Close",
                            });
                            $('.dataTable').DataTable().ajax.reload();
                            button.prop('disabled', false);
                            $('.hideModalEdit').click();
                            // validator.reload();
                        },
                        error: function(data) {
                            swal("Success !", "fail to saved data", "error", {
                                button: "Close",
                            });
                            button.prop('disabled', false);

                        }
                    });
                });
            });

            $(function() {
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: false,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
            });
            $(document).on('click', '.delete-item', function(event) {
                event.preventDefault();
                var itemId = $(this).data('id');
                var url = `{{ url('material-promotion/${itemId}/delete') }}`;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "id": itemId,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        swal("Success !", data.message, "success", {
                            button: "Close",
                        });
                        $('.dataTable').DataTable().ajax.reload();
                        $('.hideModalEdit').click();
                    },
                    error: function(data) {
                        swal("Success !", "fail to saved data", "error", {
                            button: "Close",
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
