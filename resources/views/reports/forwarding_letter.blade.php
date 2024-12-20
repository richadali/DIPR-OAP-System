<?php 
use Carbon\Carbon;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11.5pt;
        }

        table {
            width: 100%;
        }

        .heading {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }

        .line {
            margin-bottom: 10px;
        }

        .just_align {
            text-align: justify;
        }

        .font-10 {
            font-size: 10pt;
        }

        .my-table {
            width: 100%;
            border-collapse: collapse;

        }

        .my-table th,
        .my-table td {
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td colspan="2" class="heading">GOVERNMENT OF MEGALAYA</td>
        </tr>
        <tr>
            <td colspan="2" class="heading">DIRECTORATE OF INFORMATION & PUBLIC RELATIONS</td>
        </tr>
        <tr>
            <td colspan="2"><br></td>
        </tr>
        <tr>
            <td colspan="2" style="width: 100%;">
                <div style="width: 100%; position: relative;">
                    <span style="float: left;">
                        Memo No. M/Advt./Bi l l/<b>{{ $bill->bill_memo_no}}</b>
                    </span>
                    <span style="float: right;">
                        Dated Shillong, the<b>
                            {{Carbon::parse($bill->advertisement->issue_date)->format('jS F,
                            Y') }}</b>
                    </span>
                </div>
            </td>
        </tr><br>
        <tr>
            <td colspan="2"><br>The under mentioned bill (s) with <b>MIPR No. {{ $bill->advertisement->mipr_no }}</b>
                is/are
                forwarded to: -<br><br>
                <b><u>{{ $bill->advertisement->department->dept_name}}</u></b><br>
                <br>for favour of early payment. The bill(s) has/have been checked and found correct.
            </td>
        </tr>
        <tr>
            <td colspan="2"><br>The date(s) of which the bill(s) has/have been paid to the concerned may please be
                intimated to the Directorate for our record
            </td>
        </tr>
        <tr>
            <td></td>
            <td align="center"><b><br><br><br>Director of Information & Public Relations,<br>Meghalaya, Shillong </b>
            </td>
        </tr>
        <br>
        <tr>
            <td colspan="2">
                <table class="my-table">
                    <tr>
                        <th>Enclosed</th>
                        <th rowspan="2">Date</th>
                        <th colspan="2">Amount </th>
                        <th rowspan="2">From</th>
                    </tr>
                    <tr>
                        <th>Bill No.</th>
                        <th> Rs. </th>
                        <th>P.</th>
                    </tr>
                    <tr>
                        <td>{{ $bill->bill_no }}</td>
                        <td>{{ $bill->bill_date->format('d-m-Y') }}</td>
                        <td>{{ number_format($totalAmount, 0, '.', ',') }}</td>
                        <td>{{ number_format(($totalAmount - floor($totalAmount)) * 100,
                            0, '.', ',') }}</td>
                        <td>{{ $newspaperName }}</td>
                    </tr>
                </table>

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <br>(Rupees<b> {{$words}}</b>)
            </td>
        </tr><br>
        <tr>
            <td>
                Copy forwarded for information to: -
            </td>
        </tr>
        <tr>

            <td colspan="2">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The Advertisement Manager: {{ $newspaperName
                }}
            </td>
        </tr>
        <tr>

            <td colspan="2">
                <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Future correspondent concerning to above
                bill(s) may please be made to the party concerned to whom the bill(s) has been forwarded.

            </td>
        </tr>
        <tr>
            <td></td>
            <td align="center"><b><br><br><br>Director of Information & Public Relations,<br>Meghalaya, Shillong </b>
            </td>
        </tr>
    </table>
</body>

</html>