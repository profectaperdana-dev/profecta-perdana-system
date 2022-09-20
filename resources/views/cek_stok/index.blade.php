@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <style>
            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
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

            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="table" style="width:100%">
                                <thead>
                                    <tr>
                                        {{-- <th>#</th> --}}
                                        <th class="text-center">Products</th>
                                        @canany(['isSuperAdmin', 'isFinance', 'isVerificator'])
                                            <th class="text-center">Warehouse</th>
                                        @endcanany
                                        <th class="text-center">Stock</th>
                                        <th class="text-center">Uom</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>


                                            {{-- <td>{{ $loop->iteration }}</td> --}}
                                            <td>
                                                {{ $value->productBy->nama_barang .
                                                    ' (' .
                                                    $value->productBy->sub_types->type_name .
                                                    ', ' .
                                                    $value->productBy->sub_materials->nama_sub_material .
                                                    ')' }}
                                            </td>
                                            @canany(['isSuperAdmin', 'isFinance', 'isVerificator'])
                                                <td>{{ $value->warehouseBy->warehouses }}</td>
                                            @endcanany
                                            <td class="text-center">{{ $value->stock }}</td>
                                            <td class="text-center">{{ $value->productBy->uoms->satuan }}</td>


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
                            title: 'Information Stock',
                            messageTop: '<h5>{{ $title }} ({{ date('l H:i A, d F Y ') }})</h5><br>',
                            messageBottom: '<strong style="color:red;">*please always update stock in the system again, stock can change at any time</strong>',
                            extend: 'print',
                            exportOptions: {
                                columns: ':visible'
                            },
                            customize: function(win) {
                                $(win.document.body)
                                    .css('font-size', '10pt')
                                    .prepend(
                                        '<img src="{{ asset('images/logo.png') }}" style="position:absolute; top:300; left:150; bottom:; opacity: 0.2;"/>'
                                    );
                                $(win.document.body)
                                    .find('thead')
                                    .css('background-color', 'rgba(211,225,222,255)')
                                    .addClass('table-hover')
                            },
                        },
                        {
                            extend: 'excel',
                            title: 'Information Stock',
                            messageTop: 'Data stock in {{ Auth::user()->warehouseBy->warehouses }} ({{ date('l H:i A, d F Y ') }})',
                            exportOptions: {
                                columns: ':visible'
                            },

                        },
                        'colvis'
                    ]
                });

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
