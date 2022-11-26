<div class="container-fluid product-wrapper">
    <div class="product-grid">
        <div class="feature-products">

            <div class="row m-b-10">
                <div class="product-search">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Recipient's username"
                            aria-describedby="basic-addon2" id="searchRetail">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
            <div class="row row-cols row-cols-lg-7 g-2 g-lg-1 mb-5">
                <div class="btn btn-outline-primary col me-1 active btn-sub" data-id="all">All</div>
                @foreach ($sub_materials as $item)
                    <div class="btn btn-outline-primary col me-1 btn-sub" data-id="{{ $item->id }}">
                        {{ $item->nama_sub_material }}</div>
                @endforeach
            </div>
        </div>
        <div class="product-wrapper-grid">
            <div class="row" id="product-list">
                @foreach ($retail_products as $item)
                    <div class="col-12 col-xl-4 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid" style="width: 100%;height:229px"
                                        src="{{ asset('foto_produk/' . $item->productBy->foto_barang) }}"
                                        alt="">
                                    <div class="product-hover">
                                        <ul>
                                            <li><a data-bs-toggle="modal"
                                                    data-bs-target="#detailProduct{{ $item->id }}"><i
                                                        class="icon-eye"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="modal fade" id="detailProduct{{ $item->id }}">
                                    <div class="modal-dialog modal-lg modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <div class="product-box row">
                                                    <div class="product-img col-lg-6"><img class="img-fluid"
                                                            src="{{ asset('foto_produk/' . $item->productBy->foto_barang) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="product-details col-lg-6 text-start">
                                                        <h4>{{ $item->productBy->nama_barang }}</h4>

                                                        <div class="product-price">Rp.
                                                            {{ number_format($item->harga_jual, 0, ',', '.') }}
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">
                                                            <ul>
                                                                <li><strong>Material</strong>:
                                                                    {{ $item->productBy->materials->nama_material }}
                                                                </li>
                                                                <li><strong>Sub-Material</strong>:
                                                                    {{ $item->productBy->sub_materials->nama_sub_material }}
                                                                </li>
                                                                <li><strong>Type</strong>:
                                                                    {{ $item->productBy->sub_types->type_name }}</li>
                                                                <li><strong>Weight</strong>:
                                                                    {{ $item->productBy->berat }} gr</li>
                                                            </ul>
                                                            </p>
                                                        </div>
                                                        <br>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Stock:
                                                                @if ($item->productBy->stockBy == null)
                                                                    {{ '0' . ' ' . $item->productBy->uoms->satuan }}
                                                                @else
                                                                    {{ $item->productBy->stockBy->stock . ' ' . $item->productBy->uoms->satuan }}
                                                                @endif
                                                            </h6>
                                                        </div>

                                                    </div>
                                                </div>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-details">
                                    <h4>{{ $item->productBy->nama_barang }} </h4>

                                    <p>{{ $item->productBy->materials->nama_material }} -
                                        {{ $item->productBy->sub_materials->nama_sub_material }}
                                        {{ $item->productBy->sub_types->type_name }}</p>
                                    <div class="product-price">Rp.
                                        {{ number_format($item->harga_jual, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="d-flex flex-row-reverse m-1 nodeButton">
                                    <button type="button" class="btn btn-primary me-3 addProduct">Add
                                    </button>
                                    <!-- Start Parsing Data -->
                                    <input type="hidden" class="product_id" value="{{ $item->id_product }}">
                                    <input type="hidden" class="product_name"
                                        value="{{ $item->productBy->nama_barang }}">
                                    <input type="hidden" class="material"
                                        value="{{ $item->productBy->materials->nama_material }}">
                                    <input type="hidden" class="sub-material"
                                        value="{{ $item->productBy->sub_materials->nama_sub_material }}">
                                    <input type="hidden" class="sub-type"
                                        value="{{ $item->productBy->sub_types->type_name }}">
                                    <input type="hidden" class="harga" value="{{ $item->harga_jual }}">
                                    <!-- End Parsing Data -->
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
<!-- Container-fluid Ends-->
