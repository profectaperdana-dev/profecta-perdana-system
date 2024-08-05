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
            margin-top: 4.5cm;
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
            height: 4.5cm;
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
                    Date<br>
                    Return <br>
                    Retail <br>
                </td>
                <td style="width: 20%">
                    : {{ date('d F Y', strtotime($data->return_date)) }} <br>
                    : {{ $data->return_number }}<br>
                    : {{ $data->retailBy->order_number }}<br>
                </td>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center;border-top:1px solid black ">DELIVERY ORDER
                </th>
            </tr>
            <tr>
                <td colspan="6" style="width: 90%;text-align:left">Return to : <br>
                    @if (is_numeric($data->retailBy->cust_name))
                        {{ $data->retailBy->customerBy->name_cust }}
                    @else
                        {{ $data->retailBy->cust_name }}
                    @endif
                    <br>
                    {{ $data->retailBy->address . ', ' . $data->retailBy->sub_district . ', ' . $data->retailBy->district . ', ' . $data->retailBy->province }}
                    <br>
                    Reason : {{ $data->return_reason }}
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
                    <th style="text-align:center;">No</th>
                    <th style="text-align:left;">Item Description</th>
                    <th style="text-align:center;">Qty</th>
                </tr>
            </thead>
            <tbody>
                @php
                    
                    $y = 0;
                    $total_iterations = count($data->returnDetailsBy);
                    $current_iteration = 0;
                    $total_qty = 0;
                    
                @endphp
                @foreach ($data->returnDetailsBy as $key => $value)
                    {{-- @for ($i = 0; $i < 6; $i++) --}}
                    <?php
                    
                    $y++;
                    $current_iteration++;
                    $total_qty += $value->qty;
                    
                    ?>
                    <tr>
                        <td style="text-align:center;">{{ $key + 1 }}.
                        </td>
                        <td style="text-align:left;">
                            {{ $value->productBy->sub_materials->nama_sub_material }}&nbsp;
                            {{ $value->productBy->sub_types->type_name }}&nbsp;
                            {{ $value->productBy->nama_barang }} &nbsp; {{ $value->product_code }}
                        </td>
                        <td style="text-align:center;">{{ $value->qty }}</td>
                    </tr>
                    @if ($y % 7 == 0 && $y != $total_iterations)
                        <div class="page-braek"></div>
                    @endif
                    {{-- @endfor --}}
                @endforeach

            </tbody>
            <tfoot style="border-top:1px solid black">

                <tr>
                    <th colspan="4"></th>
                </tr>
                <tr>
                    <th colspan="2" style="text-align: right">Total Qty</th>
                    <th style="text-align: center;border:2px solid black">{{ $total_qty }}</th>
                </tr>

            </tfoot>
        </table>

    </main>

    <footer>
        <table style="width: 100%">
            <thead>

                <tr>
                    <th style="text-align: center;width: 33%"><i>Created By,</i> </th>
                    <th style="text-align: center;width: 33%"><i>Proposed By,</i> </th>
                    <th style="text-align: center;width: 33%"><i>Acknowledge By,</i></th>


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
                    <th style="text-align: center;width: 33%"><i>( {{ $data->createdBy->name }} )</i> </th>
                    <th style="width: 33%">&nbsp;</th>
                    <th style="width: 33%">&nbsp;</th>
                </tr>
            </thead>
        </table>
    </footer>
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
