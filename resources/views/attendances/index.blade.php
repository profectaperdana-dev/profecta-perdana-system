@extends('layouts.master')
@php
    use Carbon\Carbon;
@endphp
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet" />
        @include('report.style')
        <style>
            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }

            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
            }

            .nav-new {
                display: block;
                padding: 0.5rem 1rem;
                color: #24695c !important;
                text-decoration: none;
                transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out;
            }

            .nav-pills .nav-new.active,
            .nav-pills .show>.nav-new {
                background-color: #d0efe9 !important;
            }

            .flex-fill {
                flex: 1 1 45%;
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
            <div class="col-sm-12">
                <div class="card shadow">
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
                            <div class="col-lg-3 col-12">
                                <label class="col-form-label text-end">Employee</label>
                                <div class="input-group">
                                    <select class="form-select select-employee" name="formEmployee[employee]"></select>
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
                        <div class="mt-5">
                            <div class="row row-cols-1 row-cols-md-3 g-4" id="attendance-list">
                                @if (sizeof($attendances) > 0)
                                    @foreach ($attendances as $item)
                                        @php
                                            // Format the date and time using Carbon
                                            $date = $item->clock_time
                                                ? Carbon::parse($item->clock_time)->format('d M Y')
                                                : 'Invalid Date';
                                            $time = $item->clock_time
                                                ? Carbon::parse($item->clock_time)->format('H:i:s')
                                                : 'Invalid Time';
                                            // Ensure the folder name is in the correct format
                                            $folderName = $item->clock_time
                                                ? Carbon::parse($item->clock_time)->format('d-m-Y')
                                                : 'unknown';
                                            // Construct the Google Maps URL
                                            $googleMapsUrl =
                                                'https://www.google.com/maps?q=' . urlencode($item->location);
                                        @endphp
                                        <div class="col" data-id="{{ $item->user_id }}">
                                            <div class="card h-100 overflow-hidden">
                                                <div class="d-flex justify-content-center align-items-center"
                                                    style="height: 100%; position: relative;">
                                                    @if ($item->photo)
                                                        <a href="{{ asset('https://tracking.profectaperdana.com/public/images/attendances/' . $folderName . '/' . $item->photo) }}"
                                                            target="_blank"
                                                            style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                                            <img src="{{ asset('https://tracking.profectaperdana.com/public/images/attendances/' . $folderName . '/' . $item->photo) }}"
                                                                class="card-img-top" style="max-width: 50%; height: auto;">
                                                        </a>
                                                    @else
                                                        <p>No Photo Available</p>
                                                    @endif
                                                </div>
                                                <div class="card-body portfolioreadmode">
                                                    <h5 class="card-title title fw-bold">
                                                        {{ $item->userBy ? $item->userBy->name : 'N/A' }}
                                                    </h5>
                                                    <p class="card-text date">{{ $date }}</p>
                                                    <p class="card-text clock_time"><span class="badge @if($item->type == 'in') bg-primary @else bg-danger @endif">Clock {{ $item->type }}</span> : {{ $time }} WIB</p>
                                                    <div class="d-flex justify-content-between">
                                                        <!--<p class="card-text type">{{ $item->type }}</p>-->
                                                        <p class="card-text location"><a href="{{ $googleMapsUrl }}"
                                                                target="_blank">View Location</a></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>


        <script>
           $(document).ready(function() {
    // Fungsi untuk memformat tanggal ke dd-mm-yyyy
    function parseDate(date) {
        let day = date.getDate().toString().padStart(2, '0');
        let month = (date.getMonth() + 1).toString().padStart(2, '0');
        let year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }
    
    $('.datepicker-here').datepicker({
        onSelect: function(formattedDate, date, inst) {
            inst.hide();
        },
    });

    // Set tanggal default ke hari ini
    var todayDate = parseDate(new Date());
    $('#from_date').val(todayDate);
    $('#to_date').val(todayDate);

    // Trigger filter otomatis saat halaman pertama kali dimuat
    $('#filter').trigger('click');

    $('.select-employee').select2({
        placeholder: 'Select an employee',
        allowClear: true,
        width: '100%',
        ajax: {
            type: "GET",
            url: "/attendances/get_user/",
            data: function(params) {
                return {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    search: params.term
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
    });

    $('#filter').click(function() {
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var user_id = $('.select-employee').val();

        if (from_date && to_date) {
            $.ajax({
                url: '{{ route('attendances.index') }}',
                method: 'GET',
                data: {
                    from_date: from_date,
                    to_date: to_date,
                    user_id: user_id,
                    ajax: 1
                },
                success: function(response) {
                    var attendanceList = $('#attendance-list');
                    attendanceList.empty(); // Clear the list before appending new data

                    response.data.forEach(function(item) {
                        var date = item.clock_time ? formatDateDisplay(item.clock_time) : 'Invalid Date';
                        var time = item.clock_time ? formatTimeDisplay(item.clock_time) : 'Invalid Time';
                        var folderName = item.clock_time ? formatFolderName(item.clock_time) : 'unknown';
                        var googleMapsUrl = 'https://www.google.com/maps?q=' + encodeURIComponent(item.location);

                        var userByName = item.user_by ? item.user_by.name : 'N/A';

                        var attendanceHtml = `
                            <div class="col" data-id="${item.user_id}">
                                <div class="card h-100 overflow-hidden">
                                    <div class="d-flex justify-content-center align-items-center" style="height: 100%;">
                                        ${item.photo ? `<a href="https://tracking.profectaperdana.com/public/images/attendances/${folderName}/${item.photo}" target="_blank" style="display: flex; justify-content: center; align-items: center; height: 100%;">
                                            <img src="https://tracking.profectaperdana.com/public/images/attendances/${folderName}/${item.photo}" class="card-img-top" style="max-width: 50%; height: auto;">
                                        </a>` : '<p>No Photo Available</p>'}
                                    </div>
                                    <div class="card-body portfolioreadmode">
                                        <p class="card-title title fw-bold">${userByName}</p>
                                        <p class="card-text date">${date}</p>
                                        <p class="card-text clock_time"><span class="badge ${item.type == 'in' ? 'bg-primary' : 'bg-danger'}">Clock ${item.type}</span> : ${time} WIB</p>
                                        <div class="d-flex justify-content-between">
                                            <p class="card-text location"><a href="${googleMapsUrl}" target="_blank">View Location</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        attendanceList.append(attendanceHtml);
                    });
                },
                error: function(error) {
                    console.log('Error fetching data:', error);
                }
            });
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

    function formatDateDisplay(clockTime) {
        return new Date(clockTime).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
    }

    function formatTimeDisplay(clockTime) {
        return new Date(clockTime).toLocaleTimeString('en-GB', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    function formatFolderName(clockTime) {
        let date = new Date(clockTime);
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0'); // Month is zero-based
        let year = date.getFullYear();
        return `${day}-${month}-${year}`;
    }

    $('#refresh').click(function() {
        $('#from_date').val(parseDate(new Date()));
        $('#to_date').val(parseDate(new Date()));
        $('#filter').trigger('click');
    });
});

        </script>
    @endpush
@endsection
