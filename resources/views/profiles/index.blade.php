@extends('layouts.master')
@section('content')
    @push('css')
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
    <div class="container-fluid user-card">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xl-4 box-col-6">
                <div class="card custom-card">
                    <div class="card-header"><img width="100%" class="img-fluid" src="{{ asset('images/dpn.png') }}"
                            alt="">
                    </div>
                    <div class="card-profile">
                        @if (Auth::user()->photo_profile == null)
                            <img class="rounded-circle" src="{{ asset('images/blank.png') }}" alt="">
                        @else
                            <img class="rounded-circle" src="{{ asset('foto_profile/' . Auth::user()->photo_profile) }}"
                                alt="">
                        @endif
                    </div>

                    {{-- Edit foto Profile --}}
                    <div class="modal fade" id="changeFoto" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <form method="post" action="{{ url('/profiles/' . Auth::user()->id . '/photo') }}"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Change Photo Profile
                                        </h5>
                                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label class="font-weight-bold ">Photo Profile</label>
                                                    <input name="url_lama" value="{{ Auth::user()->photo_profile }}" hidden>
                                                    <input type="file"
                                                        class="form-control text-capitalize {{ $errors->first('photo_profile') ? ' is-invalid' : '' }}"
                                                        name="photo_profile" placeholder="Name Unit of Measurement"
                                                        required>
                                                    @error('photo_profile')
                                                        <small class="text-danger">The
                                                            Product Material field is
                                                            required.</small>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                        <button type="reset" class="btn btn-warning">Reset</button>
                                        <button class="btn btn-primary" type="submit">Save
                                            changes</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    {{-- End Modal Edit UOM --}}
                    <div class="text-center">
                        <h4><a href="javascript:void(0)">{{ Auth::user()->warehouseBy->warehouses }}</a></h4>

                    </div>


                    <div class="text-center profile-details"><a href="user-profile.html">
                            <h4>{{ Auth::user()->name }}</h4>
                        </a>
                        <h6>{{ Auth::user()->roleBy->name }}</h6>
                    </div>
                    <div class="text-center"><a href="#" data-bs-toggle="modal" data-original-title="test"
                            data-bs-target="#changeFoto"><i data-feather="edit"></i></a></div>
                    <div class="card-footer row">
                        <div class="col-6 col-sm-6">
                            <h6>Username</h6>
                            <h3 class="counter">{{ Auth::user()->username }}</h3>
                        </div>
                        <div class="col-6 col-sm-6">
                            <h6>Number Phone</h6>
                            <h3><span class="counter">{{ Auth::user()->employeeBy->phone }}</span></h3>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title mb-0">Change My Profile</h4>
                        <hr class="bg-primary">
                        <div class="card-options"><a class="card-options-collapse" href="#"
                                data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                    class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body ">
                        <form method="post" action="{{ url('/profiles/' . Auth::user()->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3 font-weight-bold">
                                <label class="form-label">Name</label>
                                <input name="name" type="text"
                                    class="form-control {{ $errors->first('name') ? ' is-invalid' : '' }}"
                                    value="{{ Auth::user()->name }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary btn-block">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h4 class="card-title mb-0">Change Password</h4>
                        <hr class="bg-primary">
                        <div class="card-options"><a class="card-options-collapse" href="#"
                                data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a
                                class="card-options-remove" href="#" data-bs-toggle="card-remove"><i
                                    class="fe fe-x"></i></a></div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ url('/profiles/' . Auth::user()->id . '/password') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label class="form-label">Old Password</label>
                                <input name="old_password"
                                    class="form-control {{ $errors->first('old_password') ? ' is-invalid' : '' }}"
                                    type="password" value="{{ old('old_password') }}">
                                @error('old_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input name="new_password"
                                    class="form-control {{ $errors->first('new_password') ? ' is-invalid' : '' }}"
                                    type="password" value="{{ old('new_password') }}">
                                @error('new_password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input name="password_confirmation"
                                    class="form-control {{ $errors->first('password_confirmation') ? ' is-invalid' : '' }}"
                                    type="password" value="">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="form-footer">
                                <button type="submit" class="btn btn-primary btn-block">Change Password</button>
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
    @endpush
@endsection
