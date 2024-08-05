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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">
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
                                        <th class="text-center">Created by</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($unapproves as $key => $value)
                                        <tr>

                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->name_cust }}</td>
                                            <td>{{ $value->areaBy->area_name }}</td>
                                            <td>{{ $value->city }}</td>
                                            <td>{{ $value->phone_cust }}</td>
                                            <td>
                                                @if ($value->status == 1)
                                                    <div class="badge badge-success">Active</div>
                                                @else
                                                    <div class="badge badge-danger">Nonactive</div>
                                                @endif
                                            </td>
                                            <td>{{ $value->createdBy->name }}</td>
                                            <td style="width: 10%">
                                                <a href="#" class="btn btn-sm btn-primary modal-btn"
                                                    data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#detailData{{ $value->id }}">Approve</a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    @canany(['level1', 'level2'])
                                                        <a class="dropdown-item"
                                                            href="{{ url('/customers/' . $value->code_cust . '/edit') }}">Edit</a>
                                                    @endcanany
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
    @foreach ($unapproves as $key => $value)
        {{-- Modul Detail --}}
        <div class="modal" id="detailData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-fullscreen modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Data
                            {{ $value->nama_barang }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form class="needs-validation" novalidate
                                action="{{ url('/customer/' . $value->id . '/approve') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
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
                                            <label>Name</label>
                                            <input type="text" class="form-control" name="name_cust"
                                                placeholder="Customer Name" required value="{{ $value->name_cust }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>ID Card
                                                Number</label>
                                            <input type="text" class="form-control " placeholder="Customer ID Card"
                                                required name="id_card_number" value="{{ $value->id_card_number }}">
                                        </div>
                                    </div>

                                    <div class="form-group row font-weight-bold">
                                        <div class="form-group col-md-3">
                                            <label>Province</label>
                                            <select name="province" class="form-control province" name="province" required>
                                                @if ($value->province != null)
                                                    <option selected value="{{ $value->province }}">
                                                        {{ $value->province }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>District</label>
                                            <select name="city" class="form-control city" required>
                                                @if ($value->city != null)
                                                    <option selected value="{{ $value->city }}">
                                                        {{ $value->city }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Sub-district</label>
                                            <select name="district" class="form-control district" required>
                                                @if ($value->district != null)
                                                    <option selected value="{{ $value->district }}">
                                                        {{ $value->district }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>City</label>
                                            <input type="text" class="form-control" name="village" id=""
                                                value="{{ $value->village }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group row font-weight-bold">
                                        <div class="form-group col-md-3">
                                            <label>Address</label>
                                            <input type="text" name="address_cust" value="{{ $value->address_cust }}"
                                                class="form-control form-control-lg" placeholder="Customer Address"
                                                required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>NPWP</label>
                                            <input type="text" name="npwp" value="{{ $value->npwp }}"
                                                class="form-control form-control-" placeholder="Customer NPWP" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>
                                                Office Phone</label>
                                            <input type="text" name="office_number"
                                                value="{{ $value->office_number }}" class="form-control"
                                                placeholder="Customer Office Phone Number" required>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>
                                                Cell Phone</label>
                                            <input type="text" name="phone_cust" value="{{ $value->phone_cust }}"
                                                class="form-control" placeholder="Cell Phone Number" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row font-weight-bold">

                                    <div class="form-group row font-weight-bold">
                                        <div class="form-group col-md-4">
                                            <label>
                                                Customer Email</label>
                                            <input type="text" name="email_cust" value="{{ $value->email_cust }}"
                                                class="form-control" placeholder="Email Customer" required>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>
                                                Customer Category</label>
                                            <select name="category_cust_id"
                                                class="form-control category-cust @error('category_cust_id') is-invalid @enderror"
                                                required>
                                                <option value="">Choose
                                                    Category
                                                    Customer</option>
                                                @foreach ($customer_categories as $customer_category)
                                                    <option value="{{ $customer_category->id }}"
                                                        @if ($customer_category->id == $value->category_cust_id) selected @elseif ($customer_category->id == old('category_cust_id')) selected @endif>
                                                        {{ $customer_category->category_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Customer Area
                                            </label>
                                            <select name="area_cust_id"
                                                class="form-control area-cust @error('area_cust_id') is-invalid @enderror"
                                                required>
                                                <option value="">Choose
                                                    Customer
                                                    Area</option>
                                                @foreach ($customer_areas as $customer_area)
                                                    <option value="{{ $customer_area->id }}"
                                                        @if ($customer_area->id == $value->area_cust_id) selected @elseif ($customer_area->id == old('area_cust_id')) selected @endif>
                                                        {{ $customer_area->area_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row font-weight-bold">
                                        <div class="form-group col-md-4">
                                            <label>Label</label>
                                            <select name="label" class="form-control uoms" required>
                                                <option value="" selected>
                                                    Choose
                                                    Customer Label</option>
                                                <option value="Prospect" @if ($value->label == 'Prospect') selected @endif>
                                                    Prospect</option>
                                                <option value="Customer" @if ($value->label == 'Customer') selected @endif>
                                                    Customer</option>
                                                <option value="Bad Customer"
                                                    @if ($value->label == 'Bad Customer') selected @endif>
                                                    Bad Customer
                                                </option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Credit Limit</label>
                                            <input type="text"
                                                value="{{ number_format($value->credit_limit, 0, ',', '.') }}"
                                                class="form-control credit" placeholder="Customer Credit Limit" required>
                                            <input type="hidden" name="credit_limit" class="form-control"
                                                value="{{ $value->credit_limit }}">

                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Due Date</label>
                                            <input type="number" name="due_date" value="{{ $value->due_date }}"
                                                class="form-control" placeholder="Customer Due Date" required>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="form-group col-md-3">
                                            <label>Store Building Image</label>
                                            <div class="mb-1 text-break">Uploaded
                                                File: <a class="link-success"
                                                    href="{{ url('public/images/customers/' . $value->reference_image) }}"
                                                    onclick="window.open(this.href, '_blank', 'width=600,height=400'); return false;">{{ $value->reference_image }}</a>
                                            </div>

                                            <input type="file" name="reference_image"
                                                class="change-image form-control @error('reference_image') is-invalid @enderror mb-2">
                                            @error('reference_image')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>ID Card Image</label>
                                            <div class="mb-1 text-break">Uploaded File: <a class="link-success"
                                                    href="{{ url('public/images/customers/ktp/' . $value->id_card_image) }}"
                                                    onclick="window.open(this.href, '_blank', 'width=600,height=400'); return false;">{{ $value->id_card_image }}</a>
                                            </div>

                                            <input type="file" name="id_card_image"
                                                class="change-image form-control @error('id_card_image') is-invalid @enderror mb-2">

                                            @error('id_card_image')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>NPWP Image</label>
                                            <div class="mb-1 text-break">Uploaded File: <a class="link-success"
                                                    href="{{ url('public/images/customers/npwp/' . $value->npwp_image) }}"
                                                    onclick="window.open(this.href, '_blank', 'width=600,height=400'); return false;">{{ $value->npwp_image }}</a>
                                            </div>

                                            <input type="file" name="npwp_image"
                                                class="change-image form-control @error('npwp_image') is-invalid @enderror mb-2">

                                            @error('npwp_image')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Selfie with Owner Image</label>
                                            <div class="mb-1 text-break">Uploaded File: <a class="link-success"
                                                    href="{{ url('public/images/customers/selfie/' . $value->selfie_image) }}"
                                                    onclick="window.open(this.href, '_blank', 'width=600,height=400'); return false;">{{ $value->selfie_image }}</a>
                                            </div>
                                            {{-- <div class="mb-1"><small class="mt-5" id="modalreference"><a
                                                        data-bs-toggle="modal" data-original-title="test"
                                                        data-bs-target="#referenceimage" href="#"><i
                                                            class="fa fa-eye" aria-hidden="true"></i>
                                                        Preview Image</a></small></div> --}}
                                            <input type="file" name="selfie_image"
                                                class="change-image form-control @error('selfie_image') is-invalid @enderror mb-2">

                                            @error('selfie_image')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row font-weight-bold">
                                        <div class="form-group col-md-6">
                                            <label>Coordinate</label>
                                            <input type="text" name="coordinate_" value="{{ $value->coordinate }}"
                                                class="form-control coordinate" placeholder="Coordinate" required>
                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="form-group col text-center">
                                        <label for="">Map Preview</label>
                                        <div id="peta{{ $value->id }}" style="width: 100%; height: 500px;"></div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#deleteData{{ $value->id }}"
                                        data-bs-dismiss="modal">Reject</button>
                                    <button class="btn btn-sm btn-primary" type="submit">Approve</button>
                                    {{-- <button class="btn btn-danger" type="button"
                                    data-bs-dismiss="modal">Close</button> --}}
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        {{-- End Modal Detail --}}

        {{-- Modul Delete UOM --}}
        <div class="modal" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('customers/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('delete')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Reject Data
                                {{ $value->name_cust }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <h5>Are you sure to reject this data? The
                                            rejected data will be deleted from database
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Yes, reject
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Modal Delete UOM --}}
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
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
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
                    let validator = $('form.needs-validation').jbvalidator({
                        errorMessage: true,
                        successClass: true,
                        language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                    });

                    validator.reload();


                    let modal_id = $(this).attr('data-bs-target');
                    let customer_id = $(modal_id).find('.modal-body').find('.id').val();
                    let coor = $(modal_id).find('.modal-body').find('.coordinate').val();
                    let split_coor = coor.trim().split(',');
                    var map;
                    setTimeout(() => {
                        mapboxgl.accessToken =
                            'pk.eyJ1IjoibWF1bGF5eWFjeWJlciIsImEiOiJja3N5bTU2ZTkxZGMyMnZsZ2V2aTc5enlrIn0.AoQDAKuMyXgRBRptUQ-8Bw';
                        map = new mapboxgl.Map({
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
                        // console.log('Hellooooo');
                    });

                    $(modal_id).on('change', '.change-image', function() {
                        if (this.files && this.files[0]) {
                            let this_change = $(this);
                            let filename = this.files[0]['name'];

                            var reader = new FileReader();
                            reader.onload = function(e) {
                                // console.log(this.files[0]);

                                this_change.parent().find('div').text('New File: ' + filename);

                                let newWindow = window.open('', '_blank', 'width=600,height=400');
                                newWindow.document.write(
                                    '<html><body><img class="img-fluid" src="' + e.target
                                    .result + '"></body></html>');

                            }
                            reader.readAsDataURL(this.files[0]);

                        }
                    });

                    $(modal_id).find('.coordinate').change(function() {
                        let new_coor = $(this).val();
                        new_coor = new_coor.trim().split(',');

                        if (new_coor != null && new_coor != '' && new_coor.length > 1 && new_coor
                            .length < 3) {

                            map.remove();
                            $('#peta' + customer_id).html('');

                            setTimeout(() => {
                                mapboxgl.accessToken =
                                    'pk.eyJ1IjoibWF1bGF5eWFjeWJlciIsImEiOiJja3N5bTU2ZTkxZGMyMnZsZ2V2aTc5enlrIn0.AoQDAKuMyXgRBRptUQ-8Bw';
                                map = new mapboxgl.Map({
                                    container: 'peta' + customer_id,
                                    style: 'mapbox://styles/mapbox/streets-v11',
                                    center: [parseFloat(new_coor[1]), parseFloat(
                                        new_coor[
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
                                var
                                    markers = []; // Tambahkan inisialisasi untuk variabel markers

                                var markerElement = document.createElement('div');
                                markerElement.className = 'marker';

                                var lang = new_coor[0];
                                var lat = new_coor[1];
                                var marker = new mapboxgl.Marker(markerElement)
                                    .setLngLat([lat, lang])
                                    .addTo(map);

                            }, 1000);
                        }
                    });

                    $('.category-cust, .area-cust').select2({
                        width: '100%',
                        dropdownParent: modal_id
                    });

                    $(".province").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                        placeholder: "Select Customer Province",
                        minimumResultsForSearch: -1,
                        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                        ajax: {
                            type: "GET",
                            url: "/customers/getProvince",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                    $('.province').change(function() {
                        let province_value = $('.province').val();

                        $(".city").select2({
                            width: "100%",
                            minimumResultsForSearch: -1,
                            dropdownParent: modal_id,
                            placeholder: "Select Customer City",
                            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                            ajax: {
                                type: "GET",
                                url: "/customers/getCity/" + province_value,
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.name,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    });

                    $('.city').change(function() {
                        let city_value = $('.city').val();

                        $(".district").select2({
                            width: "100%",
                            minimumResultsForSearch: -1,
                            dropdownParent: modal_id,
                            placeholder: "Select Customer District",
                            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                            ajax: {
                                type: "GET",
                                url: "/customers/getDistrict/" + city_value,
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.name,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    });

                    $('.district').change(function() {
                        let district_value = $('.district').val();

                        $(".village").select2({
                            width: "100%",
                            minimumResultsForSearch: -1,
                            dropdownParent: modal_id,
                            placeholder: "Select Customer Village",
                            sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
                            ajax: {
                                type: "GET",
                                url: "/customers/getVillage/" + district_value,
                                data: function(params) {
                                    return {
                                        _token: csrf,
                                        q: params.term, // search term
                                    };
                                },
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: $.map(data, function(item) {
                                            return [{
                                                text: item.name,
                                                id: item.id,
                                            }, ];
                                        }),
                                    };
                                },
                            },
                        });
                    });

                    $('.credit').on('keyup', function() {
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

                });
            });
        </script>
    @endpush
@endsection
