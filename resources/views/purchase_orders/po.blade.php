@extends('layouts.master')
@section('content')
  @push('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
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
            <h5>All Data Purchase Order</h5>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="example" class="display expandable-table text-capitalize" style="width:100%">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>#</th>
                    <th>Order Number</th>
                    <th>Warehouse</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Due Date</th>
                    <th>Receiving Status</th>
                    {{-- <th>Total</th> --}}
                  </tr>
                </thead>
                <tbody>
                  @foreach ($purchases as $value)
                    <tr>
                      <td style="width: 10%">
                        <a href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                            data-feather="settings"></i></a>
                        <div class="dropdown-menu" aria-labelledby="">
                          <h5 class="dropdown-header">Actions</h5>
                          <a class="dropdown-item" href="{{ url('send_email_po/' . $value->id) }}">Send
                            Purchase Order by Email</a>
                          <a class="dropdown-item" href="{{ url('po/' . $value->id . '/print') }}">Print
                            Purchase Order</a>

                          @can('isSuperAdmin')
                            <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal"
                              data-original-title="test" data-bs-target="#manageData{{ $value->id }}">Edit</a>
                          @endcan

                        </div>
                      </td>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $value->order_number }}</td>
                      <td>{{ $value->warehouseBy->warehouses }}</td>
                      <td>{{ $value->supplierBy->nama_supplier }}</td>
                      <td>{{ date('d-M-Y', strtotime($value->order_date)) }}</td>
                      <td>{{ date('d-M-Y', strtotime($value->due_date)) }}</td>
                      @if ($value->isvalidated == 1)
                        <td><span class="badge badge-pill badge-success text-white">Received</span></td>
                      @else
                        <td><span class="badge badge-pill badge-danger text-white">Not Received</span></td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal PO --}}
  @foreach ($purchases as $item)
    {{-- PO Manage --}}
    <div class="modal fade" id="manageData{{ $item->id }}" tabindex="-1" role="dialog" data-bs-keyboard="false"
      aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Edit Data Purchase Order:
              {{ $item->order_number }}</h5>
            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="{{ url('purchase_orders/' . $item->id . '/update_po') }}" method="POST"
              enctype="multipart/form-data">
              @csrf
              <div class="container-fluid">
                <div class="col-md-12">
                  <div class="row font-weight-bold">
                    <div class="form-group row">
                      <div class="col-md-6 form-group">
                        <label>
                          Supplier</label>
                        <select name="supplier_id" id="" required
                          class="form-control supplier-select {{ $errors->first('supplier_id') ? ' is-invalid' : '' }}">
                          <option value="" selected>-Choose Supplier-</option>
                          @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" @if ($item->supplier_id == $supplier->id) selected @endif>
                              {{ $supplier->nama_supplier }}
                            </option>
                          @endforeach
                        </select>
                        @error('supplier_id')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="col-md-6 form-group mr-5">
                        <label>Warehouse</label>
                        <select name="warehouse_id" required
                          class="form-control warehouse-select {{ $errors->first('warehouse_id') ? ' is-invalid' : '' }}">
                          <option value="" selected>-Choose Payment-</option>
                          @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @if ($item->warehouse_id == $warehouse->id) selected @endif>
                              {{ $warehouse->warehouses }}
                            </option>
                          @endforeach
                        </select>
                        @error('warehouse_id')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>

                    </div>
                    <div class="form-group row">
                      <div class="col-md-6 form-group mr-5">
                        <label>Order Date <strong>(mm/dd/yyyy)</strong></label>
                        <input class="form-control" type="date" name="order_date" value="{{ $item->order_date }}"
                          required>
                        @error('due_date')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                      <div class="col-md-6 form-group">
                        <label>TOP</label>
                        <input type="number" class="form-control" required name="top" id=""
                          value="{{ $item->top }}">
                        @error('top')
                          <div class="invalid-feedback">
                            {{ $message }}
                          </div>
                        @enderror
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-md-12 form-group mr-5">
                        <label>Remarks</label>
                        <textarea class="form-control" name="remark" id="" cols="30" rows="5" required>{{ $item->remark }}</textarea>
                      </div>
                    </div>
                    <div class="form-group row formPo">
                      @foreach ($item->purchaseOrderDetailsBy as $detail)
                        <div class="form-group row">
                          <input type="hidden" class="loop" value="{{ $loop->index }}">
                          <div class="form-group col-7">
                            <label>Product</label>
                            <select name="poFields[{{ $loop->index }}][product_id]" class="form-control productPo"
                              required>
                              <option value="">Choose Product</option>
                              @if ($detail->product_id != null)
                                <option value="{{ $detail->product_id }}" selected>
                                  {{ $detail->productBy->nama_barang .
                                      ' (' .
                                      $detail->productBy->sub_types->type_name .
                                      ', ' .
                                      $detail->productBy->sub_materials->nama_sub_material .
                                      ')' }}
                                </option>
                              @endif
                            </select>
                            @error('poFields[{{ $loop->index }}][product_id]')
                              <div class="invalid-feedback">
                                {{ $message }}
                              </div>
                            @enderror
                          </div>
                          <div class="col-3 col-md-3 form-group">
                            <label>Qty</label>
                            <input type="number" class="form-control qtyPo" required
                              name="poFields[{{ $loop->index }}][qty]" id="" value="{{ $detail->qty }}">
                            @error('poFields[{{ $loop->index }}][qty]')
                              <div class="invalid-feedback">
                                {{ $message }}
                              </div>
                            @enderror
                          </div>

                          @if ($loop->index == 0)
                            <div class="col-2 col-md-2 form-group">
                              <label for="">&nbsp;</label>
                              <a href="javascript:void(0)" class="form-control text-white text-center addPo"
                                style="border:none; background-color:green">+</a>
                            </div>
                          @else
                            <div class="col-2 col-md-2 form-group">
                              <label for="">&nbsp;</label>
                              <a href="javascript:void(0)" class="form-control text-white text-center remPo"
                                style="border:none; background-color:red">-</a>
                            </div>
                          @endif

                        </div>
                      @endforeach
                    </div>
                    <div class="form-group row">
                      <div class="form-group col-12">
                        <button type="button" class="col-12 btn btn-outline-success btn-reload">--
                          Click this to
                          reload total
                          --</button>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="form-group col-lg-4">
                        <label>Total</label>
                        <input class="form-control total"
                          value="{{ 'Rp. ' . number_format($item->total, 0, ',', '.') }}" id="" readonly>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
          </form>
        </div>
      </div>
    </div>
    {{-- PO Manage End --}}
  @endforeach
  <!-- Container-fluid Ends-->
  @push('scripts')
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
    <script>
      $(document).ready(function() {
        let date = new Date();
        let date_now = date.getDate() + '-' + (date.getMonth() + 1) + '-' + date.getFullYear();
        $('#example').DataTable({
          dom: 'Bfrtip',
          buttons: [{
              title: 'All Purchase Orders (' + date_now + ')',
              extend: 'pdf',
              pageSize: 'A4',
              exportOptions: {
                columns: ':visible'
              },
              orientation: 'landscape',
              customize: function(doc) {
                doc.styles.tableHeader.alignment = 'left';
                doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split(
                  '');
              },
            },
            {
              title: 'All Purchase Orders (' + date_now + ')',
              extend: 'print',
              orientation: 'landscape',
              exportOptions: {
                columns: ':visible'
              },
            },
            {
              title: 'All Purchase Orders (' + date_now + ')',
              extend: 'excel',
              exportOptions: {
                columns: ':visible'
              }
            },
            'colvis'
          ]
        });

        $(document).on("click", ".modal-btn2", function(event) {
          let csrf = $('meta[name="csrf-token"]').attr("content");

          let modal_id = $(this).attr('data-bs-target');

          $(modal_id).find(".supplier-select, .warehouse-select").select2({
            width: "100%",
          });
          //Get Customer ID
          $(modal_id).find(".productPo").select2({
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

          let x = $(modal_id)
            .find('.modal-body')
            .find('.formPo')
            .children('.form-group')
            .last()
            .find('.loop')
            .val();
          $(modal_id).find(".addPo").on("click", function() {
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
              '<input class="form-control qtyPo" required name="poFields[' +
              x +
              '][qty]">' +
              '</div>' +
              '<div class="col-2 col-md-2 form-group">' +
              '<label for=""> &nbsp; </label>' +
              '<a class="form-control text-white remPo text-center" style="border:none; background-color:red">' +
              '- </a> ' +
              '</div>' +
              ' </div>';
            $(modal_id).find(".formPo").append(form);

            $(modal_id).find(".productPo").select2({
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
          $(modal_id).on("click", ".remPo", function() {
            $(this).closest(".row").remove();
          });

          //reload total
          $(modal_id).on('click', '.btn-reload', function() {
            let total = 0;
            $(modal_id).find('.productPo').each(function() {
              let product_id = $(this).val();
              let cost = function() {
                let temp = 0;
                $.ajax({
                  async: false,
                  context: this,
                  type: "GET",
                  url: "/products/selectCost/" + product_id,
                  dataType: "json",
                  success: function(data) {
                    temp = data.harga_beli
                  },
                });
                return temp;
              }();

              let qty = $(this).parent().siblings().find('.qtyPo').val();
              total = total + (cost * qty);
              //   alert($(this).parent().siblings().find('.cekQty-edit').val());
            });

            $(this).closest('.row').siblings().find('.total').val('Rp. ' + Math.round(total)
              .toLocaleString(
                'us', {
                  minimumFractionDigits: 0,
                  maximumFractionDigits: 0
                }));

          });
          //   $(modal_id).on("hidden.bs.modal", function(event) {
          //     $(modal_id).off(event);
          //   });
        });

      });
    </script>
  @endpush
@endsection
