<!DOCTYPE html>
<html>
<head>
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
        th, td {
            padding: 8px;
            border: 1px solid #000;
            word-wrap: break-word;
        }
        .long-text {
            white-space: normal;
            word-break: break-all;
        }
        .ref-no {
            white-space: pre-wrap;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Billing Register</h1>
    <table>
        <thead>
            <tr>
            <th width="2%">Sl. No</th>
                <th width="20%">Branch of the Department </th>
                <th width="10%">Newspapers issued</th>
                <th width="6%">Release Order No</th>
                <th width="10%">Release Order Date</th>
                <th width="9%">Bill No</th>
                <th width="8%">Bill Date</th>
                <th width="5%">Size</th>
                <th width="6%">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bills as $key => $bill)
            <tr>
                <td align=center> {{ ++$key }}</td>
                <td align=center> {{ $bill->hod }}</td>
                <td>{{ $bill->news_name }}  </td>
                <td align=center> {{ $bill->release_order_no }}</td>
                <td  align=center> {{ $bill->release_order_date->format('d-m-Y') }}</td>
                <td align=center> {{ $bill->bill_no }}</td>
                <td align=center> {{ $bill->bill_date->format('d-m-Y')  }}</td>
                <td align=center> {{ $bill->size }}</td>
                <td align=right> {{ $bill->amount }}</td>
            
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>