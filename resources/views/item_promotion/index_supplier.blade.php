@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                </div>
            </div>
        </div>
    </div>



    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card shadow">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary mb-3 addItem" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">+ Vendor</button>
                        <div class="table-responsive">
                            <table class="dataTable table table-striped row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Name</th>
                                        <th class="text-center">Phone</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">NPWP</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <form class="needs-validation addItemPromotion" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">Add Vendor</h6>
                    </div>
                    <div class="modal-body" style="font-size: 10pt">
                        <div class="row mb-3">
                            <div class="col-12 col-lg-4">
                                <label>Name</label>
                                <input autocomplete="off" name="name" required type="text" class="form-control">
                            </div>
                            <div class="col-12 col-lg-4">
                                <label>Phone</label>
                                <input autocomplete="off" name="phone_number" required type="text" class="form-control">
                            </div>
                            <div class="col-12 col-lg-4">
                                <label>Email</label>
                                <input autocomplete="off" name="email" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-4">
                                <label>NPWP</label>
                                <input autocomplete="off" name="npwp" required type="text" class="form-control">
                            </div>
                            <div class="col-12 col-lg-4">
                                <label>Address</label>
                                <input autocomplete="off" name="address" required type="text" class="form-control">
                            </div>
                            <div class="col-12 col-lg-4">
                                <label>PIC</label>
                                <input autocomplete="off" name="pic" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-12 col-lg-4">
                                <label>Bank</label>
                                <input autocomplete="off" name="bank" required type="text" class="form-control">
                            </div>
                            <div class="col-12 col-lg-4">
                                <label>Acc. Number</label>
                                <input autocomplete="off" name="no_rek" required type="text" class="form-control">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger hideModalAdd" autocomplete="off"
                            data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            Save
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
        <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            // validation
            $(function() {
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: false,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
            });
            $(document).ready(function() {
                let id_item = '';

                $(document).on("click", ".modalItem", function(event) {
                    let modal_id = $(this).attr('data-bs-target');
                    id_item = $(modal_id).find('.id_item').val();
                    // console.log(id_item);
                    $(function() {
                        let validator = $('form.needs-validation').jbvalidator({
                            errorMessage: true,
                            successClass: false,
                            language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                        });
                    });
                    $('.selectMulti').select2({
                        dropdownParent: modal_id,
                        placeholder: 'Select an option',
                        allowClear: true,
                        maximumSelectionLength: 1,
                        width: '100%',
                    });
                });

                // index datatable
                var table = $('.dataTable').DataTable({
                    "language": {
                        "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                    },
                    "lengthChange": false,
                    "bPaginate": false,
                    "bLengthChange": false,
                    "searching": true,
                    "ordering": true,
                    "info": false,
                    "autoWidth": true,
                    fixedColumns: {
                        leftColumns: 0,
                        rightColumns: 0
                    },
                    scrollY: 400,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: false,
                    "fixedHeader": true,
                    processing: true,
                    serverSide: true,
                    pageLength: -1,
                    ajax: "{{ url('/material-promotion/vendor') }}",
                    columns: [{
                            className: 'text-end fw-bold',
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'name',
                            name: 'name',

                        },
                        {
                            className: 'text-center',
                            data: 'phone_number',
                            name: 'phone_number',

                        },
                        {
                            className: 'text-center',
                            data: 'email',
                            name: 'email'
                        },
                        {
                            className: 'text-center',
                            data: 'npwp',
                            name: 'npwp'
                        },
                        {
                            className: 'text-center',
                            data: 'status',
                            name: 'status'
                        },
                    ],
                    drawCallback: function(settings) {
                            // Kode yang akan dijalankan setelah DataTable selesai dikerjakan
                            $('#thisModal').html('');
                            $('.currentModal').each(function(){
                                let currentModal = $(this).html();
                                $(this).html('');
                                $('#thisModal').append(currentModal);
                            });
                            
                            // console.log($('#currentModal').html());
                            // Lakukan tindakan lain yang Anda inginkan di sini
                        },
                });
            });

            // add item promotion
            $(document).on('submit', '.addItemPromotion', function(event) {
                event.preventDefault();
                let form_add = new FormData($(this)[0]); // create new FormData object
                // console.log([...form_add.entries()]);
                let formElement = $(this);
                let button = formElement.find('button[type="submit"]');
                button.prop('disabled', true);
                $.ajax({
                    url: "{{ url('/material-promotion/vendor/store') }}",
                    type: "POST",
                    dataType: "JSON",
                    data: form_add, // send FormData object as data
                    processData: false, // prevent jQuery from processing the data
                    contentType: false, // prevent jQuery from setting the content type
                    success: function(data) {
                        swal("Success !", data.message, "success", {
                            button: "Close",
                        });
                        button.prop('disabled', false);
                        $('.hideModalAdd').click();
                        $('.dataTable').DataTable().ajax.reload();
                        formElement[0].reset();
                        validator.reload();
                    },
                    error: function(data) {
                        swal("Success !", "fail to saved data", "error", {
                            button: "Close",
                        });
                        button.prop('disabled', false);
                    }
                });
            });

            // edit item promotion
            $(document).on('submit', '.editItemPromotion', function(event) {
                event.preventDefault();
                let form_edit = new FormData(this);
                // console.log([...form_edit.entries()]);
                let formElement = $(this);
                let button = formElement.find('button[type="submit"]');
                button.prop('disabled', true);
                let id = $(this).find('.id').val();
                // console.log(id);
                $.ajax({
                    url: `{{ url('/material-promotion/vendor/${id}/update') }}`,
                    type: "POST",
                    dataType: "JSON",
                    data: form_edit,
                    processData: false, // Ensure FormData is not processed
                    contentType: false, // Ensure FormData is not set as content type
                    success: function(data) {
                        swal("Success !", data.message, "success", {
                            button: "Close",
                        });
                        $('.dataTable').DataTable().ajax.reload();
                        button.prop('disabled', false);
                        $('.hideModalEdit').click();
                        validator.reload();
                    },
                    error: function(data) {
                        swal("Success !", "fail to saved data", "error", {
                            button: "Close",
                        });
                    }
                });
            });

            $(document).on('click', '.delete-item', function(event) {
                event.preventDefault();
                var itemId = $(this).data('id');
                var url = `{{ url('material-promotion/vendor/${itemId}/delete') }}`;

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: 'json',
                    data: {
                        "id": itemId,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data) {
                        swal("Success !", data.message, "success", {
                            button: "Close",
                        });
                        $('.dataTable').DataTable().ajax.reload();
                        $('.hideModalEdit').click();
                    },
                    error: function(data) {
                        swal("Success !", "fail to saved data", "error", {
                            button: "Close",
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
