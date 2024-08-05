@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link href="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css" rel="stylesheet">

        <link rel="stylesheet"
            href="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.css"
            type="text/css" />

        @include('report.style')
        <style>
            .custom-popup {
                width: 1000px;
            }

            .marker {
                background-image: url('https://akademitrainer.com/wp-content/uploads/2017/12/map-marker-at.png');
                /* Ganti dengan URL gambar marker Anda */
                background-size: cover;
                width: 50px;
                height: 50px;
                cursor: pointer;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }}
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
                        <a class="btn btn-primary" href="{{ url('/customers/create') }}">
                            + Create Customers
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic2" class="table table-striped display expandable-table text-capitalize"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Area</th>
                                        <th class="text-center">City</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($customers as $key => $value)
                                        <tr>

                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->name_cust }}</td>
                                            <td>{{ $value->area_name }}</td>
                                            <td>{{ $value->city }}</td>
                                            <td>{{ $value->phone_cust }}</td>
                                            <td>
                                                @if ($value->status == 1)
                                                    <div class="badge badge-success">Active</div>
                                                @else
                                                    <div class="badge badge-danger">Nonactive</div>
                                                @endif
                                            </td>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#detailData{{ $value->id }}">Detail</a>
                                                    @canany(['level1', 'level2'])
                                                        <a class="dropdown-item"
                                                            href="{{ url('/customers/' . $value->id . '/edit') }}">Edit</a>
                                                    @endcanany
                                                    @can('level1')
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                    @endcan
                                                </div>
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
    @foreach ($customers as $key => $value)
        {{-- Modul Detail --}}
        <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Data
                            {{ $value->nama_barang }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                {{-- <div class="col-md-4 font-weight-bold mb-5">
                                    <label>Customer Reference Image</label>
                                    <img width="100%" class="img-fluid shadow-lg"
                                        src="{{ url('public/images/customers/' . $value->reference_image) }}"
                                        alt="Preview Image">
                                </div> --}}
                                <input class="id" type="hidden" value="{{ $value->id }}" readonly>

                                <div class="form-group row font-weight-bold">
                                    <div class="form-group col-md-4">
                                        <label>Customer Code</label>
                                        <input type="text" class="form-control" placeholder="Customer Code" readonly
                                            value="{{ $value->code_cust }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Customer Name</label>
                                        <input type="text" class="form-control " placeholder="Customer Name" readonly
                                            value="{{ $value->name_cust }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Customer ID Card
                                            Number</label>
                                        <input type="text" class="form-control " placeholder="Customer ID Card" readonly
                                            value="{{ $value->id_card_number }}">
                                    </div>
                                </div>

                                <div class="form-group row font-weight-bold">
                                    <div class="form-group col-md-3">
                                        <label>Province</label>
                                        <input type="text" class="form-control" name="" id=""
                                            value="{{ $value->province }}" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>City</label>
                                        <input type="text" class="form-control" name="" id=""
                                            value="{{ $value->city }}" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>District</label>
                                        <input type="text" class="form-control" name="" id=""
                                            value="{{ $value->district }}" readonly>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Village</label>
                                        <input type="text" class="form-control" name="" id=""
                                            value="{{ $value->village }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row font-weight-bold">
                                    <div class="form-group col-md-6">
                                        <label>Customer Address</label>
                                        <textarea class="form-control" rows="3" readonly>{{ $value->address_cust }}</textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Customer NPWP</label>
                                        <input type="text" class="form-control" placeholder="Customer NPWP"
                                            value="{{ $value->npwp }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row font-weight-bold">
                                    <div class="form-group col-md-6">
                                        <label>
                                            Office Phone Customer</label>
                                        <input type="text" class="form-control" placeholder="Serial Number" readonly
                                            value="{{ $value->office_number }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>
                                            Phone Customer</label>
                                        <input type="text" class="form-control" placeholder="Serial Number" readonly
                                            value="{{ $value->phone_cust }}">
                                    </div>
                                </div>
                                <div class="form-group row font-weight-bold">
                                    <div class="form-group col-md-4">
                                        <label>
                                            Customer Email</label>
                                        <input type="text" class="form-control" placeholder="Serial Number" readonly
                                            value="{{ $value->email_cust }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>
                                            Customer Category</label>
                                        <input type="text" class="form-control" placeholder="Serial Number" readonly
                                            value="{{ $value->category_name }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Customer Area
                                        </label>
                                        <input type="text" readonly class="form-control"
                                            value="{{ $value->area_name }}">
                                    </div>
                                </div>

                                <div class="form-group row font-weight-bold">
                                    <div class="form-group col-md-4">
                                        <label>Coordinate</label>
                                        <input type="text" class="form-control coordinate" readonly
                                            value="{{ $value->coordinate }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Credit Limit</label>
                                        <input type="text" class="form-control credit-limit" readonly
                                            value="{{ number_format($value->credit_limit, 0, ',', '.') }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Total Credit</label>
                                        <input type="text" class="form-control total-credit" readonly value="">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-3">
                                        <label>Store Building Image</label>
                                        <div class="mb-1 text-break">Uploaded
                                            File: <a class="link-success "
                                                href="{{ url('public/images/customers/' . $value->reference_image) }}"
                                                onclick="window.open(this.href, '_blank', 'width=600,height=400'); return false;">{{ $value->reference_image }}</a>
                                        </div>

                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>ID Card Image</label>
                                        <div class="mb-1 text-break">Uploaded File: <a class="link-success"
                                                href="{{ url('public/images/customers/ktp/' . $value->id_card_image) }}"
                                                onclick="window.open(this.href, '_blank', 'width=600,height=400'); return false;">{{ $value->id_card_image }}</a>
                                        </div>

                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>NPWP Image</label>
                                        <div class="mb-1 text-break">Uploaded File: <a class="link-success"
                                                href="{{ url('public/images/customers/npwp/' . $value->npwp_image) }}"
                                                onclick="window.open(this.href, '_blank', 'width=600,height=400'); return false;">{{ $value->npwp_image }}</a>
                                        </div>

                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Selfie with Owner Image</label>
                                        <div class="mb-1 text-break">Uploaded File: <a class="link-success"
                                                href="{{ url('public/images/customers/selfie/' . $value->selfie_image) }}"
                                                onclick="window.open(this.href, '_blank', 'width=600,height=400'); return false;">{{ $value->selfie_image }}</a>
                                        </div>

                                    </div>
                                </div>

                                <div class="form-group row font-weight-bold">
                                    <div class="form-group col-md-3">
                                        <label>Last Transaction</label>
                                        <input type="text" class="form-control" readonly
                                            value="@if ($value->last_transaction == null) No Transaction @else {{ date('d-M-Y', strtotime($value->last_transaction)) }} @endif">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Due Date</label>
                                        <input type="text" class="form-control" readonly
                                            value="{{ $value->due_date }}">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Label</label>
                                        <br>
                                        @if ($value->label == 'Prospect')
                                            <span class="badge badge-pill badge-secondary text-white">
                                                Prospect</span>
                                        @elseif($value->label == 'Customer')
                                            <span class="badge badge-pill badge-primary">
                                                Customer</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">Bad
                                                Customer</span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>Status</label>
                                        <br>
                                        @if ($value->status == 1)
                                            <h1 class="badge badge-pill badge-success">
                                                Active</h1>
                                        @else
                                            <span class="badge badge-pill badge-danger">Nonactive</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row font-weight-bold">
                                    <div class="form-group col-md-4">
                                        <label>Status Overdue</label>
                                        <br>
                                        @if ($value->isOverDue == 0)
                                            <h1 class="badge badge-pill badge-success">
                                                No</h1>
                                        @else
                                            <span class="badge badge-pill badge-danger">Yes</span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Status OverPlafond</label>
                                        <br>
                                        @if ($value->isOverPlafoned == 0)
                                            <h1 class="badge badge-pill badge-success">
                                                No</h1>
                                        @else
                                            <span class="badge badge-pill badge-danger">Yes</span>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="form-group row">
                                <div class="form-group col">
                                    <div id="peta{{ $value->id }}" style="width: 100%; height: 500px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Modal Detail --}}

        @can('level1')
            {{-- Modul Delete UOM --}}
            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{ url('customers/' . $value->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('delete')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                    {{ $value->code_cust }}</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container-fluid">
                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <h5>Are you sure delete this data ?</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary" type="submit">Yes, delete
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            {{-- End Modal Delete UOM --}}
        @endcan
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script src="https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js"></script>
        <script src="https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-directions/v4.1.0/mapbox-gl-directions.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
        <script>
            $(document).ready(function() {
                $(document).on('submit', 'form', function() {
                    // console.log('click');
                    var form = $(this);
                    var button = form.find('button[type="submit"]');
                    // console.log(form.html());

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);

                    }
                });

                $('#basic2').DataTable({
                    "pageLength": 100,
                    dom: 'Bfrtip',
                    lengthMenu: [
                        [10, 25, 50, -1],
                        ['10 rows', '25 rows', '50 rows', 'Show All']
                    ],
                    buttons: ['pageLength',
                        {
                            title: 'Customer Report Data',
                            messageTop: '<h5>{{ $title }} ({{ date('l H:i A, d F Y ') }})</h5><br>',
                            messageBottom: '<strong style="color:red;">*Please select only the type of column needed when printing so that the print is neater</strong>',
                            extend: 'print',
                            orientation: 'landscape',
                            pageSize: 'legal',
                            rowsGroup: [0],
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

                let csrf = $('meta[name="csrf-token"]').attr("content");

                $(document).on('click', '.modal-btn', function() {
                    let modal_id = $(this).attr('data-bs-target');
                    let customer_id = $(modal_id).find('.modal-body').find('.id').val();
                    let coor = $(modal_id).find('.modal-body').find('.coordinate').val();
                    let node_form = $(modal_id).find('.modal-body').find('.total-credit');

                    let split_coor = coor.trim().split(',');

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/customers/getTotalCredit/" + customer_id,
                        dataType: "json",
                        success: function(data) {
                            node_form.val(data.toLocaleString('us', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            }));
                        },
                    });

                    setTimeout(() => {
                        mapboxgl.accessToken =
                            'pk.eyJ1IjoibWF1bGF5eWFjeWJlciIsImEiOiJja3N5bTU2ZTkxZGMyMnZsZ2V2aTc5enlrIn0.AoQDAKuMyXgRBRptUQ-8Bw';
                        var map = new mapboxgl.Map({
                            container: 'peta' + customer_id,
                            style: 'mapbox://styles/mapbox/streets-v11',
                            center: [parseFloat(split_coor[1]), parseFloat(split_coor[
                                0])], // Koordinat pusat peta
                            zoom: 12 // Tingkat zoom awal
                        });
                        map.addControl(
                            new MapboxDirections({
                                accessToken: mapboxgl.accessToken,
                                unit: 'metric',
                                profile: 'mapbox/driving',
                                interactive: true,
                                steps: true,
                            }),
                            'top-left'
                        );
                        var markers = []; // Tambahkan inisialisasi untuk variabel markers

                        var markerElement = document.createElement('div');
                        markerElement.className = 'marker';

                        var lang = split_coor[0];
                        var lat = split_coor[1];
                        var marker = new mapboxgl.Marker(markerElement)
                            .setLngLat([lat, lang])
                            .addTo(map);

                    }, 1000);

                    // markers.push(marker); // Tambahkan marker ke dalam array markers

                    $(modal_id).on('hidden.bs.modal', function() {
                        map.remove(); // Destroy the map when the modal is closed
                        $('#peta' + customer_id).html('');
                        console.log('Hellooooo');
                    });

                });
            });
        </script>
    @endpush
@endsection
