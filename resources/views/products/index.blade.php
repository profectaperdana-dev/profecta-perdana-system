@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.dataTables.min.css">
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
                        <h5>All Data</h5>
                        <hr class="bg-primary">
                        <a class="btn btn-primary" href="{{ url('products/create') }}">
                            + Create Product
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basics"
                                class="table display expandable-table table-striped table-sm text-capitalize"
                                style="width:100%">
                                <thead>
                                    <tr class="text-nowrap">
                                        {{-- <th></th> --}}
                                        <th class="text-center">#</th>
                                        <th class="text-center">Status</th>

                                        <!--<th class="text-center">Code</th>-->
                                        <th class="text-center">Product</th>
                                        <!--<th class="text-center">S/N</th>-->
                                        @can('level1')
                                            <th class="text-center">Purchase Price</th>
                                            {{-- <th class="text-center">Purchase Price Real</th> --}}
                                        @endcan
                                        <th class="text-center"> Price List</th>
                                        <th class="text-center">Palembang DS Price</th>
                                        <th class="text-center">Jambi DS Price</th>
                                        <th class="text-center">Weight</th>
                                        <th></th>
                                        <!--<th class="text-center">Materials</th>-->
                                    </tr>

                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>
                                            {{-- <td></td> --}}
                                            {{-- Modul Delete UOM --}}
                                            <div class="modal fade" id="deleteData{{ $value->id }}" role="dialog"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form method="post" action="{{ url('products/' . $value->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">Delete Data
                                                                    {{ $value->nama_barang }}</h5>
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

                                            <td>{{ $key + 1 }}</td>
                                            <td class="text-center">
                                                @if ($value->status == 1)
                                                    <span class="badge badge-success">Active</span>
                                                @else
                                                    <span class="badge badge-danger">Non Active</span>
                                                @endif
                                            </td>
                                            <!--<td class="text-uppercase">{{ $value->kode_barang }}</td>-->
                                            <td>{{ $value->nama_sub_material }} {{ $value->type_name }}
                                                {{ $value->nama_barang }}</td>
                                            <!--<td>{{ $value->no_seri }}</td>-->
                                            @can('level1')
                                                <td class="text-end">
                                                    {{ number_format((float) $value->decryptPrice(), 0, '.', ',') }}</td>
                                                {{-- <td class="text-end">
                                                    {{ $value->decryptPrice() }}</td> --}}
                                            @endcan
                                            <td class="text-end">
                                                {{ number_format(round(floatval($value->harga_jual_nonretail)), 0, '.', ',') }}
                                            </td>
                                            @foreach ($value->retailPriceBy as $retail)
                                                @if ($value->retailPriceBy->count() < 2)
                                                    <td class="text-end">
                                                        {{ number_format(round(floatval($retail->harga_jual)), 0, '.', ',') }}
                                                    </td>
                                                    <td class="text-end">0</td>
                                                @else
                                                    @if ($retail->harga_jual != null)
                                                        <td class="text-end">
                                                            {{ number_format(round(floatval($retail->harga_jual)), 0, '.', ',') }}
                                                        </td>
                                                    @else
                                                        <td class="text-end">0</td>
                                                    @endif
                                                @endif
                                            @endforeach
                                            <td class="text-end">{{ number_format($value->berat, 0, '.', ',') }}</td>
                                            <!--<td>{{ $value->nama_material }}/{{ $value->nama_sub_material }}/{{ $value->type_name }}-->
                                            </td>
                                            <td>
                                                <a href="#" data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false"><i data-feather="settings"></i></a>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <h5 class="dropdown-header">Actions</h5>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#detailData{{ $value->id }}">Detail</a>
                                                    @canany(['level1', 'level2'])
                                                        <a class="dropdown-item"
                                                            href="{{ url('/products/' . $value->id . '/edit') }}">Edit</a>
                                                    @endcanany
                                                    @can('level1')
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#deleteData{{ $value->id }}">Delete</a>
                                                    @endcan
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

    <!-- Modal Detail and Password -->
    @foreach ($data as $key => $value)
        {{-- Modul Detail UOM --}}
        <div class="modal" id="detailData{{ $value->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detail Data
                            {{ $value->nama_barang }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="mb-5 col-md-4">
                               <img width="100%" class="img-fluid shadow-lg"
                                        src="{{ url('foto_produk/' . $value->foto_barang) }}" alt=""> 
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group row font-weight-bold">
                                        <!--<div class="form-group col-md-4">-->
                                        <!--    <label>Product Code</label>-->
                                        <!--    <input type="text" class="form-control text-uppercase"-->
                                        <!--        placeholder="Product Code" readonly value="{{ $value->kode_barang }}">-->
                                        <!--</div>-->
                                        <div class="form-group col-md-4">
                                            <label>Product Name {{$value->foto_barang}}</label>
                                            <input type="text" class="form-control " placeholder="Product Name"
                                                readonly value="{{ $value->nama_barang }}">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label>Serial Number</label>
                                            <input type="text" class="form-control" placeholder="Serial Number"
                                                readonly value="{{ $value->no_seri }}">

                                        </div>

                                        <div class="form-group col-md-6">
                                            <label>
                                                Unit of Measurement</label>
                                            <input type="text" class="form-control" placeholder="Serial Number"
                                                readonly value="{{ $value->satuan }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>
                                                Material</label>
                                            <input type="text" class="form-control" placeholder="Serial Number"
                                                readonly value="{{ $value->nama_material }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>
                                                Sub Material</label>
                                            <input type="text" class="form-control" placeholder="Serial Number"
                                                readonly value="{{ $value->nama_sub_material }}">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>
                                                Sub Material</label>
                                            <input type="text" class="form-control" placeholder="Serial Number"
                                                readonly value="{{ $value->type_name }}">
                                        </div>

                                        @can('level1')
                                            <div class="form-group col-md-6">
                                                <label>Purchase Price (exclude PPN)
                                                </label>
                                                <div class="purchase-price">
                                                    <!--<button class="btn btn-primary form-control text-white btn-show-ps"-->
                                                    <!--    data-bs-target="#passwordenc{{ $value->id }}"-->
                                                    <!--    data-bs-toggle="modal" data-bs-dismiss="modal">-->
                                                    <!--    Encrypted-->
                                                    <!--</button>-->
                                                    <input type="text" readonly class="form-control value-purchase"
                                                        value="{{ $value->dotSeparator($value->decryptPrice()) }}">
                                                </div>
                                            </div>
                                        @endcan


                                        <div class="form-group col-md-6">
                                            <label>Non Retail Selling
                                                Price (Excl. PPN)</label>
                                            <input type="text" class="form-control" readonly
                                                placeholder="Non Retail Selling Price"
                                                value="{{ $value->dotSeparator($value->harga_jual_nonretail) }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Product Weight (gr)</label>
                                            <input type="number" class="form-control" readonly
                                                value="{{ $value->dotSeparator($value->berat) }}">
                                        </div>

                                        <div class="form-group col-md-3">
                                            <label>Min Stock</label>
                                            <input type="number" class="form-control" readonly
                                                value="{{ $value->minstok }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Shown At</label>
                                            <input type="text" class="form-control" readonly
                                                value="{{ ucfirst($value->shown) }}">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Status</label>
                                            @if ($value->status == 0)
                                                <div>
                                                    <span class="badge badge-danger">Non
                                                        Active</span>
                                                </div>
                                            @else
                                                <div><span class="badge badge-success">
                                                        Active</span></div>
                                            @endif
                                        </div>
                                        <div class="mx-auto py-2 rounded form-group row bg-primary getIndex">

                                            @foreach ($value->productCosts as $cost)
                                                <div class="form-group col-12 col-lg-6">
                                                    <label>Warehouse</label>
                                                    <input type="text" class="form-control" readonly
                                                        value=" {{ $cost->warehouseBy->warehouses }}">
                                                </div>
                                                <div class="col-10 col-lg-6 form-group">
                                                    <label>Retail Price <small class="badge badge-primary">(ex.
                                                            PPN)</small></label>
                                                    <input type="text" class="form-control" readonly
                                                        value="{{ $value->dotSeparator($cost->harga_jual) }}">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal" id="passwordenc{{ $value->id }}" tabindex="-1" data-bs-backdrop="static"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Enter Your Password
                        </h5>
                        <button type="button" class="btn-close" data-bs-target="#detailData{{ $value->id }}"
                            data-bs-toggle="modal" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control pw" type="password" name="passwordenc"
                            placeholder="Enter The Password" autocomplete="one-time-code" readonly
                            onfocus="this.removeAttribute('readonly');">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-target="#detailData{{ $value->id }}"
                            data-bs-toggle="modal" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-enc">Open</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- End Modal Detail UOM --}}
    @endforeach
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
        <script>
            $(document).ready(function() {

                var t = $('#basics').DataTable({
                    responsive: {
                        details: {
                            type: 'column'
                        }
                    },
                    order: [1, 'asc'],
                    "pageLength": 100,
                    dom: 'lpftrip',
                    columnDefs: [{
                        className: 'dtr-control',
                        orderable: false,
                        targets: 0
                    }, {
                        searchable: false,
                        orderable: false,
                        targets: 0,
                    }, {
                        searchable: false,
                        orderable: false,
                        targets: 1,
                    }, ],
                });

                t.on('order.dt search.dt', function() {
                    let i = 1;

                    t.cells(null, 0, {
                        search: 'applied',
                        order: 'applied'
                    }).every(function(cell) {
                        this.data(i++);
                    });
                }).draw();
            });
        </script>
        <script>
            $(document).ready(function() {
                var prev_modal = '';
                var prev_enc_modal = '';

                $(document).on('click', '.btn-show-ps', function() {
                    prev_modal = $(this).parents().closest('.modal').attr('id');
                    prev_enc_modal = $(this).attr('data-bs-target');
                });


                $('.btn-enc').click(function() {
                    let pw = $(prev_enc_modal).find('.pw').val();
                    $.ajax({
                        contex: this,
                        type: 'GET',
                        url: "/decrypt",
                        data: {
                            c: pw,
                        },
                        dataType: "json",
                        delay: 250,
                        success: function(data) {
                            if (data) {
                                $('#' + prev_modal).find('.purchase-price').find('.value-purchase')
                                    .attr('hidden', false);
                                $('#' + prev_modal).find('.purchase-price').find('.btn-show-ps')
                                    .attr('hidden', true);
                                $(prev_enc_modal).modal('hide');
                                $('#' + prev_modal).modal('show');
                            } else {
                                $(prev_enc_modal).find('.modal-body').append(
                                    '<small class="text-danger">Wrong Password</small>');
                            }

                        },
                    });
                });
            });
        </script>
    @endpush
@endsection
