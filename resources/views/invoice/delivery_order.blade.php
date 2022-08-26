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
                    From Invoice

                </td>
                <td style="width: 20%">
                    @php
                        $so_number = $data->order_number;
                        $so_number = str_replace('SOPP', 'DOPP', $so_number);
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
                    {{ $data->customerBy->name_cust }} <br>
                    {{ $data->customerBy->address_cust }} <br>
                    Phone Customer : {{ $data->customerBy->phone_cust }}
                </td>
                <td>Delivery Date <br>

                </td>
                <td style="text-align:left">
                    : {{ $now = date('Y-m-d') }} <br>

                </td>

            </tr>
        </table>
    </header>

    <footer>
        <b>Page -<span class="pagenum"></span>-</b>

    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>

        <table style="width:100%;">
            <thead style="border:1px solid black">
                <tr style="">
                    <td style="text-align:center;padding:2px">No</td>
                    <td style="text-align:left;padding:2px">Item Description</td>
                    <td style="text-align:center;padding:2px">Weight (Kg)</td>
                    <td style="text-align:center;padding:2px">Qty</td>
                    <td style="text-align:center;padding:2px">Total (Kg)</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($data->salesOrderDetailsBy as $key => $value)
                    {{-- @for ($i = 0; $i < 1; $i++) --}}
                    <tr>
                        <td style="text-align:center;padding:2px">{{ $key + 1 }}</td>
                        <td style="text-align:left;padding:2px">{{ $value->productSales->nama_barang }}</td>
                        <td style="text-align:center;padding:2px">{{ $value->productSales->berat / 1000 }}</td>
                        <td style="text-align:center;padding:2px">{{ $value->qty }}</td>
                        @php
                            $sub_total = ($value->productSales->berat / 1000) * $value->qty;
                            $total = $total + $sub_total;
                        @endphp
                        <td style="text-align:center">{{ $sub_total }}</td>
                    </tr>
                    {{-- @endfor --}}
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td style="text-align: center">Weight Total (Kg)</td>
                    <td style="text-align: center">{{ $total }}</td>
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
                    <th style="text-align: center;width: 33%"><i>Warehoues,</i> </th>
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
                    <th style="text-align: center;width: 33%"><i>( {{ $data->customerBy->name_cust }} )</i> </th>
                    <th style="width: 33%">&nbsp;</th>
                    <th style="width: 33%">&nbsp;</th>
                </tr>
            </thead>
        </table>
    </main>
</body>

</html>
