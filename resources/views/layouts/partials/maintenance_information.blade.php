<div class="col-xl-12 box-col-12">
    <div class="card ">
        <div class="card-header b-l-primary border-3">
            <h6 class="pull-left">Maintenance Asset Information</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table maintenance table-sm table-hover">
                    <thead class="table-info">
                        <tr class="text-center text-nowrap">
                            <th>Status</th>

                            <th>
                                Code
                            </th>
                            <th>
                                Asset Name
                            </th>
                            <th>
                                Maintenance Last Date
                            </th>
                            <th>Distance</th>
                            <th>Next Maintenance Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($maintenance as $item)
                            <tr>
                                <td class="text-center">
                                    <span class="badge badge-warning">please follow up immediately
                                    </span>
                                </td>
                                <td>
                                    {{ $item->asset_code }}
                                </td>
                                <td>
                                    {{ $item->asset_name }}
                                </td>
                                <td class="text-center">
                                    {{ date('d M Y', strtotime($item->service_date)) }}
                                </td>
                                <td class="text-center">
                                    {{ $item->range }}
                                </td>
                                <td class="text-center">
                                    {{ date('d M Y', strtotime($item->next_service)) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</div>
