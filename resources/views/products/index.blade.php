@extends('layouts.main')
@section('content')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">{{ $title }}</h3>
                            <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have
                                <span class="text-primary">3 unread alerts!</span>
                            </h6>
                        </div>

                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            {{-- <p class="card-title">Advanced Table</p> --}}
                            <button class="btn btn-success">+ Add Products</button>
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="myTable" class="display expandable-table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>#</th>
                                                    <th>Code</th>
                                                    <th>Products</th>
                                                    <th>S/N</th>
                                                    <th>Uom</th>
                                                    <th>Group</th>
                                                    <th>Sub Group</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data as $key => $value)
                                                    <tr>
                                                        <td></td>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td>{{ $value->kode_barang }}</td>
                                                        <td>{{ $value->nama_barang }}</td>
                                                        <td>{{ $value->no_seri }}</td>
                                                        <td>{{ $value->uom }}</td>
                                                        <td>{{ $value->material_grup }}</td>
                                                        <td>{{ $value->sub_material }}</td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2021.
                    Premium <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap admin
                        template</a> from BootstrapDash. All rights reserved.</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made
                    with <i class="ti-heart text-danger ml-1"></i></span>
            </div>
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Distributed by <a
                        href="https://www.themewagon.com/" target="_blank">Themewagon</a></span>
            </div>
        </footer>
        <!-- partial -->
    </div>
@endsection
