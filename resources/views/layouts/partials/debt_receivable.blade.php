    @can('level1')
        <div class="col-xl-12 box-col-12 des-xl-100">
            <div class="row">
                <div class="col-xl-12 box-col-12">
                    <div class="card ">
                        <div class="card-header b-l-primary border-3">
                            <h5 class="pull-left">AP & AR Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="tabbed-card">
                                <ul class="pull-right nav nav-pills  nav-primary" id="top-tabdanger" role="tablist">
                                    <li class="nav-item"><a class="nav-link active" id="top-home-danger"
                                            data-bs-toggle="tab" href="#top-homedanger" role="tab"
                                            aria-controls="top-homedanger" aria-selected="false">AP</a>
                                        <div class="material-border"></div>
                                    </li>
                                    <li class="nav-item"><a class="nav-link " id="profile-top-danger" data-bs-toggle="tab"
                                            href="#top-profiledanger" role="tab" aria-controls="top-profiledanger"
                                            aria-selected="true">AR</a>
                                        <div class="material-border"></div>
                                    </li>
                                </ul>
                                <div class="tab-content" id="top-tabContentdanger">
                                    <div class="tab-pane fade active show" id="top-homedanger" role="tabpanel"
                                        aria-labelledby="top-home-tab">
                                        <div class="table-responsive">
                                            <table id="dataTable" class="table table-sm text-nowrap  table-bordernone">
                                                <thead class="table-info">
                                                    <tr class="text-center">
                                                        <th class="text-center" scope="col">Purchase</th>
                                                        <th class="text-center" scope="col">Order Date</th>
                                                        <th class="text-center" scope="col">Due Date</th>
                                                        <th class="text-center" scope="col">Vendor</th>
                                                        <th class="text-center" scope="col">AP (Rp)</th>
                                                        <th class="text-center" scope="col">Remark</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                                <tfoot>
                                                    <th colspan="5">Total</th>
                                                    <th></th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="top-profiledanger" role="tabpanel"
                                        aria-labelledby="profile-top-tab">
                                        <div class="table-responsive">
                                            <table id="dataTable1" class="table table-sm text-nowrap  table-bordernone">
                                                <thead class="table-info">
                                                    <tr class="text-center">
                                                        <th class="text-center" scope="col">#</th>
                                                        <th class="text-center" scope="col">Invoice</th>
                                                        <th class="text-center" scope="col">Order Date</th>
                                                        <th class="text-center" scope="col">Due Date</th>
                                                        <th class="text-center" scope="col">TOP</th>
                                                        <th class="text-center" scope="col">Customer</th>
                                                        <th class="text-center" scope="col">AR (Rp)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                                <tfoot>
                                                    <th colspan="5">Total</th>
                                                    <th></th>
                                                    <th></th>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan
