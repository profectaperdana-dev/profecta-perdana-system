@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }

            tr.ref-123 {
                background-color: #ff0000;
                /* Red background for rows with ref value 123 */
            }

            tr.ref-456 {
                background-color: #00ff00;
                /* Green background for rows with ref value 456 */
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
                <div class="card">
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="post"
                            action="{{ url('/finance/journal/store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row font-weight-bold " id="formTradeIn">
                                        <div class="form-group row">
                                            <div class="col-md-4 form-group">
                                                <label class="text-black">
                                                    Date
                                                </label>
                                                <input class="datepicker-here form-control digits"
                                                    data-position="bottom left" type="text" data-language="en"
                                                    id="exp_date" data-value="{{ date('d-m-Y') }}" name="date"
                                                    autocomplete="off">
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="text-black">
                                                    Warehouse
                                                </label>
                                                <select name="warehouse" multiple required
                                                    class="form-control multiple category">
                                                    @foreach ($warehouse as $item)
                                                        <option value="{{ $item->id }}">{{ $item->warehouses }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4 form-group">
                                                <label class="text-black">
                                                    Department
                                                </label>
                                                <select name="department" multiple required class="form-control department">
                                                    @foreach ($department as $item)
                                                        <option value="{{ $item }}">{{ $item }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-12 col-lg-12 form-group">
                                                <label class="font-weight-bold">Memo</label>
                                                <textarea class="form-control text-capitalize" required id="" cols="30" rows="3" name="memo"></textarea>
                                            </div>
                                        </div>
                                        <div
                                            class="mx-auto py-2 form-group row rounded nodeParent"style="background-color: #c7d7b9">
                                            <div class="form-group col-12 col-lg-3">
                                                <label class="font-weight-bold">Account</label>
                                                <select id="accountList" name="accountFields[0][account]" required
                                                    class="account form-control text-capitalize required" multiple>
                                                    @foreach ($coa as $item)
                                                        <option value="{{ $item->coa_code }}">
                                                            ({{ $item->coa_code }})
                                                            {{ $item->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group m-checkbox-inline mb-0 col-lg-3 text-center">
                                                <label for="">Type</label>
                                                <br>
                                                <div class="radio radio-primary text-white mt-2">
                                                    <input id="radioinline1" type="radio" name="accountFields[0][type]"
                                                        value="debit" checked>
                                                    <label class="mb-0" for="radioinline1">Debit</label>
                                                </div>
                                                <div class="radio radio-primary mt-2">
                                                    <input id="radioinline2" type="radio" name="accountFields[0][type]"
                                                        value="credit">
                                                    <label class="mb-0" for="radioinline2">Credit</label>
                                                </div>

                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Ref</label>
                                                <input type="text" required name="accountFields[0][ref]"
                                                    class="form-control ref text-capitalize" placeholder="Enter Ref.">

                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Total</label>
                                                <input type="text" required class="total form-control text-capitalize"
                                                    placeholder="Enter Total">
                                                <input type="hidden" value="0" name="accountFields[0][total]"
                                                    class="total_">
                                            </div>
                                            <div class="col-2 col-lg-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control text-white addfields text-center"
                                                    style="border:none; background-color:green">+</a>
                                            </div>


                                        </div>



                                    </div>
                                    <div class="my-2">
                                        <label for="">Need Adjusting?</label>
                                        {{-- <div class="form-check">
                                            <input class="form-check-input" name="isAdjusted" type="checkbox"
                                                value="1">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                Adjusted
                                            </label>
                                        </div> --}}

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                name="isAdjusted">
                                            <label class="form-check-label" for="flexSwitchCheckDefault">Adjusting</label>
                                        </div>


                                    </div>

                                </div>

                                <div class="form-group">
                                    <a class="btn btn-danger" href="{{ url('sales_order/') }}"> <i
                                            class="ti ti-arrow-left">
                                        </i> Back
                                    </a>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Expenses Data</h5>
                        <hr class="bg-primary">
                        <div class="row justify-content-end">
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-group row col-12">
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">Start Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="from_date"
                                        data-value="{{ date('d-m-Y') }}" name="from_date" autocomplete="off">

                                </div>
                            </div>
                            <div class="col-lg-4 col-12">
                                <label class="col-form-label text-end">End Date</label>
                                <div class="input-group">
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="to_date"
                                        data-value="{{ date('d-m-Y') }}" name="to_date" autocomplete="on">
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
                        <div>
                            <ul>
                                <li class="text-dark">Background <i class="fa-solid fa-square"
                                        style="color: lightblue"></i> :
                                    Need Adjusting</li>
                            </ul>

                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-sm table-borderless" style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th class="d-none">id</th>
                                        <th class="">Revisi</th>
                                        <th class="d-none">Adjusted</th>
                                        <th>Warehouse</th>
                                        <th>Account</th>
                                        <th>Date</th>
                                        <th>ref</th>
                                        <th>Type</th>
                                        <th>Total</th>
                                        <th>Created By</th>
                                        <th>Department</th>
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
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                $('.multiple').select2({
                    placeholder: 'Choose Warehouse',
                    maximumSelectionLength: 1,
                    width: '100%',
                    allowClear: true,
                });
                $('.department').select2({
                    placeholder: 'Choose Department',
                    maximumSelectionLength: 1,
                    width: '100%',
                    allowClear: true,
                });
                $('.account').select2({
                    placeholder: 'Choose CoA',
                    maximumSelectionLength: 1,
                    width: '100%',
                    allowClear: true,
                });

                $('.datepicker-here').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
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
                // Get the current date


                // Set the value of the input element
                document.querySelector('#exp_date').value = parseDate(new Date());
                document.querySelector('#from_date').value = parseDate(new Date());
                document.querySelector('#to_date').value = parseDate(new Date());

                //isi Ref
                // Lakukan permintaan Ajax saat halaman dimuat
                $.ajax({
                    url: "{{ url('/finance/journal/get/ref') }}", // Ganti dengan URL yang benar
                    method: "GET", // Ganti dengan metode HTTP yang sesuai
                    success: function(data) {
                        $('.category').on('change', function() {
                            // Set nilai yang diterima dari server ke dalam input
                            // Mendapatkan tanggal saat ini
                            const currentDate = new Date();

                            // Mendapatkan tahun saat ini
                            const currentYear = new Date().getFullYear().toString().slice(-2);

                            // Mendapatkan bulan saat ini (dalam bentuk angka, dimulai dari 0 untuk Januari hingga 11 untuk Desember)
                            const currentMonth = currentDate.getMonth() +
                                1; // Perlu ditambah 1 karena indeks bulan dimulai dari 0

                            const selected_warehouse = $(this).val()[0];
                            let converted_warehouse = '';
                            if (selected_warehouse == '1') {
                                converted_warehouse = '01';
                            } else if (selected_warehouse == '8') {
                                converted_warehouse = '02';
                            } else if (selected_warehouse == '22') {
                                converted_warehouse = '03'
                            } else {
                                converted_warehouse = '--';
                            }

                            console.log(data);

                            // Gunakan ekspresi reguler untuk mencocokkan semua digit dari paling belakang hingga karakter bukan nol pertama
                            
                            let match = data.length > 0 ? data.ref.match(/(\d*[^0])$/) : null;

                            // Ambil hasil dari grup pertama dalam pencocokan
                            let result = match ? match[1] : 0;
                            const iteration = (parseInt(result) + 1).toString().padStart(6, '0');

                            const number =
                                `${converted_warehouse}-${currentYear}.${currentMonth}.${iteration}`;
                            $(".ref").val(number);
                        });
                    },
                    error: function(error) {
                        console.error("Error fetching data:", error);
                    }
                });

                //* format total
                y = 0;
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
                        return (input === 0) ? "" : input.toLocaleString();
                    });
                    $this.next().val(input);
                });

                let accountList = $('#accountList').html();
                // console.log(accountList);

                $(document).on("click", ".addfields", function() {
                    ++y;
                    var randomChar = String.fromCharCode(97 + Math.floor(Math.random() * 26));
                    var randomChar2 = String.fromCharCode(97 + Math.floor(Math.random() * 26));
                    let selected_ref = $('.ref').val();
                    let form = `<div class="mx-auto py-2 form-group row rounded nodeParent" style="background-color: #c7d7b9">
                    <div class="form-group col-12 col-lg-3">
                                                <label class="font-weight-bold">Account</label>
                                                <select name="accountFields[${y}][account]"  multiple
                                                    class="account form-control text-capitalize required">
                                                   ${accountList}

                                                </select>
                                            </div>
                                            <div class="form-group m-checkbox-inline mb-0 col-lg-3 text-center">
                                                <label for="">Type</label>
                                                <br>
                                                <div class="radio radio-primary text-white mt-2">
                                                    <input id="radioinline${randomChar}" type="radio" name="accountFields[${y}][type]"
                                                        value="debit" checked>
                                                    <label class="mb-0" for="radioinline${randomChar}">Debit</label>
                                                </div>
                                                <div class="radio radio-primary mt-2">
                                                    <input id="radioinline${randomChar2}" type="radio" name="accountFields[${y}][type]"
                                                        value="credit">
                                                    <label class="mb-0" for="radioinline${randomChar2}">Credit</label>
                                                </div>

                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Ref</label>
                                                <input type="text" required name="accountFields[${y}][ref]" class="form-control text-capitalize"
                                                    placeholder="Enter Ref." value="${selected_ref}">
                                            </div>
                                            <div class="col-10 col-lg-2 form-group">
                                                <label class="font-weight-bold">Total</label>
                                                <input type="text" required class="total form-control text-capitalize"
                                                    placeholder="Enter Total">
                                                <input type="hidden" value="0" name="accountFields[${y}][total]" class="total_">
                                            </div>
                                            <div class="col-2 col-lg-1 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control text-white addfields text-center"
                                                    style="border:none; background-color:green">+</a>
                                            </div>
                                            <div class="col-2 col-lg-1 form-group">
                                                <label for="">&nbsp;</label>
                                                <a href="javascript:void(0)"
                                                    class="form-control text-white remfields text-center"
                                                    style="border:none; background-color:red">-</a>
                                            </div>
                                            </div>
                                         `;

                    $("#formTradeIn").append(form);
                    $(function() {

                        let validator = $('form.needs-validation').jbvalidator({
                            errorMessage: true,
                            successClass: true,
                            language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                        });

                        validator.reload();
                    })
                    $('.account').select2({
                        placeholder: 'Choose CoA',
                        maximumSelectionLength: 1,
                        width: '100%',
                        allowClear: true,
                    });

                    //* format discount rupiah
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
                            return (input === 0) ? "" : input.toLocaleString();
                        });
                        $this.next().val(input);

                    });
                    $(document).on("click", ".remfields", function() {
                        $(this).parents(".form-group").remove();
                    });
                });

                // load data from server
                load_data();

                function load_data(from_date = '', to_date = '') {
                    $('#example1').DataTable({
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
                        destroy: true,
                        ajax: {
                            url: "{{ url('/finance/journal/create') }}",
                            data: {
                                from_date: from_date,
                                to_date: to_date
                            }
                        },
                        columns: [

                            {
                                className: "text-nowrap d-none",
                                data: 'id',
                                name: 'id'

                            },
                            {
                                data: 'revisi',
                                name: 'revisi',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['id'] === meta.settings.aoData[
                                            meta.row - 1]._aData['id']) {
                                        return '';
                                    }
                                    return data;
                                }

                            },
                            {
                                className: "text-nowrap d-none",
                                data: 'isadjusted',
                                name: 'isadjusted'

                            },
                            {
                                data: 'warehouse',
                                name: 'warehouse'

                            },
                            {
                                className: "text-nowrap",
                                data: 'account_id',
                                name: 'account_id'

                            },
                            {
                                className: "text-nowrap text-center",
                                data: 'date',
                                name: 'date',
                            },

                            {
                                className: "text-center text-nowrap",
                                data: 'ref',
                                name: 'ref',
                            },

                            {
                                className: "text-center",
                                data: 'type',
                                name: 'type',
                            }, {
                                className: "text-center text-nowrap",
                                data: 'total',
                                name: 'total',
                            },
                            {
                                className: "text-center text-nowrap",
                                data: 'created_by',
                                name: 'created_by',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['id'] === meta.settings.aoData[
                                            meta.row - 1]._aData['id']) {
                                        return '';
                                    }
                                    return data;
                                }

                            }, {
                                className: "text-center text-nowrap",
                                data: 'department',
                                name: 'department',
                                render: function(data, type, row, meta) {
                                    if (meta.row > 0 && row['id'] === meta.settings.aoData[
                                            meta.row - 1]._aData['id']) {
                                        return '';
                                    }
                                    return data;
                                }

                            }
                        ],
                        // createdRow: function(row, data, dataIndex) {
                        //     // Dapatkan nilai kolom 'ref' untuk baris saat ini
                        //     var refValue = data.ref;
                        //     console.log(refValue)
                        //     // Periksa apakah nilai 'ref' tidak ditentukan
                        //     if (refValue !== undefined) {
                        //         console.log(dataIndex);
                        //         // Periksa apakah ini baris terakhir dalam grup yang memiliki nilai 'ref' yang sama
                        //         var isLastRow = dataIndex === this.api().rows({ search: 'applied' }).indexes().toArray().pop();

                        //         // if (isLastRow) {
                        //         //     // Tambahkan baris penutup untuk kelompok yang sama
                        //         //     var closingRow = this.api().row.add({
                        //         //         // Kolom-kolom yang sesuai dengan struktur tabel
                        //         //         warehouse: '',
                        //         //         account_id: '',
                        //         //         date: '',
                        //         //         ref: '',
                        //         //         type: '',
                        //         //         total: '',
                        //         //     }).draw(false).node();

                        //         //     // Tambahkan kelas ke baris penutup
                        //         //     $(closingRow).addClass('bg-danger');
                        //         // }
                        //     }
                        // },
                        drawCallback: function(settings) {
                            var api = this.api();
                            var rows = api.rows({
                                page: 'current'
                            }).nodes();
                            var firstRef = api.column(0, {
                                page: 'current'
                            }).data()[0];
                            var lastRef = firstRef;

                            api.column(0, {
                                page: 'current'
                            }).data().each(function(id, index) {
                                // console.log(id);
                                if (lastRef !== id) {
                                    lastRef = id;
                                    $(rows[index - 1]).after(
                                        '<tr><td class="bg-dark p-1" colspan="9"></td></tr>'
                                    );
                                }
                            });

                            api.column(2, {
                                page: 'current'
                            }).data().each(function(id, index) {
                                // console.log(id);
                                if (id == 1) {
                                    $(rows[index]).find('td').css('background-color', 'lightblue');
                                    // do something
                                }
                            });


                        }

                    });

                }

                // filter data
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

                // refresh data
                $('#refresh').click(function() {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#example1').DataTable().destroy();
                    load_data();
                });

                $(document).on("click", ".modal-btn", function(event) {
                    let csrf = $('meta[name="csrf-token"]').attr("content");

                    let modal_id = $(this).attr('data-bs-target');
                    $(modal_id).find('.total-in').on('keyup', function() {
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
                            return (input === 0) ? "" : input.toLocaleString();
                        });
                        $this.next().val(input);

                    });
                });
            });
        </script>
        <script>
            $(function() {

                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });

                validator.reload();
            });
        </script>
    @endpush
@endsection
