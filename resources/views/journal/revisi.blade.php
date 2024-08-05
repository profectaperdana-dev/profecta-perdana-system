@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')
        <style>
            table.dataTable thead tr>.dtfc-fixed-left,
            table.dataTable thead tr>.dtfc-fixed-right,
            table.dataTable tfoot tr>.dtfc-fixed-left,
            table.dataTable tfoot tr>.dtfc-fixed-right {
                background-color: #c0deef !important;
            }

            tr.ref-123 {
                background-color: #ff0000;
                /* Red background for rows with ref value 123 */
            }

            tr.ref-456 {
                background-color: #00ff00;
                /* Green background for rows with ref value 456 */
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="font-weight-bold">{{ $title }}</h3>

                </div>

            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">

        <div class="row">
            {{-- <div class="col-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="card-title">
                            History Journal Revision
                        </h6>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead style="background-color: rgba(211, 225, 222, 255)">
                                <tr class="text-center">
                                    <th>Warehouse</th>
                                    <th>Account</th>
                                    <th>Date</th>
                                    <th>Ref</th>
                                    <th>Type</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($journal_detail as $item)
                                    <tr>
                                        <td>{{ $item->jurnal->warehouse->warehouses }}</td>
                                        <td>{{ $item->coa->name }}</td>
                                        <td>{{ date('Y-m-d', strtotime($item->jurnal->date)) }}</td>
                                        <td>{{ $item->ref }}</td>
                                        <td>
                                            @if ($item->debit != 0)
                                                Debit
                                            @else
                                                Credit
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($item->debit != 0)
                                                {{ number_format($item->debit) }}
                                            @else
                                                {{ number_format($item->credit) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->status == 1)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactice</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
            <div class="col-12">
                <div class="card">
                    <form class="needs-validation" novalidate method="post"
                        action="{{ url('/finance/journal/' . $journal->id . '/revisi') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header pb-0">
                            <h6 class="card-title">
                                Create Journal Revision
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row form-group">
                                <div class="col-lg-4 col-12 mb-3">
                                    <label for="">Date</label>
                                    <input class="datepicker-here form-control digits" data-position="bottom left"
                                        type="text" data-language="en" id="exp_date"
                                        data-value="{{ date('d-m-Y', strtotime($journal->date)) }}" name="date"
                                        autocomplete="off">
                                    {{-- <input name="date" value="" type="date" class="form-control"> --}}
                                </div>
                                <div class="col-lg-4 col-12 mb-3">
                                    <label for="">Warehouse</label>
                                    <select name="warehouse_id" multiple required class="warehouse form-control">
                                        @foreach ($warehouse as $item)
                                            <option @if ($item->id == $journal->warehouse_id) selected @endif
                                                value="{{ $item->id }}">
                                                {{ $item->warehouses }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 col-12 form-group">
                                    <label class="text-black">
                                        Department
                                    </label>
                                    <select name="department" multiple class="form-control department">
                                        @foreach ($department as $item)
                                            <option value="{{ $item }}"
                                                @if ($item == $journal->department) selected @endif>{{ $item }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="">Memo</label>
                                    <textarea name="memo" class="form-control" id="" cols="30" rows="2">{{ $journal->memo }}
                                    </textarea>
                                </div>
                            </div>
                            <div class="canvasForm">
                                @foreach ($journal->jurnal_detail->where('status', 1) as $value)
                                    <div class="row form rounded mx-auto py-2 mb-3" style="background-color: #c7d7b9">
                                        <div class="col-3 mb-3 selectCoA">
                                            <label for="">CoA</label>
                                            <select name="requestForm[{{ $loop->index }}][account_id]" required
                                                class="account form-control text-capitalize required" multiple>
                                                @foreach ($coa as $item)
                                                    <option @if ($value->coa_code == $item->coa_code) selected @endif
                                                        value="{{ $item->coa_code }}">
                                                        ({{ $item->coa_code }})
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <label for="">Type</label>
                                            <div class="d-flex justify-content-between">
                                                <div class="radio radio-primary mt-2">
                                                    <input class="typeInputDebit" id="debit{{ $loop->index }}"
                                                        type="radio" name="requestForm[{{ $loop->index }}][type]"
                                                        value="debit" @if ($value->debit != 0) checked @endif>
                                                    <label class="typeLabelDebit mb-0"
                                                        for="debit{{ $loop->index }}">Debit</label>
                                                </div>
                                                <div class="radio radio-primary mt-2">
                                                    <input class="typeInputCredit" id="credit{{ $loop->index }}"
                                                        type="radio" name="requestForm[{{ $loop->index }}][type]"
                                                        value="credit" @if ($value->credit != 0) checked @endif>
                                                    <label class="typeLabelCredit mb-0"
                                                        for="credit{{ $loop->index }}">Credit</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <label for="">Ref</label>
                                            <input type="text" required name="requestForm[{{ $loop->index }}][ref]"
                                                class="ref form-control" placeholder="Enter Ref."
                                                value="{{ $value->ref }}">
                                        </div>
                                        <div class="col-3">
                                            <label for="">Total</label>
                                            <input type="text" class="total text-end form-control"
                                                value="{{ $value->debit != 0 ? number_format($value->debit) : number_format($value->credit) }}">
                                            <input class="total" type="hidden"
                                                name="requestForm[{{ $loop->index }}][total]"
                                                value="{{ $value->debit != 0 ? $value->debit : $value->credit }}">
                                        </div>

                                        @if ($loop->iteration == 1)
                                            <div class="col-2 col-lg-2 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control text-white addForm text-center bg-primary">+</a>
                                            </div>
                                        @else
                                            <div class="col-1 col-lg-1 form-group">
                                                <label for="">&nbsp;</label>
                                                <a href="javascript:void(0)"
                                                    class="form-control text-white deleteForm text-center bg-danger">-</a>
                                            </div>
                                            <div class="col-1 col-lg-1 form-group">
                                                <label for="">&nbsp;</label>
                                                <a id="" href="javascript:void(0)"
                                                    class="form-control text-white addForm bg-primary text-center">+</a>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>
                            <div class="my-2">
                                <label for="">Need Adjusting?</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" value="1" name="isAdjusted"
                                        @if ($journal->isadjusted == 1) checked @endif>
                                    <label class="form-check-label" for="flexSwitchCheckDefault">Adjusting</label>
                                </div>


                            </div>
                            <div class="col-12 mb-3">
                                <label for="">Reason</label>
                                <textarea name="reason" class="form-control" id="" cols="30" rows="2">-</textarea>
                            </div>
                            <div class="col-12 d-flex justify-content-between mb-3">
                                <div class="fw-bold text-dark">Debit : <span class="totalDebit"></span></div>
                                <div class="fw-bold text-dark">Credit : <span class="totalCredit"></span></div>
                            </div>
                            <div class="card-footer">
                                <div class="form-group">
                                    <a class="btn btn-danger" href="{{ url()->previous() }}">Back
                                    </a>
                                    <a href="{{ url('finance/journal/' . $journal->id . '/cancel') }}" type="button"
                                        class="btn btn-warning">Cancel Journal</a>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@emretulek/jbvalidator"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        <script>
            $(document).ready(function() {
                let csrf = $('meta[name="csrf-token"]').attr("content");
                $('.datepicker-here').datepicker({
                    onSelect: function(formattedDate, date, inst) {
                        inst.hide();
                    },
                });

                $('.department').select2({
                    placeholder: 'Choose Department',
                    maximumSelectionLength: 1,
                    width: '100%',
                    allowClear: true,
                });

                function parseDate(date) {
                    let now = date;
                    // Format the date as "dd-mm-yyyy"
                    let day = now.getDate().toString().padStart(2, '0');
                    let month = (now.getMonth() + 1).toString().padStart(2, '0');
                    let year = now.getFullYear();
                    let formattedDate = `${day}-${month}-${year}`;
                    return formattedDate;
                }

                document.querySelector('#exp_date').value = $('#exp_date').attr('data-value');

                function selectWarehouse() {
                    $(document).find('.warehouse').select2({
                        placeholder: 'Choose Warehouse',
                        maximumSelectionLength: 1,
                        width: '100%',
                        allowClear: true,
                    });
                }
                selectWarehouse();

                function selectCoa() {
                    $('.account').select2({
                        placeholder: 'Choose CoA',
                        maximumSelectionLength: 1,
                        width: '100%',
                        allowClear: true,
                    });
                }
                selectCoa();

                function inputListener() {
                    $(document).find('.total').on('input', function() {
                        var selection = window.getSelection().toString();
                        if (selection !== '') {
                            return;
                        }
                        // When the arrow keys are pressed, abort.
                        if ($.inArray(event.keyCode, [38, 40, 37, 39]) !== -1) {
                            return;
                        }
                        var $this = $(this);
                        // Get the value.
                        var input = $this.val();
                        var input = input.replace(/[\D\s\._\-]+/g, "");
                        input = input ? parseInt(input, 10) : 0;
                        $this.val(function() {
                            return (input === 0) ? "" : input.toLocaleString();
                        });
                        $this.next().val(input);

                    });
                }
                inputListener();

                function totalDebit() {
                    let totalDebit = 0;
                    $('.typeInputDebit').each(function() {
                        if ($(this).is(':checked')) {
                            let total = $(this).parents('.form').find('.total').val();
                            totalDebit += parseInt(total.replace(/,/g, ''));
                        }
                    });
                    $('.totalDebit').text(totalDebit.toLocaleString());
                }
                totalDebit();

                function totalCredit() {
                    let totalCredit = 0;
                    $('.typeInputCredit').each(function() {
                        if ($(this).is(':checked')) {
                            let total = $(this).parents('.form').find('.total').val();
                            totalCredit += parseInt(total.replace(/,/g, ''));
                        }
                    });
                    $('.totalCredit').text(totalCredit.toLocaleString());
                }
                totalCredit();

                $(document).on('change', '.typeInputDebit', function() {
                    totalDebit();
                    totalCredit();
                });

                $(document).on('change', '.typeInputCredit', function() {
                    totalCredit();
                    totalDebit();
                });

                $(document).on('input', '.total', function() {
                    totalDebit();
                    totalCredit();
                });





                $(document).off('click', '.addForm')
                $(document).on('click', '.addForm', function(e) {
                    e.preventDefault();

                    // Hapus Select2 dari elemen akun pada formulir asli
                    $('.canvasForm .form:last .account').select2('destroy').siblings('.select2-container')
                        .remove();

                    // Clone the last form
                    let newForm = $('.canvasForm .form').last().clone();

                    // Update index for new form
                    let newIndex = $('.canvasForm .form').length;

                    // Update the attributes and names of form elements
                    newForm.find('.account').attr('name', `requestForm[${newIndex}][account_id]`);

                    newForm.find('.typeInputDebit').attr('id', `debit${newIndex}`);
                    newForm.find('.typeInputDebit').attr('name', `requestForm[${newIndex}][type]`);
                    newForm.find('.typeLabelDebit').attr('for', `debit${newIndex}`);


                    newForm.find('.typeInputCredit').attr('id', `credit${newIndex}`);
                    newForm.find('.typeInputCredit').attr('name', `requestForm[${newIndex}][type]`);
                    newForm.find('.typeLabelCredit').attr('for', `credit${newIndex}`);


                    newForm.find('.ref').attr('name', `requestForm[${newIndex}][ref]`);
                    newForm.find('.total').attr('name', `requestForm[${newIndex}][total]`);

                    // Copy the selected values from the original form's account field to the cloned form
                    let selectedValues = newForm.prev('.form').find('.account').val();
                    newForm.find('.account').val(selectedValues).trigger('change');
                    newForm.find('.typeInputCredit,.typeInputDebit').prop('checked', false);
                    // newForm.find('.ref').val('');
                    newForm.find('.total').val('');

                    // Append the new form to the container
                    newForm.appendTo('.canvasForm');

                    selectCoa();
                    inputListener();
                });


                // Add event delegation for dynamically added deleteForm elements
                $(document).on('click', '.deleteForm', function(e) {
                    e.preventDefault();

                    // Remove the parent form when deleteForm is clicked
                    $(this).closest('.form').remove();
                });


                $(document).on('click', '.deleteForm', function(e) {
                    e.preventDefault();
                    $(this).parents('.form').remove();
                })




            });
        </script>
    @endpush
@endsection
