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
                    <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
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
                        <h5>All Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="display tablebaru expandable-table table table-striped table-sm"
                                style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>id detail</th>
                                        <th>Order Number</th>
                                        <th>nama barang</th>
                                        <th>price</th>

                                    </tr>
                                </thead>
                                {{-- ! read data --}}
                                <tbody>
                                    @foreach ($data as $key => $value)
                                        <tr>

                                            <td>{{ $value->id_detail }}</td>
                                            <td>{{ $value->order_number }}</td>
                                            <td>{{ $value->nama_sub_material }} {{ $value->type_name }}
                                                {{ $value->nama_barang }}</td>
                                            <td>
                                                <input type="hidden" class="id" required
                                                    value="{{ $value->id_detail }}">
                                                <input type="text" name="" required value="{{ $value->price }}"
                                                    class="form-control text-center clickWeek" readonly>
                                            </td>


                                        </tr>
                                    @endforeach
                                    {{-- ! end read data --}}
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
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>

        <script>
            $(document).ready(function() {
                $(document).on('dblclick', '.clickWeek', function() {
                    var csrf = $('meta[name="csrf-token"]').attr("content");

                    let id = $(this).siblings('.id').val();
                    console.log(id);
                    $(this).removeAttr('readonly');
                    var currentValue = $(this).val();
                    console.log(currentValue);
                    $(this).html('<input required type="text" value="' + currentValue + '">');
                    $(this).find('input').focus();
                    $(this).off('focusout');
                    $(this).focusout(function() {
                        $.ajax({
                            type: "GET",
                            context: this,
                            url: "ubah-harga/" + id,
                            data: {
                                _token: csrf,
                                week: $(this).val(),
                            },
                            dataType: "json",
                            success: function(data) {
                                $.notify({
                                    title: 'success !',
                                    message: 'Week has been updated'
                                }, {
                                    type: 'success',
                                    allow_dismiss: true,
                                    newest_on_top: true,
                                    mouse_over: true,
                                    showProgressbar: false,
                                    spacing: 10,
                                    timer: 1000,
                                    placement: {
                                        from: 'top',
                                        align: 'right'
                                    },
                                    offset: {
                                        x: 30,
                                        y: 30
                                    },
                                    delay: 1000,
                                    z_index: 3000,
                                    animate: {
                                        enter: 'animated swing',
                                        exit: 'animated swing'
                                    }
                                });
                                $(this).attr('readonly', true);
                                $(this).off('focusout');
                            },
                            error: function(data) {
                                swal("Success !", "fail to saved data", "error", {
                                    button: "Close",
                                });
                                $(this).attr('readonly', true);
                                $(this).off('focusout');
                                $(this).val(currentValue);
                            }
                        });
                    })
                });

                $(document).on('submit', 'form', function() {
                    // console.log('click');
                    var form = $(this);
                    var button = form.find('button[type="submit"]');
                    // console.log(form.html());

                    if (form[0].checkValidity()) { // check if form has input values
                        button.prop('disabled', true);

                    }
                });
                $('.tablebaru').DataTable({
                    pageLength: -1,


                });


            });
        </script>
    @endpush
@endsection
