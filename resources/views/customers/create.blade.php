@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">{{ $title }}</h3>
                        <h6 class="font-weight-normal mb-0">Let's {{ $title }}
                        </h6>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        {{-- Modul Tambah Produk --}}
                        <a class="btn btn-danger" href="{{ url('customers/') }}"> <i class="ti ti-arrow-left"> </i> Back
                        </a>

                        <hr>
                        <form method="post" action="{{ url('/customers') }}" enctype="multipart/form-data">
                            @csrf
                            @include('customers._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
