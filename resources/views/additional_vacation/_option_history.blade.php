<a href="#" class="fw-bold text-nowrap text-success modal-btn2" data-id="{{ $data->id }}" data-bs-toggle="modal" data-original-title="test" data-bs-target="#trace{{$data->id}}">{{$data->remark}}</a>

<div class="currentModal">
    <div class="modal fade" id="trace{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        
            <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6>APPROVAL ADDITION {{ $data->remark }}</h6>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($data->detailBy as $detail)
                                <div class="row formEmployee">
                                    <div class="col-lg-4 mb-3">
                                        <label for="employee_id[]">Name</label>
                                        <div class="input-group">
                                            <select class="form-select select-employee" name="employee_id[]" readonly
                                                disabled
                                                style="-webkit-appearance: none; -moz-appearance: none; appearance: none; background: none;">
                                                <option value="{{ $detail->employee_id }}" selected>
                                                    {{ $detail->teamBy->name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="addition">Addition</label>
                                    <input type="text" class="form-control" name="addition"
                                        value="{{ $data->addition }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="from_date">Date</label>
                                    <input type="text" class="form-control" name="from_date"
                                        value="{{ date('d-m-Y', strtotime($data->date)) }}" readonly>
                                </div>
                                <div class="col-lg-4 mb-3">
                                    <label for="remark">Remark</label>
                                    <input type="text" class="form-control" name="remark"
                                        value="{{ $data->remark }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>

    </div>
</div>

