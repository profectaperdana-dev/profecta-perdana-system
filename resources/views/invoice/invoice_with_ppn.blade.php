<html>

<head>
    <style>
        /* @import url(//db.onlinewebfonts.com/c/4145587a822071d1d66f5201f5233f42?family=Merchant+Copy); */
        /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page {
            size: 22cm 14cm;
            margin-top: 0.5cm;
            margin-left: 0.5cm;
            margin-right: 0.5cm;
            margin-bottom: 1cm;
            font-family: Helvetica;
        }

        /* table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        } */


        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 4.3cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 2cm;
            font-size: 10pt;
            font-family: Helvetica;
        }

        .page-break {
            /* page-break-before: always; */
            page-break-after: auto;

        }

        .page-braek {
            page-break-before: always;
            /* page-break-after: always; */

        }

        /* .page-break {
        } */


        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 4.2cm;
            /** Extra personal styles **/
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: left;
            font-size: 10pt;
            font-family: Helvetica;

            /* line-height: 1.5cm; */
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1.8cm;

            /** Extra personal styles **/
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: center;
            /* line-height: 1.5cm; */
        }

        body .pagenum:before {
            content: counter(page);
        }

        #watermark {
            z-index: -1;
            text-align: center;
            font-size: 50pt;
            font-weight: 700;
            margin-top: 70pt;
            margin-left: 40pt;
            transform: rotate(-15);
            color: rgb(247, 5, 5);
            position: absolute;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
@if ($data->isPaid == 1)
    <div id="watermark" style="position:fixed; opacity:0.2;">
        PAID {{ date('d M Y', strtotime($data->order_date)) }}
    </div>
@endif

