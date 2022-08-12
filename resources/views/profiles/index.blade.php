@extends('layouts.main')
@section('content')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bold">{{ $title }} | {{ $data->name }}
                        </h3>
                        <h6 class="font-weight-normal mb-0">Let's see you {{ $title }}
                        </h6>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        @if (session()->has('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <strong class="text-capitalize">{{ session('info') }}</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <h5>Photo {{ $title }}</h5>
                        <hr>
                        <div class="container-fluid">
                            <div class="form-group">

                                @if ($data->photo_profile == null)
                                    <img class="img-fluid shadow-lg" src="{{ asset('images/blank.png') }}" alt="profile"
                                        width="100%" />
                                @else
                                    <img class="img-fluid shadow-lg"
                                        src="{{ asset('foto_profile/' . $data->photo_profile) }}" alt="profile" />
                                @endif

                            </div>
                            <div class="form-group">
                                <a type="submit" data-toggle="modal" data-target="#changePhoto"
                                    class="btn btn-primary mr-2 text-center">Change Photo</a>

                            </div>
                        </div>

                    </div>
                </div>
                {{-- Modul Edit UOM --}}
                <div class="modal fade" id="changePhoto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog " role="document">
                        <form method="post" action="{{ url('profiles/' . $data->id . '/photo') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-capitalize" id="exampleModalLabel">
                                        Change Photo Profile :
                                        {{ $data->name }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group row">
                                                <div class="col-md-12">
                                                    <label class="font-weight-bold">Photo Profile</label>
                                                    <input type="file"
                                                        class="form-control text-capitalize {{ $errors->first('photo_profile') ? ' is-invalid' : '' }}"
                                                        name="photo_profile" placeholder="Name Unit of Measurement">
                                                    @error('photo_profile')
                                                        <small class="text-danger">{{ $message }}.</small>
                                                    @enderror
                                                    <input hidden type="text" name="url_lama" id=""
                                                        value="{{ $data->photo_profile }}">
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    <button type="reset" class="btn btn-warning">Reset</button>
                                    <button type="submit" class="btn btn-primary">Save
                                        changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                {{-- End Modal Edit UOM --}}
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5>Data {{ $title }}</h5>
                        <hr>
                        <form class="forms-sample font-weight-bold">
                            <div class="form-group">
                                <label for="exampleInputUsername1">Name</label>
                                <input type="text" class="form-control" id="exampleInputUsername1" readonly
                                    value="{{ $data->name }}" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email"
                                    value="{{ $data->email }}" readonly>
                            </div>
                            <div class="form-group text-capitalize">
                                <label for="exampleInputPassword1">Role</label>
                                <input class="form-control text-capitalize" id="exampleInputPassword1"
                                    value="{{ $data->role_name }}" readonly>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Area</label>
                                <input readonly type="text" class="form-control" id="exampleInputConfirmPassword1"
                                    value="{{ $data->warehouse_name }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary mr-2 text-center">Change Data</button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h5>Change Password </h5>
                        <hr>
                        <form class="forms-sample font-weight-bold">
                            <div class="form-group">
                                <label for="exampleInputPassword1">Old Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1"
                                    placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">New Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1"
                                    placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputConfirmPassword1">Confirm New Password</label>
                                <input type="password" class="form-control" id="exampleInputConfirmPassword1"
                                    placeholder="Password">
                            </div>

                            <button type="submit" class="btn btn-primary mr-2">Save Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
