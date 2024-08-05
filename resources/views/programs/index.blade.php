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
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }}\
                    </h6> --}}
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
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        {{-- ! create data --}}
                        <form class="form-label-left input_mask" method="post" action="{{ url('program/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold">Program Name</label>
                                            <input type="text"
                                                class="form-control text-capitalize {{ $errors->first('name') ? ' is-invalid' : '' }}"
                                                name="name" placeholder="Enter Program Name " required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold">Discount (Rp)</label>
                                            <input type="text"
                                                class="form-control harga_beli text-capitalize {{ $errors->first('discount') ? ' is-invalid' : '' }}"
                                                name="discount" placeholder="Enter Discount " required>
                                            @error('discount')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-12 form-group">
                                            <label for="">Based Program</label>

                                            <div class="animate-chk mt-2">
                                                {{-- ! Invoice Limit --}}
                                                <label class="d-block my-2" for="edo-ani">
                                                    <input required class="radio_animated" id="edo-ani" value="1"
                                                        type="radio" name="select_necess">Invoice
                                                    Limit
                                                </label>

                                                {{-- ! Date Limit  --}}
                                                <label class="d-block" for="edo-ani1">
                                                    <input class="radio_animated" id="edo-ani1" value="2"
                                                        type="radio" name="select_necess">Date Limit
                                                </label>
                                                {{-- ** Invoice Limit --}}
                                                <div class="form-group" id="invoice_limit" hidden>
                                                    <label class="">Sales Amount</label>
                                                    <div class="col-lg-12 col-12">
                                                        <input id="input_invoice" required name="invoice_limit"
                                                            class="form-control" type="number" required>
                                                    </div>
                                                </div>
                                                {{-- ** Date Limit --}}
                                                <div class="form-group" id="date_limit" hidden>
                                                    <label class="">Choose date</label>
                                                    <div class="col-lg-12 col-12">
                                                        <input id="input_date" required name="date_limit"
                                                            class="form-control" type="date" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <button type="reset" class="btn btn-warning"
                                                data-dismiss="modal">Reset</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                        {{-- ! end create data --}}
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="programTable" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>No</th>
                                        <th>Name Program</th>
                                        <th>Discount</th>
                                        <th>Invoice Limit</th>
                                        <th>Date Limit</th>
                                        <th>Discount</th>
                                        <th>Status</th>


                                    </tr>
                                </thead>
                                {{-- ! read data --}}
                                <tbody>
                                    @foreach ($programs as $key => $value)
                                        <tr>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#changeData{{ $value->id }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>
                                            {{-- !Modul Edit UOM --}}
                                            <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('product_uoms/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="PATCH">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Change Data
                                                                    {{ $value->satuan }}</h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="col-md-12">
                                                                            <label class="font-weight-bold ">Name
                                                                                Unit of Measurement</label>
                                                                            <input type="text"
                                                                                class="form-control text-capitalize {{ $errors->first('editSatuan') ? ' is-invalid' : '' }}"
                                                                                name="editSatuan"
                                                                                value="{{ $value->satuan }}"
                                                                                placeholder="Name Unit of Measurement">
                                                                            @error('editSatuan')
                                                                                <small
                                                                                    class="text-danger">{{ $message }}.</small>
                                                                            @enderror
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-danger" type="button"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="reset"
                                                                    class="btn btn-warning">Reset</button>
                                                                <button class="btn btn-primary" type="submit">Save
                                                                    changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- !End Modal Edit UOM --}}
                                            {{-- ! Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('product_uoms/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $value->satuan }}</h5>
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
                                                                <button class="btn btn-primary" type="submit">Yes, delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- ! End Modal Delete UOM --}}
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->invoice_limit }}</td>
                                            <td>{{ $value->date_limit }}</td>
                                            <td>{{ $value->discount }}</td>
                                            <td>
                                                @if ($value->status == 0)
                                                    <span class="badge bg-danger">Inactive</span>
                                                @else
                                                    <span class="badge bg-success">Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <img src="{{ asset('/storage/banners/' . $value->image) }}"
                                                    alt="image" class="img-fluid" width="100px">
                                            </td>


                                        </tr>
                                    @endforeach
                                    {{-- ! end read data --}}
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
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('form').submit(function(e) {
                    e.stopPropagation();
                });

                $('#programTable').DataTable({
                    "order": [
                        [0, "desc"]
                    ]
                });

                $('#edo-ani,#edo-ani1').on('change', function() {
                    if ($(this).val() == 1) {
                        $('#invoice_limit').attr('hidden', false);
                        $('#date_limit').attr('hidden', true);
                        $('#input_invoice').attr('required', true);
                        $('#input_date').attr('required', false);
                    } else {
                        $('#input_invoice').attr('required', false);
                        $('#input_date').attr('required', true);
                        $('#date_limit').attr('hidden', false);
                        $('#invoice_limit').attr('hidden', true);
                    }
                });
                $('.harga_beli').on('change', function() {
                    let priceReal = $(this).val();
                    let price = priceReal.replace(/\./g, '');
                    let myPrice = price.split(',');
                    let finalPrice = '';
                    if (myPrice.length > 1) {
                        let myPrice_1 = parseInt(myPrice[0]).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1 + ',' + myPrice[1];
                    } else {
                        let myPrice_1 = parseInt(price).toLocaleString(
                            'id', {
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                        finalPrice = myPrice_1;
                    }
                    $(this).val(finalPrice);
                    $('.harga_beli_').val(price);
                });
            });
        </script>
    @endpush
@endsection
