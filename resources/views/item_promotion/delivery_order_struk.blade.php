<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <title>Document</title>
    <style type="stylesheet">
        @page {
            size: 80mm 1000mm portrait;
            margin: 5mm 5mm 5mm 5mm;
            font-family: Roboto;
        }
    </style>
</head>

<body>
    <center>
        <img style="width: 50%;" src="https://iili.io/tTHjV9.png" alt="">
        <br>
        <p style="font-size: 10pt">{{ $warehouse->alamat }} <br>
            Phone : {{ $warehouse->telp1 . ' / ' . $warehouse->telp2 }}</p>

    </center>
    <table style="width: 100%">
        <tr>
            <td style="font-size: 8pt">No. Invoice</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">{{ $data->order_number }}</td>
        </tr>
        <tr>
            <td style="font-size: 8pt">No. Ref</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">{{ $data->direct_number }}</td>
        </tr>

        <tr>
            <td style="font-size: 8pt">Customer</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">
                @if (is_numeric($data->id_customer))
                    {{ $data->customerBy->name_cust }}
                @else
                    {{ $data->id_customer }}
                @endif

            </td>
        </tr>
        <tr>
            <td style="font-size: 8pt">Date / Time</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">{{ date('d F Y / H:i:s', strtotime($data->order_date)) }}</td>
        </tr>
    </table>
    <table style="width: 100%;border-top:1px solid black;border-bottom:1px solid black">

        <tr>
            <td align="center" style="font-size: 8pt">Item</td>
            <td align="center" style="font-size: 8pt">Qty</td>
        </tr>
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
            <?php
            $y++;
            $current_iteration++;
            ?>
            <tr>
                <td style="font-size: 8pt"> {{ $itemName }}

                </td>

                <td align="center" style="font-size: 8pt">{{ $totalQty }}</td>
                @php
                    $total_qty += $totalQty;
                @endphp
            </tr>
        @endforeach


    </table>
    <table style="width: 100%">
        <tr>
            <td align="right" colspan="3" style="font-size: 8pt">TOTAL</td>
            <td style="font-size: 8pt;" align="right">
                {{ $total_qty }} pcs</td>
        </tr>
    </table>
    <center>
        <p style="font-size: 10pt">******* Thank you for your trust in us *******</p>
    </center>

</body>

</html>
