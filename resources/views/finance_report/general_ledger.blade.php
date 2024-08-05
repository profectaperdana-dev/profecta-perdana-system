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
                                <label class="col-form-label text-end">Account</label>
                                <div class="input-group">
                                    <select name="account" id="account" multiple class="form-control selectMulti">
                                        @foreach ($all_account as $account)
                                            <option value="{{ $account['coa_code'] }}">
                                                {{ $account['coa_code'] }} - {{ $account['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-12 mb-2">
                                <label class="col-form-label text-end">Warehouse</label>
                                <div class="input-group">
                                    <select name="warehouse" id="warehouse" multiple class="form-control selectMulti"
                                        required>
                                        @foreach ($all_warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}"
                                                @if ($warehouse->id == 1) selected @endif>
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
                            <div class="col-6 col-lg-4">
                                <label class="col-form-label text-end">&nbsp;</label>
                                <div class="input-group">
                                    <input type="hidden" value="{{ $grouped_jurnal->count() }}" id="count">
                                    <button id="exportToExcel" class="btn text-white form-control btn-primary">Export
                                        All</button>
                                </div>
                            </div>
                        </div>


                        <hr class="fw-bold text-success">
                        <div id="jurnal-parent">
                            @foreach ($grouped_jurnal as $jurnal)
                                <div class="mb-5 ">
                                    <div class="row justify-content-between p-2 mb-2">
                                        <div class="col-4">
                                            <div class="fw-bold account-name">Account Name:
                                                {{ $account_name[$jurnal->coa_code] }}
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold">Account Code: {{ $jurnal->coa_code }}
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ $jurnal->coa_code }}" class="code">
                                    <div class="table-responsive">
                                        <table
                                            class=" table-bordered table-sm text-capitalize table-striped bg-light text-black"
                                            style="width:100%;border:1px solid black !important;"
                                            id="example{{ $loop->index }}">
                                            <thead class="text-center">
                                                <tr>
                                                    <th rowspan="2">Date</th>
                                                    <th rowspan="2">Memo</th>
                                                    <th rowspan="2">Ref</th>
                                                    <th rowspan="2">Debit</th>
                                                    <th rowspan="2">Credit</th>
                                                    <th colspan="2">Saldo</th>
                                                </tr>
                                                <tr>
                                                    <th>Debit</th>
                                                    <th>Credit</th>
                                                </tr>

                                            </thead>
                                            <tbody style="">
                                                <tr>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                    <td>-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
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

                function parseDate(date) {
                    let now = date;
                    // Format the date as "dd-mm-yyyy"
                    let day = now.getDate().toString().padStart(2, '0');
                    let month = (now.getMonth() + 1).toString().padStart(2, '0');
                    let year = now.getFullYear();
                    let formattedDate = `${day}-${month}-${year}`;
                    return formattedDate;
                }
                document.querySelector('input[name="from_date"]').value = parseDate(new Date());
                document.querySelector('input[name="to_date"]').value = parseDate(new Date());


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();


                function load_data(from_date = '', to_date = '', warehouse = '', acc_id = '', show = 'all') {
                    const length = $('#count').val();
                    let n = 0;
                    var tableData = [];



                    $('.code').each(function() {
                        // console.log($(this).val());
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
                                url: "{{ url('/finance/report/general_ledger/table') }}",
                                data: {
                                    from_date: from_date,
                                    to_date: to_date,
                                    warehouse: warehouse,
                                    acc_id: $(this).val(),
                                    show: show
                                }
                            },
                            columns: [{
                                    data: 'date',
                                    name: 'date',

                                },
                                {
                                    data: 'memo',
                                    name: 'memo',

                                },
                                {
                                    data: 'ref',
                                    name: 'ref',

                                },
                                {
                                    className: 'text-end fw-bold text-success',
                                    data: 'debit',
                                    name: 'debit',
                                },
                                {
                                    className: 'text-end fw-bold text-danger',
                                    data: 'credit',
                                    name: 'credit',
                                },
                                {
                                    className: 'text-end fw-bold text-success',
                                    data: 'sub_debit',
                                    name: 'sub_debit',
                                },
                                {
                                    className: 'text-end fw-bold text-danger',
                                    data: 'sub_credit',
                                    name: 'sub_credit',
                                },


                            ],
                            order: [

                            ],
                            dom: 'Bfrtip',
                            pageLength: -1,
                            buttons: [{
                                extend: 'excel',
                                text: '<i class="fa fa-download"></i>',
                                exportOptions: {
                                    columns: ':visible'
                                }
                            }],

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
                    var headerAdded = false;
                    $('.code').each(function() {
                        var dataTable = $('#example' + k).DataTable();
                        var data = dataTable.rows().data()
                            .toArray(); // Get all rows data (including hidden ones)
                        data = data.map(row => [row.coa_code, row.date, row.memo, row.ref, row.debit, row
                            .credit, row.sub_debit, row.sub_credit
                        ]); // Filter out only the desired columns

                        if (data.length > 0) {
                            if (!headerAdded) {
                                allData.push(["Account ID: " + data[0][
                                    0
                                ]]); // Add the header row with the account_id for the first group
                                allData.push([AllNameAccount[k]]);
                                allData.push(["Account Code", "Date", "Memo", "Ref", "Debit", "Credit",
                                    "Saldo Debit", "Saldo Kredit"
                                ]); // Add the header row for the columns
                                headerAdded = true;
                            } else {
                                allData.push([""]); // Add an empty row to separate groups

                                allData.push(["Account ID: " + data[0][
                                    0
                                ]]); // Add the header row with the account_id for subsequent groups
                                allData.push([AllNameAccount[k]]);
                                allData.push(["Account Code", "Date", "Memo", "Ref", "Debit", "Credit",
                                    "Saldo Debit", "Saldo Kredit"
                                ]); // Add the header row for the columns
                            }

                            allData = allData.concat(data); // Concatenate the data for the current group
                        }
                        k++;
                    });

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

                    // Step 5: Save the Excel file
                    var fileName = "General Ledger.xlsx";
                    saveExcelFile(excelBuffer, fileName);
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
                    var from_date = $('#from_date').val();
                    var to_date = $('#to_date').val();
                    var acc_code = $('#account').val();
                    let warehouse = $('#warehouse').val();
                    let show = $('#show').val()[0];
                    // console.log(show);
                    if (from_date != '' && to_date != '' && warehouse != '') {
                        $('#warp-table').DataTable().destroy();

                        $.ajax({
                            type: "GET",
                            url: "{{ url('/finance/report/general_ledger') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date,
                                acc_code: acc_code,
                                warehouse: warehouse,
                                show: show
                            },
                            dataType: "json",
                            success: function(data) {
                                console.log(data.grouped_jurnal);
                                let new_table = ``;
                                data.grouped_jurnal.forEach((element, index) => {
                                    $('#example' + index).DataTable().destroy();
                                    new_table += `<div class="mb-5 ">
                                        <div class="row justify-content-between p-2 mb-2">
                                            <div class="col-4">
                                                <div class="fw-bold account-name">Account Name: ${ data.account_name[element.coa_code] }
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="fw-bold">Account Code: ${element.coa_code }</div>
                                            </div>
                                        </div>
                                        <input type="hidden" value="${ element.coa_code }" class="code">
                                        <div class="table-responsive">
                                            <table class=" table-bordered text-capitalize table-striped bg-light text-black"
                                                style="width:100%;border:1px solid black !important;"
                                                id="example${index}">
                                                <thead class="text-center">
                                                    <tr>
                                                        <th rowspan="2">Date</th>
                                                        <th rowspan="2">Memo</th>
                                                        <th rowspan="2">Ref</th>
                                                        <th rowspan="2">Debit</th>
                                                        <th rowspan="2">Credit</th>
                                                        <th colspan="2">Saldo</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                    </tr>

                                                </thead>
                                                <tbody style="">

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>`;

                                    $('#jurnal-parent').html(new_table);

                                });
                                load_data(from_date, to_date, warehouse, acc_code, show);
                            },
                        });


                    } else {
                        alert('Date and Warehouse are required');
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
