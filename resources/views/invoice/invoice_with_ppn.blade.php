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
                <td style="width: 5%;text-align:left">
                    Invoice <br>
                    Revision <br>
                    Customer

                </td>
                <td style="width: 20%">
                    : {{ $data->order_number }}<br>
                    : 0<br>
                    : {{ $data->customerBy->code_cust }}
                </td>
            </tr>
            <tr>
                <th colspan="6">
                    <hr>
                </th>
            </tr>
            <tr>
                <th colspan="6" style="text-align: center">INVOICE

                </th>
            </tr>
            <tr>
                <td colspan="3" style="width: 90%;text-align:left">Invoice To : <br>
                    {{ $data->customerBy->name_cust }} <br>
                    {{ $data->customerBy->address_cust }} <br>
                    Remarks : {{ $data->remark }}
                </td>
                <td>Date <br>
                    Due Date
                </td>
                <td style="text-align:left">
                    : {{ $data->order_date }} <br>
                    : {{ $data->duedate }}
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
                    <td style="text-align:center;padding:2px">Item Description</td>
                    <td style="text-align:center;padding:2px">Price</td>
                    <td style="text-align:center;padding:2px">Qty</td>
                    <td style="text-align:center;padding:2px">Total</td>
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
                        <td style="text-align:right;padding:2px">@currency($value->productSales->harga_jual_nonretail)</td>
                        <td style="text-align:right;padding:2px">{{ $value->qty }}</td>
                        @php
                            $sub_total = $value->productSales->harga_jual_nonretail * $value->qty;
                            $total = $total + $sub_total;
                        @endphp
                        <td style="text-align:right">@currency($sub_total)</td>
                    </tr>
                    {{-- @endfor --}}
                @endforeach
            </tbody>



        </table>
        <table style="width: 100%">
            <thead>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" style="text-align: right">
                        <hr>
                    </td>

                </tr>
                <tr>
                    <td colspan="4" style="text-align: right">Total net value excl. tax</td>
                    <td style="text-align: right">@currency($total)</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right">PPN 11%</td>
                    <td style="text-align: right">@currency($data->ppn)</td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: right">

                    </td>
                    <td style="text-align: right">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <th colspan="4" style="text-align: right">Total Due</th>
                    <th style="text-align: right">@currency($data->total_after_ppn)</th>
                </tr>
                <tr>
                    <th colspan="4">&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: left">Payment Method :
                        @if ($data->payment_method == 1)
                            Cash On Delivery <br>
                        @elseif ($data->payment_method == 2)
                            Cash Before Delivery <br>
                        @else
                            Credit with Terms of Payment <br>
                        @endif
                        Bank Mandiri 113-00-7779777-1 : an. CV Profecta Perdana <br>
                        Bank BCA 853-085-3099 : an. CV Profecta Perdana <br>
                        Thank You ! <br>
                        We're looking fordward to working with you again
                    </td>

                    <th colspan="2" style="text-align: left"><i>Sincerely Yours,</i></th>
                </tr>
            </tbody>
        </table>
    </main>
</body>

</html>
