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

            #imgparent {
                /* background-image: url({{ asset('images/no-image.png') }}); */
                background-position: center;
                background-repeat: no-repeat;
                background-size: contain;
            }

            #imgparent_2 {
                /* background-image: url({{ asset('images/no-image.png') }}); */
                background-position: center;
                background-repeat: no-repeat;
                background-size: contain;
            }

            .card-img-top {
                transform-origin: 50% 65%;
                transition: transform 1s, filter 2s ease-in-out;

            }

            .card-img-top:hover {

                transform: scale(1.2);
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
                        <div class="row ">
                            <div class="card mb-10 col-lg-12 col-12 ">
                                <form action="" id="inputhomepageform" enctype="multipart/form-data">
                                    <div id="formBanner" class="border-bottom pb-3 border-2">
                                        <h4> Input Banner</h4>
                                        @if ($homepage->bannerBy->count() > 0)
                                            @foreach ($homepage->bannerBy as $item)
                                                <div class="row g-0 mt-3 banner_parent" data-index="{{ $loop->index }}">
                                                    <div class="col-md-12 align-self-center" id="imgparent_2"
                                                        style="background-image: url({{ $item->banner ? url('public/images/cms/homepages/' . $item->banner) : asset('images/no-image.png') }});">
                                                        <input class="form-control form-control-sm w-400 imginput"
                                                            name="sobanner[{{ $loop->index }}][img1]" type="file"
                                                            accept="image/png, image/jpeg"
                                                            style="min-height:250px; opacity:0;">
                                                    </div>
                                                    <div class="mt-5 row g-12">
                                                        <div class="col-12 col-lg-6">
                                                            <h5 class="card-title">
                                                                <label class="form-label">Title</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter title..."
                                                                    name="sobanner[{{ $loop->index }}][title_banner]"
                                                                    value="{{ $item->title_banner }}">
                                                            </h5>
                                                        </div>
                                                        <div class=" col-12 col-lg-6">
                                                            <p class="card-text">
                                                                <label class="form-label">Caption</label>
                                                                <textarea name="sobanner[{{ $loop->index }}][caption]" id="" class="form-control"
                                                                    placeholder="Enter caption...">{{ $item->caption }}</textarea>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="row g-12">
                                                        <div class="col-6">
                                                            <label for="">&nbsp;</label>
                                                            <a href="javascript:void(0)"
                                                                class="form-control btn btn-sm text-white addBanner text-center"
                                                                style="border:none; background-color:#276e61">+</a>
                                                        </div>
                                                        @if ($loop->index > 0)
                                                            <div class="col-6">
                                                                <label for=""> &nbsp; </label>
                                                                <a href="#"
                                                                    class="form-control text-white delete text-center"
                                                                    style="border:none; background-color:#d94f5c">
                                                                    - </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row g-0 banner_parent" data-index="0">
                                                <div class="col-md-12 align-self-center"
                                                    style="background-image: url({{ asset('images/no-image.png') }});">
                                                    <input class="form-control form-control-sm w-100 imginput"
                                                        class="img_3" name="sobanner[0][img1]" type="file"
                                                        accept="image/png, image/jpeg" style="min-height:250px; opacity:0;">
                                                </div>
                                                <div class="mt-5 row g-12">
                                                    <div class="col-12 col-lg-6">
                                                        <h5 class="card-title">
                                                            <label class="form-label">Title</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter title..."
                                                                name="sobanner[0][title_banner]" value="">
                                                        </h5>
                                                    </div>
                                                    <div class=" col-12 col-lg-6">
                                                        <p class="card-text">
                                                            <label class="form-label">Caption</label>
                                                            <textarea name="sobanner[0][caption]" id="" class="form-control" placeholder="Enter caption..."></textarea>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="row g-12">
                                                    <div class="col-6">
                                                        <label for="">&nbsp;</label>
                                                        <a href="javascript:void(0)"
                                                            class="form-control btn btn-sm text-white addBanner text-center"
                                                            style="border:none; background-color:#276e61">+</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-5 border-bottom pb-3 border-2">
                                        <h4>Input Description and Benefit</h4>
                                        <div class="mx-auto py-2 form-group rounded row">
                                            <div class="col-lg-6">
                                                <div class="col-md-4 align-self-center " id="imgparent"
                                                    style="background-image: url({{ $homepage->img ? url('public/images/cms/homepages/' . $homepage->img) : asset('images/no-image.png') }});">
                                                    <input class="form-control form-control-sm w-100" id="input_2"
                                                        name="img_input_2" type="file" accept="image/png, image/jpeg"
                                                        style="min-height:250px; opacity:0;">
                                                </div>
                                                <div class="col-12 col-lg-12">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            <div class="mb-3">
                                                                <label class="form-label">Title </label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter title..." name="title"
                                                                    value="{{ $homepage->title }}">
                                                            </div>
                                                        </h5>
                                                        <p class="card-text">
                                                        <div class="mb-3">
                                                            <label class="form-label">Description</label>
                                                            <textarea name="description" id="" class="form-control" placeholder="Enter description...">{{ $homepage->description }}</textarea>
                                                        </div>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6" id="parentbenefit">

                                                @if ($homepage->benefitBy->count() > 0)
                                                    @foreach ($homepage->benefitBy as $item)
                                                        <div class="row g-4 input_benefit"
                                                            data-index="{{ $loop->index }}">
                                                            <div class="col-7 col-md-7">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Benefit</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Enter benefit..."
                                                                        name="sobenefit[{{ $loop->index }}][title_benefit]"
                                                                        value="{{ $item->title_benefit }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-4 col-md-2">
                                                                <div class="mb-3">
                                                                    <label class="form-label">&nbsp;</label>
                                                                    <a href="javascript:void(0)"
                                                                        class="form-control btn btn-sm text-white addBenefit text-center"
                                                                        style="border:none; background-color:#276e61">+</a>
                                                                </div>
                                                            </div>
                                                            @if ($loop->index > 0)
                                                                <div class="col-2 col-md-2">
                                                                    <div class="mb-3">
                                                                        <label class="form-label" for=""> &nbsp;
                                                                        </label>
                                                                        <a href="javascript:void(0)"
                                                                            class="form-control text-white rem text-center"
                                                                            style="border:none; background-color:#d94f5c">
                                                                            - </a>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="row g-4 input_benefit" data-index="0">
                                                        <div class="col-6 col-md-9">
                                                            <div class="mb-3">
                                                                <label class="form-label">Benefit</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter benefit..."
                                                                    name="sobenefit[0][title_benefit    ]"
                                                                    value="{{ $item->title_benefit }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-6 col-md-2">
                                                            <div class="mb-3">
                                                                <label class="form-label">&nbsp;</label>
                                                                <a href="javascript:void(0)"
                                                                    class="form-control btn btn-sm text-white addBenefit text-center"
                                                                    style="border:none; background-color:#276e61">+</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>

                                        </div>
                                    </div>

                                    <div class="mt-5 border-bottom pb-3 border-2" id="formReview">
                                        <h4>Input Review</h4>
                                        @if ($homepage->reviewBy->count() > 0)
                                            @foreach ($homepage->reviewBy as $item)
                                                <div class="mx-auto py-2 form-group rounded row review_parent"
                                                    data-index="{{ $loop->index }}">
                                                    <div class="mb-2 col-12 col-lg-6">
                                                        <label class="form-label">Text Review</label>
                                                        <textarea name="soreview[{{ $loop->index }}][text_review]" id="" class="form-control"
                                                            placeholder="Enter description...">{{ $item->text_review }}</textarea>
                                                    </div>
                                                    <div class="mx-auto py-2 form-group rounded row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Author</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter Author..."
                                                                    name="soreview[{{ $loop->index }}][author]"
                                                                    value="{{ $item->author }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 row">
                                                            <div class="col-6 col-md-3">
                                                                <div class="mb-3">
                                                                    <label class="form-label">&nbsp;</label>
                                                                    <a href="javascript:void(0)"
                                                                        class="form-control btn btn-sm text-white addReview text-center mb-2"
                                                                        style="border:none; background-color:#276e61; margin-bottom: 10px;">+</a>
                                                                </div>
                                                            </div>
                                                            @if ($loop->index > 0)
                                                                <div class="col-6 col-md-3">
                                                                    <label for=""> &nbsp; </label>
                                                                    <a href="#"
                                                                        class="form-control text-white broke text-center"
                                                                        style="border:none; background-color:#d94f5c">
                                                                        - </a>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="mx-auto py-2 form-group rounded row review_parent" data-index="0">
                                                <div class="mb-2 col-12 col-lg-6">
                                                    <label class="form-label">Text Review</label>
                                                    <textarea name="soreview[0][text_review]" id="" class="form-control" placeholder="Enter description..."></textarea>
                                                </div>
                                                <div class="mx-auto py-2 form-group rounded row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label class="form-label">Author</label>
                                                            <input type="text" class="form-control"
                                                                placeholder="Enter Author..." name="soreview[0][author]">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 row">

                                                        <div class="col-6 col-md-3">
                                                            <div class="mb-3">
                                                                <label class="form-label">&nbsp;</label>
                                                                <a href="javascript:void(0)"
                                                                    class="form-control btn btn-sm text-white addReview text-center mb-2"
                                                                    style="border:none; background-color:#276e61; margin-bottom: 10px;">+</a>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>


                                    <div class="mt-5">
                                        <h4>Input Achievement</h4>
                                        <div class="mx-auto py-2 form-group rounded row">
                                            <div class="mb-2 col-6 col-lg-4">
                                                <label>Customer Total</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Enter Customer Total..." name="total_costumer"
                                                    value="{{ $homepage->costumer_total }}">
                                            </div>
                                            <div class="col-6 col-lg-4">
                                                <label>Sales Total</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Enter Sales Total..." name="tota_sales"
                                                    value="{{ $homepage->sales_total }}">
                                            </div>
                                            <div class="col-6 col-lg-4">
                                                <label>Established Since</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Enter Established Since..." name="total_established"
                                                    value="{{ $homepage->established }}">
                                            </div>
                                        </div>
                                    </div>
                            </div>
                            <div class="row col-lg-3">

                                <button type="submit" class="btn btn-success" id="btnaddhp">Save</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="statustoast">Bootstrap</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" onclick="$('#liveToast').hide()"
                    aria-label="Close"></button>
            </div>
            <div class="toast-body" id="messagetoast">
                Hello, world! This is a toast message.
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content ">

                <div class="modal-body bg-success  p-0">
                    <img src="{{ asset('images/no-image.png') }}" class="img-fluid w-100" alt="...">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
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
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $('.selectMulti').select2({
                    placeholder: 'Select an option',
                    allowClear: true,
                    maximumSelectionLength: 1,
                    width: '100%',
                });

                //Show Toast function
                function showToast(status = "", message = "") {
                    $('#statustoast').text(status);
                    $('#messagetoast').text(message);
                    $('#liveToast').show();
                }


                //Add Data Banner
                $(document).on("click", ".addBanner", function() {
                    let x = $('.banner_parent').last().attr('data-index')
                        ++x;
                    let form = `<div class="row g-0 mt-3 banner_parent" data-index="${x}">
                                            <div class="col-md-12 align-self-center imgparent2" 
                                                style="background-image: url({{ asset('images/no-image.png') }});">
                                                <input class="form-control form-control-sm w-100 imginput" 
                                                    name="sobanner[${x}][img1]" type="file" accept="image/png, image/jpeg"
                                                    style="min-height:250px; opacity:0;">
                                            </div>

                                            <div class="row g-12 mt-5">
                                                <div class="col-12 col-lg-6">
                                                    <h5 class="card-title">
                                                        <label class="form-label">Title</label>
                                                        <input type="text" class="form-control"
                                                            placeholder="Enter title..." name="sobanner[${x}][title_banner]">
                                                    </h5>
                                                </div>
                                                <div class=" col-12 col-lg-6">
                                                    <p class="card-text">
                                                        <label class="form-label">Caption</label>
                                                        <textarea name="sobanner[${x}][caption]" id="" class="form-control" placeholder="Enter description..."></textarea>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="row g-12">
                                                <div class="col-6 ">
                                                    <label for="">&nbsp;</label>
                                                    <a href="javascript:void(0)"
                                                        class="form-control btn btn-sm text-white addBanner text-center"
                                                        style="border:none; background-color:#276e61">+</a>

                                                </div>
                                                <div class="col-6 ">
                                                    <label for=""> &nbsp; </label>
                                                    <a href="javascript:void(0)" class="form-control text-white delete text-center" style="border:none; background-color:#d94f5c">
                                                    - </a>
                                                </div>
                                            </div>
                                    </div>`;
                    $("#formBanner").append(form);
                    // $("#formSo").find('.hideAdd').not(':last').attr('hidden', 'true');
                });

                $(document).on('click', '.rem', function() {
                    $(this).closest('.row').remove()
                });
                $(document).on('click', '.delete', function() {
                    $(this).parent().parent().parent().remove()
                });
                $(document).on('click', '.broke', function() {
                    $(this).closest('.review_parent').remove()
                });


                //Add Data Benefit
                $(document).on("click", ".addBenefit", function() {
                    let y = $('.input_benefit').last().attr('data-index');
                    ++y;
                    let form = `<div class="row g-4 input_benefit" data-index="${y}">
                                                            <div class="col-7 col-md-7">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Benefit</label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="Enter benefit..."
                                                                        name="sobenefit[${y}][title_benefit]"
                                                                        >
                                                                </div>
                                                            </div>
                                                            <div class="col-2 col-md-2">
                                                                <div class="mb-3">
                                                                    <label class="form-label">&nbsp;</label>
                                                                    <a href="javascript:void(0)"
                                                                        class="form-control btn btn-sm text-white addBenefit text-center"
                                                                        style="border:none; background-color:#276e61">+</a>
                                                                </div>
                                                            </div>
                                                            <div class="col-2 col-md-2">
                                                                <div class="mb-3">
                                                                <label class="form-label" for=""> &nbsp; </label>
                                                                <a href="javascript:void(0)" class="form-control text-white rem text-center" style="border:none; background-color:#d94f5c">
                                                                - </a>
                                                                </div>
                                                            </div>
                                                        </div>`;
                    $("#parentbenefit").append(form);
                    // $("#formSo").find('.hideAdd').not(':last').attr('hidden', 'true');
                });



                //Add Data Review
                $(document).on("click", ".addReview", function() {
                    let Z = $('.review_parent').last().attr('data-index');
                    ++Z;
                    let form = ` <div class="mx-auto py-2 form-group rounded row review_parent" data-index="${Z}">
                                                    <div class="mb-2 col-12 col-lg-6">
                                                        <label class="form-label">Text Review</label>
                                                        <textarea name="soreview[${Z}][text_review]" id="" class="form-control"
                                                            placeholder="Enter description..."></textarea>
                                                    </div>
                                                    <div class="mx-auto py-2 form-group rounded row">
                                                        <div class="col-lg-6">
                                                            <div class="mb-3">
                                                                <label class="form-label">Author</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Enter Author..."
                                                                    name="soreview[${Z}][author]"
                                                                    ">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6 row">
                                                            <div class="col-6 col-md-3">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">&nbsp;</label>
                                                                        <a href="javascript:void(0)"
                                                                            class="form-control btn btn-sm text-white addReview text-center mb-2"
                                                                            style="border:none; background-color:#276e61; margin-bottom: 10px;">+</a>
                                                                    </div>
                                                                </div>
                                                                <div class="col-6 col-md-3">
                                                                    <div class="mb-3">
                                                                     <label class="form-label" for=""> &nbsp; </label>
                                                                      <a href="javascript:void(0)" class="form-control text-white broke text-center" style="border:none; background-color:#d94f5c">
                                                                     - </a>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>`;
                    $("#formReview").append(form);
                    // $("#formSo").find('.hideAdd').not(':last').attr('hidden', 'true');
                });


                // Change Preview Image Handler
                $(document).on('change', '.imginput', function() {
                    let this_img = $(this);
                    if (this.files && this.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            this_img.parent().css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                // Change Preview Image Handler_2
                $(document).on('change', '#input_2', function() {
                    if (this.files && this.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imgparent').css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                // Change Preview Image 3 Handler
                $(document).on('change', '.img_3', function() {
                    if (this.files && this.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imgparent').css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });




                // Create HomePage Handler
                $(document).on('submit', '#inputhomepageform', function(e) {
                    e.preventDefault();


                    let this_form = $(this);
                    let csrfToken = '{{ csrf_token() }}';
                    let form = new FormData($('#inputhomepageform')[0]);
                    form.append(
                        "_token",
                        csrfToken
                    )
                    console.log(Object.fromEntries(form));

                    // form.append(
                    //     "img1",
                    //     $('#imginput')[0].files[0]
                    // );

                    // form.append(
                    //     "img_input_2",
                    //     $('#input_2')[0].files[0]
                    // );


                    $.ajax({
                        url: `api/homepage/store`,
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        data: form,
                        beforeSend: function() {
                            $('#btnaddhp').attr('disabled', 'disabled');
                            $('#btnaddhp').text('Loading...')

                        },
                        success: function(res) {
                            // console.log(res);
                            let message = '';
                            //Hide spinner and show button submit
                            $('#btnaddhp').removeAttr('disabled');
                            $('#btnaddhp').text('Add')
                            //Show toast as alert
                            if (res.status != 200) {

                                for (const key in res.message) {

                                    showToast("Failed", message);
                                    return false;
                                }

                            }
                            message = res.message;
                            showToast("Success", message);
                        },
                        error: function(err) {
                            //Show toast as alert

                            $('#btnaddhp').removeAttr('disabled');
                            $('#btnaddhp').text('Add')
                            showToast("Failed", err.responseJSON.message)

                        }
                    });
                });
            });

            $(function() {
                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: false,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });
            });
        </script>
    @endpush
@endsection
