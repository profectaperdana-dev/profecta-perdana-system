<html>

<head>
    <style>
        /**
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
        @page {
            margin: 1cm 1cm;
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin-top: 6cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        /** Define the header rules **/
        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 6cm;
            font-size: 14px;
            font-family: 'Arial';
            /** Extra personal styles **/
            background-color: #ffffff;
            color: rgb(0, 0, 0);
            text-align: left;
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
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 1.5cm;
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
                <td style="width: 5%"></td>
                <td></td>
                <td style="width: 30%;text-align:left">
                    Invoice : {{ $data->order_number }} <br>
                    Revision : 0 <br>
                    Customer : {{ $data->customerBy->code_cust }}

                </td>
            </tr>
            <tr>
                <th colspan="5">
                    <hr>
                </th>
            </tr>
            <tr>
                <th colspan="5" style="text-align: center">INVOICE</th>
            </tr>
            <tr>
                <td colspan="3" style="width: 50%;text-align:left">Invoice To : <br>
                    {{ $data->customerBy->name_cust }}

                </td>
                <td colspan="2" style="width: 50%;text-align:left"></td>

            </tr>
        </table>



    </header>

    <footer>
        Copyright &copy; <?php echo date('Y'); ?>
    </footer>

    <!-- Wrap the content of your PDF inside a main tag -->
    <main>
        <h1>Hello World</h1>
    </main>
</body>

</html>
