<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran</title>
    <style type="text/css">
        /* Reset CSS */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Style untuk halaman kwitansi */
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            line-height: 1;
            background-color: #fff;
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
        }

        /* Style untuk header kwitansi */
        header {
            text-align: center;
            margin-bottom: 20px;
        }

        header h1 {
            font-size: 20pt;
            margin-bottom: 10px;
        }

        header p {
            font-size: 14pt;
        }

        /* Style untuk informasi pembayaran */
        .info-pembayaran {
            margin-bottom: 20px;
            padding: 20px;
        }

        .info-pembayaran h2 {
            font-size: 16pt;
            margin-bottom: 10px;
        }

        .info-pembayaran p {
            margin-bottom: 5px;
        }

        /* Style untuk tabel daftar barang */
        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
        }

        th,
        td {
            /* border: 1px solid #000; */
            padding: 2px;
            /* text-align: center; */
        }

        th {
            background-color: #eee;
        }

        /* Style untuk total pembayaran */
        .total-pembayaran {
            margin-top: 20px;
        }

        .total-pembayaran p {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>

    <div class="info-pembayaran">
        <hr>
        <table style="width:100%">
            <tr>
                <td style="text-align: center" colspan="3">
                    <h4>Cash Receipt</h4>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <h4>CV. Profecta Perdana</h4>
                </td>
            </tr>
            <tr>
                <td>Invoice</td>

                <td colspan="2" class="text-start">{{ ' : ' . $data->order_number }}</td>
            </tr>
            <tr>
                <td>Customer</td>

                <td colspan="2" class="text-start">
                    @if (is_numeric($data->cust_name))
                        {{ ' : ' . $data->customerBy->code_cust . ' - ' . $data->customerBy->name_cust }}
                    @else
                        {{ ' : ' . $data->cust_name }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>Status</td>

                <td colspan="2" class="text-start">
                    @if ($data->isPaid == '0')
                        {{ ' : ' . 'Unpaid' }}
                    @else
                        {{ ' : ' . 'Paid' }}
                    @endif
                </td>
            </tr>
        </table>
        <table style="width:100%">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody style="border-top: 1px solid black;">

                @foreach ($data->directSalesCreditBy as $detail)
                    <tr>
                        <td style="text-align: center"><b>{{ $loop->iteration }}</b></td>
                        <td style="text-align: center"> {{ date('d F Y', strtotime($detail->payment_date)) }}
                        </td>
                        <td style="text-align: center">{{ $detail->payment_method }}</td>
                        <td style="text-align: right"> {{ number_format($detail->amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

            </tbody>
            <tfoot>
                <tr style="border-top: 1px solid black;">
                    <td style="text-align: right" colspan="3">Total Instalment</td>
                    <td style="text-align: right">
                        {{ number_format($data->directSalesCreditBy->sum('amount'), 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right" colspan="3">Total Invoice</td>
                    <td style="text-align: right">
                        {{ number_format($data->total_incl, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: right" colspan="3">Remaining Instalment</td>
                    <td style="text-align: right">
                        {{ number_format($data->total_incl - $data->directSalesCreditBy->sum('amount'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
        <hr>

    </div>
