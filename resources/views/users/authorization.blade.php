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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create
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

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display dataTable expandable-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Menu</th>
                                        <th>Sub Menu</th>
                                        <th>Sub Type Menu</th>
                                    </tr>
                                </thead>

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
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                var table = $('.dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ url('/authorization') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'menu',
                            name: 'menu'
                        },
                        {
                            data: 'submenu',
                            name: 'submenu'
                        },
                        {
                            data: 'subtypemenu',
                            name: 'subtypemenu'
                        },
                    ]
                });
            });
        </script>
        <script>
            $(document).on('submit', 'form', function() {
                var form = $(this);
                var button = form.find('button[type="submit"]');
                if (form[0].checkValidity()) { // check if form has input values
                    button.prop('disabled', true);

                }
            });
        </script>
    @endpush
@endsection
