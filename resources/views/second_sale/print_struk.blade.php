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
            Phone : 0713-82536</p>

    </center>
    <table style="width: 100%">
        <tr>
            <td style="font-size: 10pt">No. Invoice</td>
            <td style="font-size: 10pt">:</td>
            <td style="font-size: 10pt">{{ $data->second_sale_number }}</td>
        </tr>
        <tr>
            <td style="font-size: 10pt">Date</td>
            <td style="font-size: 10pt">:</td>
            <td style="font-size: 10pt">{{ date('d-m-Y H:i:s', strtotime($data->created_at)) }}</td>
        </tr>
    </table>
    <hr>
    <table style="width: 100%">

        <tr>
            <td style="font-size: 10pt">Item</td>
            <td align="right" style="font-size: 10pt">Price</td>
            <td align="center" style="font-size: 10pt">Qty</td>
            <td align="right" style="font-size: 10pt">Sub Total</td>
        </tr>
        @php
            $total_diskon = 0;
            $total_diskon_persen = 0;
        @endphp
        @foreach ($data->second_sale_details as $item)
            <tr>
                <td style="font-size: 8pt"> {{ $item->secondProduct->name_product_trade_in }}

                </td>
                <td align="right" style="font-size: 8pt">
                    {{ number_format($item->secondProduct->price_product_trade_in, 0, ',', '.') }}
                </td>
                <td align="center" style="font-size: 8pt">{{ $item->qty }}</td>
                @php
                    $diskon = $item->secondProduct->price_product_trade_in * ($item->discount / 100);
                    $hargaDiskon = $item->secondProduct->price_product_trade_in - $diskon;
                    $total_diskon += $hargaDiskon * $item->qty;
                    $total_diskon_persen += $item->discount_rp * $item->qty;
                    $sub_total = $item->secondProduct->price_product_trade_in * $item->qty;
                    
                @endphp
                <td style="font-size: 8pt" align="right">{{ number_format($sub_total, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="4">
                <hr>
            </td>

        </tr>
        @if ($data->discount > 0)
            <tr>
                <td align="right" colspan="3" style="font-size: 8pt">Discount</td>
                <td style="font-size: 8pt" align="right">{{ number_format($diskon * $item->qty, 0, ',', '.') }}</td>
            </tr>
        @endif


        @if ($item->discount_rp != 0)
            <tr>
                <td align="right" colspan="3" style="font-size: 8pt">Discount Rupiah</td>
                <td style="font-size: 8pt" align="right">
                    {{ number_format($item->discount_rp * $item->qty, 0, ',', '.') }}</td>
            </tr>
        @endif


        <tr>
            <td align="right" colspan="3" style="font-size: 8pt">TOTAL</td>
            <td style="font-size: 8pt;border:1px solid black"" align="right">
                {{ number_format($data->total, 0, ',', '.') }}</td>
        </tr>
    </table>
    <center>
        <p style="font-size: 10pt">******* Thank you for your trust in us *******</p>
    </center>

</body>

</html>
