@extends('layouts.master')
@section('content')
    @push('css')
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">
                        {{ $title }}
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
                        </div>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation" novalidate method="post" action="{{ url('/expenses/store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row font-weight-bold " id="formTradeIn">
                                        <div class="form-group row">
                                            <div class="col-md-12 form-group">
                                                <label class="text-black">
                                                    Date
                                                    @php
                                                        $now = date('Y-m-d', strtotime('now'));
                                                    @endphp
                                                    {{ $now }}
                                                </label>
                                                <input type="date" name="date" max="{{ $now }}"
                                                    class="form-control text-capitalize" placeholder="Enter Date" required>
                                            </div>
                                        </div>
                                        <div class="mx-auto py-2 form-group row bg-primary rounded">
                                            <div class="form-group col-12 col-lg-5">
                                                <label class="font-weight-bold">Account</label>
                                                <select name="accountFields[0][account]" required
                                                    class="account form-control text-capitalize required">
                                                    <option value="">--Select Account--</option>
                                                    @foreach ($account as $type_account)
                                                        <option value="{{ $type_account->id }}">
                                                            ({{ $type_account->code }})
                                                            - {{ $type_account->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-10 col-lg-5 form-group">
                                                <label class="font-weight-bold">Total</label>
                                                <input type="text" required class="total form-control text-capitalize"
                                                    placeholder="Enter Total">
                                                <input type="hidden" name="accountFields[0][total]" class="total_">
                                            </div>
                                            <div class="col-2 col-lg-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="addfields" href="javascript:void(0)"
                                                    class="form-control text-white  text-center"
                                                    style="border:none; background-color:green">+</a>
                                            </div>
                                            <div class="col-12 col-lg-12 form-group">
                                                <label class="font-weight-bold">Memo</label>
                                                <textarea class="form-control text-capitalize" required id="" cols="30" rows="3"
                                                    name="accountFields[0][memo]"></textarea>
                                            </div>

                                        </div>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <a class="btn btn-danger" href="{{ url('sales_order/') }}"> <i class="ti ti-arrow-left">
                                        </i> Back
                                    </a>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");

                //* format total
                y = 0;
                $('.total').on('keyup', function() {
                    var selection = window.getSelection().toString();
                    if (selection !== '') {
                        return;
                    }
                    // When the arrow keys are pressed, abort.
                    if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                        return;
                    }
                    var $this = $(this);
                    // Get the value.
                    var input = $this.val();
                    var input = input.replace(/[\D\s\._\-]+/g, "");
                    input = input ? parseInt(input, 10) : 0;
                    $this.val(function() {
                        return (input === 0) ? "" : input.toLocaleString("id-ID");
                    });
                    $this.next().val(input);
                });
                $('.account').select2({
                    width: '100%',
                });


                $("#addfields").on("click", function() {
                    ++y;

                    let form = `<div class="mx-auto py-2 form-group row bg-primary rounded">
                                            <div class="form-group col-12 col-lg-5">
                                                <label class="font-weight-bold">Account</label>
                                                <select name="accountFields[${y}][account]"
                                                    class="account subType form-control text-capitalize required">
                                                    <option value="">--Select Account--</option>
                                                </select>
                                            </div>
                                           
                                            <div class="col-10 col-lg-5 form-group">
                                                <label class="font-weight-bold">Total</label>
                                                <input type="text" required class="form-control text-capitalize total"
                                                     placeholder="Enter Total">
                                                <input type="hidden" class="total_" name="accountFields[${y}][total]">
                                            </div>
                                             <div class="col-2 col-lg-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a href="javascript:void(0)"
                                                    class="form-control text-white remTradeIn text-center"
                                                    style="border:none; background-color:red">-</a>
                                            </div>
                                             <div class="col-12 col-lg-12 form-group">
                                                <label class="font-weight-bold">Memo</label>
                                                <textarea class="form-control" required id="" cols="30" rows="3" name="accountFields[${y}][memo]"></textarea>
                                            </div>
                                           
                                        </div>`;

                    $("#formTradeIn").append(form);
                    $(function() {

                        let validator = $('form.needs-validation').jbvalidator({
                            errorMessage: true,
                            successClass: true,
                            language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                        });

                        validator.reload();
                    })

                    //* format discount rupiah
                    $('.total').on('keyup', function() {
                        var selection = window.getSelection().toString();
                        if (selection !== '') {
                            return;
                        }
                        // When the arrow keys are pressed, abort.
                        if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                            return;
                        }
                        var $this = $(this);
                        // Get the value.
                        var input = $this.val();
                        var input = input.replace(/[\D\s\._\-]+/g, "");
                        input = input ? parseInt(input, 10) : 0;
                        $this.val(function() {
                            return (input === 0) ? "" : input.toLocaleString("id-ID");
                        });
                        $this.next().val(input);

                    });
                    $('.account').select2({
                        width: '100%',
                    });
                    $(".subType").select2({
                        width: "100%",
                        ajax: {
                            type: "GET",
                            url: "/sub_type_account/select/",
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
                                            text: '(' + item.code + ') ' + item
                                                .name,
                                            id: item.id,
                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                    $(document).on("click", ".remTradeIn", function() {
                        $(this).parents(".form-group").remove();
                    });
                });

            });
        </script>
        <script>
            $(function() {

                let validator = $('form.needs-validation').jbvalidator({
                    errorMessage: true,
                    successClass: true,
                    language: "https://emretulek.github.io/jbvalidator/dist/lang/en.json"
                });

                validator.reload();
            })
        </script>
    @endpush
@endsection
