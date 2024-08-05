<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fas fa-cog"></i>
</a>
<div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    @canany(['level1', 'level2'])
        <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#manageData{{ $invoice->id }}">Edit </a>
        <a class="dropdown-item" href="{{ url('second_sale/print_struk/' . $invoice->id . '/print') }}">Delete</a>
    @endcanany
</div>

{{-- ! Modal Edit Invoice Trade In --}}
<div class="modal fade" data-bs-backdrop="static" id="manageData{{ $invoice->id }}" data-bs-keyboard="false"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit data journal</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="needs-validation" novalidate method="post"
                    action="{{ url('journal/' . $invoice->id . '/edit_superadmin') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="container-fluid">
                        <div class="form-group row">
                            <div class="col-12 col-md-12 form-group">
                                <label>
                                    Date</label>
                                <input type="date" name="date" required class="form-control"
                                    value="{{ $invoice->date }}">
                            </div>
                            <div class="col-12 col-md-12 form-group">
                                <label>
                                    Code - Name Transaction <span class="badge badge-success">(change)</span></label>
                                <select name="account" class="account form-control text-capitalize required">
                                    <option value="">--Select Account--</option>
                                    @foreach ($account as $type_account)
                                        <option value="{{ $type_account->id }}"
                                            @if ($invoice->code_type == $type_account->code) selected @endif>
                                            ({{ $type_account->code }})
                                            - {{ $type_account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-12 form-group">
                                <label>
                                    Memo</label>
                                <textarea class="form-control" name="memo" id="" cols="30" rows="5">{{ $invoice->memo }}</textarea>
                            </div>
                            <div class="col-12 col-md-12 form-group">
                                <label>
                                    Cost <span>(Rp)</span></label>
                                <input type="text" value="{{ number_format($invoice->total, 0, ',', '.') }}" required
                                    class="form-control text-capitalize total" placeholder="Enter Total">
                                <input type="hidden" class="" value="{{ $invoice->total }}" name="total">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
