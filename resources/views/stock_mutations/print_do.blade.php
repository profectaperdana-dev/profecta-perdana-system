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
            margin-bottom: 3cm;
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
            margin-top: 3.8cm;
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
            height: 3.5cm;
            /** Extra personal styles **/
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: left;
            font-size: 8pt;
            font-family: Helvetica;

            /* line-height: 1.5cm; */
        }

        /** Define the footer rules **/
        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 0.1cm;

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
                <td style="width: 20%"><img style="width: 100px;margin-top:15px;" src="{{ url('images/logo.png') }}"
                        alt=""></td>
                <td style="width: 34%;text-align:left">
                    <b>CV. Profecta Perdana</b> <br>
                    {{ $warehouse->alamat }} <br>
                    Phone : {{ $warehouse->telp1 . ' / ' . $warehouse->telp2 }}
                </td>
                <td></td>
                <td style="width: 20%;text-align:left">
                    Delivery Order Number <br>
                    Reference <br>
                    Delivery Date
                </td>
                <td style="width: 25%">
                    @php
                        $mutation_number = $data->mutation_number;
                        if ($data->product_type == 'Common') {
                            $do_number = str_replace('SMPP', 'SMDOPP', $mutation_number);
                        } else {
                            $do_number = str_replace('TMPP', 'SMDOPP', $mutation_number);
                        }
                    @endphp
                    : {{ $do_number }} <br>
                    : {{ $data->mutation_number }} <br>
                    : {{ $now = date('d F Y', strtotime($data->mutation_date)) }}

                </td>
            </tr>
            <!--<tr>-->
            <!--    <th colspan="6">-->
            <!--        <hr>-->
            <!--    </th>-->
            <!--</tr>-->
            <tr>
                <th colspan="6" style="text-align: center">MUTATION DELIVERY ORDER

                </th>
            </tr>
            <tr>
                <td colspan="6" style="width: 90%;text-align:left">Delivery To : <br>
                    {{ $data->toWarehouse->warehouses }} <br>
                    {{ $data->toWarehouse->alamat }} <br>
                    Remark : {{ $data->remark }}
                </td>


            </tr>
        </table>
    </header>

    <footer>




    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <table style="width:100%;margin-top:2em">
            <thead style="border-bottom:1px solid black">
                <tr style="">
                    <th style="text-align:center;padding:0px;width:15%">No</th>
                    <th style="text-align:left;padding:0px;width:30%">Item Description</th>
                    <th style="text-align:center;padding:0px">&nbsp;</th>

                    <th style="text-align:center;padding:0px">Qty</th>
                    <th style="text-align:center;padding:0px">&nbsp;</th>

                    <th style="text-align:center;padding:0px">Note</th>


                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $y = 0;
                    $total_qty = 0;
                    $total_iterations = count($data->stockMutationDetailBy);
                    $current_iteration = 0;
                @endphp
                @foreach ($data->stockMutationDetailBy as $key => $value)
                    {{-- @for ($i = 0; $i < 2; $i++) --}}
                    <?php
                    $y++;
                    ?>
                    <tr>
                        <td style="text-align:center;padding:2px">{{ $key + 1 }}</td>
                        @if ($data->product_type == 'Common')
                            <td style="text-align:left;padding:2px">
                                {{ $value->productBy->sub_materials->nama_sub_material }}&nbsp;
                                {{ $value->productBy->sub_types->type_name }}&nbsp;
                                {{ $value->productBy->nama_barang }}
                                @php
                                    $count_code = count($value->mutationDotBy);
                                @endphp

                            </td>
                        @else
                            <td style="text-align:left;padding:2px">
                                {{ $value->productSecondBy->name_product_trade_in }}
                            </td>
                        @endif

                        <td style="text-align:left;padding:2px">&nbsp;</td>

                        <td style="text-align:center">{{ $value->qty }}</td>
                        <td style="text-align:center;padding:2px">&nbsp;</td>

                        <td style="text-align:center">
                        
                            
                            @if ($data->product_type == 'Common')
                        @if($data->getProductCode)
                           {{ $data->getProductCode->product_code }}
                            @else
                                @if ($count_code > 0)
                                    @foreach ($value->mutationDotBy as $code)
                                        @if ($loop->last)
                                            {{ $code->dotBy->dot }} ({{ $code->qty }})
                                        @else
                                            {{ $code->dotBy->dot }} ({{ $code->qty }}),
                                        @endif
                                    @endforeach
                                @endif   
                                @endif
                            @endif, {{ $value->note }}
                        </td>
                        @php
                            $total_qty += $value->qty;
                            if ($data->product_type == 'Common') {
                                $sub_total = ($value->productBy->berat / 1000) * $value->qty;
                            } else {
                                $sub_total = 0;
                            }
                            $total = $total + $sub_total;
                        @endphp

                    </tr>

                    @if ($y % 7 == 0 && $y != $total_iterations)
                        <div class="page-braek"></div>
                    @endif
                    {{-- @endfor --}}
                @endforeach


            </tbody>
            <tfoot style="border-top:1px solid black; margin-top :-2px;">
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

                    @if ($data->product_type == 'Common')
                        <th style="text-align: left">Weight Total</th>
                        <th style="text-align: center;border:1px solid black"> {{ $total }} Kg</th>
                    @endif
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>

                </tr>

            </tfoot>


        </table>
        <table style="width: 100%">
            <thead>
               
                <tr>
                    <th style="text-align: center;width: 33%"><i>Prepared by,</i> </th>
                    <th style="text-align: center;width: 33%"><i>Acknowledged by,</i> </th>
                    <th style="text-align: center;width: 33%"><i>Delivered by,</i></th>
                    <th style="text-align: center;width: 33%"><i>Received by,</i></th>

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
                    <th style="width: 33%">&nbsp;</th>
                    <th style="width: 33%">&nbsp;</th>
                    <th style="width: 33%">&nbsp;</th>
                    <th style="width: 33%">({{ $data->toWarehouse->warehouses }})</th>

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
