<html>

<head>
    <style>
        @page {
            margin: 200px 50px;
        }

        #header {
            position: fixed;
            left: 0px;
            /* top: -500px; */
            height: 400px;
            right: 0px;
            text-align: center;
            margin-top: 50px;
            font-size: 14px;
        }
    </style>

<body>
    <div id="header">

    </div>


    <div id="content">
        <table style="width: 100%">
            <thead style="border:  1px solid black;">
                <tr>
                    <th>No</th>
                    <th>Item Name </th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $key => $value)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $value->nama_barang }}</td>
                        <td>{{ $value->harga_beli }}</td>
                        <td>{{ $value->minstok }}</td>
                        <td>{{ $total = $value->harga_beli * $value->minstok }}</td>
                    </tr>
                @endforeach



            </tbody>

        </table>
        <table style="width: 100%">
            <td>Payment Method <br>
                Direct Transfer <br>
                Bank Mandiri <br>
                Bank BCA <br>
                Thank You ! <br>
                sbdjbsjbdjsbdjbsjdbb
            </td>
            <td>Sincerly yours</td>
        </table>
    </div>
</body>

</html>
