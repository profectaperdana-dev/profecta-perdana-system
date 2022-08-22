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
            <form method="post" action="{{ url('customers/') }}" enctype="multipart/form-data">
              @csrf
              @include('customers._form')
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

      $(document).ready(function() {
        $(".province").select2({
          width: "100%",
          minimumResultsForSearch: -1,
          ajax: {
            type: "GET",
            url: "/customers/getProvince",
            data: {
              _token: csrf,
            },
            dataType: "json",
            delay: 250,
            processResults: function(data) {
              return {
                results: $.map(data, function(item) {
                  return [{
                    text: item.name,
                    id: item.id,
                  }, ];
                }),
              };
            },
          },
        });

        $('.province').change(function() {
          let province_value = $('.province').val();

          $(".city").select2({
            width: "100%",
            minimumResultsForSearch: -1,
            ajax: {
              type: "GET",
              url: "/customers/getCity/" + province_value,
              data: {
                _token: csrf,
              },
              dataType: "json",
              delay: 250,
              processResults: function(data) {
                return {
                  results: $.map(data, function(item) {
                    return [{
                      text: item.name,
                      id: item.id,
                    }, ];
                  }),
                };
              },
            },
          });
        });

        $('.city').change(function() {
          let city_value = $('.city').val();

          $(".district").select2({
            width: "100%",
            minimumResultsForSearch: -1,
            ajax: {
              type: "GET",
              url: "/customers/getDistrict/" + city_value,
              data: {
                _token: csrf,
              },
              dataType: "json",
              delay: 250,
              processResults: function(data) {
                return {
                  results: $.map(data, function(item) {
                    return [{
                      text: item.name,
                      id: item.id,
                    }, ];
                  }),
                };
              },
            },
          });
        });

        $('.district').change(function() {
          let district_value = $('.district').val();

          $(".village").select2({
            width: "100%",
            minimumResultsForSearch: -1,
            ajax: {
              type: "GET",
              url: "/customers/getVillage/" + district_value,
              data: {
                _token: csrf,
              },
              dataType: "json",
              delay: 250,
              processResults: function(data) {
                return {
                  results: $.map(data, function(item) {
                    return [{
                      text: item.name,
                      id: item.id,
                    }, ];
                  }),
                };
              },
            },
          });
        });
      });
    </script>
  @endpush
@endsection
