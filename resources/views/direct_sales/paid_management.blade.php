@extends('layouts.master')
@section('content')
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/date-picker.css') }}">
        @include('report.style')

        <style>
            .table {
                background-color: rgba(211, 225, 222, 255);
                -webkit-print-color-adjust: exact;
            }
        </style>
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
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-xl-12 xl-100">
                <div class="card">
                    <div class="card-body">
                        @if (request()->get('cancel_cust') && request()->get('cancel_order'))
                            <div class="alert alert-warning text-dark" role="alert">
                                <strong>Warning!</strong> You must cancel the payment before approval. Detail Invoice:
                                <strong>{{ request()->get('cancel_order') }}</strong> on behalf of
                                <strong>{{ request()->get('cancel_cust') }}</strong>
                            </div>
                        @endif
                        <div class="row align-items-end">
                            <div class="col-lg-4 ">
                                <label for="" class="form-label">Find invoice</label>
                                <div class="input-group has-validation">
                                    <input class="form-control form-control-sm" type="text"
                                        placeholder="Enter invoice number..." id="find-inv">
                                    <div class="invalid-feedback">
                                        Please enter invoice number.
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-2">
                                <button type="button" class="btn btn-primary btn-sm" id="find-inv-btn">Find</button>
                            </div>

                        </div>
                        <div class="table-responsive">
                            <table id="example1" class="table table-sm table-striped" style="width:100%">
                                <thead>
                                    <tr class="text-center">

                                        <th>No</th>
                                        <th>Customer</th>
                                        <th class="text-center">Credit Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr class="table-info">
                                        <th></th>
                                        <th class="text-center">Total</th>
                                        <th class="text-end"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <input type="text" hidden value="{{ $ }}"> --}}
    <!-- Container-fluid Ends-->
    @push('scripts')
        <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script> --}}
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.en.js') }}"></script>
        <script src="{{ asset('assets/js/datepicker/date-picker/datepicker.custom.js') }}"></script>
        <script>
            $(document).ready(function() {

                let csrf = $('meta[name="csrf-token"]').attr("content");
                $('insideModal').DataTable();
                $(document).on('click', '.update-btn', function() {
                    let modal_id = $(this).attr('data-bs-target');

                    $(modal_id).find('.datepicker-here').datepicker({
                        onSelect: function(formattedDate, date, inst) {
                            inst.hide();
                        }
                    });

                    $('form').submit(function(e) {
                        var form = $(this);
                        var button = form.find('button[type="submit"]');

                        if (form[0].checkValidity()) { // check if form has input values
                            button.prop('disabled', true);
                            // e.preventDefault(); // prevent form submission
                        }
                    });

                    $('.payment').select2({
                        'width': '100%',
                        dropdownParent: modal_id
                    });
                    
                    $('.cash-bank').select2({
                        'width': '100%',
                        'placeholder': "Select Cash & Bank",
                        dropdownParent: modal_id,
                        ajax: {
                            type: "GET",
                            url: "/finance/coa/getCoaCashBank",
                            data: function(params) {
                                return {
                                    _token: csrf,
                                    q: params.term, // search term
                                };
                            },
                            dataType: "json",
                            delay: 250,
                            processResults: function(data) {
                                return {
                                    results: $.map(data, function(item) {
                                        return [{
                                            text: '(' + item.coa_code + ') ' + item
                                                .name,
                                            id: item.coa_code,


                                        }, ];
                                    }),
                                };
                            },
                        },
                    });
                    
                    $(modal_id).find('.amount-method').on('change', function() {
                        if ($(this).val() == 'part') {
                            $(this).siblings('.total').attr('hidden', false);
                        } else {
                            $(this).siblings('.total').attr('hidden', true);
                        }
                    });

                    $(modal_id).find('.openSettlement').on('click', function() {
                        let data_id = $(this).attr('data-id');
                        
                        function parseDate(date) {
                            let now = date;
                            // Format the date as "dd-mm-yyyy"
                            let day = now.getDate().toString().padStart(2, '0');
                            let month = (now.getMonth() + 1).toString().padStart(2, '0');
                            let year = now.getFullYear();
                            let formattedDate = `${day}-${month}-${year}`;
                            return formattedDate;
                        }
                        // Get the current date


                        // Set the value of the input element
                        $('input[name="pay[0][payment_date]"]').val(parseDate(new Date()));
                        // console.log($(this).closest('.table-responsive').siblings('.settlement-parent')
                        //     .find(
                        //         '.settlement-section' + data_id).html());
                        $(this).closest('.table-responsive').siblings('.settlement-parent').find(
                            '.card').each(function() {
                            $(this).attr('hidden', true);
                        });
                        $(this).closest('.table-responsive').siblings('.settlement-parent').find(
                            '.settlement-section' + data_id).attr('hidden', false);
                    });
                    
                    $(modal_id).find('.openTrade').on('click', function() {
                        let data_id = $(this).attr('data-id');

                        $(this).closest('.table-responsive').siblings('.trade-parent').find(
                            '.card').each(function() {
                            $(this).attr('hidden', true);
                        });
                        $(this).closest('.table-responsive').siblings('.trade-parent').find(
                            '.trade-section' + data_id).attr('hidden', false);
                    });

                    $(modal_id).find('.close-settlement').on('click', function() {
                        $(this).closest('.card').attr('hidden', true);
                    });
                    
                    $(modal_id).find('.cancel-full').on('click', function() {
                        let credit_amount = $(this).closest('tr').find('.credit-amount').text();
                        credit_amount = credit_amount.trim();
                        $(this).parent().prev().find('.cancel-amount').val(credit_amount);
                        let input_next = credit_amount.replace(/[^0-9]/g, "");
                        input_next = input_next ? parseInt(input_next, 10) : 0;
                        $(this).parent().prev().find('.cancel-amount').next().val(input_next);

                        let total_instalment = $(this).closest('tbody').siblings('tfoot').find(
                            '.total-instalment').val();
                        let current_cancel_amount = 0;

                        $(this).closest('tbody').find('.cancel-amount').each(function(index) {
                            let raw_amount = $(this).val();
                            if (raw_amount == '') {
                                raw_amount = '0';
                            }
                            // console.log(raw_amount);
                            current_cancel_amount += parseInt(raw_amount.replace(/[^0-9]/g,
                                ""));
                            // console.log(current_cancel_amount);
                        });
                        // console.log(total_instalment);
                        // console.log(current_cancel_amount);
                        let updated_instalment = total_instalment - current_cancel_amount;
                        $(this).closest('tbody').siblings('tfoot').find(
                            '.total-instalment-text').text(updated_instalment.toLocaleString());
                    });

                    // let total_instalment = $(modal_id).find('.total-instalment').text();
                    // console.log(total_instalment);

                    $(modal_id).find('.cancel-amount').on('keyup', function() {
                        let curr = $(this);
                        let total_instalment = curr.closest('tbody').siblings('tfoot').find(
                            '.total-instalment').val();
                        let current_cancel_amount = 0;

                        curr.closest('tbody').find('.cancel-amount').each(function(index) {
                            let raw_amount = $(this).val();
                            if (raw_amount == '') {
                                raw_amount = '0';
                            }
                            // console.log(raw_amount);
                            current_cancel_amount += parseInt(raw_amount.replace(/[^0-9]/g,
                                ""));
                            // console.log(current_cancel_amount);
                        });
                        // console.log(total_instalment);
                        // console.log(current_cancel_amount);
                        let updated_instalment = total_instalment - current_cancel_amount;
                        curr.closest('tbody').siblings('tfoot').find(
                            '.total-instalment-text').text(updated_instalment.toLocaleString());
                    });

                    $(modal_id).find('.total').on('keyup', function() {
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
                        var input = input.replace(/[^0-9]/g, "");
                        input = input ? parseInt(input, 10) : 0;
                        $this.val(function() {
                            return (isNaN(input)) ? "" : input.toLocaleString("en");
                        });
                        $this.next().val(input);
                    });

                    let retail_id = $(modal_id).find('.modal-body').find('.id').val();
                    // let total_instalment = $(modal_id).find('.modal-body').find('.total-instalment');
                    let total = $(modal_id).find('.modal-body').find('.totalraw').val();
                    let remaining_instalment = $(modal_id).find('.modal-body').find('.remaining-instalment');

                    $.ajax({
                        context: this,
                        type: "GET",
                        url: "/retail/getTotalInstalment/" + retail_id,
                        dataType: "json",
                        success: function(data) {
                            // total_instalment.val('Rp. ' + data.toLocaleString());
                            let remain = parseInt(total) - parseInt(data);
                            remaining_instalment.val('Rp. ' + remain.toLocaleString());

                        },
                    });
                    
                    let x = 0;
                    //Dynamic Button
                    $(document).off("click", ".addPay");
                    $(document).on("click", ".addPay", function() {
                        ++x;
                        var form = `<div class="form-group row pt-2 pay">
                                        <div class="col-lg-4 form-group">
                                            <label>Pay Amount</label>
                                            <input type="hidden" name="pay[${x}][amount_method]" value="part">
                                            <input type="text" class="form-control total"
                                                placeholder="Enter amount...">
                                            <input type="hidden" name="pay[${x}][amount]" class="form-control">
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label>Pay Date</label>
                                            <input class="datepicker-here form-control digits"
                                                data-position="bottom left" type="text"
                                                data-language="en" name="pay[${x}][payment_date]"
                                                autocomplete="off">
                                        </div>
                                        <div class="col-lg-3 form-group">
                                            <label>Payment Method</label>
                                            <select name="pay[${x}][payment_method]" id=""
                                                class="form-control payment">
                                                <option value="Transfer">Transfer</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Trade In">Trade In</option>
                                                <option value="Rebate">Rebate</option>
                                                <option value="Voucher">Voucher</option>
                                            </select>
                                        </div>
                                        <div class="col-4 col-lg-1 form-group">
                                            <label>&nbsp;</label>

                                            <a href="javascript:void(0)"
                                                class="form-control text-white text-center remPay"
                                                style="border:none; background-color:#c35245">-</a>
                                        </div>
                                        <div class="col-4 col-lg-1 form-group">
                                            <label>&nbsp;</label>

                                            <a href="javascript:void(0)"
                                                class="form-control text-white text-center addPay"
                                                style="border:none; background-color:#38a34c">+</a>
                                        </div>

                                    </div>`;
                        $(modal_id).find(".payParent").append(form);

                        $('.payment').select2({
                            'width': '100%',
                            dropdownParent: modal_id
                        });

                        $(this).closest('.pay').next().find('.datepicker-here').datepicker({
                            onSelect: function(formattedDate, date, inst) {
                                inst.hide();
                            }
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
                        // Get the current date

                        // Set the value of the input element
                        $(this).closest('.pay').next().find(`input[name="pay[${x}][payment_date]"]`)
                            .val(parseDate(
                                new Date()));

                        $(modal_id).find('.total').on('keyup', function() {
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
                            var input = input.replace(/[^0-9]/g, "");
                            input = input ? parseInt(input, 10) : 0;
                            $this.val(function() {
                                return (isNaN(input)) ? "" : input.toLocaleString("en");
                            });
                            $this.next().val(input);
                        });
                    });

                    //remove Sales Order fields
                    $(modal_id).on("click", ".remPay", function() {
                        $(this).closest(".row").remove();
                    });

                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                load_data();

                function load_data(invoice_number = '') {
                    $('#example1').DataTable({
                        "language": {
                            "processing": `<i class="fa text-success fa-refresh fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>`,
                        },
                        "lengthChange": false,
                        "paging": false,
                        "bPaginate": false, // disable pagination
                        "bLengthChange": false, // disable show entries dropdown
                        "searching": true,
                        "ordering": true,
                        "info": false,
                        "autoWidth": false,
                        "responsive": true,
                        "processing": true,
                        "serverSide": true,
                        "pageLength": -1,
                        destroy: true,
                        ajax: {
                            url: "{{ url('/retail/manage_payment') }}",
                            data: {
                                invoice_number: invoice_number
                            }
                        },
                        columns: [{
                                width: '5%',
                                data: 'DT_RowIndex',
                                name: 'DT_Row_Index',
                                "className": "text-center fw-bold",
                                orderable: false,
                                searchable: false
                            },
                            {
                                className: " fw-bold text-center",
                                data: 'action',
                                name: 'action',
                                orderable: true,
                                searchable: true
                            },

                            {
                                className: "text-end",
                                data: 'total_after_ppn',
                                name: 'total_after_ppn',
                                orderable: true,
                                searchable: true
                            },
                        ],
                        footerCallback: function(row, data, start, end, display) {
                            var api = this.api();

                            // PPN
                            var visibleData = api.column(2).nodes().to$().map(function() {
                                return $(this).text();
                            }).toArray();
                            var visibleColumns = api.columns().visible();
                            var filteredData = visibleData.filter(function(data) {
                                return data.trim() !== '';
                            });
                            var totalPPN = 0;
                            filteredData.forEach(function(data) {
                                if (data != '') {
                                    let raw1 = data.split(",");
                                    raw2 = raw1.join('');

                                    totalPPN += parseInt(raw2);
                                }
                            });


                            $(api.column(2).footer()).html(totalPPN.toLocaleString());
                        },
                        drawCallback: function(settings) {
                            // Kode yang akan dijalankan setelah DataTable selesai dikerjakan
                            $('#thisModal').html('');
                            $('.currentModal').each(function(){
                                let currentModal = $(this).html();
                                $(this).html('');
                                $('#thisModal').append(currentModal);
                            });
                            
                            // console.log($('#currentModal').html());
                            // Lakukan tindakan lain yang Anda inginkan di sini
                        },

                    });
                }
                
                $('#find-inv-btn').click(function() {
                    let inv = $('#find-inv');
                    let invValue = inv.val();
                    if (invValue.length === 0) {
                        inv.addClass('is-invalid');
                    } else {
                        inv.removeClass('is-invalid');

                        load_data(invoice_number = invValue);
                    }
                });


            });
        </script>
    @endpush
@endsection
