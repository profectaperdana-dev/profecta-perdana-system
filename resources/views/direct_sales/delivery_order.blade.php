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

        .page-break {
            page-break-before: always;
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
                <td style="width: 20%;text-align:left">
                    Delivery Order Number <br>
                    Reference

                </td>
                <td style="width: 20%">
                    @php
                        $so_number = $data->order_number;
                        $so_number = str_replace('RSPP', 'DOPP', $so_number);
                        $do = $so_number;
                    @endphp
                    : {{ $do }} <br>
                    : {{ $data->order_number }}

                </td>
            </tr>
            <tr>
                <th colspan="6">
                    <hr>
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center">DELIVERY ORDER

                </th>
            </tr>
            <tr>
                <td colspan="3" style="width: 90%;text-align:left">Delivery To : <br>
                    @if (is_numeric($data->cust_name))
                        {{ $data->customerBy->name_cust }}
                    @else
                        {{ $data->cust_name }}
                    @endif
                    <br>
                    {{ $data->address }} <br>
                    Phone Customer : {{ $data->cust_phone }}
                </td>
                <td>Delivery Date <br>

                </td>
                <td style="text-align:left">
                    : {{ $now = date('d-m-Y') }} <br>

                </td>

            </tr>
        </table>
    </header>

    <footer>




    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <table style="width:100%;">
            <thead style="border-bottom:1px solid black">
                <tr style="">
                    <th style="text-align:center;padding:2px;width:15%">No</th>
                    <th style="text-align:left;padding:2px;width:30%">Item Description</th>
                    <th style="text-align:center;padding:2px">&nbsp;</th>
                    <th style="text-align:center;padding:2px">Qty</th>

                    <th style="text-align:center;padding:2px">&nbsp;</th>
                    <th style="text-align:center;padding:2px">&nbsp;</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $y = 0;
                    $total_qty = 0;
                @endphp
                @foreach ($data->directSalesDetailBy as $key => $value)
                    {{-- @for ($i = 0; $i < 2; $i++) --}}
                    <?php
                    $y++;
                    ?>
                    <tr>
                        <td style="text-align:center;padding:2px">{{ $key + 1 }}</td>
                        <td style="text-align:left;padding:2px">
                            {{ $value->productBy->sub_materials->nama_sub_material }}&nbsp;
                            {{ $value->productBy->sub_types->type_name }}&nbsp;
                            {{ $value->productBy->nama_barang }}
                        </td>
                        <td style="text-align:left;padding:2px">&nbsp;</td>
                        <td style="text-align:center;padding:2px">{{ $value->qty }}</td>

                        <td style="text-align:center">&nbsp; </td>
                        <td style="text-align:center">&nbsp; </td>


                        @php
                            $total_qty += $value->qty;
                            $sub_total = ($value->productBy->berat / 1000) * $value->qty;
                            $total = $total + $sub_total;
                        @endphp

                    </tr>

                    @if ($y % 5 == 0)
                        <div class="page-break"></div>
                    @endif
                    {{-- @endfor --}}
                @endforeach
                <tr>
                    <td colspan="6" style="text-align: right">
                        <hr>
                    </td>
                </tr>

            </tbody>
            <tfoot>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                    <th style="text-align: left">Qty Total</th>
                    <th style="text-align: center;border:1px solid black"> {{ $total_qty }} PCS</th>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                    <th style="text-align: left">Weight Total</th>
                    <th style="text-align: center;border:1px solid black"> {{ $total }} Kg</th>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                </tr>

            </tfoot>


        </table>
        <table style="width: 100%">
            <thead>
                <tr>
                    <th style="width: 33%">&nbsp;</th>
                    <th style="width: 33%">&nbsp;</th>
                    <th style="width: 33%">&nbsp;</th>
                </tr>
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
                    <th style="text-align: center;width: 33%"><i>(@if (is_numeric($data->cust_name))
                                {{ $data->customerBy->name_cust }}
                            @else
                                {{ $data->cust_name }}
                            @endif )</i> </th>
                    <th style="width: 33%">&nbsp;</th>
                    <th style="width: 33%">&nbsp;</th>
                </tr>
            </thead>
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
