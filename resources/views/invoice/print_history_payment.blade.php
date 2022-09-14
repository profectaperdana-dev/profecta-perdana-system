<html>

<head>
    <style>
        /* @import url(//db.onlinewebfonts.com/c/4145587a822071d1d66f5201f5233f42?family=Merchant+Copy); */

        /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page {
            margin: 0.5cm 1cm 1cm;
            width: 100%;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 4.6cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 1cm;
            font-size: 9.5pt;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 4.6cm;
            /** Extra personal styles **/
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: left;
            font-size: 9.5pt;
            font-family: Verdana, Geneva, Tahoma, sans-serif;

            /* line-height: 1.5cm; */
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1cm;

            /** Extra personal styles **/
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: center;
            /* line-height: 1.5cm; */
        }

        body .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body>
    <!-- Define header and footer blocks before your content -->
    <header>

        <table style="width: 100%">
            <tr>
                <td style="width: 20%"><img style="width: 100px;margin-top:15px;"
                        src="{{ public_path('images/logo.png') }}" alt=""></td>
                <td style="width: 34%;text-align:left">
                    <b>CV. Profecta Perdana</b> <br>
                    {{ $warehouse->alamat }} <br>
                    Phone : 0713-82536
                </td>
                <td></td>
                <td style="width: 5%;text-align:left">
                    Invoice <br>

                </td>
                <td style="width: 20%">
                    : {{ $sales_order->order_number }}<br>

                </td>
            </tr>
            <tr>
                <th colspan="6">
                    <hr>
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center">HISTORY PAYMENT

                </th>
            </tr>
            <tr>
                <td colspan="3" style="width: 90%;text-align:left">Customer : <br>
                    {{ $sales_order->customerBy->name_cust }} - {{ $sales_order->customerBy->code_cust }} <br>
                    {{ $sales_order->customerBy->address_cust }} <br>
                </td>
                <td style="width: 10%">Order Date <br>
                    Due Date
                </td>
                <td style="text-align:left">
                    : {{ $sales_order->order_date }} <br>
                    : {{ $sales_order->duedate }}
                </td>

            </tr>
        </table>
    </header>

    <footer>
        {{-- <b>Page-<span class="pagenum"></span>-</b> --}}

    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>

        <table style="width:100%;">
            <thead style="border:1px solid black">
                <tr>
                    <td style="text-align:center;padding:2px;width:10%">No</td>
                    <td style="text-align:left;padding:2px">Payment</td>
                    <td style="text-align:left;padding:2px">Payment Date</td>
                    <td style="text-align:center;padding:2px;width:16%">Amount</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($sales_order->salesOrderCreditsBy as $key => $value)
                    <tr>
                        <td style="text-align:center;padding:2px">{{ $key + 1 }}</td>
                        <td style="text-align:left;padding:2px">Payment -{{ $key + 1 }}</td>
                        <td style="text-align:left;padding:2px">{{ $value->payment_date }}</td>

                        <td style="text-align:right;padding:2px">@currency($value->amount)</td>
                        @php
                            $total += $value->amount;
                        @endphp
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right">
                        <hr>
                    </td>

                </tr>
                <tr>
                    <td colspan="3" style="text-align: right">Total Payment</td>
                    <td style="text-align: right"> @currency($sales_order->total_after_ppn) </td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right">Total Instalment</td>
                    <td style="text-align: right"> @currency($total) </td>
                    @php
                        $sisa = $sales_order->total_after_ppn - $total;
                    @endphp

                </tr>
                <tr>
                    <td colspan="3">

                    </td>
                    <td style="text-align: right">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: right">Remaining Instalment</th>
                    <th style="text-align: right;border:1px solid black">@currency($sisa)</th>
                </tr>
            </tfoot>


        </table>

        <table style="width: 100%">
            <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>






            </tbody>
        </table>
    </main>
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
            '); // End of page_script
        }
    </script>
</body>

</html>
