@extends('layouts.master')
@section('content')
    @push('css')
        @include('report.style')

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.min.css"
            integrity="sha512-ELV+xyi8IhEApPS/pSj66+Jiw+sOT1Mqkzlh8ExXihe4zfqbWkxPRi8wptXIO9g73FSlhmquFlUOuMSoXz5IRw=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/gh/dubrox/Multiple-Dates-Picker-for-jQuery-UI@master/jquery-ui.multidatespicker.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/themes/eggplant/theme.min.css"
            integrity="sha512-W7T9CmbGyR3T8S8gHkzLXMbXbP9tzYYKAQXM9x4C8OkDwGZd+NTsJvUAghZQdMW8Wkq5hr+bojzHdtuW2yaahA=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        {{-- <style>
            .red span {
                background-color: red !important;
                color: white !important;
                pointer-events: initial !important;
            }

            .green span {
                background-color: green !important;
                color: white !important;
                pointer-events: initial !important;
            }
        </style> --}}
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create {{ $title }} </h6>
                </div>

            </div>
        </div>
    </div>
    {{-- @php
        dd($arr_days);
    @endphp --}}
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Leave Additional Proposal Form</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" method="POST" enctype="multipart/form-data" novalidate
                            action="{{ url('additional_leave/store_additional_vacation') }}" enctype="multipart/form-data">
                            @method('POST')
                            @csrf
                            @include('additional_vacation.form_additional')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmationModal" data-bs-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <h4 id="selectedDateText"></h4>
                    <hr>
                    <center>
                        <button type="button" data="full" class="btn btn-block btn-primary cekButton">Full Day</button>
                        <button type="button" data="half" class="btn btn-block btn-secondary cekButton">Half
                            Day</button>
                    </center>
                </div>
            </div>
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
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>

        <script>
            $(function() {
                // Initialize validator
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });

                // Handle form submission
                $('form').submit(function(e) {
                    // Prevent form submission
                    e.preventDefault();

                    // Disable submit button to prevent multiple submissions
                    let submitButton = $(this).find('button[type="submit"]');
                    submitButton.prop('disabled', true);

                    // Check if validation passes
                    if (validator.checkAll() == 0) {
                        // If validation passes, submit the form
                        this.submit();
                    } else {
                        // If validation fails, enable submit button again
                        submitButton.prop('disabled', false);
                    }
                });

                // Handle "Back" button click
                $('.btn-back').click(function(e) {
                    e.preventDefault();
                    history.back();
                });
            });
        </script>

        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var csrf = $('meta[name="csrf-token"]').attr('content');

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

                //Show Toast function
                function showToast(status = "", message = "") {
                    $('#statustoast').text(status);
                    $('#messagetoast').text(message);
                    $('#liveToast').show();
                };

                var x = 0;
                $(document).on("click", ".addEmployee", function() {
                    x++;
                    let form = `<div class="row" 
                                <div class="col-12 col-lg-12 mx-auto form-group rounded">
                                        <div class="col-6 col-lg-4">
                                            <label>Employee</label>
                                            <select name="formEmployee[${x}][employee]" class="form-control select-employee" multiple></select>
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label for="">&nbsp;</label>
                                            <a href="javascript:void(0)" class="form-control addEmployee text-white text-center" style="border:none;background-color:#276e61">+</a>
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label for=""> &nbsp; </label>
                                            <a href="#" class="form-control text-white rem text-center" style="border:none; background-color:#d94f5c">-</a>
                                        </div>
                                </div>
                                </div>`;

                    $("#formEmployee").append(form);

                    // Inisialisasi Select2 pada elemen select-employee yang baru ditambahkan
                    $("#formEmployee").find('.select-employee:last').select2({
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                        ajax: {
                            context: this,
                            type: "GET",
                            url: "/trip/get-employee/",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return {
                                            id: item.id,
                                            text: item.name
                                        };
                                    })
                                };
                            },
                        },
                    }).on('select2:select', function(e) {
                        var selectedOption = $(this).select2('data')[0];
                    });

                    x++; // Tambahkan 1 ke nilai x setiap kali menambahkan formulir
                });

                $("#formEmployee").find('.select-employee').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                    ajax: {
                        context: this,
                        type: "GET",
                        url: "/trip/get-employee/",
                        data: function(params) {
                            return {
                                _token: csrf,
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return {
                                        id: item.id,
                                        text: item.name
                                    };
                                })
                            };
                        },
                    },
                })




                $(document).on('click', '.rem', function() {
                    $(this).closest('.row').remove()
                })


                function load_data(employee = '') {

                    $('#dataTable').DataTable({
                        "scrollX": true,
                        scrollY: 350,
                        "scrollCollapse": true,
                        "paging": false,
                        "searching": true,
                        "ordering": false,
                        "info": false,
                        "autoWidth": true,
                        "responsive": true,
                        "pageLength": -1,
                        "destroy": true,
                        processing: true,
                        serverSide: true,
                        ajax: {
                            url: "{{ url('/leave/') }}",
                            data: {
                                employee: employee
                            }
                        },
                        columns: [{
                                width: '5%',
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center",
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: null,
                                orderable: false,
                                searchable: false,
                                className: 'details-control',
                                defaultContent: '<i data-feather="plus"></i>'
                            },
                            {
                                data: 'user_id',
                                name: 'user_id'

                            },

                            {
                                className: 'text-nowrap',
                                data: 'date_range',
                                name: 'date_range'

                            },
                            {
                                className: 'text-center',
                                data: 'count_days',
                                name: 'count_days'

                            },
                            {
                                className: 'text-center',
                                data: 'remaining',
                                name: 'remaining'

                            },
                            {
                                className: 'text-center',
                                data: 'status',
                                name: 'status',
                            },
                            {
                                className: '',
                                data: 'action',
                                name: 'action',
                                orderable: false,
                            },
                        ],
                        drawCallback: function(settings) {
                            // Kode yang akan dijalankan setelah DataTable selesai dikerjakan
                            $('#thisModal').html('');
                            $('.currentModal').each(function() {
                                let currentModal = $(this).html();
                                $(this).html('');
                                $('#thisModal').append(currentModal);
                            });

                            // console.log($('#currentModal').html());
                            // Lakukan tindakan lain yang Anda inginkan di sini
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
                                        row.child(format(row.data())).show();
                                        tr.addClass('shown');
                                    }
                                });
                        },

                    });

                }

                $('#refresh').click(function() {
                    $('#from_date').val(parseDate(new Date()));
                    $('#to_date').val(parseDate(new Date()));
                    $('#example1').DataTable().destroy();
                    load_data();
                });


                $(function() {
                    let validator = $('form.needs-validation').jbvalidator({
                        errorMessage: true,
                        successClass: false,
                        language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                    });
                });
            });
        </script>
    @endpush
@endsection
