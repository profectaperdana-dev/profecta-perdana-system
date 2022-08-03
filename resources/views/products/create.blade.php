@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">Create {{ $title }}</h3>
                        <h6 class="font-weight-normal mb-0">Let's Create {{ $title }}
                        </h6>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="{{ url('/products') }}" enctype="multipart/form-data">
                            @csrf
                            @include('products._form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
