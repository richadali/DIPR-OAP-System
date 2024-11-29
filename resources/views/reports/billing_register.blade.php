<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>PDF View</title>
    <style>
        @page {
            size: landscape;
            margin: 0;
        }

        body {
            margin: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #000;
            word-wrap: break-word;
            font-size: 9pt;
        }

        .long-text {
            white-space: normal;
            word-break: break-all;
        }

        .ref-no {
            white-space: pre-wrap;
        }

        h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>Billing Register</h3>
    <table>
        <thead>
            <tr>
                <th width="5%">Slno.</th>
                <th width="9%">Entering Date</th>
                <th width="21%">Branch of Department</th>
                <th width="14%">Organizations issued</th>
                <th width="8%">MIPR No</th>
                <th width="9%">MIPR Date</th>
                <th width="12%">Bill No</th>
                <th width="9%">Bill Date</th>
                <th width="5%">Size/Sec</th>
                <th width="8%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bills as $key => $bill)
            <tr>
                <td align="center"> {{ ++$key }}</td>
                <td align="center"> {{ $bill->created_at->format('d-m-Y')}}</td>
                <td> {{ $bill->dept_name }}</td>
                <td>{{ $bill->news_name }} </td>
                <td align="center"> {{ $bill->mipr_no }}</td>
                <td align="center"> {{ $bill->issue_date->format('d-m-Y')}}</td>
                <td align="center"> {{ $bill->bill_no }}</td>
                <td align="center"> {{ $bill->bill_date->format('d-m-Y')}}</td>
                <td align="center">{!! $bill->sizes !!}</td>
                <td align="right"> {{ $bill->total_amount }}</td>
            </tr>
            @endforeach
            <tr>
                <td colspan="9" align="right"><strong>Total:</strong></td>
                <td align="right"><strong>{{ number_format($grandTotalAmount, 2) }}</strong></td>
            </tr>
        </tbody>

    </table>
</body>

</html>