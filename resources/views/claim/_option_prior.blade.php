<a class="fw-bold text-nowrap modalItem" href="javascript:void(0)" data-bs-toggle="modal"
    data-bs-target="#staticBackdrop{{ $data->id }}">{{ $data->claim_number }}
</a>

<div class="currentModal">
    <div class="modal fade" id="staticBackdrop{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
    tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <form id="{{ $data->id }}" class="needs-validation processClaim" novalidate>
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel">Information Customer</h6>
                </div>
                <div class="modal-body" style="font-size: 10pt">
                    <div class="mb-3">
                        <label>Customer</label>
                        <select name="customer_id" required class="form-select selectMulti" multiple>
                            <option value="Other Customer"
                                {{ $data->customer_id == 'Other Customer' ? 'selected' : '' }}>
                                Other Customers</option>

                            @foreach ($customer as $row)
                                @if ($row->id == $data->customer_id)
                                    <option value="{{ $row->id }}" selected>
                                        {{ $row->code_cust }} - {{ $row->name_cust }}</option>
                                    @continue
                                @endif
                                <option value="{{ $row->id }}">
                                    {{ $row->code_cust }} - {{ $row->name_cust }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Name</label>
                        <input name="sub_name" value="{{ $data->sub_name }}" autocomplete="off" required type="text"
                            class="form-control text-capitalize">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input name="sub_phone" value="{{ $data->sub_phone }}" number data-v-min-length="9"
                            data-v-max-length="13" type="number" required class="form-control fw-bold "
                            placeholder="Enter Phone" aria-label="Server">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input name="email" value="{{ $data->email != null ? $data->email : '-' }}"
                            autocomplete="off" required type="text" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Address</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Enter Address">{{ $data->alamat }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label>Plate Number</label>
                        <input type="text" placeholder="Enter Plate Number" required
                            class="form-control text-uppercase " name="plate_number">
                    </div>
                    <div class="mb-3">
                        <label>Vehicle</label>
                        <select name="vehicle" required class="form-select vehicle selectMulti" multiple>
                            <option value="Car">Car</option>
                            <option value="Motocycle">Motorcycle</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="row" id="car" hidden>
                        <div class="form-group">
                            <label>Car Brand</label>
                            <select multiple class="form-control selectMulti car-brand" name="car_brand_id">
                                @foreach ($car_brand as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->car_brand }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Car Type</label>
                            <select class="form-control selectMulti car-type" name="car_type_id" multiple>
                            </select>
                        </div>

                    </div>
                    <div class="row" id="motor" hidden>
                        <div class="mb-3">
                            <label>Motorcycle Brand</label>
                            <select multiple class="form-control selectMulti motor-brand" name="motor_brand_id">
                                @foreach ($motor_brand as $item)
                                    <option value="{{ $item->id }}">
                                        {{ $item->name_brand }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Motorcycle Type</label>
                            <select multiple class="form-control selectMulti motor-type" name="motor_type_id">
                            </select>
                        </div>

                    </div>
                    <div class="row" id="other" hidden>
                        <div class="mb-3">
                            <label>Other</label>
                            <input class="form-control other-brand" placeholder="Enter Other Machine" type="text"
                                name="other_machine">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @canany(['level1'])
                        <button class="btn btn-danger delete-item" type="button"
                            data-id="{{ $data->id }}">Delete</button>
                    @endcanany
                    <button type="button" class="btn btn-warning hideModalAdd" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btnSubmit">Process</button>
                </div>
            </div>
        </form>
    </div>
</div>

</div>
<!-- Modal -->
