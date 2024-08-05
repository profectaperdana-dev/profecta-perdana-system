@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.3.2/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.snow.css" rel="stylesheet" />
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

                        <div class="mt-5">
                            <h4>Product List</h4>
                            <div class="d-flex-inline flex-row bd-highlight mb-3">
                                @foreach ($all_sub_material as $item)
                                    <a href="javascript:void(0)" data-submaterial="{{ $item->id }}"
                                        class="submaterialbtn mb-2 p-2 bd-highlight @if ($loop->index == 0) text-decoration-underline fw-bold text-success @endif">{{ $item->nama_sub_material }}</a>
                                @endforeach

                            </div>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-2 mt-3 productparent">
                                @if (sizeof($filtered_products) > 0)
                                    @foreach ($filtered_products as $item)
                                        <div class="col productrow" data-id="{{ $item->id }}">
                                            <div class="card h-100 overflow-hidden">

                                                <div class="card-body productreadmode">
                                                    <span
                                                        class="card-title title fw-bold fs-6">{{ $item->sub_materials->nama_sub_material }}
                                                        {{ $item->type_name }}</span>
                                                    <form class="productform">
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $item->id }}">
                                                        <div class="row my-3">
                                                            <div class="col align-self-center imgparent"
                                                                style="background-image: url({{ $item->cmsProductBy?->photo && $item->cmsProductBy?->photo != 'blank' ? url('public/images/cms/products/' . $item->cmsProductBy?->photo) : asset('images/no-image.png') }});
                                                                background-size: contain;background-repeat:no-repeat;background-position:center">
                                                                <input class="form-control form-control-sm w-100 imginput"
                                                                    name="img" type="file"
                                                                    accept="image/png, image/jpeg"
                                                                    style="min-height:250px; opacity:0;">
                                                            </div>
                                                        </div>
                                                        {{-- <textarea name="additional_desc" cols="30" rows="10" class="form-control mb-2">{{ $item->cmsProductBy?->additional_desc }}</textarea> --}}
                                                        {{-- <input type="hidden"
                                                            value="{{ $item->cmsProductBy?->additional_desc }}"
                                                            class="desc-val"> --}}
                                                        <label for="">Description</label>
                                                        <div class="additional-desc" style="height: 250px">
                                                            {{ $item->cmsProductBy?->additional_desc }}
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-warning btnsaveproduct">Save</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col">No data</div>
                                @endif


                            </div>
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

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
        <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        {{-- <script src="https://cdn.tiny.cloud/1/2x9b4ofxf7a0flf3vawalcqujio9eow8gdqrxx8eu5bn5mid/tinymce/7/tinymce.min.js"
            referrerpolicy="origin"></script> --}}
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.0/dist/quill.js"></script>


        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // tinymce.init({
                //     selector: 'textarea',
                //     plugins: 'lists',
                //     toolbar: 'undo redo bold strikethrough bullist numlist',
                //     menubar: false


                // });

                function convertHTMLtoDelta(html) {
                    const container = document.createElement('div');
                    container.innerHTML = html;
                    const quill = new Quill(container);
                    return quill.getContents();
                }

                $('.additional-desc').each(function() {
                    var quill = new Quill(this, {
                        theme: 'snow',
                        placeholder: 'Enter additional description...',
                        height: '300px' // Atur tinggi sesuai kebutuhan

                    });
                    let htmlContent = $(this).text();
                    // console.log($(this).siblings('.desc-val').val());
                    let deltaContent = convertHTMLtoDelta(htmlContent);

                    quill.setContents(deltaContent);
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

                //Change preview image
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

                //filter
                $(document).on('click', '.submaterialbtn', function() {
                    $('.submaterialbtn').removeClass('text-decoration-underline fw-bold text-success');
                    $(this).addClass('text-decoration-underline fw-bold text-success');
                    let sub_material_id = $(this).attr('data-submaterial');

                    $.ajax({
                        url: `/api/getproduct/${sub_material_id}/filterbysubmaterial`,
                        type: 'GET',
                        cache: true,
                        success: function(res) {
                            // console.log(res.data);


                            let new_el = ``;
                            if (res.data) {
                                res.data.map(function(item) {
                                    new_el += `
                                    <div class="col productrow" data-id="${item.id}">
                                            <div class="card h-100 overflow-hidden">

                                                <div class="card-body productreadmode">
                                                    <span
                                                        class="card-title title fw-bold fs-6">${ item.sub_materials.nama_sub_material }
                                                        ${ item.type_name }</span>
                                                    <form class="productform">
                                                        <input type="hidden" name="product_id"
                                                            value="${ item.id }">
                                                        <div class="row my-3">
                                                            <div class="col align-self-center imgparent"
                                                                style="background-image: url(${item.cms_product_by && item.cms_product_by != 'blank' ? "/public/images/cms/products/" + item.cms_product_by.photo : "{{ asset('images/no-image.png') }}"});
                                                                background-size: contain;background-repeat:no-repeat;background-position:center">
                                                                <input class="form-control form-control-sm w-100 imginput"
                                                                    name="img" type="file"
                                                                    accept="image/png, image/jpeg"
                                                                    style="min-height:250px; opacity:0;">
                                                            </div>
                                                        </div>
                                                        <label for="">Description</label>
                                                        <div class="additional-desc" style="height: 250px">
                                                            ${item.cms_product_by ? item.cms_product_by.additional_desc : ''}
                                                        </div>
                                                        <button type="submit"
                                                            class="btn btn-warning btnproduct">Save</button>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>
                                    `;


                                });

                            }
                            $('.productparent').html(new_el);

                            $('.additional-desc').each(function() {
                                var quill = new Quill(this, {
                                    theme: 'snow',
                                    placeholder: 'Enter additional description...',
                                    height: '300px' // Atur tinggi sesuai kebutuhan

                                });
                                let htmlContent = $(this).html();
                                // console.log($(this).siblings('.desc-val').val());
                                let deltaContent = convertHTMLtoDelta(htmlContent);

                                quill.setContents(deltaContent);
                            });

                            // tinymce.init({
                            //     selector: 'textarea',
                            //     plugins: 'lists',
                            //     toolbar: 'undo redo bold strikethrough bullist numlist',
                            //     menubar: false
                            // });
                        },
                        error: function(err) {
                            showToast("Failed", err.responseJSON.message)

                        }
                    })
                });


                //  CRUD HANDLER START

                // Save Handler

                $(document).on('submit', '.productform', function(e) {
                    e.preventDefault();
                    let this_form = $(this);
                    // Temukan elemen .additional-desc di dalam formulir yang sedang disubmit
                    let quillContainer = this_form.find('.additional-desc');

                    // Buat instance Quill dari elemen .additional-desc yang terkait
                    let quill = new Quill(quillContainer[0]);
                    // let quill = quillContainer[0].quill;
                    let content = quill.root.innerHTML;

                    let csrfToken = '{{ csrf_token() }}';

                    let current_img = this_form.find('.imginput');
                    // let formData = $(this).serializeArray();
                    let formData = new FormData(this);
                    formData.append(
                        "_token",
                        csrfToken
                    );

                    formData.append(
                        "additional_desc",
                        content
                    );

                    formData.append(
                        "photo",
                        current_img[0].files[0]
                    );

                    $.ajax({
                        url: `/cms/api/product/save`,
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        data: formData,
                        beforeSend: function() {
                            this_form.find('.btnsaveproduct').attr('disabled', 'disabled');
                        },
                        success: function(res) {
                            // console.log(res);
                            let message = '';
                            //Show toast as alert
                            if (res.status != 200) {

                                for (const key in res.message) {
                                    if (res.message.hasOwnProperty(key)) {
                                        message += '\n' + res.message[key];
                                    }
                                }
                                showToast("Failed", message);
                                return false;
                            }
                            message = res.message;
                            showToast("Success", message)
                            this_form.find('.btnsaveproduct').removeAttr('disabled');
                        },
                        error: function(err) {
                            //Show toast as alert

                            // this_form.find('.spinner-border').toggle();
                            // this_form.find('.addfaq').toggle("slow");
                            showToast("Failed", err.responseJSON.message)

                        }
                    });
                });

                // TEAM CRUD HANDLER END

                // GALLERY CRUD HANDLER START



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
