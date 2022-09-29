@extends('layouts.master')
@section('content')
    @push('css')
        <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css"
            rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">

        <style>
            .kbw-signature {
                width: 100%;
                height: 300px;
            }

            #sig canvas {
                width: 100% !important;
                height: auto;
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
                        {{-- <h5>All Data</h5> --}}
                        <a class="btn btn-primary" href="{{ url('claim/create') }}">
                            + Create Claim
                        </a>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Claim Number</th>
                                        <th>Customer</th>
                                        <th>Accu Type</th>
                                        <th>Finish Claim</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#detailData{{ $value->id }}">Early Check</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>
                                            {{-- early check --}}
                                            <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header text-center">
                                                            <h5 class="modal-title" id="exampleModalLabel">Detail Data Early
                                                                Check
                                                                {{ $value->claim_number }}</h5>
                                                            </h5>
                                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container-fluid">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <div class="form-group row font-weight-bold">
                                                                            <div class="form-group col-md-6">
                                                                                <label>Claim number</label>
                                                                                <input type="text" class="form-control "
                                                                                    placeholder="Product Name" readonly
                                                                                    value="{{ $value->claim_number }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Claim date</label>
                                                                                <input type="date" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->claim_date }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>Car Type</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->car_type }}">

                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>Accu type</label>
                                                                                <input type="text"
                                                                                    class="form-control text-uppercase"
                                                                                    placeholder="Product Code" readonly
                                                                                    value="{{ $value->productSales->sub_materials->nama_sub_material }}/{{ $value->productSales->sub_types->type_name }}/{{ $value->productSales->nama_barang }}">
                                                                            </div>

                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Plat Number</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->plate_number }}">
                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Customer/Phone Number</label>
                                                                                <input type="text" class="form-control"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="{{ $value->customer_id }}">
                                                                            </div>
                                                                            <div class="form-group col-md-12">
                                                                                <input type="text"
                                                                                    class="form-control bg-warning text-white text-center"
                                                                                    placeholder="Serial Number" readonly
                                                                                    value="Early Check">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>
                                                                                    Voltage
                                                                                </label>
                                                                                <input type="text" readonly
                                                                                    class="form-control"
                                                                                    value="{{ $value->e_voltage }}">
                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>CCA </label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    placeholder="Retail Selling Price"
                                                                                    value="{{ $value->e_cca }}">

                                                                            </div>
                                                                            <div class="form-group col-md-3">
                                                                                <label>Starting</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    placeholder="Non Retail Selling Price"
                                                                                    value="{{ $value->e_starting }}">
                                                                            </div>

                                                                            <div class="form-group col-md-3">
                                                                                <label>Charging</label>
                                                                                <input type="text" class="form-control"
                                                                                    readonly
                                                                                    value="{{ $value->e_charging }}">
                                                                            </div>
                                                                            <div class="form-group col-md-12">
                                                                                <label>Diagnosa</label>
                                                                                <p>
                                                                                    @php
                                                                                        echo htmlspecialchars_decode(htmlspecialchars_decode($value->diagnosa));
                                                                                    @endphp
                                                                                </p>

                                                                            </div>
                                                                            <div class="form-group col-md-6">
                                                                                <label>
                                                                                    Submitted By,</label>
                                                                                <br>
                                                                                <p><strong>{{ $value->createdBy->name }}</strong>
                                                                                </p>
                                                                            </div>
                                                                            <div class="form-group text-center col-md-6">
                                                                                <label>
                                                                                    Received By,</label>
                                                                                <br>
                                                                                <img class="img-fluid"
                                                                                    src="{{ asset('receivedBy/' . $value->e_receivedBy) }}"
                                                                                    alt="">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-danger" type="button"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- End early check --}}
                                            {{-- Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('claim/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete
                                                                    Data
                                                                    {{ $value->claim_number }}</h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                                <button class="btn btn-danger" type="button"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button class="btn btn-primary" type="submit">Yes,
                                                                    delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- End Modal Delete UOM --}}
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-uppercase">{{ $value->claim_number }}</td>
                                            <td>{{ $value->customer_id }}</td>
                                            <td>{{ $value->productSales->sub_materials->nama_sub_material }}/{{ $value->productSales->sub_types->type_name }}/{{ $value->productSales->nama_barang }}
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-primary"
                                                    href="{{ url('/claim/' . $value->id . '/edit') }}">Finish
                                                    Claim</a>
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
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
        <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
        <script type="text/javascript">
            CKEDITOR.replace('result');
        </script>
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });
                var sig = $('#sig').signature({
                    syncField: '#signature64',
                    syncFormat: 'PNG',
                    // distance: 0

                });
                $('#clear').click(function(e) {
                    e.preventDefault();
                    sig.signature('clear');
                    $("#signature64").val('');
                });
                $('#otheCustomer').hide();
                $('#cust').change(function() {
                    var val_cust = $('#cust').val();

                    if (val_cust == 'other') {
                        $('#otheCustomer').show();
                    } else {
                        $('#otheCustomer').hide();

                    }
                });
                $('#file_received').hide();
                $('#ttd_received').hide();
                $('#choose_received').change(function() {
                    var val_cust = $('#choose_received').val();

                    if (val_cust == 'file') {
                        $('#file_received').show();
                        $('#ttd_received').hide();
                    } else if (val_cust == 'signature') {
                        $('#file_received').hide();
                        $('#ttd_received').show();
                    } else {
                        $('#file_received').hide();
                        $('#ttd_received').hide();
                    }
                });
            });
        </script>
    @endpush
@endsection
