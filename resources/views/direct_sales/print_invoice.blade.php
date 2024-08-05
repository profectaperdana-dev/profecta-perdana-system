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
            margin-top: 4.8cm;
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
                    : {{ date('d F Y', strtotime($data->order_date)) }}
                    <br>
                    : {{ $data->order_number }}
                </td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center;border-top:1px solid black">INVOICE
                </th>
            </tr>
            <tr>
                <td colspan="6" style="width: 90%;text-align:left">Invoice to : <br>
                    @if (is_numeric($data->cust_name))
                        {{ $data->customerBy->name_cust }}
                    @else
                        {{ $data->cust_name }}
                    @endif
                    <br>
                    <?php $address = wordwrap($data->address, 50, "<br>"); ?>
                    {!! $address !!} <br>
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
                    $total_diskon = 0;
                    $total_diskon_rp = 0;
                    $total_diskon_rp = 0;
                    $total_diskonPersen = 0;
                    $y = 0;
                    $total_iterations = count($data->directSalesDetailBy);
                    $current_iteration = 0;
                @endphp
                @foreach ($data->directSalesDetailBy as $key => $value)
                    {{-- @for ($i = 0; $i < 6; $i++) --}}
                    <?php
                    $y++;
                    $retail_cost = $value->price;
                    // foreach ($value->retailPriceBy as $retail) {
                    //     if ($retail->id_warehouse == $data->warehouse_id) {
                    //         $retail_cost = (int) $retail->harga_jual;
                    //     }
                    // }
                    $current_iteration++;
                    ?>
                    @php
                        $retail_price = $value->price;
                        foreach ($value->retailPriceBy as $retail) {
                            if ($retail->id_warehouse == $data->warehouse_id) {
                                $retail_price = (float)$value->price;
                                $diskon = ceil((float)$retail_price * ($value->discount / 100));
                                $hargaDiskon =($retail_price - $diskon - $value->discount_rp) / 1.11;
                                
                            }
                        }
                        
                    @endphp
                    <tr>
                        <td style="text-align:center;padding:5px">{{ $key + 1 }}.
                        </td>
                        <td style="text-align:left;padding:5px;">
                            {{ $value->productBy->sub_materials->nama_sub_material }}&nbsp;
                            {{ $value->productBy->sub_types->type_name }}&nbsp;
                            {{ $value->productBy->nama_barang }} &nbsp;
                            @php
                                $count_code = count($value->directSalesCodeBy);
                            @endphp
                            @if ($count_code > 0)
                                @foreach ($value->directSalesCodeBy as $code)
                                    @if ($code->product_code != '-')
                                        [{{ $code->product_code }}]
                                    @endif
                                @endforeach
                            @endif

                        </td>
                        {{-- @php
                            $harga = str_replace(',', '.', $retail_cost);
                            $ppns = (float) $harga * 0.11;
                            $harga_s = (float) $harga + $ppns;
                        @endphp --}}
                        <td style="text-align:right;padding:5px">
                            {{ number_format(floor($hargaDiskon) < 0 ? 0 : floor($hargaDiskon), 0, ',', '.') }}
                        </td>
                        <td style="text-align:center;padding:5px">{{ $value->qty }}</td>
                        {{-- <td style="text-align:center;padding:5px">{{ $value->discount }}</td> --}}
                        @php
                            
                            $diskon = ceil((float)$retail_price * ($value->discount / 100));
                            $hargaDiskon =($retail_price - $diskon - $value->discount_rp) / 1.11;
                            
                            $total_diskon += $diskon * $value->qty;
                            $discount_rp = $value->discount_rp * $value->qty;
                            
                            $total_diskon_rp += $value->discount_rp * $value->qty;
                            $sub_total =$hargaDiskon * $value->qty;
                            
                        @endphp
                        <td style="text-align:right;margin-right:30px">
                            {{ number_format(floor($sub_total) < 0 ? 0 : floor($sub_total), 0, ',', '.') }}
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
                    <td colspan="3" style="text-align: right">Total net value excl. tax</td>

                    <td colspan="2" style="text-align: right">
                        {{ number_format(floor($data->total_excl), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: right">PPN {{ $ppn * 100 }}%</td>
                    <td colspan="2" style="text-align: right">
                        {{ number_format(ceil($data->total_ppn), 0, ',', '.') }}</td>
                </tr>
                </tr>
                <tr>
                    <th colspan="3" style="text-align: right">Total</th>
                    <th colspan="2" style="text-align: right;border:1px solid black">@currency($data->total_incl)</th>
                </tr>

            </tfoot>
        </table>

    </main>
    {{-- END CONTENT --}}
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
