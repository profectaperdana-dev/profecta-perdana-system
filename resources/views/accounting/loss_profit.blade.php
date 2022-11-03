@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    @endpush

    @include('accounting.detail')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>
                    {{-- <h6 class="font-weight-normal mb-0 breadcrumb-item active">Create, Read, Update and Delete
                        {{ $title }} --}}
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
                        <h5>Report Profit & Loss</h5>
                        <hr class="bg-primary">
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="form-group row">
                                <div class="col-lg-4 col-6">
                                    <label class="col-form-label text-end">Start Date</label>
                                    <div class="input-group">
                                        <input class="form-control digits" type="date" data-language="en"
                                            placeholder="Start" name="from_date" id="from_date">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-6">
                                    <label class="col-form-label text-end">End Date</label>
                                    <div class="input-group">
                                        <input class="form-control digits" type="date" data-language="en"
                                            placeholder="Start" name="to_date" id="to_date">
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2">
                                    <label class="col-form-label text-end">&nbsp;</label>
                                    <div class="input-group">
                                        <button type="submit" class="btn btn-primary form-control text-white"
                                            name="filter" id="filter">Filter</button>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-2">
                                    <label class="col-form-label text-end">&nbsp;</label>
                                    <div class="input-group">
                                        <a href="{{ url('/profit_loss') }}"
                                            class="btn btn-warning form-control text-white">Refresh</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table id="income"
                                class="table table-sm table-hover table-striped expandable-table text-capitalize"
                                style="width:100%">
                                <tbody>
                                    <tr>
                                        <th class="text-start">Gross Income</th>
                                        <td></td>
                                        <td class="text-end"></td>
                                        <td class="text-end">@currency($income)</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Sales Discount </td>
                                        <td class="text-end">@currency($load_discount)</td>
                                        <td class="text-end"></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Sales Return </td>
                                        <td class="text-end">@currency($load_return)</td>
                                        </td>
                                        <td class="text-end"></td>
                                        <td></td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th class="text-start">Total Selling Expense
                                        </th>
                                        <td class="text-end"></td>
                                        <td class="text-end fw-bold">
                                            @currency($load_discount + $load_return)
                                        </td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-start fw-bold">Net Income (@currency($income) - @currency($load_discount + $load_return))
                                        </th>
                                        </th>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                        </td>
                                        <td class="text-end"></td>
                                        <th class="text-end"> @currency($income - ($load_discount + $load_return))</th>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start fw-bold">Cost Of Goods Sold </td>
                                        <td class="text-end">
                                        </td>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                            (@currency($load_hpp - $load_return_hpp))
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-start fw-bold">Gross Profit (@currency($income - ($load_discount + $load_return)) -
                                            @currency($load_hpp))</td>
                                        <td></td>
                                        <td class="text-end">
                                        </td>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                            @php
                                                $gross_profit = $income - ($load_discount + $load_return) - ($load_hpp - $load_return_hpp);
                                            @endphp
                                            @currency($gross_profit)
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-start fw-bold">Operational Expense :</td>
                                    </tr>
                                    @if ($biaya_pembelian != null)
                                        <tr>
                                            <td></td>
                                            <td class="text-start">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-original-title="test" data-bs-target="#pembelian">Purchase
                                                    Operational
                                                    Expense
                                                </a>
                                            </td>
                                            <td class="text-end">@currency($biaya_pembelian)</td>
                                            <td class="text-end"></td>
                                            <td></td>
                                        </tr>
                                    @endif

                                    @if ($biaya_komunikasi != null)
                                        <tr>
                                            <td></td>
                                            <td class="text-start">
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                    data-original-title="test" data-bs-target="#komunikasi">Communication
                                                    Expense
                                                </a>
                                            </td>
                                            <td class="text-end">@currency($biaya_komunikasi)</td>
                                            <td class="text-end"></td>
                                            <td></td>
                                        </tr>
                                    @endif
                                    @if ($biaya_gaji != null)
                                        <tr>
                                            <td></td>
                                            <td class="text-start"> <a class="dropdown-item" href="#"
                                                    data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#gaji">Salaries Expense
                                                </a></td>
                                            <td class="text-end">@currency($biaya_gaji)</td>
                                            <td class="text-end"></td>
                                            <td></td>

                                        </tr>
                                    @endif

                                    @if ($biaya_promosi != null)
                                        <tr>
                                            <td></td>
                                            <td class="text-start"><a class="dropdown-item" href="#"
                                                    data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#promosi">Promotion Expense</a></td>
                                            <td class="text-end">@currency($biaya_promosi)</td>
                                            <td class="text-end"></td>
                                            <td></td>

                                        </tr>
                                    @endif

                                    @if ($biaya_kendaraan != null)
                                        <tr>
                                            <td></td>
                                            <td class="text-start"><a class="dropdown-item" href="#"
                                                    data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#kendaraan">Vehicle Expense</a></td>
                                            <td class="text-end">@currency($biaya_kendaraan)</td>
                                            <td class="text-end"></td>
                                            <td></td>

                                        </tr>
                                    @endif

                                    @if ($biaya_gedung != null)
                                        <tr>
                                            <td></td>
                                            <td class="text-start"><a class="dropdown-item" href="#"
                                                    data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#gedung">Building Expense</a></td>
                                            <td class="text-end">@currency($biaya_gedung)</td>
                                            <td class="text-end"></td>
                                            <td></td>

                                        </tr>
                                    @endif

                                    @if ($biaya_penjualan != null)
                                        <tr>
                                            <td></td>
                                            <td class="text-start"><a class="dropdown-item" href="#"
                                                    data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#penjualan">Sales Operational Expense</a></td>
                                            <td class="text-end">@currency($biaya_penjualan)</td>
                                            <td class="text-end"></td>
                                            <td></td>

                                        </tr>
                                    @endif

                                    @if ($biaya_kantor != null)
                                        <tr>
                                            <td></td>
                                            <td class="text-start"><a class="dropdown-item" href="#"
                                                    data-bs-toggle="modal" data-original-title="test"
                                                    data-bs-target="#kantor">Office Expense</a></td>
                                            <td class="text-end">@currency($biaya_kantor)</td>
                                            <td class="text-end"></td>
                                            <td></td>

                                        </tr>
                                    @endif

                                    @php
                                        $total_operational = $biaya_pembelian + $biaya_komunikasi + $biaya_gaji + $biaya_promosi + $biaya_kendaraan + $biaya_gedung + $biaya_penjualan + $biaya_kantor;
                                    @endphp
                                    <tr>
                                        <td></td>
                                        <th class="text-start">Total Operational Expense
                                        </th>
                                        <td class="text-end"></td>
                                        <td class="text-end fw-bold">
                                            @currency($total_operational)
                                        </td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    {{-- <tr>
                                        <td colspan="5" class="text-start fw-bold"> Non Operational Expense :</td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Depreciation Expense</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Interest Expense</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Entertainment Expense</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Bank Service Charge</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">PPN</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Income Tax Expense</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Return Expense</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Magazine Expense</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td class="text-start">Car GPS Renewal Expense</td>
                                        <td class="text-end">@currency($biaya_pembelian)</td>
                                        <td class="text-end"></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <th class="text-start">Total Non Operational Expense
                                        </th>
                                        <td class="text-end"></td>
                                        <td class="text-end fw-bold">
                                            @currency($biaya_pembelian + $biaya_komunikasi + $biaya_gaji + $biaya_promosi + $biaya_kendaraan + $biaya_gedung + $biaya_penjualan + $biaya_kantor)
                                        </td>
                                        </td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th class="text-start">Total Expense (@currency($total_operational) )
                                        </th>
                                        <td></td>

                                        <td class="text-end"></td>

                                        </td>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                            @currency($biaya_pembelian + $biaya_komunikasi + $biaya_gaji + $biaya_promosi + $biaya_kendaraan + $biaya_gedung + $biaya_penjualan + $biaya_kantor)
                                        </td>
                                    </tr> --}}
                                    <tr>
                                        <th class="text-start">Net Profit
                                        </th>
                                        <td></td>

                                        <td class="text-end"></td>

                                        </td>
                                        <td></td>
                                        <td class="text-end fw-bold">
                                            @currency($gross_profit - $total_operational)
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>

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
