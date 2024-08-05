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

            /* .table-bordered {
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                border-width: 10px !important;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            } */
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
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
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
                                        name="to_date" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="warehouse" id="warehouse" multiple class="form-control selectMulti">
                                        @foreach ($all_warehouses as $warehouse)
                                            <option @if ($warehouse->id == 1) selected @endif
                                                value="{{ $warehouse->id }}">
                                                {{ $warehouse->warehouses }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Show</label>
                                <div class="input-group">
                                    <select name="show" id="show" multiple class="form-control selectMulti">
                                        <option value="all" selected>
                                            All
                                        </option>
                                        <option value="non-zero">
                                            Non-zero
                                        </option>
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
                        {{-- <input type="hidden" value="{{ $nameHeader->count() }}" id="count"> --}}
                        <button id="exportToExcel" class="btn btn-primary my-3"><i class="fa fa-download"></i></button>
                        <div id="journalParent">
                            @foreach ($nameHeader as $key => $item)
                                <input type="hidden" class="code" value="{{ $key }}">
                                <div class="table-responsive">
                                    <table id="example{{ $loop->index }}"
                                        class="table table-sm table-borderless table-striped" style="width:100%">
                                        <thead>
                                            <tr class="text-center">
                                                <th class="account-name">{{ $nameHeader[$key] }}</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr class="text-center table-info">
                                                <th>Total {{ $nameHeader[$key] }}</th>
                                                <th class="account-total t-{{ $key }}"
                                                    data-group="{{ $nameHeader[$key] }}"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <br>
                                <br>
                                @if ($key == '666')
                                    <div class="table-responsive">
                                        <table id="dirty_profit" class="table table-sm table-borderless table-striped"
                                            style="width:100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>Laba Kotor</th>
                                                    <th class="dirty-profit"></th>

                                                </tr>
                                            </thead>


                                        </table>
                                    </div>
                                    <br>
                                    <br>
                                @endif
                            @endforeach
                        </div>

                        <div class="table-responsive">
                            <table id="example3" class="table table-sm table-borderless table-striped" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>Laba bersih sebelum pajak</th>
                                        <th class="profit"></th>

                                    </tr>
                                </thead>


                            </table>
                        </div>
                    </div>


                </div>
            </div>
        </div>

    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.4.1/papaparse.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <!-- Add the Papaparse library for parsing CSV data -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>
        <!-- Add the SheetJS library for exporting to Excel -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.3/xlsx.full.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(document).ready(function() {
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

                function getStartOfYear() {
                    let now = new Date();
                    let year = now.getFullYear();
                    return new Date(year, 0, 1); // Month is zero-based, so 0 represents January
                }

                function getEndOfYear() {
                    let now = new Date();
                    let year = now.getFullYear();
                    return new Date(year, 11, 31); // Month is zero-based, so 11 represents December
                }

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


                // Set the value of the input element
                document.querySelector('input[name="from_date"]').value = parseDate(getStartOfYear());
                document.querySelector('input[name="to_date"]').value = parseDate(getEndOfYear());


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // console.log('{{ $all_account }}');

                load_data();
                // console.log($('#pendapatan').html());


                function load_data(from_date = '', to_date = '', warehouse = '', show = 'all') {
                    let n = 0;
                    $('.code').each(function() {
                        let table = $('#example' + n).DataTable({
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
                            destroy: true,
                            processing: true,
                            serverSide: true,
                            pageLength: -1,
                            ajax: {
                                url: "{{ url('/finance/report/profit_loss') }}",
                                data: {
                                    from_date: from_date,
                                    to_date: to_date,
                                    warehouse: warehouse,
                                    acc_id: $(this).val(),
                                    show: show
                                },

                            },
                            columns: [{
                                    data: 'acc_name',
                                    name: 'acc_name',

                                },
                                {
                                    className: 'text-end fw-bold text-success',
                                    data: 'sub_total',
                                    name: 'sub_total',

                                },
                                // {
                                //     className: 'text-end fw-bold text-success',
                                //     data: 'total',
                                //     name: 'total',

                                // },

                            ],
                            order: [

                            ],
                            footerCallback: function(row, data, start, end, display) {
                                var api = this.api();
                                // DEBIT
                                var visibleData1 = api.column(0).nodes().to$().map(function() {
                                    return $(this).text();
                                }).toArray();
                                var visibleColumns1 = api.columns().visible();
                                var filteredData1 = visibleData1.filter(function(data) {
                                    return data.trim() !== '';
                                });
                                var array_name = [];
                                filteredData1.forEach(function(data) {
                                    // console.log(data);
                                    if (data != '-') {
                                        array_name.push(data);
                                    }
                                });
                                var visibleData = api.column(1).nodes().to$().map(function() {
                                    return $(this).text();
                                }).toArray();
                                var visibleColumns = api.columns().visible();
                                var filteredData = visibleData.filter(function(data) {
                                    return data.trim() !== '';
                                });
                                if (array_name.includes("Persediaan Akhir")) {
                                    let t_persediaan = $('.t-333').text().replace(/,/g, '');
                                    let v_persediaan_akhir = 0;
                                    filteredData.forEach(function(data, i) {
                                        // console.log(data);
                                        if (data != '-') {
                                            let raw1 = data.split(",");
                                            raw2 = raw1.join('');
                                            // console.log(filteredData);
                                            v_persediaan_akhir += parseInt(raw2);

                                        }
                                    });
                                    let t_hpp = t_persediaan - v_persediaan_akhir;
                                    $(api.column(1).footer()).html(Math.abs(t_hpp).toLocaleString(
                                        'en', {}));
                                } else {

                                    var totalPPN = 0;
                                    filteredData.forEach(function(data, i) {
                                        // console.log(data);
                                        if (data != '-') {
                                            let raw1 = data.split(",");
                                            raw2 = raw1.join('');
                                            // console.log(filteredData);
                                            if (array_name[i] == "Retur Penjualan" ||
                                                array_name[i] == "Diskon Penjualan" ||
                                                array_name[i] == "Retur Pembelian") {
                                                totalPPN -= parseInt(raw2);
                                            } else {
                                                totalPPN += parseInt(raw2);
                                            }

                                        }
                                    });

                                    $(api.column(1).footer()).html(totalPPN.toLocaleString(
                                        'en', {}));
                                }


                            },
                            initComplete: function() {
                                // initComplete logic
                                // You can update the 'profit' element here
                                let current_profit = $('.profit').text();
                                let dirty_profit = $('.dirty-profit').text();

                                if (current_profit == '' || current_profit == undefined) {
                                    current_profit = 0
                                } else {
                                    current_profit = parseFloat(current_profit.replace(/,/g, ''))
                                }

                                if (dirty_profit == '' || dirty_profit == undefined) {
                                    dirty_profit = 0
                                } else {
                                    dirty_profit = parseFloat(dirty_profit.replace(/,/g, ''))
                                }

                                let new_profit = ''
                                let new_dirty_profit = '';


                                if ($(this).find('.account-total').attr('data-group') ==
                                    "Pendapatan") {
                                    new_dirty_profit = parseFloat($(this).find('.account-total')
                                        .text()
                                        .replace(/,/g,
                                            ''));
                                } else if ($(this).find('.account-total').attr('data-group') ==
                                    "Harga Pokok Penjualan") {
                                    new_dirty_profit -= parseFloat($(this).find('.account-total')
                                        .text()
                                        .replace(/,/g,
                                            ''));
                                }

                                $('.dirty-profit').text(parseFloat(dirty_profit + new_dirty_profit)
                                    .toLocaleString('en', {}));

                                if ($(this).find('.account-total').attr('data-group') ==
                                    "Biaya Operasional") {
                                    new_profit = parseFloat($(this).find('.account-total')
                                        .text()
                                        .replace(/,/g,
                                            ''));
                                    console.log('Operasional: ' + new_profit);
                                } else if ($(this).find('.account-total').attr('data-group') ==
                                    "Biaya Non Operasional") {
                                    new_profit += parseFloat($(this).find('.account-total')
                                        .text()
                                        .replace(/,/g,
                                            ''));
                                    console.log('non-Operasional: ' + new_profit);
                                }
                                // console.log('profit: ' + (current_profit + new_profit));

                                let final_profit = dirty_profit - (new_profit);
                                if (final_profit < 0) {
                                    final_profit = "-" + Math.abs(final_profit).toLocaleString(
                                        'en', {});
                                } else {
                                    final_profit = final_profit.toLocaleString(
                                        'en', {});
                                }
                                // console.log(final_profit)
                                $('.profit').text(final_profit);
                                // Example: Set 'profit' to a constant value
                                // console.log($('.account-total').text());

                                let showAll = show === 'all';

                                // Iterate through each row and hide rows with sub_total <= 0 if show is not 'all'
                                $(this).find('tbody tr').each(function() {
                                    let subTotal = parseFloat($(this).find(
                                            'td.text-end.fw-bold.text-success').text()
                                        .replace(/,/g, ''));
                                    if (!showAll && subTotal == 0) {
                                        $(this).hide();
                                    }
                                });
                            },
                        });
                        n++;
                    });
                }

                // Function to concatenate and export the DataTables to Excel
                function exportTablesToExcel() {
                    var allData = [];
                    let AllNameAccount = [];
                    // console.log($('#jurnal-parent').html());
                    $('.account-name').each(function() {
                        AllNameAccount.push($(this).text());
                    });

                    let k = 0;
                    let profit = 0;
                    var headerAdded = false;
                    $('.code').each(function() {
                        var dataTable = $('#example' + k).DataTable();
                        var data = dataTable.rows().data()
                            .toArray(); // Get all rows data (including hidden ones)
                        data = data.map(row => [row.coa_code, row.name, row
                            .sub_total
                        ]); // Filter out only the desired columns
                        var columns = dataTable.columns().data().toArray();

                        // console.log(data);
                        if (data.length > 0) {
                            var total = calculateTotal(data); // Calculate total for the current group

                            if (!headerAdded) {

                                // Add the header row with the account_id for the first group
                                allData.push([AllNameAccount[k]]);
                                allData.push(["Account Code", "Account Name",
                                    "Sub-total"
                                ]); // Add the header row for the columns
                                headerAdded = true;
                            } else {
                                allData.push([""]); // Add an empty row to separate groups

                                // Add the header row with the account_id for subsequent groups
                                allData.push([AllNameAccount[k]]);
                                allData.push(["Account Code", "Account Name",
                                    "Sub-total"
                                ]); // Add the header row for the columns
                            }

                            allData = allData.concat(data); // Concatenate the data for the current group
                            allData.push(["Total", "", total]); // Add the total row for the current group
                            if (AllNameAccount[k] == "Pendapatan") {
                                profit += parseFloat(total.replace(/,/g, ''));
                            } else {
                                profit -= parseFloat(total.replace(/,/g, ''));
                            }

                        }
                        k++;
                    });

                    allData.push(["Laba bersih sebelum pajak", "", profit.toLocaleString('en', {})]);

                    // Step 2: Create a new worksheet
                    var worksheetData = allData;
                    var worksheet = XLSX.utils.aoa_to_sheet(worksheetData);

                    // Step 3: Create a new workbook and add the worksheet to it
                    var workbook = XLSX.utils.book_new();
                    XLSX.utils.book_append_sheet(workbook, worksheet, "Merged Data");

                    // Step 4: Export the workbook to Excel
                    var excelBuffer = XLSX.write(workbook, {
                        bookType: "xlsx",
                        type: "array"
                    });

                    function formatDate(date) {
                        // Split the date string into day, month, and year components
                        let dateParts = date.split('-');

                        // Create a new Date object using the year, month, and day components
                        let dateObject = new Date(dateParts[2], dateParts[1] - 1,
                            dateParts[0]);

                        // Format the date as "yyyy-mm-dd"
                        let year = dateObject.getFullYear();
                        let month = (dateObject.getMonth() + 1).toString().padStart(2,
                            '0');
                        let day = dateObject.getDate().toString().padStart(2, '0');
                        let formattedDate = `${day}-${month}-${year}`;

                        return formattedDate;
                    }

                    var from_date = formatDate($('#from_date').val());
                    var to_date = formatDate($('#to_date').val());
                    let warehouse = $('#warehouse option:selected').text().trim();
                    if (warehouse == '') {
                        warehouse = 'AllWarehouse'
                    }

                    // Step 5: Save the Excel file
                    var fileName = `ProfitLoss_(${from_date} to ${to_date}_${warehouse}).xlsx`;
                    saveExcelFile(excelBuffer, fileName);
                }

                function calculateTotal(data) {
                    var total = 0;
                    for (var i = 0; i < data.length; i++) {
                        total += parseFloat(data[i][2].replace(/,/g, '')) ||
                            0; // Assuming the total is in the third column (index 2)
                    }
                    return total.toLocaleString('en', {});
                }

                // Function to save the Excel file
                function saveExcelFile(buffer, fileName) {
                    var blob = new Blob([buffer], {
                        type: "application/octet-stream"
                    });
                    var url = URL.createObjectURL(blob);
                    var a = document.createElement("a");
                    a.href = url;
                    a.download = fileName;
                    a.click();

                    // Clean up the URL object
                    setTimeout(function() {
                        URL.revokeObjectURL(url);
                    }, 100);
                }

                // Attach the function to the button click event
                document.getElementById("exportToExcel").addEventListener("click", exportTablesToExcel);
                $('#filter').click(function() {
                    // console.log('hellooo');
                    $('.profit').text('');
                    $('.dirty-profit').text('');
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    var warehouse = $('#warehouse').val();
                    let show = $('#show').val()[0];
                    // var acc_code = $('#account').val();
                    if (from_date != '' && to_date != '') {
                        let m = 0;
                        $('.code').each(function() {
                            $('#example' + m).DataTable().destroy();
                            m++;
                        });
                        load_data(from_date, to_date, warehouse, show)
                        // console.log($('.account-total').text());
                        // $('#warp-table').DataTable().destroy();

                        // $.ajax({
                        //     type: "GET",
                        //     url: "/general_ledger",
                        //     data: {
                        //         from_date: from_date,
                        //         to_date: to_date,
                        //         acc_code: acc_code,
                        //         warehouse: warehouse
                        //     },
                        //     dataType: "json",
                        //     success: function(data) {
                        //         // console.log(data.grouped_jurnal);
                        //         let new_table = ``;
                        //         data.grouped_jurnal.forEach((element, index) => {
                        //             $('#example' + index).DataTable().destroy();
                        //             new_table += `<div class="mb-5 ">
                //                 <div class="row justify-content-between p-2 mb-2">
                //                     <div class="col-4">
                //                         <div class="fw-bold account-name">Account Name: ${ data.account_name[element.account_id] }
                //                         </div>
                //                     </div>
                //                     <div class="col-4">
                //                         <div class="fw-bold">Account Code: ${element.account_id }</div>
                //                     </div>
                //                 </div>
                //                 <input type="hidden" value="${ element.account_id }" class="code">
                //                 <div class="table-responsive">
                //                     <table class=" table-bordered text-capitalize table-striped bg-light text-black"
                //                         style="width:100%;border:1px solid black !important;"
                //                         id="example${index}">
                //                         <thead class="text-center">
                //                             <tr>
                //                                 <th rowspan="2">Date</th>
                //                                 <th rowspan="2">Memo</th>
                //                                 <th rowspan="2">Ref</th>
                //                                 <th rowspan="2">Debit</th>
                //                                 <th rowspan="2">Credit</th>
                //                                 <th colspan="2">Saldo</th>
                //                             </tr>
                //                             <tr>
                //                                 <th>Debit</th>
                //                                 <th>Credit</th>
                //                             </tr>

                //                         </thead>
                //                         <tbody style="">

                //                         </tbody>
                //                     </table>
                //                 </div>
                //             </div>`;

                        //             $('#jurnal-parent').html(new_table);

                        //         });
                        //         load_data(from_date, to_date, warehouse);
                        //     },
                        // });


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
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });
                $(document).on("click", ".modal-btn2", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");
                    let modal_id = $(this).attr('data-bs-target');
                    $(function() {

                        let validator = $('form.needs-validation').jbvalidator({
                            errorMessage: true,
                            successClass: true,
                            language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                        });

                        validator.reload();
                    })
                    $(modal_id).find(".role-acc, .job-acc, .warehouse-acc").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });
                    $('.total').on('keyup', function() {
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


                    $(".account").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });
                });
            });
        </script>
    @endpush
@endsection
