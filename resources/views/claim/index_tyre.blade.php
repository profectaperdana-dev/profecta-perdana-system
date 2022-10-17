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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Early Checking
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
                        <a class="btn btn-primary" href="{{ url('claim_tyre/create') }}">
                            + Create Tyre Claim
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
                                        <th>Tyre Type</th>
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
                                                    <a class="dropdown-item"
                                                        href="{{ url('/pdf_claim_accu/' . $value->id) }}">Download
                                                        PDF</a>
                                                    @if ($value->email != null)
                                                        <a class="dropdown-item"
                                                            href="{{ url('/send_early_accu_claim/' . $value->id) }}">Send By
                                                            Email</a>
                                                    @endif
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>
                                            {{-- early check --}}
                                            <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">

                                                    <div class="container-fluid">
                                                        <div class="row">
                                                            <div class="col-sm-14 col-md-12 col-lg-12">
                                                                <div class="ribbon-wrapper card">
                                                                    <div class="card-body shadow">
                                                                        <div class="ribbon ribbon-clip ribbon-warning">
                                                                            Early Checking</div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group row font-weight-bold">
                                                                                <div class="form-group col-lg-4 col-md-12">
                                                                                    <label>Claim number</label>
                                                                                    <input type="text"
                                                                                        class="form-control "
                                                                                        placeholder="Product Name" readonly
                                                                                        value="{{ $value->claim_number }}">
                                                                                </div>
                                                                                <div class="form-group col-lg-4 col-md-12">
                                                                                    <label>
                                                                                        Claim date</label>
                                                                                    <input type="date"
                                                                                        class="form-control"
                                                                                        placeholder="Serial Number" readonly
                                                                                        value="{{ $value->claim_date }}">
                                                                                </div>
                                                                                <div class="form-group col-lg-4 col-md-12">
                                                                                    <label>
                                                                                        Customer Source</label>
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        placeholder="Serial Number" readonly
                                                                                        value="{{ $value->customer_id }}">

                                                                                </div>
                                                                                <div class="form-group col-lg-4 col-md-12">
                                                                                    <label for="">Name
                                                                                        Customer</label>
                                                                                    {{-- Sub Name Customer --}}
                                                                                    <input name="sub_name" type="text"
                                                                                        id="other_name" readonly
                                                                                        class="form-control text-capitalize fw-bold "
                                                                                        placeholder="Enter Name"
                                                                                        aria-label="Username"
                                                                                        value="{{ $value->sub_name }}">

                                                                                    {{-- End Sub Name Customer --}}

                                                                                </div>
                                                                                <div class="form-group col-lg-4 col-md-12">
                                                                                    <label for="">Phone/Email
                                                                                        Customer</label>
                                                                                    {{-- SUb Phone Customer --}}
                                                                                    <input name="sub_phone" type=""
                                                                                        id="other_phone"
                                                                                        class="form-control fw-bold "
                                                                                        readonly placeholder="Enter Phone"
                                                                                        aria-label="Server"
                                                                                        value="{{ $value->sub_phone }}@if ($value->email != null) / {{ $value->email }} @endif">
                                                                                    {{-- End Sub Phone Customer --}}
                                                                                </div>


                                                                                <div class="form-group col-lg-4 col-md-12">
                                                                                    <label>Car Type</label>
                                                                                    <input type="text"
                                                                                        class="form-control text-capitalize"
                                                                                        placeholder="Serial Number"
                                                                                        readonly
                                                                                        value="{{ $value->carBrandBy->car_brand }} / {{ $value->carTypeBy->car_type }}">

                                                                                </div>
                                                                                <div class="form-group col-lg-6 col-md-12">
                                                                                    <label>Tyre type</label>
                                                                                    <input type="text"
                                                                                        class="form-control text-uppercase"
                                                                                        placeholder="Product Code" readonly
                                                                                        value="{{ $value->material }}/{{ $value->type_material }}/{{ $value->productBy->nama_barang }}">
                                                                                </div>

                                                                                <div class="form-group col-lg-6 col-md-12">
                                                                                    <label>
                                                                                        Plat Number</label>
                                                                                    <input type="text"
                                                                                        class="form-control text-uppercase"
                                                                                        placeholder="Serial Number"
                                                                                        readonly
                                                                                        value="{{ $value->plate_number }}">
                                                                                </div>

                                                                                <div
                                                                                    class="col-lg-6  col-md-12 form-group">
                                                                                    <label>Application</label>
                                                                                    <input type="text"
                                                                                        class="form-control text-capitalize"
                                                                                        placeholder="Serial Number"
                                                                                        readonly disabled
                                                                                        value="{{ $value->application }}">

                                                                                </div>

                                                                                <div class="col-lg-6 col-md-12 form-group">
                                                                                    <label>DOT/DOM</label>
                                                                                    <input type="text"
                                                                                        class="form-control text-capitalize"
                                                                                        placeholder="Serial Number"
                                                                                        readonly disabled
                                                                                        value="{{ $value->dot }}">
                                                                                </div>
                                                                                <div class="col-lg-6 col-md-12 form-group">
                                                                                    <label>Serial Number</label>
                                                                                    <input type="text"
                                                                                        class="form-control text-capitalize"
                                                                                        placeholder="Serial Number"
                                                                                        readonly disabled
                                                                                        value="{{ $value->serial_number }}">

                                                                                </div>
                                                                                <div class="col-lg-6 col-md-12 form-group">
                                                                                    <label for="">Remaining Thread
                                                                                        Depth</label>
                                                                                    <div class="input-group mb-3">
                                                                                        <div class="input-group-prepend">
                                                                                            <span class="input-group-text"
                                                                                                id="basic-addon1">RTD</span>
                                                                                        </div>
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            placeholder="Enter" disabled
                                                                                            readonly
                                                                                            value="{{ $value->rtd1 }}"
                                                                                            name="rtd1"
                                                                                            aria-label="Username"
                                                                                            aria-describedby="basic-addon1">
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            placeholder="Enter" disabled
                                                                                            readonly
                                                                                            value="{{ $value->rtd2 }}"
                                                                                            name="rtd2"
                                                                                            aria-label="Username"
                                                                                            aria-describedby="basic-addon1">
                                                                                        <input type="number"
                                                                                            class="form-control"
                                                                                            placeholder="Enter" disabled
                                                                                            readonly
                                                                                            value="{{ $value->rtd3 }}"
                                                                                            name="rtd3"
                                                                                            aria-label="Username"
                                                                                            aria-describedby="basic-addon1">
                                                                                    </div>

                                                                                </div>
                                                                                <div class="col-lg-6 col-md-12 form-group">
                                                                                    <label for="">Complaint
                                                                                        Area</label>
                                                                                    <input type="text"
                                                                                        class="form-control text-capitalize"
                                                                                        placeholder="Serial Number"
                                                                                        readonly disabled
                                                                                        value="{{ $value->complaint_area }}">

                                                                                </div>
                                                                                <div class="col-lg-6 col-md-12 form-group">
                                                                                    <label for="">Reason for
                                                                                        Complaint</label>
                                                                                    <input type="text"
                                                                                        class="form-control text-capitalize"
                                                                                        placeholder="Serial Number"
                                                                                        readonly disabled
                                                                                        value="{{ $value->reason }}">



                                                                                </div>


                                                                                <div
                                                                                    class="form-group col-lg-4 col-md-12 text-center">
                                                                                    <label class="text-center">
                                                                                        Submitted By,</label>
                                                                                    <br>
                                                                                    <div class="text-center"> <img
                                                                                            class="img-fluid img-rotate"
                                                                                            style="width: 200px"
                                                                                            id="img"
                                                                                            src="{{ asset('file_signature/' . $value->e_signature) }}"
                                                                                            alt="">
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                    class="form-group col-lg-4 col-md-12 text-center">
                                                                                    <label class="text-center">
                                                                                        Evidence,</label>
                                                                                    <br>
                                                                                    <div class="text-center"> <img
                                                                                            class="img-fluid shadow"
                                                                                            style="width: 200px"
                                                                                            id="img"
                                                                                            src="{{ asset('file_evidence/' . $value->e_foto) }}"
                                                                                            alt="">
                                                                                    </div>

                                                                                </div>
                                                                                <div
                                                                                    class="form-group col-lg-4 col-md-12 text-center">
                                                                                    <label class="text-center">
                                                                                        Received By,</label>
                                                                                    <br>
                                                                                    <p class="text-center">
                                                                                        <strong>{{ $value->createdBy->name }}</strong>
                                                                                    </p>


                                                                                </div>
                                                                                <hr>
                                                                                <a class="btn btn-danger" href="#"
                                                                                    data-bs-dismiss="modal">Close</a>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    {{-- </div> --}}

                                                    {{-- </div> --}}
                                                </div>
                                            </div>
                                            {{-- End early check --}}
                                            {{-- Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post"
                                                        action="{{ url('claim_tyre_del/' . $value->id) }}"
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
                                                                            <h5>Are you sure delete this data ?
                                                                            </h5>
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
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-uppercase">{{ $value->claim_number }}</td>
                                            <td>{{ $value->customer_id }}/{{ $value->sub_name }}</td>
                                            <td>

                                                {{ $value->material }}/{{ $value->type_material }}/{{ $value->productBy->nama_barang }}



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
