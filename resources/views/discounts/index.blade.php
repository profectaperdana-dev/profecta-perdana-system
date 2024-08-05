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
                   
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                   
                    <div class="card-body">
                        <form class="form-label-left input_mask" method="post" action="{{ url('/discounts') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12" id="formdynamic">
                                    <div class="form-group row">
                                        <div class="form-group col-md-12">
                                            <label>Customer</label>
                                            <select name="customer_id" id="customer"
                                                class="form-control discount @error('customer_id') is-invalid @enderror"
                                                required>
                                                <option value="">Choose Customer</option>
                                                @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        @if ($customer->id == old('customer_id')) selected @endif>
                                                        {{ $customer->name_cust . ' | ' . $customer->code_cust }}</option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        @foreach ($types as $type)
                                            <div class="form-group col-5">
                                                <select name="discountFields[{{ $loop->index }}][product_id]" readonly
                                                    class="form-control">
                                                    <option value="{{ $type->id }}">
                                                        {{ $type->sub_materials->nama_sub_material }}
                                                        {{ $type->type_name }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-4">
                                                <input type="text" name="discountFields[{{ $loop->index }}][discount]"
                                                    id="discount"
                                                    class="form-control @error('discountFields[0][discount]') is-invalid @enderror"
                                                    placeholder="Disc (%)" required>
                                                @error('discountFields[0][discount]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <button type="reset" class="btn btn-warning" data-dismiss="modal">Reset</button>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-2" class="display table table-striped expandable-table text-capitalize "
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th class="text-center">Customer Name</th>
                                        <th style="width: 10%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $count = 1;
                                    @endphp
                                    @foreach ($customers_with_disc as $key => $value)
                                        <tr>

                                            {{-- Modul Edit Discount --}}

                                            {{-- End Modal Edit discount --}}
                                            {{-- Modul Delete discount --}}
                                            {{-- <div class="modal fade" id="deleteData{{ $discount->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <form method="post" action="{{ url('discounts/' . $discount->id) }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('delete') <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                {{ $discount->customerBy->name_cust . '| Product: ' . $discount->productBy->type_name }}
                                                            </h5>
                                                            <button class="btn-close" type="button"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="container-fluid">
                                                                <div class="form-group row">
                                                                    <div class="col-md-12">
                                                                        <h5>Are you sure delete this data ?</h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-danger" type="button"
                                                                data-bs-dismiss="modal">Close</button>
                                                            <button class="btn btn-primary" type="submit">Yes, delete
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div> --}}
                                            {{-- End Modal Delete UOM --}}
                                            <td class="text-end">{{ $count++ }}</td>
                                            <td>{{ $value->code_cust }} - {{ $value->name_cust }}</td>
                                            <td style="width: 10%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item modal-btn" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#changeData{{ $value->id }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>
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

    @foreach ($customers_with_disc as $key => $value)
        <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <form id="changeData{{ $value->id }}form" method="post" action="{{ url('discounts/' . $value->id) }}"
                    enctype="multipart/form-data" class="form">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Change
                                Discount
                                Data of
                                {{ $value->name_cust }}
                            </h5>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="form-group row">
                                    <div class="form-group col-md-12">
                                        <label>Customer</label>
                                        <input class="form-control" type="text" name="" id=""
                                            value="{{ $value->name_cust }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    @foreach ($types as $type)
                                        <div class="form-group col-7 col-lg-6">
                                            @if ($loop->first)
                                                <label for="">Product</label>
                                            @endif
                                            <select name="editFields[{{ $loop->index }}][product_id]" readonly
                                                class="form-control">
                                                <option value="{{ $type->id }}">
                                                    {{ $type->sub_materials->nama_sub_material }}
                                                    {{ $type->type_name }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-5 col-lg-6">
                                            @if ($loop->first)
                                                <label for="">Disc(%)</label>
                                            @endif
                                            <input type="text" name="editFields[{{ $loop->index }}][discount]"
                                                id="discount"
                                                class="form-control @error('editFields[{{ $loop->index }}][discount]') is-invalid @enderror"
                                                placeholder="Disc (%)" value="{{ $value->getDiscount($type->id) }}"
                                                required>
                                            @error('editFields[{{ $loop->index }}][discount]')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    @endforeach
                                </div>
                                {{-- @foreach ($value->haveDiscounts as $item)
                                <div class="form-group row">
                                    <div class="form-group col-7 col-lg-6">
                                        @if ($loop->first)
                                            <label for="">Product</label>
                                        @endif
                                        <select
                                            name="editFields[{{ $loop->index }}][product_id]"
                                            class="form-control" readonly>
                                            <option selected
                                                value="{{ $item->product_id }}">
                                                {{ $item->productBy->sub_materials->nama_sub_material }}
                                                {{ $item->productBy->type_name }}
                                            </option>
                                        </select>
                                        @error('product_id_edit')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-5 col-lg-6">
                                        @if ($loop->first)
                                            <label for="">Disc(%)</label>
                                        @endif
                                        <input type="text"
                                            name="editFields[{{ $loop->index }}][discount]"
                                            id="discount"
                                            class="form-control @error('discount_edit') is-invalid @enderror"
                                            placeholder="Disc"
                                            value="{{ str_replace('.', ',', $item->discount) }}"
                                            required>
                                        @error('discount_edit')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach --}}

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                            <button type="reset" class="btn btn-warning">Reset</button>
                            <button class="btn btn-primary" type="submit">Save
                                changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
    <!-- Container-fluid Ends-->

    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script>
            $(document).ready(function() {
                $(document).on('submit', 'form', function() {
                    var form = $(this);
                    var button = form.find('button[type="submit"]');
                    // console.log(form.html());

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);

                    }
                });
                let csrf = $('meta[name="csrf-token"]').attr("content");

                // $('.edit').dblclick(function() {
                //     let cur_text = $(this).text().trim();
                //     $(this).html(`<input name="" value="${cur_text}">`);
                //     let id_ = $(this).attr('data-id');

                //     $(this).focusout(function() {
                //         $.ajax({
                //             type: "GET",
                //             context: this,
                //             url: "discount/updateInline/" + id_,
                //             data: {
                //                 _token: csrf,
                //                 i: $(this).find('input').val(),
                //             },
                //             dataType: "json",
                //             success: function(data) {
                //                 $(this).html($(this).find('input').val());
                //             },
                //         });
                //     })
                // });
                $(".product-append-discount").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/product_sub_types/selectAll",
                        data: function(params) {
                            return {
                                _token: csrf,
                                q: params.term, // search term
                            };
                        },
                        dataType: "json",
                        delay: 250,
                        processResults: function(data) {
                            return {
                                results: $.map(data, function(item) {
                                    return [{
                                        text: item.nama_sub_material + " " + item.type_name,
                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });

                $(document).on("click", ".modal-btn", function(event) {
                    let modal_id = $(this).attr('data-bs-target');

                    var this_form = $(modal_id).parent().find('.form');
                    console.log(this_form.parent().html());

                    this_form.on('submit', function(e) {
                        var buttons = this_form.find('button[type="submit"]');

                        if (this_form[0].checkValidity()) {
                            buttons.prop('disabled', true);
                        }
                    });
                    // $(modal_id).find('form').submit(function() {

                    //     var form = $(this);
                    //     var button = form.find('button[type="submit"]');
                    //     // console.log(form.html());
                    //     if (form[0].checkValidity()) { // check if form has input values
                    //         button.prop('disabled', true);
                    //         // e.preventDefault(); // prevent form submission
                    //     }
                    // });

                    $(".product-append-discount").select2({
                        dropdownParent: modal_id,
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/product_sub_types/selectAll",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_sub_material + " " +
                                                item.type_name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                });

                let i = 0;

                $("#addfields").on("click", function() {
                    ++i;
                    let form =
                        '<div class="form-group row"> <div class="form-group col-7" > <label> Product </label> <select name="discountFields[' +
                        i +
                        '][product_id]"' +
                        'class="form-control product-append-discount" required> <option value=""> Choose Product </option> </select>' +
                        '</div> <div class="form-group col-3">' +
                        '<label>Disc (%)</label> <input type="text" name="discountFields[' +
                        i +
                        '][discount]" id="discount"' +
                        'class="form-control" placeholder="Disc" required>' +
                        '</div>  <div class="form-group col-2">' +
                        '<label for="">&nbsp;</label>' +
                        '<a href="javascript:void(0)" class="form-control text-center text-white remfields" style="border:none; background-color:red">&#9747;</a> </div> </div>';

                    $("#formdynamic").append(form);
                    $(".product-append-discount").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/product_sub_types/selectAll",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: item.nama_sub_material + " " +
                                                item.type_name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                });
                $(document).on("click", ".remfields", function() {
                    $(this).parents(".form-group").remove();
                });

                var table =

                    $('#basic-2').DataTable({
                        pageLength: -1
                    });

                // //Order by the grouping
                $('#discount-table tbody').on('click', 'tr.group', function() {
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
