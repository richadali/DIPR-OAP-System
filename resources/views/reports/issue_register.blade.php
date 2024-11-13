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

        th,
        td {
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
    <h1>Issue of Advertisement Register</h1>
    <table>
        <thead>
            <tr>
                <th width="10%">Mipr No</th>
                <th width="9%">Date of Issue</th>
                <th width="20%">Name of Department concerned</th>
                <th width="5%">Size/Seconds</th>
                <th width="15%">Subject</th>
                <th width="15%">Ref. No & Date</th>
                <th width="9%">Positively on</th>
                <th width="5%">No of Insertion</th>
                <th width="20%">Issued to Organization</th>
                <th width="10%">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($advertisements as $advertisement)
            <tr>
                <td align=center> {{ $advertisement->mipr_no }}</td>
                <td align=center> {{ $advertisement->issue_date->format('d-m-Y') }}</td>
                <td> {{ $advertisement->department->dept_name }}</td>
                <td align="center">
                    @if(!empty($advertisement->cm) && !empty($advertisement->columns))
                    {{ $advertisement->cm }}x{{ $advertisement->columns }}
                    @elseif(!empty($advertisement->seconds))
                    {{ $advertisement->seconds }}s
                    @else
                    &nbsp;
                    @endif
                </td>
                <td align=center> {{ $advertisement->subject->subject_name ?? ' '}}</td>
                <td align=center class="long-text ref-no"> {{ $advertisement->ref_no . ' Dated ' .
                    $advertisement->ref_date }} </td>
                <td align=center> {{ $advertisement->positively_on }}</td>
                <td align=center> {{ $advertisement->no_of_entries }}</td>
                <td>
                    {{ implode(', ', $advertisement->assigned_news->pluck('empanelled.news_name')->toArray()) }}
                </td>

                <td> {{ $advertisement->remarks }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>