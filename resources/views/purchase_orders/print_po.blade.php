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
            margin-bottom: 4cm;
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
                    Purchase Date <br>
                    Purchase Order<br>


                </td>
                <td style="width: 20%">
                    @php
                        $so_number = $data->order_number;
                        $so_number = str_replace('IVPP', 'DOPP', $so_number);
                        $do = $so_number;
                    @endphp
                    : {{ date('d F Y', strtotime($data->order_date)) }} <br>
                    : {{ $data->order_number }}

                </td>
            </tr>

            <tr>
                <th colspan="6" style="text-align: center;border-top:1px solid black ">PURCHASE ORDER

                </th>
            </tr>
            <tr>
                <td colspan="6" style="width: 90%;text-align:left">Supplier : <br>
                    {{ $data->supplierBy->nama_supplier }} <br>
                    {{ $data->supplierBy->alamat_supplier }} -
                    {{ $data->supplierBy->no_telepon_supplier }}
                </td>

            </tr>
        </table>
    </header>


    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <table style="width:100%;">
            <thead style="border-bottom:1px solid black">
                <tr style="">
                    <th style="text-align:center;padding:2px">No</th>
                    <th style="text-align:center;padding:2px">&nbsp;</th>
                    <th style="text-align:center;padding:2px">Item Description</th>
                    <th style="text-align:center;padding:2px">&nbsp;</th>
                    <th style="text-align:center;padding:2px">&nbsp;</th>
                    <th style="text-align:center;padding:2px">Qty</th>

                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                    $y = 0;
                @endphp
                @foreach ($data->purchaseOrderDetailsBy as $key => $value)
                    {{-- @for ($i = 0; $i < 2; $i++) --}}
                    <?php
                    $y++;
                    ?>
                    <tr>
                        <td style="text-align:center;padding:2px">{{ $key + 1 }}</td>
                        <td style="text-align:center;padding:2px">&nbsp;</td>
                        <td style="text-align:left;padding:2px">
                            {{ $value->productBy->sub_materials->nama_sub_material . ' ' . $value->productBy->sub_types->type_name . ' ' . $value->productBy->nama_barang }}
                        </td>

                        <td style="text-align:center">&nbsp; </td>
                        <td style="text-align:center">&nbsp; </td>
                        <td style="text-align:center;padding:2px">{{ $value->qty }}</td>

                        @php
                            $total = $total + $value->qty;
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
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <th style="text-align: center"> Total</th>
                    <th style="text-align: center;border:1px solid black">{{ $total }}</th>
                </tr>
            </tfoot>


        </table>

    </main>
    <div class="page-break">
        <footer>
            <table style="width: 100%">
                <thead>

                    <tr>
                        <th style="text-align: center;width: 33%"><i>Owner,</i> </th>
                        <th style="text-align: center;width: 33%"><i>Vendor,</i> </th>
                        <th style="text-align: center;width: 33%"><i>Warehouse,</i></th>


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
                        <th style="text-align: center;width: 33%">&nbsp; </th>
                        <th style="width: 33%">( {{ $data->supplierBy->nama_supplier }} )</th>
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
