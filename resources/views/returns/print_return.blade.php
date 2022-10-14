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
            margin-top: 5cm;
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
            height: 5cm;
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
                    Return <br>
                    Invoice <br>
                </td>
                <td style="width: 20%">
                    : {{ $data->return_number }}<br>
                    : {{ $data->salesOrderBy->order_number }}<br>
                </td>
            </tr>
            <tr>
                <th colspan="6">
                    <hr>
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center">RETURN

                </th>
            </tr>
            <tr>
                <td colspan="3" style="width: 90%;text-align:left">Return From : <br>
                    {{ $data->salesOrderBy->customerBy->name_cust }} - {{ $data->salesOrderBy->customerBy->code_cust }}
                    <br>
                    {{ $data->salesOrderBy->customerBy->address_cust }} <br>
                    Reason : {{ $data->return_reason }}
                </td>
                <td style="width: 10%">Date
                </td>
                <td style="text-align:left">
                    : {{ date('d-m-Y', strtotime($data->return_date)) }} <br>
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
                    <th style="text-align:left;padding:5px">Warehouse</th>
                    <th style="text-align:right;padding:5px">Price (Rp)</th>
                    <th style="text-align:center;padding:5px">Qty</th>
                    <th style="text-align:right;padding:5px;margin-right:30px";>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $y = 0;
                @endphp
                @foreach ($data->returnDetailsBy as $key => $value)
                    {{-- @for ($i = 0; $i < 6; $i++) --}}
                    <?php
                    $y++;
                    ?>
                    <tr>
                        <td style="text-align:center;padding:5px">{{ $key + 1 }}.
                        </td>
                        <td style="text-align:left;padding:5px">
                            {{ $value->productBy->sub_materials->nama_sub_material }}&nbsp;
                            {{ $value->productBy->sub_types->type_name }}&nbsp;
                            {{ $value->productBy->nama_barang }}
                        </td>
                        <td style="text-align:left;padding:5px">
                            {{ $data->salesOrderBy->customerBy->warehouseBy->warehouses }}
                        </td>
                        <td style="text-align:right;padding:5px">
                            {{ number_format($value->productBy->harga_jual_nonretail, 0, ',', '.') }}</td>
                        <td style="text-align:center;padding:5px">{{ $value->qty }}</td>
                        @php
                            $diskon = 0;
                            $diskon_rp = 0;
                            $getdiskon = $value->returnBy->salesOrderBy->salesOrderDetailsBy;
                            foreach ($getdiskon as $dis) {
                                if ($dis->products_id == $value->product_id) {
                                    $diskon = $dis->discount / 100;
                                    $diskon_rp = $dis->discount_rp;
                                }
                            }
                            $hargaDiskon = $value->productBy->harga_jual_nonretail * $diskon;
                            $hargaAfterDiskon = $value->productBy->harga_jual_nonretail - $hargaDiskon - $diskon_rp;
                            $sub_total = $hargaAfterDiskon * $value->qty;
                            $ppn_total = $ppn * $sub_total;
                            $total = $sub_total + $ppn_total;
                        @endphp
                        <td style="text-align:right;margin-right:30px">{{ number_format($total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @if ($y % 5 == 0)
                        <div class="page-break"></div>
                    @endif
                    {{-- @endfor --}}
                @endforeach
                <tr>
                    <td colspan="7" style="text-align: right">
                        <hr>
                    </td>

                </tr>
            </tbody>
        </table>
        <table style="width: 100%">
            <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>

                <tr>
                    <th colspan="9" style="text-align: right">Total Return</th>
                    <th style="text-align: right;border:1px solid black">@currency($data->total)</th>
                </tr>
                <tr>
                    <th colspan="4">&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: center"><i>Created By,</i></th>
                    <th colspan="2" style="text-align: left"><i>&nbsp;</i></th>
                    <th colspan="2" style="text-align: center"><i>Proposed By,</i></th>
                    <th colspan="2" style="text-align: right"><i>&nbsp;</i></th>
                    <th colspan="2" style="text-align: center"><i>Acknowledge By,</i></th>
                </tr>
            </tbody>
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
