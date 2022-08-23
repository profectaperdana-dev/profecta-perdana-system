@extends('layouts.master')
@section('content')
    @push('css')
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Sample Page</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Pages</li>
                        <li class="breadcrumb-item active">Sample Page</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <!-- Bookmark Start-->
                    <div class="bookmark">
                        <ul>
                            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover"
                                    data-placement="top" title="" data-original-title="Tables"><i
                                        data-feather="inbox"></i></a></li>
                            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover"
                                    data-placement="top" title="" data-original-title="Chat"><i
                                        data-feather="message-square"></i></a>
                            </li>
                            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover"
                                    data-placement="top" title="" data-original-title="Icons"><i
                                        data-feather="command"></i></a></li>
                            <li><a href="javascript:void(0)" data-container="body" data-bs-toggle="popover"
                                    data-placement="top" title="" data-original-title="Learning"><i
                                        data-feather="layers"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="bookmark-search" data-feather="star"></i></a>
                                <form class="form-inline search-form">
                                    <div class="form-group form-control-search">
                                        <input type="text" placeholder="Search..">
                                    </div>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <!-- Bookmark Ends-->
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
                        @php
                            $date = Auth::user()->created_at->format('Y-m-d');
                            $now = date('Y-m-d');
                            $reminder = date('Y-m-d', strtotime('+30 days', strtotime($date))); //kurang tanggal sebanyak 6 bulan
                        @endphp
                        @if ($now < $reminder)
                            <a href="{{ url('/profiles') }}" class="text-white">
                                <div class="alert alert-primary dark alert-dismissible fade show" role="alert">
                                    <strong>Hallo,
                                        {{ Auth::user()->name }} !
                                    </strong> Don't forget to change your account password regularly.
                                    Click This ! {{ $reminder }}

                                    <button class="btn-close" type="button" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            </a>
                        @endif

                        <h5>Sample Card</h5><span>lorem ipsum dolor sit amet, consectetur adipisicing
                            elit</span>
                    </div>
                    <div class="card-body">
                        <p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                            incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis
                            nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                            fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in
                            culpa qui officia deserunt mollit anim id est laborum."</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('script')
    @endpush
@endsection
