<a class="text-success fw-bold text-nowrap modal-btn" href="#" data-bs-toggle="modal" data-original-title="test"
    data-bs-target="#detailData{{ $data->id }}">
    {{ $data->coa->name }}

</a>

@canany(['level1', 'level2'])
    <div class="modal" id="detailData{{ $data->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
        data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title no-print" id="exampleModalLabel">
                        Edit Expenses: {{ $data->jurnal->memo }}</h6>
                </div>
                <div class="modal-body">
                    <form action="{{ url('finance/journal/' . $data->id . '/edit') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="container-fluid">
                            <div class="row">
                                <div class="form-group m-checkbox-inline mb-0 col-lg-6 ">
                                    <label for="">Type</label>

                                    <div class="radio radio-primary mt-2">
                                        <input id="radioinlinev{{ $data->id }}" type="radio" name="type"
                                            value="debit" @if ($data->debit != 0) checked @endif>
                                        <label class="mb-0" for="radioinlinev{{ $data->id }}">Debit</label>
                                    </div>
                                    <div class="radio radio-primary mt-2">
                                        <input id="radioinlinex{{ $data->id }}" type="radio" name="type"
                                            value="credit" @if ($data->credit != 0) checked @endif>
                                        <label class="mb-0" for="radioinlinex{{ $data->id }}">Credit</label>
                                    </div>

                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-12 col-lg-6 form-group">
                                    <label for="">Ref</label>
                                    <input type="text" name="ref" class="form-control" value="{{ $data->ref }}">
                                </div>
                                <div class="col-12 col-lg-6 form-group">
                                    <label for="">Amount</label>
                                    <input type="text" class="total-in form-control"
                                        @if ($data->debit != 0) value="{{ number_format($data->debit) }}"
                                        @else
                                            value="{{ number_format($data->credit) }}" @endif>
                                    <input
                                        @if ($data->debit != 0) value="{{ $data->debit }}"
                                    @else
                                        value="{{ $data->credit }}" @endif
                                        type="hidden" name="total" class="total_">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-original-title="test"
                                data-bs-target="#delete{{ $data->id }}" data-bs-dismiss="modal">
                                Delete
                            </button>
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="saveBtn">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                    aria-hidden="true"></span>
                                <span class="sr-only">Loading...</span>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endcan

@canany(['level1'])
    <div class="modal" id="delete{{ $data->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Delete Expense: {{ $data->jurnal->memo }}</h6>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this expense ?
                </div>
                <div class="modal-footer">
                    <a class="btn btn-secondary modal-btn2" type="button" data-bs-toggle="modal" data-original-title="test"
                        data-bs-target="#detailData{{ $data->id }}" data-bs-dismiss="modal">Back
                    </a>
                    <a type="button" class="btn  btn-danger" data-bs-dismiss="modal">Close</a>
                    <a type="button" href="{{ url('expenses/delete/' . $data->id) }}"
                        class="btn btn-delete btn-primary">Yes, delete</a>
                </div>
            </div>
        </div>
    </div>
@endcan
<!-- Modal -->
