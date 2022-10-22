<a href="#" class="btn btn-sm btn-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    INV</a>
<div class="dropdown-menu" aria-labelledby="">
    <h5 class="dropdown-header">Actions</h5>
    @can('isSuperAdmin')
        <a class="dropdown-item modal-btn2" href="#" data-bs-toggle="modal" data-original-title="test"
            data-bs-target="#manageData{{ $invoice->id }}">Edit Invoice</a>
    @endcan

    @if ($invoice->customer_email != '-')
        <a class="dropdown-item" href="{{ url('send_email_trade_invoice/' . $invoice->id) }}">Send Invoice by
            Email</a>
    @endif
    <h5 class="dropdown-header">Prints</h5>
    <a class="dropdown-item" href="{{ url('trade_invoice/' . $invoice->id . '/print') }}">Print Invoice</a>
</div>
