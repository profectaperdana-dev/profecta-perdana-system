<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    INV</a>
<div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    <a class="dropdown-item" href="#">Send Invoice by Email</a>
    <a class="dropdown-item" href="{{ url('invoice/' . $id . '/invoice_with_ppn') }}">Print Invoice with PPN</a>
    <a class="dropdown-item" href="">Print Invoice without PPN</a>
    <a class="dropdown-item" href="#">Print Delivary Order</a>
</div>
