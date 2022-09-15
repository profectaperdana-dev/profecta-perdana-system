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
                            <form class="" action="{{ url('/file_invoice') }}" method="get">
                                @csrf
                                @method('GET')
                                <div class="row">
                                    <div class="col-3">
                                        <select name="" id="filterBy" class="form-control uoms">
                                            <option value="" selected>-Choose Filter-</option>
                                            <option value="1">Customer</option>
                                            <option value="2">Interval Date</option>
                                            <option value="3">Interval Date & Customer</option>
                                        </select>
                                    </div>
                                    <div class="col-3" id="customer">
                                        <select name="val_cus" class="form-control uoms">
                                            <option value="" selected>-Choose Customer-</option>
                                            @foreach ($customer as $val)
                                                <option value="{{ $val->id }}">{{ $val->code_cust }} -
                                                    {{ $val->name_cust }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2" id="date">
                                        <input type="date" class="form-control" name="date">
                                    </div>
                                    <div class="col-2" id="date2">
                                        <input type="date" class="form-control" name="date2">
                                    </div>
                                    <div class="col-4" id="search">
                                        <div class="form-inline form-group d-flex mb-0"> <i class="fa fa-search"></i>
                                            <input class="form-control-plaintext" name="search" value="{{ @$keyword }}"
                                                type="text" placeholder="Search...">
                                        </div>
                                    </div>
                                    <div class="col-1  mt-1">
                                        <a class="btn btn-primary ms-2" href="{{ url('/file_invoice') }}"><i
                                                data-feather="refresh-cw"> </i>
                                        </a>
                                    </div>
                                    <div class="col-1  mt-1">
                                        <button class="btn btn-primary ms-2" type="submit"><i data-feather="arrow-right">
                                            </i>
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>
                        <div class="card-body file-manager">
                            <hr>

                            <ul class="files">
                                @foreach ($data as $value)
                                    @if ($value->pdf_invoice != '')
                                        <li class="file-box">
                                            <a href="#" data-bs-toggle="modal" data-original-title="test"
                                                data-bs-target="#changeData{{ $value->id }}">
                                                <div class="file-top"> <i class="fa fa-file-pdf-o txt-primary"></i>
                                                </div>
                                            </a>

                                            <div class="file-bottom">
                                                <p class="text-dark mt-1"><strong>{{ $value->pdf_invoice }}</strong><br>
                                                    <strong
                                                        class="text-success">{{ $value->customerBy->code_cust }}-{{ $value->customerBy->name_cust }}</strong>
                                                </p>

                                                <p class="text-primary">
                                                    @php
                                                        $fileSize = File::size(public_path('pdf/' . $value->order_number . '.pdf'));
                                                    @endphp
                                                    {{ $fileSize / 1000 }} Kb
                                                </p>
                                            </div>
                                        </li>
                                        <div class="modal fade" id="changeData{{ $value->id }}" tabindex="-1"
                                            role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        <script>
            $(document).ready(function() {
                $('#customer').hide();
                $('#date').hide();
                $('#date2').hide();
                $('#filterBy').change(function() {
                    let filterBy = $(this).val();
                    if (filterBy == 1) {
                        $('#customer').show();
                        $('#search').hide();
                        $('#date').hide();
                        $('#date2').hide();

                    } else if (filterBy == 2) {
                        $('#date').show();
                        $('#date2').show();
                        $('#customer').hide();
                        $('#search').hide();

                    } else if (filterBy == 3) {
                        $('#date').show();
                        $('#date2').show();
                        $('#customer').show();
                        $('#search').hide();

                    } else {
                        $('#customer').hide();
                        $('#search').show();
                        $('#date').hide();
                        $('#date2').hide();
                    }
                });
            });
        </script>
    @endpush
@endsection
