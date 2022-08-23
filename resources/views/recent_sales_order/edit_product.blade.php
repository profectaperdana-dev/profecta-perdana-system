@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12 col-12">
                    <h3 class="font-weight-bold">{{ $title }} {{ $value->order_number }}</h3>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">
                        {{ $title }}</h6> --}}
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 col-6 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Edit Product</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ url('updateso/' . $value->id . '/editso') }}"
                            enctype="multipart/form-data" id="">
                            @csrf
                            @method('PUT')
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="form-group row">
                                        <div class="col-md-6 form-group">
                                            <label>
                                                Customers</label>
                                            <select name="customer_id" id="" required
                                                class="form-control sub_type customer-append {{ $errors->first('customer_id') ? ' is-invalid' : '' }}">
                                                <option value="" selected>-Choose Customers-</option>
                                                @foreach ($customer as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        @if ($customer->id == $value->customers_id) selected @endif>
                                                        {{ $customer->code_cust }} |
                                                        {{ $customer->name_cust }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        {{-- <div class="col-md-4 form-group">
                                        <label for="">PPN 11%</label><br>
                                        <select name="ppn" id="" class="sub_type form-control">
                                            <option value="" selected>-Choose PPN-</option>
                                            <option value="1">Include PPN</option>
                                            <option value="2">Without PPN</option>
                                        </select>
                                    </div> --}}
                                        <div class="col-md-6 form-group mr-5">
                                            <label>Payment Method</label>
                                            <select name="payment_method" id="payment_method" required
                                                class="form-control sub_type {{ $errors->first('payment_method') ? ' is-invalid' : '' }}">
                                                <option value="" selected>-Choose Payment-</option>
                                                <option value="1" @if ($value->payment_method == 1) selected @endif>
                                                    Cash On Delivery
                                                </option>
                                                <option value="2" @if ($value->payment_method == 2) selected @endif>
                                                    Cash Before Delivery
                                                </option>
                                                <option value="3" @if ($value->payment_method == 3) selected @endif>
                                                    Credit
                                                </option>
                                            </select>
                                            @error('payment_method')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <div id="top" hidden class="col-md-12 form-group">
                                            <label>Terms of Payment</label>
                                            <input type="text" class="form-control" placeholder="Product Name"
                                                name="top" value="{{ $value->top }}">
                                            @error('top')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-12 form-group mr-5">
                                            <label>Remarks</label>
                                            <textarea class="form-control" name="remark" id="" cols="30" rows="5">{{ $value->remark }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <a class="btn btn-danger" href="{{ url('recent_sales_order/') }}"> <i
                                                class="ti ti-arrow-left"> </i> Back
                                        </a>
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-6 col-xl-6">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Add Product</h5>
                    </div>
                    <div class="card-body">


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
                $('#payment_method').change(function() {
                    if ($(this).val() == 1) {
                        $('#payment').removeAttr('hidden');
                        $('#payment_type').removeAttr('hidden');
                        $('#top').attr('hidden', 'true');
                    } else {
                        $('#payment').attr('hidden', 'true');
                        $('#payment_type').attr('hidden', 'true');
                        $('#top').removeAttr('hidden');
                    }

                });
                if ($('#payment_method').val() == 1) {
                    $('#payment').removeAttr('hidden');
                    $('#payment_type').removeAttr('hidden');
                    $('#top').attr('hidden', 'true');
                } else {
                    $('#payment').attr('hidden', 'true');
                    $('#payment_type').attr('hidden', 'true');
                    $('#top').removeAttr('hidden');
                }

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
