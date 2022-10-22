@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold"> {{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create Order at Retail

                </div>

            </div>
        </div>
    </div>

    <div class="position-relative">
        <div class="position-fixed bottom-0 end-0" style="z-index: 100">
            <button class="btn btn-primary btn-circle btn-xl me-3 mb-3" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#miniCart" data-bs-toggle="tooltip" data-bs-placement="top" title="Cart">
                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger" id="count-cart">
                    0
                    <span class="visually-hidden">Products in Cart</span>
                </span>
            </button>
        </div>
    </div>


    <!-- Container-fluid starts-->
    <div class="container-fluid">

        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>Product List</h5>
                        <hr class="bg-primary">
                    </div>


                    <div class="card-body">
                        @include('direct_sales._form')
                    </div>
                </div>
            </div>

        </div>
        <form method="post" action="{{ url('retail/checkout') }}" enctype="multipart/form-data" id="">
            @csrf
            <div class="offcanvas offcanvas-end" id="miniCart" data-bs-scroll="true" data-bs-backdrop="false">
                <div class="offcanvas-header" id="headerCart">
                    <h4 class="offcanvas-title" id="offcanvasExampleLabel"><strong>Cart</strong></h4>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <hr class="bg-primary">
                    <table class="table table-borderless table-success table-striped">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Product</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody id="table-cart">
                            {{-- @foreach ($retail_products as $item)
                                <tr class="form-group">
                                    <td><button type="button" class="btn btn-sm p-3 text-center text-danger fs-5"><i
                                                class="fa fa-trash" aria-hidden="true"></i>
                                        </button></td>
                                    <td>{{ $item->materials->nama_material }} -
                                        {{ $item->sub_materials->nama_sub_material }} {{ $item->sub_types->type_name }}:
                                        <strong>{{ $item->nama_barang }}</strong>
                                    </td>
                                    <td><strong><input type="number" class="form-control" name=""></strong></td>
                                    <input type="hidden" name="retails[0]['product_id']" value="">
                                    <input type="hidden" name="retails[0]['qty']" value="">
                                    <input type="hidden" name="retails[0]['discount']" value="">
                                </tr>
                                @if ($loop->iteration == 5)
                                @break
                            @endif
                        @endforeach --}}
                        </tbody>

                    </table>


                </div>


            </div>
        </form>

    </div>

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });

                let count_cart = 0;
                $(document).on('click', '.addProduct', function() {
                    $(this).prop('disabled', true);
                    $(this).html('<i class="fa fa-check" aria-hidden="true"></i>');
                    $('#count-cart').text(++count_cart);

                    let product_id = $(this).parent().find('.product_id').val();
                    let product_name = $(this).parent().find('.product_name').val();
                    let material = $(this).parent().find('.material').val();
                    let sub_material = $(this).parent().find('.sub-material').val();
                    let sub_type = $(this).parent().find('.sub-type').val();
                    let harga_jual = $(this).parent().find('.harga').val();

                    let addCart = '<tr class="form-group"><td>' +
                        '<button type="button" class="btn btn-sm p-3 text-center text-danger fs-5 remProduct">' +
                        '<i class="fa fa-trash" aria-hidden="true"></i>' +
                        '</button>' +
                        '<input type="hidden" class="product-id-cart" value="' + product_id + '">' +
                        '</td>' +
                        '<td>' + material + ' - ' +
                        sub_material + ' ' + sub_type + ': ' +
                        '<strong>' + product_name + '</strong>' +
                        '</td>' +
                        '<td><strong><input type="number" class="form-control" name=""></strong></td>' +
                        '<input type="hidden" name="retails[0][product_id]" value="">' +
                        '<input type="hidden" name="retails[0][qty]" value="">' +
                        '<input type="hidden" name="retails[0][discount]" value="">' +
                        '</tr>';

                    $(document).find('#table-cart').append(addCart);
                });

                $(document).on('click', '.remProduct', function() {
                    let product_id_cart = $(this).parent().find('.product-id-cart').val();
                    let product_list = $('#product-list').find('#detailProduct' + product_id_cart)
                        .siblings()
                        .html();
                    console.log(product_list);
                    $(this).closest('.form-group').remove();
                    $('#count-cart').text(--count_cart);
                });

            });
        </script>
    @endpush
@endsection
