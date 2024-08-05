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
            margin-left: 0.3cm;
            margin-right: 0.5cm;
            margin-bottom: 1cm;
            font-family: Roboto;
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
            margin-bottom: 0cm;
            font-size: 9pt;
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
            font-size: 9pt;
            font-family: Helvetica;

            /* line-height: 1.5cm; */
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 4cm;

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
    <!-- Define header and footer blocks before your content -->
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
                <td style="width: 20%;text-align:left">
                    Date <br>
                    Delivery Order Number <br>
                    Reference

                </td>
                <td style="width: 20%">
                    @php
                        $so_number = $data->order_number;
                        $so_number = str_replace('IPPP', 'DIPP', $so_number);
                        $do = $so_number;
                    @endphp
                    : {{ $now = date('d F Y', strtotime($data->order_date)) }} <br>
                    : {{ $do }} <br>
                    : {{ $data->order_number }}

                </td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center;border-top:1px solid black ">DELIVERY ORDER
                </th>
            </tr>
            <tr>
                <td colspan="6" style="width: 90%;text-align:left">Delivery to : <br>
                    @if(is_numeric($data->id_customer))
                        {{ $data->customerBy->name_cust }} - {{ $data->customerBy->code_cust }} <br>
                        {{ $data->customerBy->address_cust }} ({{ $data->customerBy->phone_cust }} /
                        {{ $data->customerBy->office_number }}) 
                    @else
                        {{ $data->id_customer }} <br>
                        {{ $data->address }}
                    @endif    
                    <br>

                    Remarks : {{ $data->remark }}

                </td>


            </tr>
        </table>
    </header>



    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <table style="width:100%;">
            <thead style="border-bottom:1px solid black">
                <tr style="">
                    <th style="text-align:center;padding:2px;width:15%">No</th>
                    <th style="text-align:center;padding:2px;width:30%">Item Description</th>
                    <th style="text-align:center;padding:2px">&nbsp;</th>
                    <th style="text-align:center;padding:2px">&nbsp;</th>
                    <th style="text-align:center;padding:2px">Qty</th>
                    <th style="text-align:center;padding:2px">&nbsp;</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $y = 0;
                    $total_qty = 0;
                    $total_iterations = count($data->transactionDetailBy);
                    $current_iteration = 0;
                    $totals = [];
                @endphp


                @foreach ($data->transactionDetailBy as $value)
                    <?php
                    $itemName = $value->itemBy->name;
                    $qty = $value->qty;
                    $totals[$itemName] = isset($totals[$itemName]) ? $totals[$itemName] + $qty : $qty;
                    ?>
                @endforeach

                @foreach ($totals as $itemName => $totalQty)
                    {{-- @for ($i = 0; $i < 2; $i++) --}}
                    <?php
                    $y++;
                    $current_iteration++;
                    ?>
                    <tr>
                        <td style="text-align:center;">{{ $loop->iteration }}.</td>
                        <td style="text-align:left;">
                            {{ $itemName }}
                        </td>
                        <td style="text-align:left;">&nbsp;</td>
                        <td style="text-align:center">&nbsp; </td>
                        <td style="text-align:center;">{{ $totalQty }}</td>
                        <td style="text-align:center">&nbsp; </td>
                        @php
                            $total_qty += $totalQty;
                        @endphp

                    </tr>

                    @if ($y % 7 == 0 && $y != $total_iterations)
                        <div class="page-braek"></div>
                    @endif
                    {{-- @endfor --}}
                @endforeach
            </tbody>
            <tfoot style="border-top:1px solid black">
                <tr>
                    <th colspan="4" style="text-align: right">Qty Total</th>
                    <th style="text-align: center;;border:2px solid black"> {{ $total_qty }} pcs</th>
                </tr>
            </tfoot>
        </table>
    </main>
    <div class="page-break">
        <footer>
            <table style="width: 100%">
                <thead>

                    <tr>
                        <th style="text-align: center;width: 33%"><i>Customer,</i> </th>
                        <th style="text-align: center;width: 33%"><i>Warehouse,</i> </th>
                        <th style="text-align: center;width: 33%"><i>Driver,</i></th>


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
                        <th style="text-align: center;width: 33%"><i>
                            @if(is_numeric($data->id_customer))
                                ( {{ $data->customerBy->name_cust }} )
                            @else
                                ( {{ $data->id_customer }} )
                            @endif    
                            </i> </th>
                        <th style="width: 33%">&nbsp;</th>
                        <th style="width: 33%">&nbsp;</th>
                    </tr>
                </thead>
            </table>
        </footer>
    </div>
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
