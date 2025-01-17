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
                  
                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Create Data</h5>
                        <hr class="bg-primary">
                        <div class="row justify-content-end">
                            <button class="col-2 btn btn-primary btn-sm" id="addStocks">+</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="form-label-left input_mask" method="post" action="{{ url('/second_product') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-12" id="formdynamic">
                                        <div class="form-group row">
                                            <div class="form-group col-12 col-lg-12">
                                                <div class="form-group col-md-12">
                                                    <label>Warehouse</label>
                                                    <select name="warehouses_id"
                                                        class="form-control role-acc @error('warehouses_id') is-invalid @enderror"
                                                        required>
                                                        <option value="">Choose Warehouse</option>
                                                        @foreach ($warehouse as $warehouses)
                                                            <option value="{{ $warehouses->id }}">
                                                                {{ $warehouses->warehouses }}
                                                                {{-- /{{ $warehouses->typeBy->name }} --}}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('warehouses_id')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row rounded py-2 bg-primary" style="margin-top: -40px">
                                            <div class="form-group col-12 col-lg-12">
                                                <label>Product</label>
                                                <select name="stockFields[0][product_id]"
                                                    class="form-control @error('stockFields[0][product_id]') is-invalid @enderror all_product_TradeIn"
                                                    required>
                                                    <option value="">Choose Product</option>
                                                </select>
                                                @error('stockFields[0][product_id]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="form-group col-12 col-lg-12">
                                                <label>Stock</label>
                                                <input type="number" name="stockFields[0][stock]" id="stock"
                                                    class="form-control @error('stockFields[0][stock]') is-invalid @enderror"
                                                    placeholder="Enter stock" required>
                                                @error('stockFields[0][stock]')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-12">
                                            <button type="reset" class="btn btn-warning"
                                                data-dismiss="modal">Reset</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">

                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                                                      <table id="basics" class="table display expandable-table table-striped table-sm text-capitalize" style="width:100%">

                                <thead>
                                    <tr class="text-center">
                                        <th >Action</th>
                                        <th>#</th>
                                        <th>Warehouse</th>
                                        <th>Product</th>
                                        <th>Qty</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            <td style="width: 5%">
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#changeData{{ $value->id }}">Edit</a>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                </div>
                                            </td>
                                            {{-- Modul Edit UOM --}}
                                            <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('second_product/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="PATCH">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Change Data
                                                                    {{ $value->productTradeBy->name_product_trade_in }}
                                                                </h5>
                                                                <button class="btn-close" type="button"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="container-fluid">
                                                                    <div class="form-group row">
                                                                        <div class="form-group col-md-12">
                                                                            <label class="font-weight-bold ">Qty Second
                                                                                Product</label>
                                                                            <input type="number"
                                                                                class="form-control text-capitalize {{ $errors->first('stock_') ? ' is-invalid' : '' }}"
                                                                                name="stock_"
                                                                                value="{{ old('stock_', $value->stock) }}"
                                                                                placeholder="Quantity of Second Product">
                                                                            @error('stock_')
                                                                                <small
                                                                                    class="text-danger">{{ $message }}.</small>
                                                                            @enderror
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-danger" type="button"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="reset"
                                                                    class="btn btn-warning">Reset</button>
                                                                <button class="btn btn-primary" type="submit">Save
                                                                    changes</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            {{-- End Modal Edit UOM --}}
                                            {{-- Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post"
                                                        action="{{ url('second_product/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $value->productTradeBy->name_product_trade_in }}
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
                                            </div>
                                            {{-- End Modal Delete UOM --}}
                                            <td class="text-end" style="width: 5%">{{ $key + 1 }}</td>
                                            <td>{{ $value->warehouseStockBy->warehouses }}</td>
                                            <td class="text-center">{{ $value->productTradeBy->name_product_trade_in }}</td>
                                            <td class="text-center">{{ $value->qty }}</td>


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
                let csrf = $('meta[name="csrf-token"]').attr("content");


                y = 0;

                $(".all_product_TradeIn").select2({
                    width: "100%",
                    ajax: {
                        type: "GET",
                        url: "/all_product_trade_in",
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
                                        text: item.name_product_trade_in,


                                        id: item.id,
                                    }, ];
                                }),
                            };
                        },
                    },
                });
                $("#addStocks").on("click", function() {
                    ++y;
                    let form =
                        '<div class="form-group row bg-primary rounded py-2"> <div class="form-group col-12 col-lg-12"> <label> Product </label> <select name="stockFields[' +
                        y +
                        '][product_id]"' +
                        'class="form-control all_product_TradeIn" required> <option value=""> Choose Product </option> </select>' +
                        '</div> <div class="form-group col-10 col-lg-10">' +
                        '<label> Stock </label> <input type="number" name="stockFields[' +
                        y +
                        '][stock]" id="discount"' +
                        'class="form-control" placeholder="Enter Stocks" required>' +
                        '</div>  <div class="form-group col-2 col-lg-2">' +
                        '<label for="">&nbsp;</label>' +
                        '<a href="javascript:void(0)" class="form-control text-white remStock text-center" style="border:none; background-color:red">X</a></div></div>'

                    $("#formdynamic").append(form);
                    $(".all_product_TradeIn").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/all_product_trade_in",
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
                                            text: item.name_product_trade_in,


                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });

                });
                $(document).on("click", ".remTradeIn", function() {
                    $(this).parents(".form-group").remove();
                });
               
            });
        </script>
         <script>
            $(document).ready(function() {
               
               var t = $('#basics').DataTable({
                   "pageLength" : 100,
                        dom: 'lpftrip',
                        columnDefs: [
                        {
                            searchable: false,
                            orderable: false,
                            targets: 0,
                        },{
                            searchable: false,
                            orderable: false,
                            targets: 1,
                        },
                    ],
                });
             
                t.on('order.dt search.dt', function () {
                    let i = 1;
             
                    t.cells(null, 1, { search: 'applied', order: 'applied' }).every(function (cell) {
                        this.data(i++);
                    });
                }).draw();
            });
            
        </script>
    @endpush
@endsection
