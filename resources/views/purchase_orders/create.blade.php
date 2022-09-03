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
            <h5>Create Purchase Order</h5>
            <hr class="bg-primary">
          </div>
          <div class="card-body">
            <form method="post" action="{{ url('purchase_orders/') }}" enctype="multipart/form-data" id="">
              @csrf
              @include('purchase_orders._form')
            </form>
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
      let csrf = $('meta[name="csrf-token"]').attr("content");

      $(document).ready(function() {
        $(".supplier-select, .warehouse-select").select2({
          width: "100%",
        });

        $(".productPo").select2({
          width: "100%",
          ajax: {
            type: "GET",
            url: "/products/selectAll",
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
                    text: item.nama_barang +
                      " (" +
                      item.type_name +
                      ", " +
                      item.nama_sub_material +
                      ")",
                    id: item.id,
                  }, ];
                }),
              };
            },
          },
        });

        let x = 0;
        $("#addPo").on("click", function() {
          ++x;
          let form =
            '<div class="form-group row">' +
            '<div class="form-group col-7">' +
            "<label>Product</label>" +
            '<select name="poFields[' +
            x +
            '][product_id]" class="form-control productPo" required>' +
            '<option value=""> Choose Product </option> ' +

            '</select>' +
            '</div>' +
            '<div class="col-3 col-md-3 form-group">' +
            '<label> Qty </label> ' +
            '<input class="form-control" required name="poFields[' +
            x +
            '][qty]">' +
            '</div>' +
            '<div class="col-2 col-md-2 form-group">' +
            '<label for=""> &nbsp; </label>' +
            '<a class="form-control text-white remPo text-center" style="border:none; background-color:red">' +
            '- </a> ' +
            '</div>' +
            ' </div>';
          $("#formPo").append(form);

          $(".productPo").select2({
            width: "100%",
            ajax: {
              type: "GET",
              url: "/products/selectAll",
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
                      text: item.nama_barang +
                        " (" +
                        item.type_name +
                        ", " +
                        item.nama_sub_material +
                        ")",
                      id: item.id,
                    }, ];
                  }),
                };
              },
            },
          });
        });

        //remove Purchase Order fields
        $(document).on("click", ".remPo", function() {
          $(this).parents(".form-group").remove();
        });
      });
    </script>
  @endpush
@endsection
