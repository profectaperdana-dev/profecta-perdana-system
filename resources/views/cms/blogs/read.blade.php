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
        </style>
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold mb-3">Reading Page</h3>
                    <a href="{{ url('/cms/blog') }}"><button class="btn btn-primary"><i class="fa-solid fa-left-long"></i>
                            Back</button></a>
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
                        <h1 class="display-4 fw-bold">{{ $content->title }}</h1>
                        <div class="d-flex mt-4">
                            <div class="me-3 fs-6"><i class="fa-solid fa-calendar-days"></i> Posted at
                                {{ $content->post_date ? date('F d, Y', strtotime($content->post_date)) : '-' }}</div>
                            <div class="me-3 fs-6"><i class="fa-solid fa-pencil"></i>
                                {{ $content->authorBy->name }}</div>
                            @if ($content->isposted == 1)
                                <div class=" fs-6 bg-white text-info fw-bold">Posted</div>
                            @else
                                <div class="fs-6 bg-white text-warning fw-bold">Drafted</div>
                            @endif
                        </div>
                        <div class="row justify-content-center pt-3">
                            <div class="col-8">
                                <img class="img-fluid" src="{{ url('public/images/cms/blogs/' . $content->img_header) }}"
                                    alt="">
                            </div>
                        </div>

                        <div class="mt-5">
                            {!! $content->article !!}
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
        <script src="https://cdn.tiny.cloud/1/2x9b4ofxf7a0flf3vawalcqujio9eow8gdqrxx8eu5bn5mid/tinymce/7/tinymce.min.js"
            referrerpolicy="origin"></script>


        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                tinymce.init({
                    selector: 'textarea',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate ai mentions tinycomments tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                    tinycomments_mode: 'embedded',
                    tinycomments_author: 'Author name',
                    height: "580",
                    contextmenu: "blocks fontfamily fontsize |bold italic underline strikethrough | align lineheight | link image table | removeformat",
                    mergetags_list: [{
                            value: 'First.Name',
                            title: 'First Name'
                        },
                        {
                            value: 'Email',
                            title: 'Email'
                        },
                    ],
                    ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
                        "See docs to implement AI Assistant")),

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

                // Fungsi untuk membandingkan dua array serialize
                function compareSerializeArrays(arr1, arr2) {
                    // Ubah array menjadi string JSON agar mudah dibandingkan
                    let json1 = JSON.stringify(arr1);
                    let json2 = JSON.stringify(arr2);

                    // Bandingkan string JSON
                    return json1 === json2;
                }



                // GALLERY CRUD HANDLER START

                // Change Preview Image Handler
                $(document).on('change', '#imginput', function() {
                    if (this.files && this.files[0]) {
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            $('#imgparent').css('background-image', `url(${e.target.result})`);
                        }
                        reader.readAsDataURL(this.files[0]);
                    }
                });

                $(document).on('click', '#draftbtn', function() {
                    let formData = new FormData($('#formblog')[0]);
                    let csrfToken = '{{ csrf_token() }}';
                    let content = tinymce.activeEditor.getContent();
                    formData.append(
                        "_token",
                        csrfToken
                    )
                    formData.append(
                        "content",
                        content
                    )

                    $.ajax({
                        url: `{{ route('save_as_draft') }}`,
                        type: 'POST',
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        data: formData,
                        beforeSend: function() {
                            $('#draftbtn').attr('disabled', 'disabled');
                            $('#draftbtn').text('Loading...')

                        },
                        success: function(res) {
                            // console.log(res);
                            let message = '';
                            //Hide spinner and show button submit
                            $('#draftbtn').removeAttr('disabled');
                            $('#draftbtn').text('Save as Draft')
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
                            showToast("Success", message);
                            $('#id-blog').val(res.data.id);
                            $('#imginput').val(null);
                            if (res.data.img_header) {
                                $('#imgparent').css('background-image',
                                    `url('{{ url('public/images/cms/blogs/${res.data.img_header}') }}')`
                                );
                            }

                        },
                        error: function(err) {
                            //Show toast as alert

                            $('#draftbtn').removeAttr('disabled');
                            $('#draftbtn').text('Save as Draft')
                            showToast("Failed", err.responseJSON.message)

                        }
                    });
                    // console.log(tinymce.activeEditor.getContent());
                });


                // GALLERY CRUD HANDLER END

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
