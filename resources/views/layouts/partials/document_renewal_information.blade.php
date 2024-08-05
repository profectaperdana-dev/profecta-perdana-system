<div class="col-xl-12 box-col-12">
    <div class="card ">
        <div class="card-header b-l-primary border-3">
            <h6 class="pull-left">Document Renewal Information</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table maintenance table-sm table-hover">
                    <thead class="table-info">
                        <tr class="text-center text-nowrap">
                            <th>Status</th>
                            <th>Document Name</th>
                            <th>Remark</th>
                            <th>Last Renewal Date</th>
                            <th>Renewal Period (Month)</th>
                            <th>Renewal Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($documents as $item)
                            <tr>
                                <td>
                                    @if ($item->status == 'Renewed')
                                        <span class="badge rounded-pill bg-primary">Renewed</span>
                                    @elseif($item->status == 'Need Renewing')
                                        <span class="badge rounded-pill bg-warning">Need Renewing</span>
                                    @else
                                        <span class="badge rounded-pill bg-danger">Unrenewed</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->name }}
                                </td>
                                <td>
                                    {{ $item->remark }}
                                </td>
                                <td class="text-center">
                                    {{ date('d F Y', strtotime($item->last_updated)) }}
                                </td>
                                <td class="text-end">
                                    {{ $item->update_period }}
                                </td>
                                <td class="text-center">
                                    {{ date('d F Y', strtotime($item->renewalDeadline())) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>
