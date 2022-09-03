@extends('layouts.master')
@section('content')
    @push('css')
    @endpush
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="font-weight-bold">{{ $title }}</h3>

                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">

            <div class="col-xl-12 col-md-12 box-col-12">


                <div class="file-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="media col-12">
                                <form class="form-inline" action="{{ url('/file_invoice') }}" method="get">
                                    @csrf
                                    @method('GET')
                                    <div class="form-group d-flex mb-0"> <i class="fa fa-search"></i>
                                        <input class="form-control-plaintext" name="search" value="{{ @$keyword }}"
                                            type="text" placeholder="Search...">
                                    </div>

                                </form>

                            </div>
                        </div>
                        <div class="card-body file-manager">
                            <hr>

                            <ul class="files">
                                @foreach ($data as $value)
                                    @if ($value->pdf_invoice != '')
                                        <li class="file-box">
                                            <a href="#" data-bs-toggle="modal" data-original-title="test"
                                                data-bs-target="#changeData{{ substr($value->pdf_invoice, -3) }}">
                                                <div class="file-top"> <i class="fa fa-file-pdf-o txt-primary"></i>
                                                </div>
                                            </a>

                                            <div class="file-bottom">
                                                <p class="text-dark mt-1"><strong>{{ $value->pdf_invoice }}</strong>
                                                </p>
                                                <p class="text-primary">
                                                    @php
                                                        $fileSize = File::size(public_path('pdf/' . $value->order_number . '.pdf'));
                                                    @endphp
                                                    {{ $fileSize / 1000 }} Kb
                                                </p>
                                            </div>
                                        </li>
                                        <div class="modal fade" id="changeData{{ substr($value->pdf_invoice, -3) }}"
                                            tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">View File
                                                            {{ $value->pdf_invoice }}
                                                        </h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <iframe src="{{ asset('pdf/' . $value->pdf_invoice) }}"
                                                            width="100%" height="500px">
                                                        </iframe>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-danger" type="button"
                                                            data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    @endpush
@endsection
