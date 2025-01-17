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
            margin-top: 5cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 1.5cm;
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
            height: 3cm;

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
        
         .dot {
              width: 10px;
              height: 10px;
              background-color: black;
              border-radius: 50%;
              display: inline-block;
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
                    Delivery Date <br>
                    Delivery Order Number <br>
                    Reference

                </td>
                <td style="width: 20%">
                    @php
                        $so_number = $data->order_number;
                        $so_number = str_replace('RSPP', 'DOPP', $so_number);
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
                <td colspan="6" style="width: 90%;text-align:left;">Delivery to : <br>
                    @if (is_numeric($data->cust_name))
                        {{ $data->customerBy->name_cust }}
                    @else
                        {{ $data->cust_name }}
                    @endif
                    <br>
                    <?php $address = wordwrap($data->address, 50, "<br>"); ?>
                    {!! $address !!} <br>
                    <span class="dot"></span> <b>Delivery Point: {{ $data->delivery_point }}</b> <br>
                    Remarks : {{ $data->remark }}

                </td>


            </tr>
        </table>
    </header>


    <main>
        <table style="width:100%;">
            <thead style="border-bottom:1px solid black">
                <tr style="">
                    <th style="text-align:center;width:5%">No</th>
                    <th style="text-align:center;width:60%">Item Description</th>
                    <th style="text-align:center">&nbsp;</th>

                    <th style="text-align:center">&nbsp;</th>
                    <th style="text-align:center">Qty</th>

                    <th style="text-align:center">&nbsp;</th>

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
                        <td style="text-align:center;">{{ $key + 1 }}</td>
                        <td style="text-align:left;">
                            {{ $value->productBy->sub_materials->nama_sub_material }}&nbsp;
                            {{ $value->productBy->sub_types->type_name }}&nbsp;
                            {{ $value->productBy->nama_barang }} &nbsp; @php
                                $count_code = count($value->directSalesCodeBy);
                            @endphp
                            @if ($count_code > 0)
                                @foreach ($value->directSalesCodeBy as $code)
                                    @if ($loop->last)
                                        [{{ $code->product_code }}]
                                    @else
                                        [{{ $code->product_code }}]
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td style="text-align:left;">&nbsp;</td>

                        <td style="text-align:center">&nbsp; </td>
                        <td style="text-align:center;">{{ $value->qty }}</td>

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


            </tbody>
            <tfoot style="border-top:1px solid black;padding-top:20px;">
                {{-- <tr>
                    <td colspan="6">
                        &nbsp;
                    </td>
                </tr> --}}
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                    <th style="text-align: left">Qty Total</th>
                    <th style="text-align: center;;border:2px solid black"> {{ $total_qty }} pcs</th>
                    <td>&nbsp;</td>

                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                    <th style="text-align: left">Weight Total </th>
                    <th style="text-align: center;;border:2px solid black"> {{ $total }} Kg</th>
                    <td>&nbsp;</td>
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
                        <th style="text-align: center;width: 33%"><i>( @if (is_numeric($data->cust_name))
                                    {{ $data->customerBy->name_cust }}
                                @else
                                    {{ $data->cust_name }}
                                @endif )</i> </th>
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
