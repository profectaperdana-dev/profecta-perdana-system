@foreach ($stocks as $item)
    <div class="modal fade" id="trace{{ $item->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Trace Record Product
                        :
                        {{ $item->productBy->sub_materials->nama_sub_material .
                            ' ' .
                            $item->productBy->sub_types->type_name .
                            ' ' .
                            $item->productBy->nama_barang }}
                        at {{ $item->warehouseBy->warehouses }}
                    </h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="table-responsive">
                            <table style="font-size: 10pt" id="example{{ $item->id }}"
                                class="table table-striped table-borderless text-nowrap table-sm" style="width:100%">
                                <thead>
                                    <tr class="text-center">
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Transaction</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" style="text-align:right">Total</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
