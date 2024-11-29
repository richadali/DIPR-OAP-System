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
            table-layout: fixed;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #000;
            word-wrap: break-word;
            font-size: 9pt;
            page-break-inside: avoid;
            /* Prevent page break inside table cells */
        }

        tr {
            page-break-inside: avoid;
            /* Prevent page break inside rows */
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
    <h3>Issue of Advertisement Register</h3>
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th width="10%">Mipr No</th>
                <th width="8%">Date of Issue</th>
                <th width="22%">Name of Department Concerned</th>
                <th width="10%">Subject</th>
                <th width="10%">Ref. No & Date</th>
                <th width="10%">Newspaper</th>
                <th width="8%">Positively On</th>
                <th width="6%">Size/Secs</th>
                <th width="5%">No of Insertion</th>
                <th width="10%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($advertisements as $advertisement)
            @php $firstRow = true; @endphp
            @foreach($advertisement->grouped_rows as $row)
            <tr>
                @if($firstRow)
                <td align="center" rowspan="{{ $advertisement->grouped_rows->count() }}">
                    {{ $advertisement->mipr_no }}
                </td>
                <td align="center" rowspan="{{ $advertisement->grouped_rows->count() }}">
                    {{ $advertisement->issue_date->format('d-m-Y') }}
                </td>
                <td rowspan="{{ $advertisement->grouped_rows->count() }}">
                    {{ $advertisement->department->dept_name }}
                </td>
                <td align="center" rowspan="{{ $advertisement->grouped_rows->count() }}">
                    {{ $advertisement->subject->subject_name ?? ' ' }}
                </td>
                <td align="center" rowspan="{{ $advertisement->grouped_rows->count() }}">
                    {{ $advertisement->ref_no . ' Dated ' . $advertisement->ref_date }}
                </td>
                @endif

                <td>{{ $row['newspaper'] }}</td>
                <td align="center">{{ $row['positively_on'] }}</td>
                <td align="center">{{ $row['sizes'] }}</td>
                <td align="center">{{ $row['no_of_insertions'] }}</td>

                @if($firstRow)
                <td rowspan="{{ $advertisement->grouped_rows->count() }}">
                    {{ $advertisement->remarks }}
                </td>
                @endif

                @php $firstRow = false; @endphp
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</body>






</html>