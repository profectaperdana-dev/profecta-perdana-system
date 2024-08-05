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
                            <form class="" action="{{ url('/file_po') }}" method="get">
                                @csrf
                                @method('GET')
                                <div class="row">

                                    <div class="col-lg-3 form-group" id="customer">
                                        <label for="">Supplier</label>
                                        <select name="val_cus" class="form-control uoms">
                                            <option value="" selected>-- All --</option>
                                            @foreach ($customer as $val)
                                                <option value="{{ $val->id }}"
                                                    @if ($val->id == $selected_supplier) selected @endif>
                                                    {{ $val->nama_supplier }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-2 form-group" id="date">
                                        <label for="">From date</label>
                                        <input type="date" class="form-control" name="date"
                                            value="{{ old('date', $from_date) }}">
                                    </div>
                                    <div class="col-lg-2 form-group" id="date2">
                                        <label for="">To date</label>
                                        <input type="date" class="form-control" value="{{ old('date2', $to_date) }}"
                                            name="date2">
                                    </div>
                                    <div class="col-lg-3 form-group">
                                        @if ($user_warehouse->count() == 1)
                                            @foreach ($user_warehouse as $item)
                                                <input type="hidden" name="warehouse_id" id="warehouse"
                                                    class="form-control" value="{{ $item->id }}">
                                            @endforeach
                                        @else
                                            <label for="">Warehouse</label>
                                            <select name="warehouse_id" class="form-control uoms" id="warehouse">
                                                <option value="">-- All --</option>
                                                @foreach ($user_warehouse as $item)
                                                    <option value="{{ $item->id }}"
                                                        @if ($item->id == $warehouse_id) selected @endif>
                                                        {{ $item->warehouses }}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                    </div>
                                    <div class="col-lg-4 form-group" id="search">
                                        <div class="form-inline form-group d-flex mb-0"> <i class="fa fa-search"></i>
                                            <input class="form-control-plaintext" name="search"
                                                value="{{ old('search', $keyword) }}" type="text"
                                                placeholder="Search...">
                                        </div>
                                    </div>

                                    <div class="col-6 col-lg-1  mt-1 form-group">
                                        <button class="btn btn-primary text-white ms-2 form-control" type="submit"><i
                                                data-feather="arrow-right">
                                            </i>
                                        </button>
                                    </div>
                                    <div class="col-6 col-lg-1  mt-1 form-group">
                                        <a class="btn btn-primary ms-2 form-control text-white"
                                            href="{{ url('/file_do') }}"><i data-feather="refresh-cw"> </i>
                                        </a>
                                    </div>

                                </div>
                            </form>

                        </div>
                        <div class="card-body file-manager">

                            <hr>
                            <ul class="files">
                                @foreach ($data as $value)
                                    @if ($value->pdf_po != '')
                                        <li class="file-box ">
                                            <a href="#" data-bs-toggle="modal" data-original-title="test"
                                                data-bs-target="#po{{ $value->id }}">
                                                <div class="file-top"> <i class="fa fa-file-pdf-o txt-primary"></i>
                                                </div>
                                            </a>
                                            <div class="file-bottom">
                                                <p class="text-dark mt-1"><strong>{{ $value->pdf_po }}</strong><br />
                                                    <strong class="text-success">{{ $value->supplierBy->nama_supplier }}
                                                        <br>
                                                        <span
                                                            class="text-warning">{{ date('d-m-Y', strtotime($value->order_date)) }}</span>
                                                    </strong>
                                                </p>
                                                <p class="text-primary">
                                                    {{-- @php
                                                        $fileSize = File::size(public_path('pdf/' . $value->pdf_po));

                                                    @endphp
                                                    {{ $fileSize / 1000 }} Kb --}}
                                                </p>
                                            </div>
                                        </li>
                                        <div class="modal fade" id="po{{ $value->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">View File
                                                            {{ $value->pdf_po }}
                                                        </h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <iframe src="{{ asset('pdf/' . $value->pdf_po) }}" width="100%"
                                                            height="500px">
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

            });
        </script>
    @endpush
@endsection
