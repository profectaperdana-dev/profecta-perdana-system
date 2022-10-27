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
            margin-top: 4cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 1cm;
            font-size: 9.5pt;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }

        .page-break {
            page-break-before: always;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 4cm;
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

        #watermark {
            position: fixed;

            /**
                    Set a position in the page for your image
                    This should center it vertically
                **/
            bottom: 1cm;
            left: 6cm;
            opacity: 0.5;

            /** Change image dimensions**/
            width: 7cm;
            height: 6cm;

            /** Your watermark should be behind every content**/
            z-index: -1000;
        }
    </style>
</head>

<body>
    {{-- <div id="watermark">
        @if ($data->isPaid == 1)
            <img src="{{ public_path('images/paid.png') }}" height="100%" width="100%" /> jabdjhasbd
        @endif
    </div> --}}
    {{-- HEADER --}}
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
                    Invoice Trade-In <br>
                </td>
                <td style="width: 20%">
                    : {{ $data->trade_in_number }}<br>
                </td>
            </tr>
            <tr>
                <th colspan="6">
                    <hr>
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center">INVOICE TRADE-IN

                </th>
            </tr>
            <tr>
                <td colspan="3" style="width: 90%;text-align:left">Customer :
                    {{ $data->customer }} <br>
                    Phone :
                    {{ $data->customer_phone }} <br>

                </td>
                <td style="width: 10%">Date
                </td>
                <td style="text-align:left">
                    : {{ date('d F Y', strtotime($data->trade_in_date)) }} <br>
                </td>
            </tr>
        </table>
    </header>
    {{-- END HEADER --}}

    {{-- FOOTER --}}
    <footer>
    </footer>
    {{-- END FOOTER --}}

    {{-- CONTENT --}}
    <main>
        <table style="width:100%;">
            <thead style="border-bottom:1px solid black">
                <tr style="">
                    <th style="text-align:center;padding:5px">No</th>
                    <th style="text-align:left;padding:5px">Item Description</th>
                    <th style="text-align:center;padding:5px">Qty</th>
                    <th style="text-align:right;padding:5px;margin-right:30px";>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    
                    $y = 0;
                @endphp
                @foreach ($data->tradeInDetailBy as $key => $value)
                    {{-- @for ($i = 0; $i < 6; $i++) --}}
                    <?php
                    $y++;
                    ?>
                    <tr>
                        <td style="text-align:center;padding:5px">{{ $key + 1 }}.
                        </td>
                        <td style="text-align:left;padding:5px">
                            {{ $value->productTradeIn->name_product_trade_in }}
                            &nbsp;
                        </td>
                        <td style="text-align:center;padding:5px">{{ $value->qty }}</td>
                        @php
                            $sub_total = $value->productTradeIn->price_product_trade_in * $value->qty;
                            $total = $total + $sub_total;
                        @endphp
                        <td style="text-align:right;margin-right:30px">{{ number_format($sub_total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if ($y % 5 == 0)
                        <div class="page-break"></div>
                    @endif
                    {{-- @endfor --}}
                @endforeach
                <tr>
                    <td colspan="4" style="text-align: right">
                        <hr>
                    </td>

                </tr>
            </tbody>
        </table>
        <table style="width: 100%">

            <tbody>
                <tr>
                    <th style="width: 70%">&nbsp;</th>
                    <th style="text-align: right;width: 15%">Total</th>
                    <th style="width: 15%;text-align: right;border:1px solid black"><i>@currency($total)</i> </th>
                </tr>

            </tbody>
        </table>
        <table style="width: 100%">
            <tr>
                <th style="width: 33%">&nbsp;</th>
                <th style="width: 33%">&nbsp;</th>
                <th style="width: 33%">&nbsp;</th>
            </tr>
            <tr>
                <th style="text-align: center;width: 33%"><i>Created By,<br>{{ Auth::user()->name }}</i></th>
                <th style="text-align: right;width: 33%"><i>&nbsp;</i></th>
                <th style="text-align: center;width: 33%" style="text-transform: capitalize"><i>Customer,
                        <br>{{ $data->customer }}</i></th>
            </tr>
        </table>
    </main>
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
    {{-- END PAGE NUMBER --}}
    @if ($data->isPaid == 1)
        <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
            $height = $pdf->get_height();
            $width = $pdf->get_width();
            $text = "PAID "."{{ date('d-m-Y', strtotime($data->paid_date)) }}";
            $pdf->set_opacity(.2, "Multiply");
            $pdf->set_opacity(.2);
            $pdf->page_text($width / 5, $height / 1.5, $text , null, 50, array(216, 0, 0), 2, 2, -15);
            ');
        }
    </script>
    @endif


</body>

</html>
