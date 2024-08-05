@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
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
                        <a class="btn btn-primary" href="{{ url('/asset/create') }}">
                            + Create Asset
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic3" class="table table-sm table-hover" style="width:100%">
                                <thead>
                                    <tr class="text-nowrap text-center">
                                        <th>No.</th>
                                        <th>#</th>
                                        <th>QR</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Qty</th>
                                        {{-- <th>Lifetime (In Month)</th>
                                        <th>Year of Acquisition</th>
                                        <th>Cost of Acquisition (Rp)</th>
                                        <th>Maintenance Date</th>
                                        <th>Maintenance Distance</th>
                                        <th>Next Maintenance Date</th>
                                        <th>Status</th> --}}
                                        <th>Created By</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @foreach ($assets as $key => $value)
                                        <tr class="text-nowrap">
                                            <td>{{ $key + 1 }}</td>
                                            <td> {!! QrCode::size(100)->generate(url('asset/information/' . $value->id)) !!}</td>
                                            <td>{{ $value->asset_code }}</td>
                                            <td>{{ $value->asset_name }}</td>
                                            <td>{{ $value->amount }}</td>
                                            <td class="text-center">{{ $value->lifetime }}</td>
                                            <td class="text-center">
                                                {{ date('d M Y', strtotime($value->acquisition_year)) }}</td>
                                            <td class="text-end">{{ number_format($value->acquisition_cost, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                @if ($value->service_date == null)
                                                    <span class="badge bg-danger">Not Set</span>
                                                @else
                                                    {{ date('d M Y', strtotime($value->service_date)) }}
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $value->range }}
                                            </td>
                                            <td class="text-center">
                                                @if ($value->next_service == null)
                                                    <span class="badge bg-danger">Not Set</span>
                                                @else
                                                    {{ date('d M Y', strtotime($value->next_service)) }}
                                                @endif
                                            <td>
                                                {{ $value->status }}
                                            </td>
                                            <td>{{ $value->createdBy->name }}</td>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    @canany(['level1', 'level2'])
                                                        <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#editData{{ $value->id }}">Edit</a>
                                                    @endcanany
                                                    @can('level1')
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @foreach ($assets as $value)
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $(document).on('submit', 'form', function() {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');
                    // console.log(form.html());

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);

                    }
                });

                let csrf = $('meta[name="csrf-token"]').attr("content");

                function parseDate(date) {
                    let splitted = date.split('-');
                    return splitted[2] + '-' + splitted[1] + '-' + splitted[0];
                }

                const format = (d) => {
                    let cost = parseInt(d.acquisition_cost).toLocaleString('en', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: 0
                    });
                    let status = (d.status == 1) ? 'Active' : 'Non-active';
                    // console.log(typeof cost);
                    return `
                            <div style="margin-left:2px;" class="row col-lg-3 card shadow">
                                    <table class="table fw-bold" style="border:0;" border="0">
                                        <tr>
                                            <td>Year of Acquisition</td>
                                            <td>:</td>
                                            <td>${parseDate(d.acquisition_year)}</td>
                                        </tr>
                                        <tr>
                                            <td>Cost of Acquisition (Rp)</td>
                                            <td>:</td>
                                            <td>${cost}</td>
                                        </tr>
                                        <tr>
                                            <td>Lifetime (In Month)</td>
                                            <td>:</td>
                                            <td>${d.lifetime}</td>
                                        </tr>
                                        <tr>
                                            <td>Maintenance Date</td>
                                            <td>:</td>
                                            <td>${parseDate(d.service_date)}</td>
                                        </tr>
                                        <tr>
                                            <td>Maintenance Distance</td>
                                            <td>:</td>
                                            <td>${d.range}</td>
                                        </tr>
                                        <tr>
                                            <td>Next Maintenance Date</td>
                                            <td>:</td>
                                            <td>${parseDate(d.next_service)}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>:</td>
                                            <td>${status}</td>
                                        </tr>
                                    </table>
                            </div>
                        `;
                };
                load_data();

                function load_data() {
                    $('#basic3').DataTable({
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
                        ajax: {
                            url: "{{ url('/asset') }}",
                            // data: {
                            //     from_date: from_date,
                            //     to_date: to_date
                            // }
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center fw-bold align-middle",
                                orderable: false,
                                searchable: false
                            }, {
                                data: null,
                                orderable: false,
                                searchable: false,
                                className: 'details-control',
                                defaultContent: '<i data-feather="plus"></i>'
                            },
                            {
                                className: 'text-center',
                                data: 'qr',
                                name: 'qr',
                            },
                            {
                                className: 'text-center align-middle',
                                data: 'asset_code',
                                name: 'asset_code'
                            },
                            {
                                className: 'align-middle',
                                data: 'asset_name',
                                name: 'asset_name',
                            },
                            {
                                className: 'text-center align-middle',
                                data: 'amount',
                                name: 'amount',
                            },
                            {
                                className: 'align-middle',
                                data: 'created_by',
                                name: 'created_by',
                            },
                            {
                                className: 'align-middle',
                                data: 'action',
                                name: 'action',
                                orderable: true,
                                searchable: true
                            },
                        ],

                        initComplete: function() {
                            var table = $('#basic3').DataTable();
                            $(document).find('#basic3 tbody').off().on('click', 'td.details-control',
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

                $(document).on('click', '.modal-btn', function() {
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
                    let modal_id = $(this).attr('data-bs-target');
                    let customer_id = $(modal_id).find('.modal-body').find('.id').val();
                    let node_form = $(modal_id).find('.modal-body').find('.total-credit');

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/customers/getTotalCredit/" + customer_id,
                        dataType: "json",
                        success: function(data) {
                            node_form.val(data.toLocaleString('id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }));
                        },
                    });

                });
            });
        </script>
    @endpush
@endsection
