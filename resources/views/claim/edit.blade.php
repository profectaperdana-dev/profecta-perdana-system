@extends('layouts.master')
@section('content')
    @push('css')
        <link type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css"
            rel="stylesheet">

        <link rel="stylesheet" type="text/css" href="http://keith-wood.name/css/jquery.signature.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">

        <style>
            .kbw-signature {
                width: 100%;
                height: 300px;
            }

            #sig canvas {
                width: 100% !important;
                height: auto;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
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
                        <form method="post" action="{{ url('claim/' . $value->id) }}" enctype="multipart/form-data">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                            @include('claim._form_finish')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
        <script src="{{ asset('js/jquery.ui.touch-punch.min.js') }}"></script>
        <script type="text/javascript" src="http://keith-wood.name/js/jquery.signature.js"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        {{-- <script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
        <script type="text/javascript">
            CKEDITOR.replace('result');
        </script> --}}
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });
                // $('.result').select2({
                //     placeholder: '-Select Result-',
                //     allowClear: true
                // });
                // $('.receipt').select2({
                //     placeholder: '-Select Method-',
                //     allowClear: true
                // });
                var sig = $('#sig').signature({
                    syncField: '#signature64',
                    syncFormat: 'PNG',
                    // distance: 0
                });
                $('#clear').click(function(e) {
                    e.preventDefault();
                    sig.signature('clear');
                    $("#signature64").val('');
                });
                $('#otheCustomer').hide();
                $('#cust').change(function() {
                    var val_cust = $('#cust').val();

                    if (val_cust == 'other') {
                        $('#otheCustomer').show();
                    } else {
                        $('#otheCustomer').hide();

                    }
                });
                $('#file_received').hide();
                $('#ttd_received').hide();
                $('#choose_received').change(function() {
                    var val_cust = $('#choose_received').val();

                    if (val_cust == 'file') {
                        $('#file_received').show();
                        $('#ttd_received').hide();
                    } else if (val_cust == 'signature') {
                        $('#file_received').hide();
                        $('#ttd_received').show();
                    } else {
                        $('#file_received').hide();
                        $('#ttd_received').hide();
                    }
                });
            });
        </script>
    @endpush
@endsection
