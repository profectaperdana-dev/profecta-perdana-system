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
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <div class="container-fluid">
                            <form class="form-label-left input_mask" method="post" action="{{ url('/supliers') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Vendor</label>
                                                <input type="text" value="{{ old('nama_supplier') }}"
                                                    class="form-control {{ $errors->first('nama_supplier') ? ' is-invalid' : '' }}"
                                                    name="nama_supplier" placeholder="Vendor Name" required>
                                                @error('nama_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Warehouse</label>
                                                <select name="id_warehouse" id="" class="form-control uoms">
                                                    <option value="">-- Select Warehouse --
                                                    </option>
                                                    @foreach ($warehouse as $row)
                                                        <option value="{{ $row->id }}">{{ $row->warehouses }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Address</label>
                                                <textarea placeholder="Vendor Address" name="alamat_supplier" id="" cols="30" rows="5"
                                                    class="form-control {{ $errors->first('alamat_supplier') ? ' is-invalid' : '' }}" required>{{ old('alamat_supplier') }}</textarea>
                                                @error('alamat_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Email</label>
                                                <input type="text" value="{{ old('email') }}"
                                                    class="form-control {{ $errors->first('email') ? ' is-invalid' : '' }}"
                                                    name="email" placeholder="Vendor Email">
                                                @error('email')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">Phone Number</label>
                                                <input type="text" value="{{ old('no_telepon_supplier') }}"
                                                    class="form-control {{ $errors->first('no_telepon_supplier') ? ' is-invalid' : '' }}"
                                                    name="no_telepon_supplier" placeholder="Phone Number" required>
                                                @error('no_telepon_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">NPWP</label>
                                                <input type="text" value="{{ old('npwp_supplier') }}"
                                                    class="form-control {{ $errors->first('npwp_supplier') ? ' is-invalid' : '' }}"
                                                    name="npwp_supplier" placeholder="NPWP Vendor" required>
                                                @error('npwp_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="font-weight-bold">PIC</label>
                                                <input type="text" value="{{ old('pic_supplier') }}"
                                                    class="form-control {{ $errors->first('pic_supplier') ? ' is-invalid' : '' }}"
                                                    name="pic_supplier" placeholder="PIC Vendor" required>
                                                @error('pic_supplier')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Bank</label>
                                                <input type="text" value="{{ old('bank') }}"
                                                    class="form-control {{ $errors->first('bank') ? ' is-invalid' : '' }}"
                                                    name="bank" placeholder="Bank" required>
                                                @error('bank')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
                                            </div>

                                            <div class="col-md-6">
                                                <label class="font-weight-bold">Bank Account Number</label>
                                                <input type="text" value="{{ old('no_rek') }}"
                                                    class="form-control {{ $errors->first('no_rek') ? ' is-invalid' : '' }}"
                                                    name="no_rek" placeholder="Bank Account Number" required>
                                                @error('no_rek')
                                                    <small class="text-danger">{{ $message }}.</small>
                                                @enderror
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
                        </div>
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
                            <table id="basics" class="table table-sm table-striped" style="width:100%">
                                <thead>
                                    <tr class="text-center text-nowrap">
                                        <th></th>
                                        <th class="text-center">Vendor</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">NPWP</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>



                                            <td class="text-end">{{ $key + 1 }}</td>
                                            <td>{{ $value->nama_supplier }}</td>
                                            <td>{{ $value->no_telepon_supplier }}</td>
                                            <td>{{ $value->email }}</td>
                                            <td class="text-nowrap">{{ $value->npwp_supplier }}</td>
                                            <td class="text-center">
                                                @if ($value->status_supplier == 0)
                                                    <div><span class="badge badge-danger">Non Active</span></div>
                                                @else
                                                    <div><span class="badge badge-success"> Active</span></div>
                                                @endif
                                            </td>
                                            <td style="width: 5%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item modal-btn" href="#"
                                                        data-bs-toggle="modal" data-original-title="test"
                                                        data-bs-target="#changeData{{ $value->id }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
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
    @foreach ($data as $key => $value)
        {{-- Modul Edit UOM --}}
        <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form method="post" action="{{ url('supliers/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="PATCH">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change Data
                                {{ $value->supliers }}</h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold">Vendor</label>
                                            <input type="text"
                                                value="{{ old('nama_supplier_', $value->nama_supplier) }}"
                                                class="form-control {{ $errors->first('nama_supplier_') ? ' is-invalid' : '' }}"
                                                name="nama_supplier_" placeholder="Vendor Name" required>
                                            @error('nama_supplier_')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold">Warehouse</label>
                                            <select name="id_warehouse_" id="" class="form-control uoms">
                                                <option value="">-- Select
                                                    Warehouse --
                                                </option>
                                                @foreach ($warehouse as $val)
                                                    <option value="{{ $val->id }}"
                                                        {{ $val->id == $value->id_warehouse ? 'selected' : '' }}>
                                                        {{ $val->warehouses }}</option>
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="font-weight-bold">Address</label>
                                            <textarea placeholder="Address Vendor" name="alamat_supplier_" id="" cols="30" rows="5"
                                                class="form-control {{ $errors->first('alamat_supplier_') ? ' is-invalid' : '' }}" required>{{ old('alamat_supplier_', $value->alamat_supplier) }}</textarea>
                                            @error('alamat_supplier_')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="font-weight-bold">Phone
                                                Number</label>
                                            <input type="text"
                                                value="{{ old('no_telepon_supplier_', $value->no_telepon_supplier) }}"
                                                class="form-control {{ $errors->first('no_telepon_supplier_') ? ' is-invalid' : '' }}"
                                                name="no_telepon_supplier_" placeholder="Phone Number" required>
                                            @error('no_telepon_supplier_')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold">Email
                                            </label>
                                            <input type="text" value="{{ $value->email }}"
                                                class="form-control {{ $errors->first('email_') ? ' is-invalid' : '' }}"
                                                name="email_" placeholder="Enter email">
                                            @error('email_')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label class="font-weight-bold">NPWP</label>
                                            <input type="text"
                                                value="{{ old('npwp_supplier_', $value->npwp_supplier) }}"
                                                class="form-control {{ $errors->first('npwp_supplier_') ? ' is-invalid' : '' }}"
                                                name="npwp_supplier_" placeholder="NPWP Vendor" required>
                                            @error('npwp_supplier_')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label class="font-weight-bold">PIC</label>
                                            <input type="text"
                                                value="{{ old('pic_supplier_', $value->pic_supplier) }}"
                                                class="form-control {{ $errors->first('pic_supplier_') ? ' is-invalid' : '' }}"
                                                name="pic_supplier_" placeholder="PIC Vendor" required>
                                            @error('pic_supplier_')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 form-group">
                                            <label class="font-weight-bold">Bank</label>
                                            <input type="text" value="{{ old('bank_', $value->bank) }}"
                                                class="form-control {{ $errors->first('bank_') ? ' is-invalid' : '' }}"
                                                name="bank_" placeholder="Bank" required>
                                            @error('bank_')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <label class="font-weight-bold">Bank
                                                Account Number</label>
                                            <input type="text" value="{{ old('no_rek_', $value->no_rek) }}"
                                                class="form-control {{ $errors->first('no_rek_') ? ' is-invalid' : '' }}"
                                                name="no_rek_" placeholder="Bank Account Number" required>
                                            @error('no_rek_')
                                                <small class="text-danger">{{ $message }}.</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-3 form-group">
                                            <label>
                                                Status</label>
                                            <select id="" required name="status_supplier"
                                                class="form-control uoms {{ $errors->first('status_supplier') ? ' is-invalid' : '' }}">
                                                <option value="{{ $value->status_supplier }}" selected>
                                                    @if ($value->status_supplier == 0)
                                                        Non Active
                                                    @else
                                                        Active
                                                    @endif
                                                </option>
                                                <option value="1">Active
                                                </option>
                                                <option value="0">Non
                                                    Active</option>
                                            </select>
                                            @error('status_supplier')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                            <button class="btn btn-primary" type="submit">Save
                                changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        {{-- End Modal Edit UOM --}}
        {{-- Modul Delete UOM --}}
        <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form method="post" action="{{ url('supliers/' . $value->id) }}" enctype="multipart/form-data">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                {{ $value->supliers }}</h5>
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
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
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
                $('#basics').dataTable();
                $(document).on("click", ".modal-btn", function(event) {
                    let modal_id = $(this).attr('data-bs-target');

                    $(modal_id).find(".uoms").select2({
                        width: "100%",
                        dropdownParent: modal_id,
                    });

                });
            })
        </script>
    @endpush
@endsection
