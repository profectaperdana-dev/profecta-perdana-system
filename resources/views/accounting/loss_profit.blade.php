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
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h5>All Data</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="income" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="fs-3 fw-bold">I. Sales</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">Revenue </td>
                                        <td class="text-end"></td>
                                        <td class="text-end">{{ number_format($income, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Sales Discount </td>
                                        <td class="text-end">{{ number_format($load_discount, 0, ',', '.') }}</td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Sales Return </td>
                                        <td class="text-end">{{ number_format($load_return, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end"></td>
                                    </tr>

                                    <tr>
                                        <td class="text-start fw-bold">Total Load Income </td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($load_discount + $load_return, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fw-bold">Total Trading Income </td>
                                        <td class="text-end">
                                        </td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($income - ($load_discount + $load_return), 0, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table id="hpp" class="display expandable-table text-capitalize" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="fs-3 fw-bold">II.Hpp</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-start">Revenue </td>
                                        <td class="text-end"></td>
                                        <td class="text-end">{{ number_format($income, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Sales Discount </td>
                                        <td class="text-end">{{ number_format($load_discount, 0, ',', '.') }}</td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start">Sales Return </td>
                                        <td class="text-end">{{ number_format($load_return, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end"></td>
                                    </tr>

                                    <tr>
                                        <td class="text-start fw-bold">Total Load Income </td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($load_discount + $load_return, 0, ',', '.') }}
                                        </td>
                                        <td class="text-end"></td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fw-bold">Total Trading Income </td>
                                        <td class="text-end">
                                        </td>
                                        <td class="text-end fw-bold">
                                            {{ number_format($income - ($load_discount + $load_return), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
        <script>
            $(document).ready(function() {
                $('form').submit(function() {
                    $(this).find('button[type="submit"]').prop('disabled', true);
                });
            });
        </script>
    @endpush
@endsection
