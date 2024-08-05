<a class="fw-bold text-nowrap modalItem" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop{{ $data->id }}">{{ $data->claim_number }}
</a>
<!-- Modal -->
<div class="currentModal">
    <div class="modal fade" id="staticBackdrop{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel">Information Prior Checking</h6>
            </div>
            <div class="modal-body" style="font-size: 10pt">
                <div class="row">
                    <div class="mb-3 col-12 col-lg-6">
                        <label>Customer</label>
                        <input type="text" readonly class="form-control"
                            value="{{ is_numeric($data->customer_id) ? $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust : $data->customer_id }}">
                    </div>
                    <div class="mb-3 col-12 col-lg-6">
                        <label>Name</label>
                        <input name="sub_name" value="{{ $data->sub_name }}" autocomplete="off" readonly type="text"
                            class="form-control">
                    </div>
                    <div class="mb-3 col-12 col-lg-6">
                        <label>Phone</label>
                        <input name="sub_phone" value="{{ $data->sub_phone }}" number data-v-min-length="9"
                            data-v-max-length="13" type="number" readonly class="form-control fw-bold "
                            placeholder="Enter Phone" aria-label="Server">
                    </div>
                    <div class="mb-3 col-12 col-lg-6">
                        <label>Email</label>
                        <input name="email_cust" readonly
                            value="{{ $data->email_cust != null ? $data->email_cust : '-' }}" autocomplete="off"
                            required type="text" class="form-control">
                    </div>
                    <div class="mb-3 col-12 col-lg-6">
                        <label>Plate Number</label>
                        <input type="text" placeholder="Enter Plate Number" value="{{ $data->plate_number }}"
                            readonly class="form-control text-uppercase " name="plate_number">
                    </div>
                    <div class="mb-3 col-12 col-lg-6">
                        <label>Vehicle</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $data->other_machine ? $data->other_machine : ($data->car_brand_id ? $data->carBrandBy->car_brand . ' - ' . (is_numeric($data->car_type_id) ? $data->carTypeBy->car_type : $data->car_type_id) : $data->motorBrandBy->name_brand . ' - ' . (is_numeric($data->motor_type_id) ? $data->motorTypeBy->name_type : $data->motor_type_id)) }}">
                    </div>
                    <div class="mb-3 col-12 {{ $data->loan_product_id != null ? 'col-lg-6' : 'col-lg-12' }} ">
                        <label>Battery</label>
                        <input type="text" readonly class="form-control"
                            value="{{ $data->productSales->sub_materials->nama_sub_material . ' ' . $data->productSales->sub_types->type_name . ' ' . $data->productSales->nama_barang }}">
                    </div>
                    @if ($data->loan_product_id != null)
                        <div class="mb-3 col-12 col-lg-6">
                            <label>Lended</label>
                            <input type="text" readonly class="form-control"
                                value="{{ $data->loanBy->sub_materials->nama_sub_material . ' ' . $data->loanBy->sub_types->type_name . ' ' . $data->loanBy->nama_barang }}">
                        </div>
                    @endif
                    <div class="mb-3 col-6 col-lg-3">
                        <label>Voltage</label>
                        <input type="text" readonly class="form-control" value="{{ $data->e_voltage }}">
                    </div>
                    <div class="mb-3 col-6 col-lg-3">
                        <label>CCA</label>
                        <input type="text" readonly class="form-control" value="{{ $data->e_cca }}">
                    </div>
                    <div class="mb-3 col-6 col-lg-3">
                        <label>Starting</label>
                        <input type="text" readonly class="form-control" value="{{ $data->e_starting }}">
                    </div>
                    <div class="mb-3 col-6 col-lg-3">
                        <label>Charging</label>
                        <input type="text" readonly class="form-control" value="{{ $data->e_charging }}">
                    </div>
                    <div class="mb-3 col-12 col-lg-12">
                        <label>Cost</label>
                        <p>Rp {{ number_format($data->cost) }}</p>
                    </div>
                    <div class="mb-3 col-12 col-lg-12">
                        <label for="">Diagnostic Result</label>
                        <div class="row">
                            <div class="col-6 ">
                                @foreach ($data->accuClaimDetailsBy as $key => $row)
                                    <div>{{ $key + 1 }}. {{ $row->diagnosa }}</div>
                                    @if (($key + 1) % 4 == 0)
                            </div>
                            <div class="col-6">
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 col-lg-4 col-12">
                        <label>
                            Received
                            By,</label>
                        <br>
                        <p><strong>{{ $data->createdBy->name }}</strong>
                        </p>
                    </div>
                    <div class="mb-3 col-lg-4 col-12">
                        <label>
                            Evidence,</label>
                        <br>
                        <div class="text-center">
                            <img class="img-fluid shadow" style="width: 200px" id="img"
                                src="{{ url('file_evidence/' . $data->e_foto) }}" alt="">
                        </div>

                    </div>
                    <div class="mb-3 col-lg-4 col-12">
                        <label>
                            Submitted
                            By</label>
                        <br>
                        <div class="text-center">
                            <img class="img-fluid img-rotate " style="width: 200px" id="img"
                                src="{{ asset('file_signature/' . $data->e_receivedBy) }}" alt="">
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <a href="#" type="button" class="btn btn-warning hideModalEdit"
                            data-bs-dismiss="modal">Close</a>
                        <a href="{{ url('claim/' . $data->id . '/create/final') }}"
                            class="btn btn-primary btnSubmit">Process</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
