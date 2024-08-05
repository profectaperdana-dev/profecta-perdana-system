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
            <td style="font-size: 8pt;white-space:nowrap;">No. Invoice</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">{{ $data->order_number }}</td>
        </tr>
        <tr>
            <td style="font-size: 8pt;white-space:nowrap;">Date / Time</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">{{ date('d F Y / H:i:s', strtotime($data->created_at)) }}</td>
        </tr>
        <tr>
            <td style="font-size: 8pt;white-space:nowrap;">Customer</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt;white-space:nowrap;">
                @if (is_numeric($data->cust_name))
                    {{ $data->customerBy->name_cust }}
                @else
                    {{ $data->cust_name }}
                @endif
            </td>
            </td>
        </tr>
        <tr>
            <td style="font-size: 8pt;white-space:nowrap;">Phone Number</td>
            <td style="font-size: 8pt">:</td>
            <td style="font-size: 8pt">

                @if (is_numeric($data->cust_name))
                    {{ $data->customerBy->phone_cust }}
                @else
                    {{ $data->cust_phone }}
                @endif

            </td>
            </td>
        </tr>
        @if (!is_numeric($data->cust_name))
            <tr>
                <td style="font-size: 8pt;white-space:nowrap;">Machine</td>
                <td style="font-size: 8pt">:</td>
                <td style="font-size: 8pt;">
                    @if ($data->car_brand_id != null)
                        @if (is_numeric($data->car_brand_id) && is_numeric($data->car_type_id))
                            {{ $data->carBrandBy->car_brand }} {{ $data->carTypeBy->car_type }}
                        @else
                            {{ $data->car_brand_id }} {{ $data->car_type_id }}
                        @endif
                    @elseif($data->motor_brand_id != null)
                        @if (is_numeric($data->motor_brand_id) && is_numeric($data->motor_type_id))
                            {{ $data->motorBrandBy->name_brand }} {{ $data->motorTypeBy->name_type }}
                        @else
                            {{ $data->motor_brand_id }} {{ $data->motor_type_id }}
                        @endif
                    @else
                        {{ $data->other }}
                    @endif

                    {{-- @if ($data->plate_number != '-')
                        - {{ $data->plate_number }}
                    @endif --}}
                </td>
                </td>
            </tr>
            <tr>
                <td style="font-size: 8pt;white-space:nowrap;">Plate Number</td>
                <td style="font-size: 8pt">:</td>
                <td style="font-size: 8pt">

                    {{ $data->plate_number }}

                </td>
                </td>
            </tr>
        @endif

    </table>
    {{-- <hr> --}}
    <table style="width: 100%;border-top:1px solid black;border-bottom:1px solid black">

        <tr>
            <td align="center" style="font-size: 8pt">Item</td>
            <td align="center" style="font-size: 8pt">Price</td>
            <td align="center" style="font-size: 8pt">Qty</td>
            <td align="center" style="font-size: 8pt">Sub Total</td>
        </tr>
        @php
            $total_diskon = 0;
            $total_diskon_rp = 0;
            $total_diskon_rp = 0;
            $total_diskonPersen = 0;
        @endphp
        @foreach ($data->directSalesDetailBy as $item)
            @php
                $retail_price = $item->price;
                
                if ($item->price == 0 || $item->price == null) {
                    $retail_price = $item->getPrice($data->warehouse_id);
                    $ppns = (float) $retail_price * 0.11;
                    $retail_price = (float) $retail_price + (float) $ppns;
                }
                
            @endphp
            <tr>
                <td style="font-size: 8pt">
                    {{ $item->productBy->sub_materials->nama_sub_material }}
                    {{ $item->productBy->sub_types->type_name }}
                    <br />
                    {{ $item->productBy->nama_barang }}
                    <br />
                    @foreach ($item->directSalesCodeBy as $code)
                        @if ($loop->last)
                            {{ $code->product_code }}
                        @else
                            {{ $code->product_code . ', ' }}
                        @endif
                        {{-- {{ $code->product_code  }} --}}
                    @endforeach
                </td>
                <td align="right" style="font-size: 8pt">
                    {{ number_format((float) $retail_price - $item->discount / 100 - $item->discount_rp, 0, ',', '.') }}
                </td>
                <td align="center" style="font-size: 8pt">{{ $item->qty }}</td>
                @php
                    // $harga_ppn = $retail_price
                    $diskon = $retail_price * ($item->discount / 100);
                    $hargaDiskon = $retail_price - $diskon;
                    
                    $total_diskon += $diskon * $item->qty;
                    // $discount_rp = $item->discount_rp * $item->qty;
                    
                    $total_diskon_rp += $item->discount_rp * $item->qty;
                    $sub_total = ($hargaDiskon - $item->discount_rp) * $item->qty;
                    
                @endphp
                <td style="font-size: 8pt" align="right">{{ number_format((float) $sub_total, 0, ',', '.') }}</td>

            </tr>
        @endforeach


    </table>
    <table style="width:100%">
        <tr>
            {{-- <td style="width:25%"></td>
            <td style="width:25%"></td> --}}
            <td colspan="3" align="right" style="font-size: 8pt;width: 70%">TOTAL</td>
            <td style="font-size: 8pt;width: 30%;" align="right">
                @currency($data->total_incl)</td>
        </tr>
    </table>
    <center>
        <p style="font-size: 8pt">******** Thank you for trusting us ********</p>
    </center>

</body>

</html>
