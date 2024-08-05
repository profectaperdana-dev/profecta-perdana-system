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
        <img style="width: 50%;" src="{{ url('images/logo.png') }}" alt="">
        <br>
        <p style="font-size: 10pt">{{ $warehouse->alamat }} <br>
            Phone : {{ $warehouse->telp1 . ' / ' . $warehouse->telp2 }}</p>

    </center>
    <table style="width: 100%">
        <tr>
            <td style="font-size: 8pt">No. Invoice</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">{{ $data->trade_in_number }}</td>
        </tr>
        <tr>
            <td style="font-size: 8pt">No. Ref</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">{{ $data->retail_order_number }}</td>
        </tr>
        @if ($data->retailBy != null)
            <tr>
                <td style="font-size: 8pt">Customer</td>
                <td style="font-size: 8pt">:</td>
                <td style="font-size: 8pt">
                  @if ($data->retailBy != null) 
    @if (is_numeric($data->retailBy->cust_name)) 
        @if ($data->retailBy->customerBy == null) 
            {{ $data->retailBy->cust_name }}
        @else 
            {{ $data->retailBy->customerBy->code_cust }} - {{ $data->retailBy->customerBy->name_cust }}
        @endif
    @else 
        {{ $data->retailBy->cust_name }}
    @endif
@else 
    -
@endif

                    
                      
                </td>
            </tr>
        @endif
        <tr>
            <td style="font-size: 8pt">Date / Time</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">{{ date('d F Y / H:i:s', strtotime($data->created_at)) }}</td>
        </tr>
    </table>
    <table style="width: 100%;border-top:1px solid black;border-bottom:1px solid black">

        <tr>
            <td align="center" style="font-size: 8pt">Item</td>
            <td align="center" style="font-size: 8pt">Price</td>
            <td align="center" style="font-size: 8pt">Qty</td>
            <td align="center" style="font-size: 8pt">Sub Total</td>
        </tr>
        @php
            $total_diskon = 0;
            $total_diskon_persen = 0;
        @endphp
        @foreach ($data->tradeInDetailBy as $item)
            <tr>
                <td style="font-size: 8pt"> {{ $item->productTradeIn->name_product_trade_in }}

                </td>
                <td align="right" style="font-size: 8pt">
                    @php
                        $price__ = $item->price;
                        if ($item->price == null) {
                            foreach ($price as $row) {
                                if ($row->id_product_trade_in == $item->product_trade_in) {
                                    $price__ = $row->price_purchase;
                                }
                            }
                        }
                    @endphp
                    {{ number_format($price__, 0, ',', '.') }}
                </td>
                <td align="center" style="font-size: 8pt">{{ $item->qty }}</td>
                @php
                    $sub_total = $price__ * $item->qty;
                @endphp
                <td style="font-size: 8pt" align="right">{{ number_format($sub_total, 0, ',', '.') }}</td>
            </tr>
        @endforeach


    </table>
    <table style="width: 100%">
        <tr>
            <td align="right" colspan="3" style="font-size: 8pt">TOTAL</td>
            <td style="font-size: 8pt;" align="right">
                {{ number_format($data->total, 0, ',', '.') }}</td>
        </tr>
    </table>
    <center>
        <p style="font-size: 10pt">******* Thank you for trusting us *******</p>
    </center>

</body>

</html>
