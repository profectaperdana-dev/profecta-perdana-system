<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $data->code }}</title>
</head>

<body>
    <table style="width: 100%">
        <tr>
            <th colspan="6" align="right" colspan=""><img style="width: 100px" src="https://iili.io/tTHjV9.png"
                    alt="">
            </th>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th colspan="6" align="left">
                Personal Data
            </th>

        </tr>
        <tr>
            <td style="width: 24%">Full Name</td>
            <td style="width:2%">:</td>
            <td style="width: 24%;text-transform:capitalize !important" align="left"><u>{{ $data->name }}</u></td>
            <td style="width: 24%">Gender</td>
            <td style="width:2%">:</td>
            <td style="width: 24%;text-transform:capitalize !important" align="left"><u>{{ $data->gender }}</u></td>
        </tr>
        <tr>
            <td style="width: 24%">Place of Birth</td>
            <td style="width:2%">:</td>
            <td style="width: 24%;text-transform:capitalize !important" align="left">
                <u>{{ $data->place_of_birth }}</u>
            </td>
            <td style="width: 24%">Date of Birth</td>
            <td style="width:2%">:</td>
            <td style="width: 24%;text-transform:capitalize !important" align="left">
                <u>{{ date('d-m-Y', strtotime($data->date_of_birth)) }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">Email</td>
            <td style="width:2%">:</td>
            <td colspan="3" style="width: 74%;" align="left"><u>{{ $data->email }}</u></td>
        </tr>
        <tr>
            <td style="width: 24%">Phone Number</td>
            <td style="width:2%">:</td>
            <td style="width: 24%;text-transform:capitalize !important" align="left"><u>{{ $data->phone_number }}</u>
            </td>
            <td style="width: 24%">Telp. House</td>
            <td style="width:2%">:</td>
            <td style="width: 24%;text-transform:capitalize !important" align="left">
                <u>{{ $data->house_phone_number }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">Address</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;text-transform:capitalize !important" align="left">
                <u>{{ $data->address }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">Number of Birth</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 78%;text-transform:capitalize !important" align="left">
                <u>{{ $data->birth_order }} from {{ $data->from_order }} brothers</u>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th align="left">
                Family Data
            </th>
        </tr>
        <tr>
            <td style="width: 24%">Marital Status</td>
            <td style="width:2%">:</td>
            <td colspan="3" style="width: 74%;text-transform:capitalize !important" align="left">
                <u>{{ $data->marital_status }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">Couple Name</td>
            <td style="width:2%">:</td>
            <td colspan="3" style="width: 78%;text-transform:capitalize !important" align="left">
                <u>{{ $data->couple_name }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">Education</td>
            <td style="width:2%">:</td>
            <td colspan="3" style="width: 74%;text-transform:capitalize !important" align="left">
                <u>{{ $data->couple_education }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">Occupation</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 78%;text-transform:capitalize !important" align="left">
                <u>{{ $data->couple_occupation }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">Children</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 78%;text-transform:capitalize !important" align="left">
                <u>{{ $data->number_of_children }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 25%">Child's Age
            </td>
            <td style="width:2%">:</td>
            <td style="width: 25%;text-transform:capitalize !important" align="left">1.

                @if ($data->child_1_age == null)
                    -
                @else
                    <u> {{ $data->child_1_age }} years old</u>
                @endif



            </td>
            <td style="width: 25%;text-transform:capitalize !important" align="left">3.

                @if ($data->child_3_age == null)
                    -
                @else
                    <u> {{ $data->child_3_age }} years old</u>
                @endif

            </td>
            <td style="width:2%"></td>

            <td style="width: 25%">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 25%">&nbsp;
            </td>
            <td style="width:2%">:</td>
            <td style="width: 25%;text-transform:capitalize !important" align="left">2.

                @if ($data->child_2_age == null)
                    -
                @else
                    <u> {{ $data->child_2_age }} years old</u>
                @endif



            </td>
            <td style="width: 25%;text-transform:capitalize !important" align="left">4.

                @if ($data->child_4_age == null)
                    -
                @else
                    <u>{{ $data->child_4_age }} years old</u>
                @endif

            </td>
            <td style="width:2%"></td>

            <td style="width: 25%">&nbsp;</td>
        </tr>
        <tr>
            <td style="width: 25%">Father Name</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left"><u>{{ $data->father_name }}</u>
            </td>
            <td style="width: 25%">Mother Name</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->mother_name }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">Occupation</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->father_occupation }}</u>
            </td>
            <td style="width: 20%">Occupation</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->mother_occupation }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">Address</td>
            <td style="width:1%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->father_address }}</u>
            </td>
            <td style="width: 20%">Address</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->mother_address }}</u>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th colspan="6" align="left">
                Name & Number That Can be Contacted in an Emergency </th>
        </tr>
        <tr>
            <td style="width: 20%">1. Name</td>
            <td style="width:1%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->related_name_1 }}</u>
            </td>
            <td style="width: 20%">Number Phone</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->related_number_phone_1 }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">2. Name</td>
            <td style="width:1%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->related_name_2 }}</u>
            </td>
            <td style="width: 20%">Number Phone</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->related_number_phone_2 }}</u>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th colspan="6" align="left">
                Formal Education </th>
        </tr>
        <tr>
            <td style="width: 24%">1. Education</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;" align="left">
                <u>{{ $data->formal_education_1 }}</u> from <u>{{ $data->formal_education_from_1 }} to
                    {{ $data->formal_education_to_1 }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">2. Education</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;" align="left">
                <u>{{ $data->formal_education_2 }}</u> from <u>{{ $data->formal_education_from_2 }} to
                    {{ $data->formal_education_to_2 }}</u>
            </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <th colspan="6" align="left">
                Experience </th>
        </tr>
        <tr>
            <td style="width: 20%">1. Company Name</td>
            <td style="width:1%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->company_name_1 }}</u>
            </td>
            <td style="width: 20%">2. Company Name</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->company_name_2 }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">Position</td>
            <td style="width:1%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->position_1 }}</u>
            </td>
            <td style="width: 20%">Position</td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                <u>{{ $data->position_2 }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 20%">Length of Work
            </td>
            <td style="width:1%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">

                @if ($data->length_of_work_1 == null)
                    -
                @else
                    <u> {{ $data->length_of_work_1 }} Month</u>
                @endif

            </td>
            <td style="width: 20%">Length of Work
            </td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">

                @if ($data->length_of_work_2 == null)
                    -
                @else
                    <u>{{ $data->length_of_work_2 }} Month </u>
                @endif


            </td>
        </tr>
        <tr>
            <td style="width: 20%">Last Salary
            </td>
            <td style="width:1%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">

                @if ($data->last_salary_1 == null)
                    -
                @else
                    <u> @currency($data->last_salary_1)</u>
                @endif

            </td>
            <td style="width: 20%">Last Salary
            </td>
            <td style="width:2%">:</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">

                @if ($data->last_salary_2 == null)
                    -
                @else
                    <u> (@currency($data->last_salary_2))</u>
                @endif



            </td>
        </tr>
        <tr>
            <td style="width: 24%">1. Language Skill</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;" align="left">
                <u>{{ $data->language_skill_1 }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">2. Language Skill</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;" align="left">
                <u>{{ $data->language_skill_2 }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">3. Language Skill</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;" align="left">
                <u>{{ $data->language_skill_3 }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">Computer Skill</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;" align="left">
                <u>{{ $data->computer_skill }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">Place Outside City</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;" align="left">
                <u>{{ $data->placement }}</u>
            </td>
        </tr>
        <tr>
            <td style="width: 24%">Salary Expected</td>
            <td style="width:2%">:</td>
            <td colspan="4" style="width: 74%;" align="left">
                <u>@currency($data->salary_expected)</u>
            </td>
        </tr>
        <tr>
            <td style="width: 25%">&nbsp;</td>
            <td style="width:2%">&nbsp;</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">&nbsp;
            </td>
            <td style="width: 25%">Signature</td>
            <td style="width:2%">&nbsp;</td>
            <td style="width: 20%;text-transform:capitalize !important" align="left">
                date
            </td>
        </tr>

        {{-- <tr>
            <td>Tempat/Tanggal Lahir</td>
            <td>: <u>{{ $data->place_of_birth }}, {{ $data->date_of_birth }} </u></td>
        </tr>
       
       
        <tr>
            <td>Jumlah Anak</td>
            <td>: <u>{{ $data->number_of_children }}</u></td>
        </tr>
        <tr>
            <td>Usia Anak</td>
            <td>: 1. <u>{{ $data->child_1_age }}</u></td>
            <td> 3. <u>{{ $data->child_2_age }}</u></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>: 2. <u>{{ $data->child_3_age }}</u></td>
            <td> 4. <u>{{ $data->child_4_age }}</u></td>
        </tr>
        <tr>
            <td>
                <h5>Orang Tua</h5>
            </td>
        </tr>
        <tr>
            <td>Ayah</td>
            <td>: <u>{{ $data->father_name }}</u></td>
            <td>Ibu</td>
            <td>: <u>{{ $data->mother_name }}</u></td>
        </tr>
        <tr>
            <td>Pekerjaan</td>
            <td>: <u>{{ $data->father_occupation }}</u></td>
            <td>Pekerjaan</td>
            <td>: <u>{{ $data->mother_occupation }}</u></td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>: <u>{{ $data->father_address }}</u></td>
            <td>Alamat</td>
            <td>: <u>{{ $data->mother_address }}</u></td>
        </tr>

        <tr>
            <td colspan="2">
                <h5>Nama & Nomor yang bisa dihubungi pada saat darurat</h5>
            </td>
        </tr>
        <tr>
            <td>1. Nama</td>
            <td>: {{ $data->related_name_1 }}</td>
            <td>1. Nomor</td>
            <td>: {{ $data->related_number_phone_1 }}</td>
        </tr>
        <tr>
            <td>2. Nama</td>
            <td>: {{ $data->related_name_2 }}</td>
            <td>2. Nomor</td>
            <td>: {{ $data->related_number_phone_2 }}</td>
        </tr>
        <tr>
            <td colspan="3">
                <h5>Pendidikan Formal</h5>
            </td>
        </tr>
        <tr>
            <td>1. Pendidikan</td>
            <td>: {{ $data->formal_education_1 }} | {{ $data->formal_education_from_1 }} dari
                {{ $data->formal_education_to_1 }}</td>

        </tr>
        <tr>
            <td>2. Pendidikan</td>
            <td>: {{ $data->formal_education_2 }} | {{ $data->formal_education_from_2 }} dari
                {{ $data->formal_education_to_2 }}</td>

        </tr>
        <tr>
            <td colspan="3">
                <h5>Pengalaman</h5>
            </td>
        </tr> --}}

    </table>

</body>

</html>
