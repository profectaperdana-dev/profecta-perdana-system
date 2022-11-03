{{-- *Modal Pembelian --}}
<div class="modal fade" id="pembelian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: black !important;" class="modal-title" id="exampleModalLabel">Detail Purchase
                    Operational
                    Expense
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Memo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_pembelian = 0;
                                    @endphp
                                    @foreach ($pembelian as $item)
                                        <tr>
                                            <td style="color: black !important;">
                                                {{ $loop->iteration }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->date }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->code }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->memo }}</td>
                                            <td class="text-end" style="color: black !important;">
                                                @currency($item->total)</td>
                                            @php
                                                $total_pembelian += $item->total;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th colspan="2" class="text-end">@currency($total_pembelian)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

{{-- *Modal Komunikasi --}}
<div class="modal fade" id="komunikasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: black !important;" class="modal-title" id="exampleModalLabel">Detail Communication
                    Expense
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Memo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_pembelian = 0;
                                    @endphp
                                    @foreach ($komunikasi as $item)
                                        <tr>
                                            <td style="color: black !important;">
                                                {{ $loop->iteration }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->date }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->code }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->memo }}</td>
                                            <td class="text-end" style="color: black !important;">
                                                @currency($item->total)</td>
                                            @php
                                                $total_pembelian += $item->total;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th colspan="2" class="text-end">@currency($total_pembelian)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

{{-- *Modal Gaji --}}
<div class="modal fade" id="gaji" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: black !important;" class="modal-title" id="exampleModalLabel">Detail Salaries
                    Expense
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Memo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_pembelian = 0;
                                    @endphp
                                    @foreach ($gaji as $item)
                                        <tr>
                                            <td style="color: black !important;">
                                                {{ $loop->iteration }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->date }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->code }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->memo }}</td>
                                            <td class="text-end" style="color: black !important;">
                                                @currency($item->total)</td>
                                            @php
                                                $total_pembelian += $item->total;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th colspan="2" class="text-end">@currency($total_pembelian)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

{{-- *promosi --}}
<div class="modal fade" id="promosi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: black !important;" class="modal-title" id="exampleModalLabel">Detail Promotion
                    Expense
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Memo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_pembelian = 0;
                                    @endphp
                                    @foreach ($promosi as $item)
                                        <tr>
                                            <td style="color: black !important;">
                                                {{ $loop->iteration }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->date }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->code }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->memo }}</td>
                                            <td class="text-end" style="color: black !important;">
                                                @currency($item->total)</td>
                                            @php
                                                $total_pembelian += $item->total;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th colspan="2" class="text-end">@currency($total_pembelian)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

{{-- *kendaraan --}}
<div class="modal fade" id="kendaraan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: black !important;" class="modal-title" id="exampleModalLabel">Detail Vehicle
                    Expense
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Memo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_pembelian = 0;
                                    @endphp
                                    @foreach ($kendaraan as $item)
                                        <tr>
                                            <td style="color: black !important;">
                                                {{ $loop->iteration }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->date }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->code }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->memo }}</td>
                                            <td class="text-end" style="color: black !important;">
                                                @currency($item->total)</td>
                                            @php
                                                $total_pembelian += $item->total;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th colspan="2" class="text-end">@currency($total_pembelian)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

{{-- *gedung --}}
<div class="modal fade" id="gedung" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: black !important;" class="modal-title" id="exampleModalLabel">Detail Building
                    Expense
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Memo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_pembelian = 0;
                                    @endphp
                                    @foreach ($gedung as $item)
                                        <tr>
                                            <td style="color: black !important;">
                                                {{ $loop->iteration }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->date }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->code }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->memo }}</td>
                                            <td class="text-end" style="color: black !important;">
                                                @currency($item->total)</td>
                                            @php
                                                $total_pembelian += $item->total;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th colspan="2" class="text-end">@currency($total_pembelian)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

{{-- *penjualan --}}
<div class="modal fade" id="penjualan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: black !important;" class="modal-title" id="exampleModalLabel">Detail Sales
                    Operational
                    Expense
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Memo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_pembelian = 0;
                                    @endphp
                                    @foreach ($penjualan as $item)
                                        <tr>
                                            <td style="color: black !important;">
                                                {{ $loop->iteration }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->date }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->code }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->memo }}</td>
                                            <td class="text-end" style="color: black !important;">
                                                @currency($item->total)</td>
                                            @php
                                                $total_pembelian += $item->total;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th colspan="2" class="text-end">@currency($total_pembelian)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>

{{-- *Kantor --}}
<div class="modal fade" id="kantor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: black !important;" class="modal-title" id="exampleModalLabel">Detail Office
                    Operational
                    Expense
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <table class="table table-striped table-responsive">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Memo</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_pembelian = 0;
                                    @endphp
                                    @foreach ($kantor as $item)
                                        <tr>
                                            <td style="color: black !important;">
                                                {{ $loop->iteration }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->date }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->code }}</td>
                                            <td style="color: black !important;">
                                                {{ $item->memo }}</td>
                                            <td class="text-end" style="color: black !important;">
                                                @currency($item->total)</td>
                                            @php
                                                $total_pembelian += $item->total;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total</th>
                                        <th colspan="2" class="text-end">@currency($total_pembelian)</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>

            </div>
        </div>
    </div>
</div>
