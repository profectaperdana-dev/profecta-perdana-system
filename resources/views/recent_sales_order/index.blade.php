@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <style>
            tr.group,
            tr.group:hover {
                background-color: #ddd !important;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Check
                        {{ $title }}
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-6 xl-100">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data Sales Order Not Verified</h5>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" id="pills-warningtab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="pills-warninghome-tab" data-bs-toggle="pill"
                                    href="#pills-warninghome" role="tab" aria-controls="pills-warninghome"
                                    aria-selected="true">No Debt</a></li>
                            <li class="nav-item"><a class="nav-link" id="pills-warningprofile-tab" data-bs-toggle="pill"
                                    href="#pills-warningprofile" role="tab" aria-controls="pills-warningprofile"
                                    aria-selected="false">Debt</a></li>

                        </ul>
                        <div class="tab-content" id="pills-warningtabContent">
                            <div class="tab-pane fade show active" id="pills-warninghome" role="tabpanel"
                                aria-labelledby="pills-warninghome-tab">
                                <p class="mb-0 m-t-30">
                                <div class="table-responsive">
                                    <table id="example" class="display expandable-table text-capitalize table-hover"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>SO Number</th>
                                                <th>Order Date</th>
                                                <th>Customer</th>
                                                <th>Remark</th>
                                                <th>Verified</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataSalesOrder as $key => $value)
                                                <tr>


                                                    <td style="width: 10%">
                                                        <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false"><i data-feather="settings"></i></a>
                                                        <div class="dropdown-menu" aria-labelledby="">
                                                            <h5 class="dropdown-header">Actions</h5>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-original-title="test"
                                                                data-bs-target="#changeData{{ $value->id }}">Edit Sales
                                                                Order</a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-original-title="test"
                                                                data-bs-target="#changeData{{ $value->id }}">Edit
                                                                Product</a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-original-title="test"
                                                                data-bs-target="#deleteData{{ $value->id }}">Add
                                                                Product</a>
                                                        </div>

                                                    </td>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->order_number }}</td>
                                                    <td>{{ $value->order_date }}</td>
                                                    <td>{{ $value->soBy->name_cust }}</td>
                                                    <td>{{ $value->remark }}</td>
                                                    <td class="text-center"><a class="btn btn-primary btn-sm"
                                                            href="{{ url('/cek_jam') }}">Verificate</td>
                                                    </a>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </p>

                            </div>
                            <div class="tab-pane fade" id="pills-warningprofile" role="tabpanel"
                                aria-labelledby="pills-warningprofile-tab">
                                <p class="mb-0 m-t-30">
                                <div class="table-responsive">
                                    <table id="example1" class="display expandable-table text-capitalize table-hover"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>#</th>
                                                <th>SO Number</th>
                                                <th>Order Date</th>
                                                <th>Customer</th>
                                                <th>Remark</th>
                                                <th>Verified</th>


                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dataSalesOrder as $key => $value)
                                                <tr>


                                                    <td style="width: 10%">
                                                        <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false"><i data-feather="settings"></i></a>
                                                        <div class="dropdown-menu" aria-labelledby="">
                                                            <h5 class="dropdown-header">Actions</h5>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-original-title="test"
                                                                data-bs-target="#changeData{{ $value->id }}">Edit Sales
                                                                Order</a>
                                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                                data-original-title="test"
                                                                data-bs-target="#changeData{{ $value->id }}">Edit
                                                                Product</a>
                                                            <a class="dropdown-item" href="#"
                                                                data-bs-toggle="modal" data-original-title="test"
                                                                data-bs-target="#deleteData{{ $value->id }}">Add
                                                                Product</a>
                                                        </div>

                                                    </td>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $value->order_number }}</td>
                                                    <td>{{ $value->order_date }}</td>
                                                    <td>{{ $value->soBy->name_cust }}</td>
                                                    <td>{{ $value->remark }}</td>
                                                    <td class="text-center"><a class="btn btn-primary btn-sm"
                                                            href="{{ url('/cek_jam') }}">Verificate</td>
                                                    </a>


                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">

                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#example').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            title: 'RAB',
                            extend: 'pdf',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: ':visible'
                            },
                        },
                        {
                            title: 'Data Stock Profecta ',
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            },
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        'colvis'
                    ]
                });
                $('#example1').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            title: 'RAB',
                            extend: 'pdf',
                            pageSize: 'A4',
                            exportOptions: {
                                columns: ':visible'
                            },
                        },
                        {
                            title: 'Data Stock Profecta ',
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            },
                        },
                        {
                            extend: 'excel',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        'colvis'
                    ]
                });
                //     var table = ,
                //         "columnDefs": [{
                //             "visible": false,
                //             "targets": 2
                //         }],
                //         "order": [
                //             [2, 'asc']
                //         ],
                //         "displayLength": 25,
                //         "drawCallback": function(settings) {
                //             var api = this.api();
                //             var rows = api.rows({
                //                 page: 'current'
                //             }).nodes();
                //             var last = null;

                //             api.column(2, {
                //                 page: 'current'
                //             }).data().each(function(group, i) {
                //                 if (last !== group) {
                //                     $(rows).eq(i).before(
                //                         '<tr class="group"><td colspan="4">' + group + '</td></tr>'
                //                     );

                //                     last = group;
                //                 }
                //             });
                //         }
                // });
                // Order by the grouping
                $('#example tbody').on('click', 'tr.group', function() {
                    var currentOrder = table.order()[0];
                    if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                        table.order([2, 'desc']).draw();
                    } else {
                        table.order([2, 'asc']).draw();
                    }
                });
            });
        </script>
    @endpush
@endsection
