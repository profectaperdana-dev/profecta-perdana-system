@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
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
                        {{-- <button type="button" class="btn btn-primary mb-3 addItem" data-bs-toggle="modal"
                            data-bs-target="#staticBackdrop">+ Add Item Promotion</button> --}}
                        <div class="table-responsive">
                            <table class="dataTable table table-striped row-border order-column table-sm"
                                style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">Warehouse</th>
                                        <th class="text-center">Qty</th>
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
    {{-- <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="needs-validation addItemPromotion" novalidate>
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel">Add Item Promotion</h6>
                    </div>
                    <div class="modal-body" style="font-size: 10pt">
                        <div class="mb-3">
                            <label>Item</label>
                            <input autocomplete="off" name="name" required type="text" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" id="" cols="30" rows="1" class="form-control"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Cost</label>
                            <input autocomplete="off" required type="text" class="form-control cost">
                            <input name="cost" autocomplete="off" required type="hidden" class="form-control cost_">
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
    </div> --}}

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
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
                $(document).on("click", ".modalItem", function(event) {
                    let modal_id = $(this).attr('data-bs-target');
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
                    $('.cost').on('change', function() {
                        let hargaInput = $(this).val().replace(/,/g, '');
                        let hargaPisah = hargaInput.split('.');
                        let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString('en', {
                            minimumFractionDigits: 0,
                            maximumFractionDigits: hargaPisah.length > 1 ? hargaPisah[1]
                                .length : 0
                        });
                        $(this).val(hargaFloat);
                        $('.cost_').val(hargaInput);
                    });

                });


                // change cost to currency format
                $('.cost').on('change', function() {
                    let hargaInput = $(this).val().replace(/,/g, '');
                    let hargaPisah = hargaInput.split('.');
                    let hargaFloat = parseFloat(hargaPisah[0]).toLocaleString('en', {
                        minimumFractionDigits: 0,
                        maximumFractionDigits: hargaPisah.length > 1 ? hargaPisah[1].length : 0
                    });
                    $(this).val(hargaFloat);
                    $('.cost_').val(hargaInput);
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
                    destroy: true,
                    ajax: "{{ url('/material-promotion/stock') }}",
                    columns: [{
                            className: 'text-center fw-bold',
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'name',
                            name: 'name',

                        },
                        {
                            className: 'text-center',
                            data: 'warehouse',
                            name: 'warehouse',

                        },
                        {
                            className: 'text-end fw-bold',
                            data: 'qty',
                            name: 'qty'
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
                    order: [],
                });
            });

            // edit item promotion
            $(document).on('submit', '.editItemPromotionStock', function(event) {
                event.preventDefault();
                let form_edit = $(this).serialize();
                // console.log(form_edit);
                let formElement = $(this);
                let button = formElement.find('button[type="submit"]');
                button.prop('disabled', true);
                let id = $(this).find('.id').val();
                // console.log(id);
                $.ajax({
                    url: `{{ url('/material-promotion/${id}/update_stock') }}`,
                    type: "POST",
                    dataType: "JSON",
                    data: form_edit,
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
        </script>
    @endpush
@endsection