<body>
    <header>
        <table style="width: 100%">
            <tr>
                <td style="width: 20%;text-align: center;"><img style="width: 120px;margin-top:0px;"
                        src="{{ url('images/logo.png') }}" alt=""></td>
                <td style="width: 40%;text-align:left">
                    <b>CV. Profecta Perdana</b> <br>
                    {{ $warehouse->alamat }} <br>
                    Phone : {{ $warehouse->telp1 . ' / ' . $warehouse->telp2 }}
                </td>
                {{-- <td></td> --}}
                <td style="width: 20%;text-align:left">
                    Date <br>
                    Invoice <br>
                    Due Date <br>
                    Revision <br>

                </td>
                <td style="width: 20%">
                    : {{ date('d F Y', strtotime($data->order_date)) }} <br>
                    : {{ $data->order_number }}<br>
                    : @if ($data->payment_method == 3)
                        {{ date('d F Y', strtotime('+30 days', strtotime($data->order_date))) }} <br>
                    @else
                        @if ($data->duedate != null)
                            {{ date('d F Y', strtotime($data->duedate)) }} <br>
                        @else
                            - <br>
                        @endif
                    @endif
                    : 0<br>
                </td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center;border-top:1px solid black ">INVOICE

                </th>
            </tr>
            <tr>
                <td colspan="6" style="width: 90%;text-align:left">Invoice to : <br>
                    {{ $data->customerBy->name_cust }} - {{ $data->customerBy->code_cust }} <br>
                    {{ $data->customerBy->address_cust }} ({{ $data->customerBy->phone_cust }} /
                    {{ $data->customerBy->office_number }}) <br>

                    Remarks : {{ $data->remark }}

                </td>
                </td>
            </tr>
        </table>
    </header>
    {{-- END HEADER --}}

    {{-- FOOTER --}}

    {{-- END FOOTER --}}

    {{-- CONTENT --}}
    <main>

        <table style="width:100%;">
            <thead style="border-top:1px solid black">
                <tr style="">
                    <th style="text-align:center;padding:5px">No</th>
                    <th style="text-align:center;padding:5px">Item Description</th>
                    <th style="text-align:center;padding:5px">Price</th>
                    <th style="text-align:center;padding:5px">Qty</th>
                    <th style="text-align:center;padding:5px;">Total</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $y = 0;
                    $total_iterations = count($data->salesOrderDetailsBy);
                    $current_iteration = 0;
                @endphp
                @foreach ($data->salesOrderDetailsBy as $key => $value)
                    {{-- @for ($i = 0; $i < 2; $i++) --}}
                    <?php
                    $y++;
                    $current_iteration++;
                    
                    ?>
                    @php
                        $price = $value->price;
                        if ($value->price == null || $value->price == 0) {
                            $ppn_cost = (float) $value->productSales->harga_jual_nonretail * (float) $ppn;
                            $price = (float) $value->productSales->harga_jual_nonretail + $ppn_cost;
                        }
                        $disc = (float) $value->discount / 100.0;
                        $disc_cost = (float) $price * $disc;
                        $price_disc = (float) ($price - $disc_cost - $value->discount_rp);
                        $sub_total = round(($price - $disc_cost - $value->discount_rp) * $value->qty);
                    @endphp
                    <tr>
                        <td style="text-align:center;">{{ $key + 1 }}.
                        </td>
                        <td style="text-align:left;">
                            {{ $value->productSales->sub_materials->nama_sub_material }}&nbsp;
                            {{ $value->productSales->sub_types->type_name }}&nbsp;
                            {{ $value->productSales->nama_barang }}
                        </td>
                        <td style="text-align:right;">
                            {{ number_format(round($price_disc), 0, ',', '.') }}
                        </td>
                        <td style="text-align:center;">{{ $value->qty }}</td>

                        <td style="text-align:right;">
                            <span
                                style="margin-right: 60px">{{ number_format((float) $sub_total, 0, ',', '.') }}</span>
                        </td>
                        <td>&nbsp;</td>
                    </tr>
                    @if ($y % 7 == 0 && $y != $total_iterations)
                        <div class="page-braek"></div>
                    @endif
                    {{-- @endfor --}}
                @endforeach
            </tbody>
            <tfoot style="border-top:1px solid black">
                <tr>
                    <td colspan="4" style="text-align: right">Total net value excl. PPN</td>
                    <td style="text-align: right"><span
                            style="margin-right: 60px">{{ number_format((float) $data->total, 0, ',', '.') }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right">PPN {{ $ppn * 100 }}%</td>
                    <td style="text-align: right"><span
                            style="margin-right: 60px">{{ number_format((float) $data->ppn, 0, ',', '.') }}</span></td>
                </tr>
                <tr>
                    <th colspan="4" style="text-align: right">Total Due</th>
                    <th style="text-align: right;"><span
                            style="margin-right: 60px;border:1pt solid black;padding: 3pt">@currency($data->total_after_ppn)</span></th>
                </tr>
            </tfoot>
        </table>

    </main>
    <div class="page-break">

        <footer>
            <table class="total" style="width: 100%">
                <tbody>

                    <tr>
                        <td colspan="3" style="text-align: left">
                            Direct transfer to CV. Profecta Perdana <br>
                            Bank Mandiri {{ $warehouse->rek_1 }}<br>
                            Bank BCA {{ $warehouse->rek_2 }}<br>
                            Thank You! We're looking forward to working with you again<br>

                        </td>
                        <th colspan="2" style="text-align: left">
                            <p style="line-height:1.2;margin-top: -30px"><i style="padding-top:0;">Sincerely Yours,</i>
                            </p>
                        </th>
                    </tr>

                </tbody>
            </table>
        </footer>
    </div>
    {{-- END CONTENT --}}

    {{-- PAGE NUMBER --}}
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $text = sprintf(_("Page -%d/%d-"),  $PAGE_NUM, $PAGE_COUNT);
                // Uncomment the following line if you use a Laravel-based i18n
                //$text = __("Page :pageNum/:pageCount", ["pageNum" => $PAGE_NUM, "pageCount" => $PAGE_COUNT]);
                $font = null;
                $size = 9;
                $color = array(0,0,0);
                $word_space = 0.0;  //  default
                $char_space = 0.0;  //  default
                $angle = 0.0;   //  default

                // Compute text width to center correctly
                $textWidth = $fontMetrics->getTextWidth($text, $font, $size);

                $x = ($pdf->get_width() - $textWidth) / 2;
                $y = $pdf->get_height() - 30;

                $pdf->text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
            ');
        }
    </script>



</body>

</html>
