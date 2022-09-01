<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  INV</a>
<div class="dropdown-menu" aria-labelledby="">
  <h5 class="dropdown-header">Actions</h5>
  <a class="dropdown-item" href="{{ url('send_email/' . $id) }}">Send Invoice by Email</a>
  <h5 class="dropdown-header">Prints</h5>
  <a class="dropdown-item" href="{{ url('invoice/' . $id . '/invoice_with_ppn') }}">Print Invoice with PPN</a>
  {{-- <a class="dropdown-item" href="{{ url('invoice/' . $id . '/invoice_without_ppn') }}">Print Invoice without PPN</a> --}}
  <a class="dropdown-item" href="{{ url('invoice/' . $id . '/delivery_order') }}">Print Delivary Order</a>
</div>
