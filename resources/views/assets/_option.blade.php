<a href="javascript:void(0)" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#editData{{ $asset->id }}"><i class="fa fa-spin fa-cog fs-5 mx-auto"></i></a>


{{-- Modul Detail --}}
<div class="modal fade" id="editData{{ $asset->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Data
                    {{ $asset->asset_name }} | Code: {{ $asset->asset_code }}</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url('asset/' . $asset->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="container-fluid">
                        <div class=" row">
                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Name of Asset</label>
                                <input type="text"
                                    class="form-control {{ $errors->first('asset_name') ? ' is-invalid' : '' }}"
                                    name="asset_name" value="{{ $asset->asset_name }}" placeholder="Enter Name of Asset"
                                    required>
                                @error('asset_name')
                                    <small class="text-danger">{{ $message }}.</small>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Amount</label>
                                <input type="number"
                                    class="form-control {{ $errors->first('amount') ? ' is-invalid' : '' }}"
                                    name="amount" value="{{ $asset->amount }}" placeholder="Enter Amount of Asset"
                                    required>
                                @error('amount')
                                    <small class="text-danger">{{ $message }}.</small>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label class="font-weight-bold">Lifetime (In Month)</label>
                                <input type="number"
                                    class="form-control {{ $errors->first('lifetime') ? ' is-invalid' : '' }}"
                                    name="lifetime" value="{{ $asset->lifetime }}"
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
                                    name="acquisition_year" value="{{ $asset->acquisition_year }}" required>
                                @error('acquisition_year')
                                    <small class="text-danger">{{ $message }}.</small>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label class="font-weight-bold">Cost of Acquisition</label>
                                <input type="text"
                                    class="form-control total {{ $errors->first('acquisition_cost') ? ' is-invalid' : '' }}"
                                    placeholder="Enter Cost of Acquisition"
                                    value="{{ number_format($asset->acquisition_cost, 0, ',', '.') }}" required>
                                <input type="hidden" value="{{ $asset->acquisition_cost }}" name="acquisition_cost">
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
    <div class="modal fade" id="deleteData{{ $asset->id }}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ url('asset/' . $asset->id) }}" enctype="multipart/form-data">
                @csrf
                @method('delete')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Data
                            {{ $asset->asset_name }} | Code: {{ $asset->asset_code }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
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
