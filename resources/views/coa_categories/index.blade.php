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
                            data-bs-target="#staticBackdrop">+ Create CoA Category</button>
                        <div class="table-responsive">
                            <table class="dataTable table table-striped row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th class="text-center">Name</th>
                                        <th style="width: 10%" class="text-center">CoA Group</th>
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
        <div class="modal-dialog">
            <form class="needs-validation addCoaCategory" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">Create CoA Category</h6>
                    </div>
                    <div class="modal-body" style="font-size: 10pt">
                        <div class="row">
                            <div class="mb-3 col-12">
                                <label>Name</label>
                                <input autocomplete="off" name="name" required type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="">CoA Group</label>
                            <div class="input-group">
                                <input type="text" name="coa_group" class="form-control" placeholder="Group Number">
                                <span class="input-group-text">-</span>
                                <input type="text" name="category_number" class="form-control"
                                    placeholder="Category Number">
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
            $(document).ready(function() {
                // table coa category
                let table = $('.dataTable').DataTable({
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
                    ajax: "{{ url('finance/coa/categories') }}",
                    columns: [{
                            className: 'text-end fw-bold',
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'action',
                            name: 'action',

                        },
                        {
                            className: 'text-center fw-bold',
                            data: 'coa_group',
                            name: 'coa_group',

                        },


                    ],
                });

                // add coa category
                $(document).on('submit', '.addCoaCategory', function(event) {
                    event.preventDefault();
                    let form_add = new FormData($(this)[0]);
                    let formElement = $(this);
                    let button = formElement.find('button[type="submit"]');
                    button.prop('disabled', true);
                    $.ajax({
                        url: "{{ url('finance/coa/category/store') }}",
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
                            // validator.reload();
                        },
                        error: function(data) {
                            swal("Success !", data.message, "error", {
                                button: "Close",
                            });
                            button.prop('disabled', false);
                        }
                    });
                });

                // edit coa category
                $(document).on('submit', '.editItemPromotion', function(event) {
                    event.preventDefault();
                    let form_edit = new FormData(this);
                    console.log([...form_edit.entries()]);
                    let formElement = $(this);
                    let button = formElement.find('button[type="submit"]');
                    button.prop('disabled', true);
                    let id = $(this).find('#id').val();
                    console.log(id);
                    $.ajax({
                        url: `{{ url('finance/coa/category/${id}/update') }}`,
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
                            // validator.reload();
                        },
                        error: function(data) {
                            swal("Success !", "fail to saved data", "error", {
                                button: "Close",
                            });
                            button.prop('disabled', false);

                        }
                    });
                });

            });
        </script>



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
                $(document).on("click", ".addItem", function(event) {
                    let modal_id = $(this).attr('data-bs-target');

                    $('.uoms').select2({
                        dropdownParent: modal_id,
                        placeholder: 'Choose Category',
                        width: '100%',
                    });
                });
                $(document).on("click", ".modalItem", function(event) {
                    let modal_id = $(this).attr('data-bs-target');
                    id_item = $(modal_id).find('.id_item').val();
                    console.log(id_item);
                    $(function() {
                        let validator = $('form.needs-validation').jbvalidator({
                            errorMessage: true,
                            successClass: false,
                            language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                        });
                    });


                    let imgInput2 = $('#inputreference' + id_item);
                    let image_real = $('#img_real' + id_item);
                    let imgEl2 = $('#previewimg' + id_item);
                    let previewLabel2 = $('#previewLabel' + id_item);
                    imgInput2.on('change', function() {
                        if (imgInput2[0].files && imgInput2[0].files[0]) {
                            image_real.attr('hidden', true);
                            let reader = new FileReader();
                            reader.onload = function(e) {
                                imgEl2.attr('src', e.target.result);
                                imgEl2.removeAttr('hidden');
                                previewLabel2.removeAttr('hidden');
                            }
                            reader.readAsDataURL(imgInput2[0].files[0]);
                        }
                    });
                });


                // change cost to currency format
                // $('.cost').on('change', function() {
                //     let hargaInput = $(this).val().replace(/,/g, '');
                //     let hargaPisah = hargaInput.split('.');
                //     let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString('en', {
                //         minimumFractionDigits: 0,
                //         maximumFractionDigits: hargaPisah.length > 1 ? hargaPisah[1].length : 0
                //     });
                //     $(this).val(hargaFloat);
                //     $('.cost_').val(hargaInput);
                // });

                const imgInput = $('#inputreference');
                const imgEl = $('#previewimg');
                const previewLabel = $('#previewLabel');
                imgInput.on('change', function() {
                    if (imgInput[0].files && imgInput[0].files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imgEl.attr('src', e.target.result);
                            imgEl.removeAttr('hidden');
                            previewLabel.removeAttr('hidden');
                        }
                        reader.readAsDataURL(imgInput[0].files[0]);
                    }
                });




            });

            // add item promotion

            // edit item promotion


            $(document).on('click', '.delete-item', function(event) {
                event.preventDefault();
                var itemId = $(this).data('id');
                var url = `{{ url('material-promotion/${itemId}/delete') }}`;

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
