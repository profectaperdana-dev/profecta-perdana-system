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
            <h5>Create Data</h5>
            <hr class="bg-primary">
          </div>
          <div class="card-body">
            <form method="post" action="{{ url('sales_order/') }}" enctype="multipart/form-data" id="">
              @csrf
              @include('sales_orders._form')
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
    <script src="{{ asset('js/custom.js') }}"></script>
    <script>
      let csrf = $('meta[name="csrf-token"]').attr("content");

      $(".productSo").select2({
        width: "100%",
        ajax: {
          type: "GET",
          url: "{{ url('/products/select') }}",
          data: {
            _token: csrf,
          },
          dataType: "json",
          delay: 250,
          processResults: function(data) {
            return {
              results: $.map(data, function(item) {
                return [{
                  text: item.nama_barang,
                  id: item.id,
                }, ];
              }),
            };
          },
        },
      });

      //Get Customer ID
      let customer_id = "";
      $(".customer-append").change(function() {
        customer_id = $(".customer-append").val();
      });

      let x = 0;
      let product_id = 0;
      //Get discount depent on product
      $(document).on("change", ".productSo", function() {
        product_id = $(this).val();

        let parent_product = $(this)
          .parent()
          .siblings()
          .find(".discount-append");

        $.ajax({
          type: "GET",
          url: "{{ url('/discounts/select') }}" + "/" + customer_id + "/" + product_id,
          dataType: "json",
          success: function(data) {
            parent_product.val(data.discount);
          },
        });
      });

      $("#addSo").on("click", function() {
        ++x;
        let form =
          '<div class="form-group row">' +
          '<div class="form-group col-4">' +
          "<label>Product</label>" +
          '<select name="soFields[' +
          x +
          '][product_id]" class="form-control productSo" required>' +
          '<option value=""> Choose Product </option> ' +
          "</select>" +
          "</div>" +
          '<div class="col-3 col-md-3 form-group">' +
          "<label> Qty </label> " +
          '<input class="form-control" name="soFields[' +
          x +
          '][qty]">' +
          "</div> " +
          '<div class="col-3 col-md-4 form-group">' +
          "<label>Discount %</label>" +
          '<input class="form-control discount-append" name="soFields[0][discount]" id="" readonly>' +
          "</div>" +
          '<div class="col-2 col-md-1 form-group">' +
          '<label for=""> &nbsp; </label>' +
          '<a class="form-control text-white remSo text-center" style="border:none; background-color:red">' +
          "- </a> " +
          "</div>" +
          " </div>";
        $("#formSo").append(form);

        $(".productSo").select2({
          width: "100%",
          ajax: {
            type: "GET",
            url: "{{ url('/products/select') }}",
            data: {
              _token: csrf,
            },
            dataType: "json",
            delay: 250,
            processResults: function(data) {
              return {
                results: $.map(data, function(item) {
                  return [{
                    text: item.nama_barang,
                    id: item.id,
                  }, ];
                }),
              };
            },
          },
        });


      });

      //remove Sales Order fields
      $(document).on("click", ".remSo", function() {
        $(this).parents(".form-group").remove();
      });
    </script>
  @endpush
@endsection
