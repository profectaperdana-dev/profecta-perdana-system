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

        /* table,
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        } */


        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 4cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 3cm;
            font-size: 9.5pt;
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
            height: 4cm;
            /** Extra personal styles **/
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: left;
            font-size: 9.5pt;
            font-family: Helvetica;

            /* line-height: 1.5cm; */
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 5cm;

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
{{-- @if ($data->isPaid == 1)
    <div id="watermark" style="position:fixed; opacity:0.2;">
        PAID {{ date('d M Y', strtotime($data->order_date)) }}
    </div>
@endif --}}

<body>

    <header>
        <table style="width: 100%">
            <tr>
                <td style="width: 20%;text-align: center;"><img style="width: 120px;margin-top:0px;"
                        src="{{ url('images/logo.png') }}" alt=""></td>
                <td style="width: 34%;text-align:left">
                    <b>CV. Profecta Perdana</b> <br>
                    {{ $warehouse->alamat }} <br>
                    Phone : {{ $warehouse->telp1 . ' / ' . $warehouse->telp2 }}
                </td>
                <td></td>
                <td style="width: 5%;text-align:left">
                    Date : <br>
                    Invoice <br>
                </td>
                <td style="width: 20%">
                    : {{ date('d F Y', strtotime($data->order_date)) }}<br>
                    : {{ $data->order_number }}
                </td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center;border-top:1px solid black">PURCHASE ORDER
                </th>
            </tr>
            <tr>
                <td colspan="6" style="width: 90%;text-align:left">Purchase To: <br>
                    {{ $data->supplierBy->name }} <br>
                    {{ $data->supplierBy->address }} <br>
                    Remarks : {{ $data->remark }}
                </td>
            </tr>
        </table>
    </header>
    {{-- END HEADER --}}



    {{-- CONTENT --}}
    <main>
        <table style="width:100%;">
            <thead style="border-bottom:1px solid black">
                <tr style="">
                    <th style="text-align:center;padding:5px">No</th>
                    <th style="text-align:center;padding:5px">Item Description</th>
                    <th style="text-align:center;padding:5px">Price</th>
                    <th style="text-align:center;padding:5px">Qty</th>
                    {{-- <th style="text-align:right;padding:5px">Disc (%)</th> --}}
                    <th style="text-align:center;padding:5px;margin-right:30px";>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $y = 0;
                    $current_iteration = 0;
                    $total_iterations = count($data->purchaseDetailBy);
                @endphp
                @foreach ($data->purchaseDetailBy as $key => $value)
                    {{-- @for ($i = 0; $i < 6; $i++) --}}
                    <?php
                    $y++;
                    // $retail_cost = 0;
                    // foreach ($value->retailPriceBy as $retail) {
                    //     if ($retail->id_warehouse == $data->warehouse_id) {
                    //         $retail_cost = (int) $retail->harga_jual;
                    //     }
                    // }
                    $current_iteration++;
                    ?>

                    <tr>
                        <td style="text-align:center;padding:5px">{{ $key + 1 }}.
                        </td>
                        <td style="text-align:left;padding:5px">
                            {{ $value->itemBy->name }}
                        </td>
                        {{-- @php
                            $harga = str_replace(',', '.', $retail_cost);
                            $ppns = (float) $harga * 0.11;
                            $harga_s = (float) $harga + $ppns;
                        @endphp --}}
                        <td style="text-align:right;padding:5px">
                            {{ number_format($value->price, 0, ',', '.') }}
                        </td>
                        <td style="text-align:center;padding:5px">{{ $value->qty }}</td>
                        {{-- <td style="text-align:center;padding:5px">{{ $value->discount }}</td> --}}
                        <td style="text-align:right;margin-right:25px">
                            {{ number_format($value->price * $value->qty, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if ($y % 7 == 0 && $y != $total_iterations)
                        <div class="page-braek"></div>
                    @endif
                    {{-- @endfor --}}
                @endforeach
            </tbody>
            <tfoot style="border-top:1px solid black">
                <tr>

                </tr>
                <tr>
                    <th colspan="4" style="text-align: right">Total</th>
                    <th style="text-align: right;border:1px solid black">@currency($data->total)</th>
                </tr>

            </tfoot>
        </table>

    </main>
    {{-- END CONTENT --}}
    <div class="page-break">

        <footer>
            <table style="width: 100%">
                <thead>

                    <tr>
                        <th style="text-align: center;width: 33%"><i>Created By, </i> </th>
                        <th style="text-align: center;width: 33%"><i>&nbsp; </i> </th>
                        <th style="text-align: center;width: 33%"><i>Acknowledged By, </i></th>


                    </tr>

                    <tr>
                        <th style="width: 33%">&nbsp;</th>
                        <th style="width: 33%">&nbsp;</th>
                        <th style="width: 33%">&nbsp;</th>
                    </tr>
                    <tr>
                        <th style="width: 33%">&nbsp;</th>
                        <th style="width: 33%">&nbsp;</th>
                        <th style="width: 33%">&nbsp;</th>
                    </tr>
                    <tr>
                        <th style="width: 33%">({{ $data->createdBy->name }})</th>
                        <th style="width: 33%">&nbsp;</th>
                        <th style="width: 33%">&nbsp;</th>
                    </tr>
                </thead>
            </table>
        </footer>
    </div>
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
