@extends('layouts.master')
@section('content')
  @push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
  @endpush

  <div class="container-fluid">
    <div class="page-header">
      <div class="row">
        <div class="col-sm-12 col-12">
          <h3 class="font-weight-bold">{{ $title }} {{ $value->order_number }}</h3>
          <h6 class="font-weight-normal mb-0 breadcrumb-item active">
            Edit sales orders discount & product</h6>
        </div>

      </div>
    </div>
  </div>
  <!-- Container-fluid starts-->
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header pb-0">
            <h5>Edit Product</h5>
            <hr>
          </div>
          <div class="card-body">
            <form method="post" action="{{ url('update_product/' . $value->id . '/edit_product') }}"
              enctype="multipart/form-data" id="">
              @csrf
              @method('PUT')
              <div class="container-fluid">
                <div class="form-group row">
                  @foreach ($value->salesOrderDetailsBy as $detail)
                    <div class="form-group row">
                      <div class="col-md-7 col-4 form-group">
                        <label>
                          Product </label>
                        <select name="editProduct[{{ $loop->index }}][products_id]" id="" required
                          class="form-control productSo-edit {{ $errors->first('editProduct[' . $loop->index . '][products_id]') ? ' is-invalid' : '' }}">
                          @if ($detail->products_id != null)
                            <option value="{{ $detail->products_id }}" selected>
                              {{ $detail->productSales->nama_barang .
                                  ' (' .
                                  $detail->productSales->sub_types->type_name .
                                  ', ' .
                                  $detail->productSales->sub_materials->nama_sub_material .
                                  ')' }}
                            </option>
                          @endif
                        </select>
                        @error('editProduct[' . $loop->index . '][products_id]s')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="col-md-2 col-3 form-group">
                        <label>Qty</label>
                        <input type="text" class="form-control cekQty-edit"
                          name="editProduct[{{ $loop->index }}][qty]" value="{{ $detail->qty }}">
                        <small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>
                        @error('top')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      {{-- id sod --}}
                      <input hidden type="text" name="editProduct[{{ $loop->index }}][id_sod]"
                        value="{{ $detail->id }}">
                      <div class="col-md-2 col-3 form-group">
                        <label>Discount</label>
                        <input type="text" class="form-control" placeholder="Product Name"
                          name="editProduct[{{ $loop->index }}][discount]" value="{{ $detail->discount }}">
                        @error('top')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="col-md-1 col-2 form-group">
                        <label>&nbsp;</label>
                        <a href="{{ url('delete_product/' . $value->id . '/' . $detail->id) }}"
                          class="btn btn-danger">X</a>
                      </div>
                    </div>
                  @endforeach
                  <div class="form-group">
                    <a class="btn btn-danger" href="{{ url('recent_sales_order/') }}"> <i class="ti ti-arrow-left"> </i>
                      Back
                    </a>
                    <button type="reset" class="btn btn-warning">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card">
          <div class="card-header pb-0">
            <h5>Add Product</h5>
          </div>
          <div class="card-body">
            <form method="post" action="{{ url('update_product/' . $value->id . '/add_product') }}"
              enctype="multipart/form-data" id="">
              @csrf
              <div class="col-md-12">
                <div class="row font-weight-bold " id="formSo-edit">
                  <div class="form-group row">
                    <input type="hidden" id="customer_selected" value="{{ $value->customers_id }}">
                    <div class="form-group col-md-7 col-4">
                      <label>Product</label>
                      <select name="soFields[0][product_id]" class="form-control productSo-edit" required>
                        <option value="">Choose Product</option>
                      </select>
                      @error('soFields[0][product_id]')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>
                    <div class="col-3 col-md-2 form-group">
                      <label>Qty</label>
                      <input class="form-control cekQty-edit" required name="soFields[0][qty]" id="">
                      <small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>
                      @error('soFields[0][qty]')
                        <div class="invalid-feedback">
                          {{ $message }}
                        </div>
                      @enderror
                    </div>

                    <div class="col-3 col-md-2 form-group">
                      <label>Discount%</label>
                      <input class="form-control discount-append-edit" name="soFields[0][discount]" id=""
                        readonly>
                    </div>
                    <div class="col-2 col-md-1 form-group">
                      <label for="">&nbsp;</label>
                      <a id="addSo-edit" href="javascript:void(0)" class="btn btn-success form-control text-white">+</a>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <button type="reset" class="btn btn-warning">Reset</button>
                  <button type="submit" class="btn btn-primary">Add</button>
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
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script>
      $(document).ready(function() {
        let csrf = $('meta[name="csrf-token"]').attr("content");

        $(".productSo-edit").select2({
          width: "100%",
          ajax: {
            type: "GET",
            url: "/products/select",
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

        //Get Customer ID
        let customer_id = $('#customer_selected').val();
        let x = 0;
        let product_id = 0;
        //Get discount depent on product
        $(document).on("change", ".productSo-edit", function() {
          product_id = $(this).val();

          let parent_product = $(this)
            .parent()
            .siblings()
            .find(".discount-append-edit");

          $.ajax({
            type: "GET",
            url: "/discounts/select" + "/" + customer_id + "/" + product_id,
            dataType: "json",
            success: function(data) {
              parent_product.val(data.discount);
            },
          });
        });
        $(document).on("input", ".cekQty-edit", function() {
          let qtyValue = $(this).val();
          let product_id = $(this).parents('.form-group').siblings('.form-group').find('.productSo-edit').val();

          $.ajax({
            context: this,
            type: "GET",
            url: "/stocks/cekQty/" + product_id,
            dataType: "json",
            success: function(data) {
              if (parseInt(qtyValue) > parseInt(data.stock)) {
                $(this).parent().find(".qty-warning").removeAttr("hidden");
                $(this).addClass("is-invalid");
              } else {
                $(this)
                  .parent()
                  .find(".qty-warning")
                  .attr("hidden", "true");
                $(this).removeClass("is-invalid");
              }
            },
          });
        });

        $("#addSo-edit").on("click", function() {
          ++x;
          let form =
            '<div class="form-group row">' +
            '<div class="form-group col-md-7 col-4">' +
            "<label>Product</label>" +
            '<select name="soFields[' +
            x +
            '][product_id]" class="form-control productSo-edit" required>' +
            '<option value=""> Choose Product </option> ' +
            "</select>" +
            "</div>" +
            '<div class="col-3 col-md-2 form-group">' +
            "<label> Qty </label> " +
            '<input class="form-control cekQty-edit" required name="soFields[' +
            x +
            '][qty]">' +
            '<small class="text-danger qty-warning" hidden>The number of items exceeds the stock</small>' +
            "</div> " +
            '<div class="col-3 col-md-2 form-group">' +
            "<label>Discount %</label>" +
            '<input class="form-control discount-append-edit" name="soFields[' +
            x +
            '][discount]" id="" readonly>' +
            "</div>" +
            '<div class="col-2 col-md-1 form-group">' +
            '<label for=""> &nbsp; </label>' +
            '<a class="btn btn-danger form-control text-white remSo-edit text-center">' +
            "- </a> " +
            "</div>" +
            " </div>";
          $("#formSo-edit").append(form);

          $(".productSo-edit").select2({
            width: "100%",
            ajax: {
              type: "GET",
              url: "/products/select",
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

        //remove Sales Order fields
        $(document).on("click", ".remSo-edit", function() {
          $(this).parents(".form-group").remove();
        });

      });
    </script>
  @endpush
@endsection
