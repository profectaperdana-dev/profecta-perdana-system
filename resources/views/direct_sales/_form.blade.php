<div class="container-fluid product-wrapper">
    <div class="product-grid">
        <div class="feature-products">
            <div class="row m-b-10">
                <div class="product-search">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search..." aria-label="Recipient's username"
                            aria-describedby="basic-addon2">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                    </div>
                </div>
            </div>
            <div class="row row-cols row-cols-lg-7 g-2 g-lg-1 mb-5">
                <div class="btn btn-outline-primary col me-1">All</div>
                @foreach ($sub_materials as $item)
                    <div class="btn btn-outline-primary col me-1">{{ $item->nama_sub_material }}</div>
                @endforeach
            </div>
        </div>
        <div class="product-wrapper-grid">
            <div class="row">
                @foreach ($retail_products as $item)
                    <div class="col-12 col-xl-6 col-sm-6 xl-3">
                        <div class="card">
                            <div class="product-box">
                                <div class="product-img"><img class="img-fluid" style="width: 100%;height:229px"
                                        src="{{ asset('foto_produk/' . $item->foto_barang) }}" alt="">
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
                                                            src="{{ asset('foto_produk/' . $item->foto_barang) }}"
                                                            alt="">
                                                    </div>
                                                    <div class="product-details col-lg-6 text-start">
                                                        <h4>{{ $item->nama_barang }}</h4>

                                                        <div class="product-price">Rp.
                                                            {{ number_format($item->harga_jual, 0, ',', '.') }}
                                                        </div>
                                                        <div class="product-view">
                                                            <h6 class="f-w-600">Product Details</h6>
                                                            <p class="mb-0">
                                                            <ul>
                                                                <li><strong>Material</strong>:
                                                                    {{ $item->materials->nama_material }}</li>
                                                                <li><strong>Sub-Material</strong>:
                                                                    {{ $item->sub_materials->nama_sub_material }}</li>
                                                                <li><strong>Type</strong>:
                                                                    {{ $item->sub_types->type_name }}</li>
                                                                <li><strong>Weight</strong>:
                                                                    {{ $item->berat }} gr</li>
                                                            </ul>
                                                            </p>
                                                        </div>
                                                        <br>
                                                        <div class="product-qnty">
                                                            <h6 class="f-w-600">Stock:
                                                                {{ $item->stockBy->stock . ' ' . $item->uoms->satuan }}
                                                            </h6>
                                                        </div>
                                                        <div class="product-qnty">

                                                            <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                    href="cart.html">Add to Cart </a><a
                                                                    class="btn btn-primary"
                                                                    href="product-page.html">View
                                                                    Details</a></div>
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
                                    <h4>{{ $item->nama_barang }} </h4>

                                    <p>{{ $item->materials->nama_material }} -
                                        {{ $item->sub_materials->nama_sub_material }}
                                        {{ $item->sub_types->type_name }}</p>
                                    <div class="product-price">Rp.
                                        {{ number_format($item->harga_jual, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="d-flex flex-row-reverse m-1"><a class="btn btn-primary me-3"
                                        href="cart.html">Add
                                    </a></div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-6 col-xl-4 col-sm-4 xl-3">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img"><img class="img-fluid" src="../assets/images/ecommerce/01.jpg"
                                    alt="">
                                <div class="product-hover">
                                    <ul>
                                        <li><a href="cart.html"><i class="icon-shopping-cart"></i></a></li>
                                        <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter16"><i
                                                    class="icon-eye"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCenter16">
                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="product-box row">
                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                        src="../assets/images/ecommerce/01.jpg" alt=""></div>
                                                <div class="product-details col-lg-6 text-start"><a
                                                        href="product-page.html">
                                                        <h4>Man's Jacket </h4>
                                                    </a>
                                                    <div class="product-price">$26.00
                                                        <del>$35.00</del>
                                                    </div>
                                                    <div class="product-view">
                                                        <h6 class="f-w-600">Product Details</h6>
                                                        <p class="mb-0">Sed ut perspiciatis, unde omnis iste natus
                                                            error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam eaque ipsa, quae ab illo.</p>
                                                    </div>
                                                    <div class="product-size">
                                                        <ul>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">M</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">L</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">Xl</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-qnty">
                                                        <h6 class="f-w-600">Quantity</h6>
                                                        <fieldset>
                                                            <div class="input-group">
                                                                <input class="touchspin text-center" type="text"
                                                                    value="5">
                                                            </div>
                                                        </fieldset>
                                                        <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                href="cart.html">Add to Cart </a><a
                                                                class="btn btn-primary" href="product-page.html">View
                                                                Details</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-details"><a href="product-page.html">
                                    <h4>Man's Jacket </h4>
                                </a>
                                <p>Solid Denim Jacket</p>
                                <div class="product-price">$26.00
                                    <del>$35.00</del>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-4 col-sm-4 xl-3">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img">
                                <div class="ribbon ribbon-danger">Sale</div><img class="img-fluid"
                                    src="../assets/images/ecommerce/02.jpg" alt="">
                                <div class="product-hover">
                                    <ul>
                                        <li><a href="cart.html"><i class="icon-shopping-cart"></i></a></li>
                                        <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter1"><i
                                                    class="icon-eye"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCenter1">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="product-box row">
                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                        src="../assets/images/ecommerce/02.jpg" alt=""></div>
                                                <div class="product-details col-lg-6 text-start"><a
                                                        href="product-page.html">
                                                        <h4>fido dido</h4>
                                                    </a>
                                                    <div class="product-price">$55.00
                                                        <del>$62.00 </del>
                                                    </div>
                                                    <div class="product-view">
                                                        <h6 class="f-w-600">Product Details</h6>
                                                        <p class="mb-0">Sed ut perspiciatis, unde omnis iste natus
                                                            error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam eaque ipsa, quae ab illo.</p>
                                                    </div>
                                                    <div class="product-size">
                                                        <ul>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">M</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">L</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">Xl</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-qnty">
                                                        <h6 class="f-w-600">Quantity</h6>
                                                        <fieldset>
                                                            <div class="input-group">
                                                                <input class="touchspin text-center" type="text"
                                                                    value="5">
                                                            </div>
                                                        </fieldset>
                                                        <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                href="cart.html">Add to Cart</a><a
                                                                class="btn btn-primary" href="product-page.html">View
                                                                Details</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-details"><a href="product-page.html">
                                    <h4>fido dido</h4>
                                </a>
                                <p>Solid Polo Collar T-shirt</p>
                                <div class="product-price">$55.00
                                    <del>$62.00</del>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-4 col-sm-4 xl-3">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img"><img class="img-fluid" src="../assets/images/ecommerce/03.jpg"
                                    alt="">
                                <div class="product-hover">
                                    <ul>
                                        <li><a href="cart.html"><i class="icon-shopping-cart"></i></a></li>
                                        <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter2"><i
                                                    class="icon-eye"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCenter2">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="product-box row">
                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                        src="../assets/images/ecommerce/03.jpg" alt=""></div>
                                                <div class="product-details col-lg-6 text-start"><a
                                                        href="product-page.html">
                                                        <h4>Wonder Woman</h4>
                                                    </a>
                                                    <div class="product-price">$45.00
                                                        <del>$52.00</del>
                                                    </div>
                                                    <div class="product-view">
                                                        <h6 class="f-w-600">Product Details</h6>
                                                        <p class="mb-0">Sed ut perspiciatis, unde omnis iste natus
                                                            error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam eaque ipsa, quae ab illo.</p>
                                                    </div>
                                                    <div class="product-size">
                                                        <ul>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">M</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">L</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">Xl</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-qnty">
                                                        <h6 class="f-w-600">Quantity</h6>
                                                        <fieldset>
                                                            <div class="input-group">
                                                                <input class="touchspin text-center" type="text"
                                                                    value="5">
                                                            </div>
                                                        </fieldset>
                                                        <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                href="cart.html">Add to Cart</a><a
                                                                class="btn btn-primary" href="product-page.html">View
                                                                Details</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-details"><a href="product-page.html">
                                    <h4>Wonder Woman</h4>
                                </a>
                                <p>Woman Gray Round T-shirt</p>
                                <div class="product-price">$45.00
                                    <del>$52.00</del>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-4 col-sm-4 xl-3">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img">
                                <div class="ribbon ribbon-success ribbon-right">50%</div><img class="img-fluid"
                                    src="../assets/images/ecommerce/04.jpg" alt="">
                                <div class="product-hover">
                                    <ul>
                                        <li><a href="cart.html"><i class="icon-shopping-cart"></i></a></li>
                                        <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter3"><i
                                                    class="icon-eye"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCenter3">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="product-box row">
                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                        src="../assets/images/ecommerce/04.jpg" alt=""></div>
                                                <div class="product-details col-lg-6 text-start"><a
                                                        href="product-page.html">
                                                        <h4>Roadster</h4>
                                                    </a>
                                                    <div class="product-price">$38.00
                                                        <del>$45.00 </del>
                                                    </div>
                                                    <div class="product-view">
                                                        <h6 class="f-w-600">Product Details</h6>
                                                        <p class="mb-0">Sed ut perspiciatis, unde omnis iste natus
                                                            error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam eaque ipsa, quae ab illo.</p>
                                                    </div>
                                                    <div class="product-size">
                                                        <ul>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">M</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">L</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">Xl</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-qnty">
                                                        <h6 class="f-w-600">Quantity</h6>
                                                        <fieldset>
                                                            <div class="input-group">
                                                                <input class="touchspin text-center" type="text"
                                                                    value="5">
                                                            </div>
                                                        </fieldset>
                                                        <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                href="cart.html">Add to Cart</a><a
                                                                class="btn btn-primary" href="product-page.html">View
                                                                Details</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"> </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-details"><a href="product-page.html">
                                    <h4>Roadster</h4>
                                </a>
                                <p>Women Solid Denim Jacket</p>
                                <div class="product-price">$38.00
                                    <del>$45.00 </del>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-4 col-sm-4 xl-3">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img">
                                <div class="ribbon ribbon-success ribbon-right">50%</div><img class="img-fluid"
                                    src="../assets/images/ecommerce/04.jpg" alt="">
                                <div class="product-hover">
                                    <ul>
                                        <li><a href="cart.html"><i class="icon-shopping-cart"></i></a></li>
                                        <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter4"><i
                                                    class="icon-eye"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCenter4">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="product-box row">
                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                        src="../assets/images/ecommerce/04.jpg" alt=""></div>
                                                <div class="product-details col-lg-6 text-start"><a
                                                        href="product-page.html">
                                                        <h4>Roadster</h4>
                                                    </a>
                                                    <div class="product-price">$38.00
                                                        <del>$45.00 </del>
                                                    </div>
                                                    <div class="product-view">
                                                        <h6 class="f-w-600">Product Details</h6>
                                                        <p class="mb-0">Sed ut perspiciatis, unde omnis iste natus
                                                            error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam eaque ipsa, quae ab illo.</p>
                                                    </div>
                                                    <div class="product-size">
                                                        <ul>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">M</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">L</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">Xl</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-qnty">
                                                        <h6 class="f-w-600">Quantity</h6>
                                                        <fieldset>
                                                            <div class="input-group">
                                                                <input class="touchspin text-center" type="text"
                                                                    value="5">
                                                            </div>
                                                        </fieldset>
                                                        <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                href="cart.html">Add to Cart</a><a
                                                                class="btn btn-primary" href="product-page.html">View
                                                                Details</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"> </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-details"><a href="product-page.html">
                                    <h4>Roadster</h4>
                                </a>
                                <p>Women Solid Denim Jacket</p>
                                <div class="product-price">$38.00
                                    <del>$45.00 </del>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-4 col-sm-4 xl-3">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img"><img class="img-fluid" src="../assets/images/ecommerce/01.jpg"
                                    alt="">
                                <div class="product-hover">
                                    <ul>
                                        <li><a href="cart.html"><i class="icon-shopping-cart"></i></a></li>
                                        <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter5"><i
                                                    class="icon-eye"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCenter5">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="product-box row">
                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                        src="../assets/images/ecommerce/01.jpg" alt=""></div>
                                                <div class="product-details col-lg-6 text-start"><a
                                                        href="product-page.html">
                                                        <h4>Man's Jacket </h4>
                                                    </a>
                                                    <div class="product-price">$26.00
                                                        <del>$35.00</del>
                                                    </div>
                                                    <div class="product-view">
                                                        <h6 class="f-w-600">Product Details</h6>
                                                        <p class="mb-0">Sed ut perspiciatis, unde omnis iste natus
                                                            error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam eaque ipsa, quae ab illo.</p>
                                                    </div>
                                                    <div class="product-size">
                                                        <ul>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">M</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">L</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">Xl</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-qnty">
                                                        <h6 class="f-w-600">Quantity</h6>
                                                        <fieldset>
                                                            <div class="input-group">
                                                                <input class="touchspin text-center" type="text"
                                                                    value="5">
                                                            </div>
                                                        </fieldset>
                                                        <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                href="cart.html">Add to Cart</a><a
                                                                class="btn btn-primary" href="product-page.html">View
                                                                Details</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-details"><a href="product-page.html">
                                    <h4>Man's Jacket </h4>
                                </a>
                                <p>Solid Denim Jacket</p>
                                <div class="product-price">$26.00
                                    <del>$35.00</del>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-4 col-sm-4 xl-3">
                    <div class="card">
                        <div class="product-box">
                            <div class="product-img"><img class="img-fluid" src="../assets/images/ecommerce/01.jpg"
                                    alt="">
                                <div class="product-hover">
                                    <ul>
                                        <li><a href="cart.html"><i class="icon-shopping-cart"></i></a></li>
                                        <li><a data-bs-toggle="modal" data-bs-target="#exampleModalCenter15"><i
                                                    class="icon-eye"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalCenter15">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="product-box row">
                                                <div class="product-img col-lg-6"><img class="img-fluid"
                                                        src="../assets/images/ecommerce/01.jpg" alt="">
                                                </div>
                                                <div class="product-details col-lg-6 text-start"><a
                                                        href="product-page.html">
                                                        <h4>Man's Jacket </h4>
                                                    </a>
                                                    <div class="product-price">$26.00
                                                        <del>$35.00</del>
                                                    </div>
                                                    <div class="product-view">
                                                        <h6 class="f-w-600">Product Details</h6>
                                                        <p class="mb-0">Sed ut perspiciatis, unde omnis iste natus
                                                            error sit voluptatem accusantium doloremque laudantium,
                                                            totam rem aperiam eaque ipsa, quae ab illo.</p>
                                                    </div>
                                                    <div class="product-size">
                                                        <ul>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">M</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">L</button>
                                                            </li>
                                                            <li>
                                                                <button class="btn btn-outline-light"
                                                                    type="button">Xl</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="product-qnty">
                                                        <h6 class="f-w-600">Quantity</h6>
                                                        <fieldset>
                                                            <div class="input-group">
                                                                <input class="touchspin text-center" type="text"
                                                                    value="5">
                                                            </div>
                                                        </fieldset>
                                                        <div class="addcart-btn"><a class="btn btn-primary me-3"
                                                                href="cart.html">Add to Cart</a><a
                                                                class="btn btn-primary" href="product-page.html">View
                                                                Details</a></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-details"><a href="product-page.html">
                                    <h4>Man's Jacket </h4>
                                </a>
                                <p>Solid Denim Jacket</p>
                                <div class="product-price">$26.00
                                    <del>$35.00</del>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- Container-fluid Ends-->
