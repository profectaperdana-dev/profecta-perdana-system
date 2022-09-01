<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  INV</a>
<div class="dropdown-menu" aria-labelledby="">
  <h5 class="dropdown-header">Actions</h5>
  <a class="dropdown-item" href="#">Send Invoice by Email</a>
  <a class="dropdown-item" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#markData{{ $id }}">Mark as paid</a>
  <h5 class="dropdown-header">Prints</h5>
  <a class="dropdown-item" href="{{ url('invoice/' . $id . '/invoice_with_ppn') }}">Print Invoice with PPN</a>
  <a class="dropdown-item" href="{{ url('invoice/' . $id . '/invoice_without_ppn') }}">Print Invoice without PPN</a>
  <a class="dropdown-item" href="{{ url('invoice/' . $id . '/delivery_order') }}">Print Delivary Order</a>
</div>

<!-- Verify Product Modal Start -->
<div class="modal fade" id="markData{{ $id }}" tabindex="-1" role="dialog"
  aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
          Mark as Paid Data:
          {{ $order_number }}</h5>
        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="form-group row">
            <div class="col-md-12">
              <h5>Are you sure to mark as paid this data ?</h5>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
        <a href="{{ url('invoice/' . $id . '/mark_as_paid') }}"><button class="btn btn-primary" type="submit">Yes
          </button></a>
      </div>

    </div>
  </div>
</div>
<!-- Verify Product Modal End -->
