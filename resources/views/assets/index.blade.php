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
                        <a class="btn btn-primary" href="{{ url('/asset/create') }}">
                            + Create Asset
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Amount</th>
                                        <th>Lifetime (In Month)</th>
                                        <th>Year of Acquisition</th>
                                        <th>Cost of Acquisition (Rp)</th>
                                        <th>Created By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assets as $key => $value)
                                        <tr>
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


                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->asset_code }}</td>
                                            <td>{{ $value->asset_name }}</td>
                                            <td>{{ $value->amount }}</td>
                                            <td>{{ $value->lifetime }}</td>
                                            <td>{{ date('d M Y', strtotime($value->acquisition_year)) }}</td>
                                            <td>{{ number_format($value->acquisition_cost, 0, ',', '.') }}</td>
                                            <td>{{ $value->createdBy->name }}</td>

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

    @foreach ($assets as $value)
        {{-- Modul Detail --}}
        <div class="modal fade" id="editData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Data
                            {{ $value->asset_name }} | Code: {{ $value->asset_code }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ url('asset/' . $value->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="container-fluid">
                                <div class=" row">
                                    <div class="form-group col-md-4">
                                        <label class="font-weight-bold">Name of Asset</label>
                                        <input type="text"
                                            class="form-control {{ $errors->first('asset_name') ? ' is-invalid' : '' }}"
                                            name="asset_name" value="{{ $value->asset_name }}"
                                            placeholder="Enter Name of Asset" required>
                                        @error('asset_name')
                                            <small class="text-danger">{{ $message }}.</small>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="font-weight-bold">Amount</label>
                                        <input type="number"
                                            class="form-control {{ $errors->first('amount') ? ' is-invalid' : '' }}"
                                            name="amount" value="{{ $value->amount }}" placeholder="Enter Amount of Asset"
                                            required>
                                        @error('amount')
                                            <small class="text-danger">{{ $message }}.</small>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label class="font-weight-bold">Lifetime (In Month)</label>
                                        <input type="number"
                                            class="form-control {{ $errors->first('lifetime') ? ' is-invalid' : '' }}"
                                            name="lifetime" value="{{ $value->lifetime }}"
                                            placeholder="Enter Lifetime of Asset" required>
                                        @error('lifetime')
                                            <small class="text-danger">{{ $message }}.</small>
                                        @enderror
                                    </div>

                                </div>

                                <div class=" row">


                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold">Year of Acquisition</label>
                                        <input type="date"
                                            class="form-control {{ $errors->first('acquisition_year') ? ' is-invalid' : '' }}"
                                            name="acquisition_year" value="{{ $value->acquisition_year }}" required>
                                        @error('acquisition_year')
                                            <small class="text-danger">{{ $message }}.</small>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold">Cost of Acquisition</label>
                                        <input type="text"
                                            class="form-control total {{ $errors->first('acquisition_cost') ? ' is-invalid' : '' }}"
                                            placeholder="Enter Cost of Acquisition"
                                            value="{{ number_format($value->acquisition_cost, 0, ',', '.') }}" required>
                                        <input type="hidden" value="{{ $value->acquisition_cost }}"
                                            name="acquisition_cost">
                                        @error('acquisition_cost')
                                            <small class="text-danger">{{ $message }}.</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">Save</button>
                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
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
                    <form method="post" action="{{ url('asset/' . $value->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('delete')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                    {{ $value->asset_name }} | Code: {{ $value->asset_code }}</h5>
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
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");

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
                            return (input === 0) ? "" : input.toLocaleString("id-ID");
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
