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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Read
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

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Claim Number</th>
                                        <th> Date</th>
                                        <th>Customer</th>
                                        <th>Battery Type</th>
                                        <th>Plate Number</th>
                                        <th>Detail</th>
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td style="width: 5%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    {{-- <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#detailData{{ $value->id }}">Result Claim</a> --}}
                                                    <a class="dropdown-item"
                                                        href="{{ url('/pdf_claim_accu_finish/' . $value->id) }}">Download
                                                        PDF</a>
                                                    @if ($value->email != null)
                                                        <a class="dropdown-item"
                                                            href="{{ url('/send_early_accu_claim_finish/' . $value->id) }}">Send
                                                            By
                                                            Email</a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-uppercase">{{ $value->claim_number }}</td>
                                            <td>{{ $value->claim_date }}</td>
                                            <td>{{ $value->customer_id }}/{{ $value->sub_name }}</td>
                                            <td>
                                                @if ($value->material == null)
                                                    {{ $value->product_id }}
                                                @else
                                                    {{ $value->material }}/{{ $value->type_material }}/{{ $value->product_id }}
                                                @endif
                                            </td>
                                            <td class="text-uppercase">{{ $value->plate_number }}</td>

                                            <td class="text-center">
                                                <a class="btn btn-sm btn-primary" href="#" data-bs-toggle="modal"
                                                    data-original-title="test"
                                                    data-bs-target="#detailData{{ $value->id }}">View Detail</a>
                                            </td>
                                            {{-- finish claim --}}
                                            <div class="modal fade" id="detailData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-xl" role="document">
                                                    <div class="container-fluid">
                                                        <div class="row">
                                                            <div class="col-sm-14 col-md-12 col-lg-12">
                                                                <div class="ribbon-wrapper card">
                                                                    <div class="card-body shadow">
                                                                        <div class="ribbon ribbon-clip ribbon-primary">
                                                                            Card Claim</div>
                                                                        <div class="col-md-12">
                                                                            <div class="form-group row font-weight-bold">
                                                                                <div class="form-group col-md-12">
                                                                                    <div class="row">
                                                                                        <div class="form-group col-md-4">
                                                                                            <label>Claim Number</label>
                                                                                            <input type="text"
                                                                                                class="form-control "
                                                                                                placeholder="Product Name"
                                                                                                readonly
                                                                                                value="{{ $value->claim_number }}">
                                                                                        </div>
                                                                                        <div class="form-group col-md-4">
                                                                                            <label>
                                                                                                Claim Date</label>
                                                                                            <input type="date"
                                                                                                class="form-control"
                                                                                                placeholder="Serial Number"
                                                                                                readonly
                                                                                                value="{{ $value->claim_date }}">
                                                                                        </div>
                                                                                        <div class="form-group col-md-4">
                                                                                            <label>
                                                                                                Replaced Date</label>
                                                                                            <input type="date"
                                                                                                class="form-control"
                                                                                                placeholder="Serial Number"
                                                                                                readonly
                                                                                                value="{{ $value->date_replaced }}">
                                                                                        </div>
                                                                                        <div class="form-group col-md-4">
                                                                                            <label>Car Type</label>
                                                                                            <input type="text"
                                                                                                class="form-control text-capitalize"
                                                                                                placeholder="Serial Number"
                                                                                                readonly
                                                                                                value="{{ $value->carBrandBy->car_brand }} / {{ $value->carTypeBy->car_type }}">

                                                                                        </div>
                                                                                        <div class="form-group col-md-4">
                                                                                            <label>Battery type</label>
                                                                                            <input type="text"
                                                                                                class="form-control text-uppercase"
                                                                                                placeholder="Product Code"
                                                                                                readonly
                                                                                                value="{{ $value->material }}/{{ $value->type_material }}/{{ $value->productSales->nama_barang }}">
                                                                                        </div>

                                                                                        <div class="form-group col-md-4">
                                                                                            <label>
                                                                                                Plate Number</label>
                                                                                            <input type="text"
                                                                                                class="form-control text-uppercase"
                                                                                                placeholder="Serial Number"
                                                                                                readonly
                                                                                                value="{{ $value->plate_number }}">
                                                                                        </div>
                                                                                        <div class="form-group col-md-6">
                                                                                            <label>
                                                                                                Customer Name</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                placeholder="Serial Number"
                                                                                                readonly
                                                                                                value="{{ $value->customer_id }}/{{ $value->sub_name }}">
                                                                                        </div>
                                                                                        <div class="form-group col-md-6">
                                                                                            <label>
                                                                                                Customer Phone/Email</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                placeholder="Serial Number"
                                                                                                readonly
                                                                                                value="{{ $value->sub_phone }}@if ($value->email != null) /{{ $value->email }} @endif">
                                                                                        </div>
                                                                                        <div class="form-group col-md-12">
                                                                                            <label>
                                                                                                Loaned Battery</label>
                                                                                            <input type="text"
                                                                                                class="form-control"
                                                                                                placeholder="Serial Number"
                                                                                                readonly
                                                                                                value="{{ $value->loanBy->sub_materials->nama_sub_material }}/{{ $value->loanBy->sub_types->type_name }}/{{ $value->loanBy->nama_barang }}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row">
                                                                                    <div class="form-group col-md-6">
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div class="form-group">
                                                                                                    <input type="text"
                                                                                                        class="form-control bg-warning text-white text-center"
                                                                                                        placeholder="Serial Number"
                                                                                                        readonly
                                                                                                        value="Early Check">
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-md-6">
                                                                                                <label>
                                                                                                    Voltage
                                                                                                </label>
                                                                                                <input type="text"
                                                                                                    name="" readonly
                                                                                                    class="form-control"
                                                                                                    value="{{ $value->e_voltage }}">
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-md-6">
                                                                                                <label>CCA </label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    readonly
                                                                                                    placeholder="Retail Selling Price"
                                                                                                    value="{{ $value->e_cca }}"
                                                                                                    name="f_cca">

                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-md-6">
                                                                                                <label>Starting</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    readonly
                                                                                                    placeholder="Non Retail Selling Price"
                                                                                                    name=""
                                                                                                    value="{{ $value->e_starting }}">
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-md-6">
                                                                                                <label>Charging</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    readonly name=""
                                                                                                    value="{{ $value->e_charging }}">
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-md-12">

                                                                                                <label
                                                                                                    for="">Diagnosa</label>
                                                                                                @foreach ($value->accuClaimDetailsBy as $key => $row)
                                                                                                    <div>
                                                                                                        {{ $key + 1 }}.
                                                                                                        {{ $row->diagnosa }}
                                                                                                    </div>
                                                                                                @endforeach


                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-lg-4 col-md-12">
                                                                                                <label>
                                                                                                    Received By,</label>
                                                                                                <br>
                                                                                                <p><strong>{{ $value->createdBy->name }}</strong>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-lg-4 col-md-12">
                                                                                                <label>
                                                                                                    Evidence,</label>
                                                                                                <br>
                                                                                                <div class="text-center">
                                                                                                    <img class="img-fluid shadow"
                                                                                                        style="width: 200px"
                                                                                                        id="img"
                                                                                                        src="{{ asset('file_evidence/' . $value->e_foto) }}"
                                                                                                        alt="">
                                                                                                </div>

                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-lg-4 col-md-12">
                                                                                                <label>
                                                                                                    Submitted By</label>
                                                                                                <br>
                                                                                                <div class="text-center">
                                                                                                    <img class="img-fluid img-rotate "
                                                                                                        style="width: 200px"
                                                                                                        id="img"
                                                                                                        src="{{ asset('file_signature/' . $value->e_receivedBy) }}"
                                                                                                        alt="">
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="form-group col-md-6">
                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="form-group col-md-12">
                                                                                                <input type="text"
                                                                                                    class="form-control bg-primary text-white text-center"
                                                                                                    placeholder="Serial Number"
                                                                                                    readonly
                                                                                                    value="Final Check">
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-md-6">
                                                                                                <label>
                                                                                                    Voltage
                                                                                                </label>
                                                                                                <input type="text"
                                                                                                    name="" readonly
                                                                                                    class="form-control"
                                                                                                    value="{{ $value->f_voltage }}">
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-md-6">
                                                                                                <label>CCA </label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    readonly
                                                                                                    placeholder="Retail Selling Price"
                                                                                                    value="{{ $value->f_cca }}"
                                                                                                    name="f_cca">
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-md-6">
                                                                                                <label>Starting</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    readonly
                                                                                                    placeholder="Non Retail Selling Price"
                                                                                                    name=""
                                                                                                    value="{{ $value->f_starting }}">
                                                                                            </div>

                                                                                            <div
                                                                                                class="form-group col-md-6">
                                                                                                <label>Charging</label>
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    readonly name=""
                                                                                                    value="{{ $value->f_charging }}">
                                                                                            </div>


                                                                                            <div
                                                                                                class="form-group col-md-12">
                                                                                                <label>Result</label>

                                                                                                <p>@php
                                                                                                    echo htmlspecialchars_decode(htmlspecialchars_decode($value->result));
                                                                                                @endphp</p>


                                                                                            </div>

                                                                                            <div
                                                                                                class="form-group col-lg-4 col-md-12">
                                                                                                <label>
                                                                                                    Submitted By,</label>
                                                                                                <br>
                                                                                                <p><strong>{{ $value->createdBy->name }}</strong>
                                                                                                </p>
                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-lg-4 col-md-12">
                                                                                                <label>
                                                                                                    Evidence,</label>
                                                                                                <br>
                                                                                                <div class="text-center">
                                                                                                    <img class="img-fluid shadow"
                                                                                                        style="width: 200px"
                                                                                                        id="img"
                                                                                                        src="{{ asset('file_evidence/' . $value->f_foto) }}"
                                                                                                        alt="">
                                                                                                </div>

                                                                                            </div>
                                                                                            <div
                                                                                                class="form-group col-lg-4 col-md-12">
                                                                                                <label>
                                                                                                    Received By,</label>
                                                                                                <br>
                                                                                                <div class="text-center">
                                                                                                    <img class="img-fluid img-rotate "
                                                                                                        style="width: 200px"
                                                                                                        id="img"
                                                                                                        src="{{ asset('file_signature/' . $value->f_receivedBy) }}"
                                                                                                        alt="">
                                                                                                </div>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <button class="btn btn-danger"
                                                                                    type="button"
                                                                                    data-bs-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                {{-- </div> --}}

                                                                {{-- </div> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end finish claim --}}
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
