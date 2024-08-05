<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    ACT</a>
<div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
        data-bs-target="#manageData{{ $vacation->id }}">Check</a>
</div>
<div class="currentModal">
    <div class="modal" id="reject{{ $vacation->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <form action="{{ url('leave/reject/' . $vacation->id) }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="exampleModalLabel">Reject </h6>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="">Reason</label>
                                <textarea name="reason" id="" cols="30" rows="5" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal" id="delete{{ $vacation->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Delete</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            Are you sure want to delete this data?
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <a type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                    {{-- <button type="submit" class="btn btn-primary"></button> --}}
                    <a type="button" class="btn btn-success"
                        href="{{ url('leave/' . $vacation->id . '/delete') }}">Save
                        changes</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal" id="manageData{{ $vacation->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Application Leave</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="taskadd shadow">
                        <div class="table-responsive">
                            <table class="table table-light table-stripped">
                                <tr>
                                    <td>
                                        <h6 class="task_title_0">Status</h6>
                                    </td>
                                    <td>
                                        <p class="task_desc_0">
                                            @if ($vacation->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($vacation->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </p>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <h6 class="task_title_0">Employee Name</h6>
                                    </td>
                                    <td>
                                        <p class="task_desc_0">{{ $vacation->employeeBy->name }}</p>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <h6 class="task_title_0">Number of Days</h6>
                                    </td>
                                    <td>
                                        <p class="task_desc_0">
                                            {{-- {!! $vacation->hitungHari($vacation->count_days) !!} --}}
                                            {{ $vacation->count_days }}
                                        </p>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <h6 class="task_title_0">Leave Period </h6>
                                    </td>
                                    <td>
                                        <p class="task_desc_0">
                                            {{ date('d F Y', strtotime($vacation->start_date)) . ' - ' . date('d F Y', strtotime($vacation->end_date)) }}
                                        </p>
                                    </td>

                                </tr>
                                <tr>
                                    <td>
                                        <h6 class="task_title_0">Leave Reason</h6>
                                    </td>
                                    <td>
                                        <p class="task_desc_0">{{ $vacation->reason }}</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h6 class="task_title_0">Remaining Leave Day</h6>
                                    </td>
                                    <td>
                                        <p class="task_desc_0">{{ $vacation->employeeBy->vacation }}</p>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if ($vacation->status != 'approved' && $vacation->status != 'rejected')
                        <a class="btn btn-warning" href="javascript:void(0)" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#reject{{ $vacation->id }}">Reject</a>
                    @endif
                    <a type="button" href="#" class="btn btn-danger" data-bs-dismiss="modal">Close</a>
                    @if ($vacation->status == 'approved')
                        <a class="btn btn-warning" href="javascript:void(0)" data-bs-toggle="modal"
                            data-original-title="test" data-bs-target="#delete{{ $vacation->id }}">Delete</a>
                    @endif
                    @if ($vacation->status != 'approved' && $vacation->status != 'rejected')
                        <a type="button" class="btn btn-primary"
                            href="{{ url('leave/approve/' . $vacation->id) }}">Approve</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
