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
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
      Echo.channel('so-created').listen('SOMessage', (e) => console.log('RealTimeMessage: ' + e.message));
    </script>
  @endpush
@endsection
