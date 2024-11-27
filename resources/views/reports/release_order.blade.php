<?php 
use Carbon\Carbon;
?>

<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }

        table {
            width: 100%;
        }

        .heading {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
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

        .small-column {
            width: 30px;
            /* Adjust the width of the small column as needed */
            font-weight: bold;
        }

        .center-box {
            padding: 5px;
        }

        .center-box .inner-box {
            display: inline-block;
            background-color: black;
            color: white;
            padding: 5px;
            border: 1px solid #000;
            width: 160px;
            text-align: center;
        }

        .border-box {
            padding: 10px;
        }

        .border-box .bordered-text {
            display: inline-block;
            border: 1px solid #000;
            padding: 5px;
        }

        .remarks-box {
            padding: 5px;
        }

        .remarks-box .remarks-text {
            display: inline-block;
            padding: 5px;
            border: 1px solid #000;
            width: 160px;
        }
    </style>
</head>


{{-- For Print --}}
@if($advertisement->advertisement_type_id == 7)
@foreach($groupedAssignedNews as $empanelledId => $newsGroup)

<body>
    <table>
        <tr>
            <td colspan="2" class="heading">GOVERNMENT OF MEGHALAYA</td>
        </tr>
        <tr>
            <td colspan="2" class="heading">DIRECTORATE OF INFORMATION & PUBLIC RELATIONS</td>
        </tr>
        <tr>
            <td colspan="2"><br></td>
        </tr>
        <tr>
            <td colspan="2">No. M /Advt./<b>{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{ $advertisement->mipr_no
                    }}</b>…………………Dated Shillong, the ……<b>{{ Carbon::parse($advertisement->issue_date)->format('jS F,
                    Y') }}</b>....</td>
        </tr>
        <tr>
            <td colspan="2">To,</td>
        </tr>
        <tr>
            <td colspan="2">The Editor/Advertisement Manager:
                <b>{{ $newsGroup->first()->empanelled->news_name }}</b><br>
            </td>
        </tr>
        <tr>
            <td></td>
            <td width="70%" class="just_align"><b>THE ADVERTISEMENT SHOULD BE PUBLISHED AS FOLLOWS:</b></td>
        </tr>
        <tr>
            <td class="center-box">
                <div class="inner-box">
                    <b>To be published <br>Positively on<br>
                        @foreach($newsGroup->pluck('positively_on') as $date)
                        {{ \Carbon\Carbon::parse($date)->format('jS F, Y') }}
                        @if(!$loop->last), @endif
                        @endforeach
                    </b>
                </div>
            </td>
            <td class="just_align">
                (a) The Headline or heading of advertisement should be printed in type face size not exceeding 14
                points.
            </td>
        </tr>
        <tr>
            @if(!empty($advertisement->remarks))
            <td class="remarks-box">
                <div class="remarks-text">
                    {{ $advertisement->remarks }}
                </div>
            </td>
            @else
            <td></td>
            @endif

            <td class="just_align">(d) Spacing between the 'Heading' or 'headings' and the contents of advertisement
                or
                between its paragraph(s) or between paragraph and the designation of the issuing authority should
                not
                exceed 3 point lead.</td>
        </tr>
        <tr>
            <td></td>
            <td class="just_align">(e) The Standard width of the advertisement column should not be less than 4.5
                centimetres. </td>
        </tr>
        <tr>
            <td></td>
            <td class="just_align">(f) The advertisement if publish in <b>{{ $newsGroup->first()->columns }}
                    column(s)</b>
                should not exceed <b>{{ $newsGroup->first()->cm }}
                    centimeters.</td></b>
        </tr>
        <tr>
            <td colspan="2"><b><u>N.B.</u>:-</b></td>
        </tr>
        <tr>
            <td colspan="2">
                <ol>
                    <li>Bills for <b><i><u>Government Departments</u></i></b> should be submitted to Directorate of
                        Information & Public Relations, Meghalaya, Shillong for arrangement of payment.</li>
                    <li>Bills for <b><i><u>Public Sector Undertakings, Units/Corporations/Autonomous Bodies/Other
                                    Agencies</u></i></b> of the Government may be addressed to
                        @if($advertisement->payment_by == "D")
                        _____________________________________________
                        @else
                        the <b><i>{{$advertisement->department->dept_name}}</i></b>
                        @endif
                        for payment
                        and should be routed through DIPR for verification of the rate of advertisement as approved
                        by
                        the Government from time to time.</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td></td>
            <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.</td>
        </tr>
        <tr>
            <td colspan="2">Copy to the:-</td>
        </tr>
        <tr>
            <td colspan="2">
                <ol>
                    <li>{{$advertisement->department->dept_name}} for information and necessary action. This has a
                        reference to letter No.
                        <b><i>{{ $advertisement->ref_no }} Dated {{
                                \Carbon\Carbon::parse($advertisement->ref_date)->format('d.m.Y') }}</i></b>
                    </li>
                    <li>Advertisement section, for information and necessary action.</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td class="border-box">
                <div class="bordered-text">
                    {{ $advertisement->department->dept_name}}
                </div>
            </td>
            <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.</td>
        </tr>
    </table>

</body>
@endforeach

@for ($i = 0; $i < 2; $i++) <body>
    <table>
        <tr>
            <td colspan="2" class="heading">GOVERNMENT OF MEGHALAYA</td>
        </tr>
        <tr>
            <td colspan="2" class="heading">DIRECTORATE OF INFORMATION & PUBLIC RELATIONS</td>
        </tr>
        <tr>
            <td colspan="2"><br></td>
        </tr>
        <tr>
            <td colspan="2">No. M /Advt./<b>{{ $miprFileNo->mipr_file_no}}/{{ date('y') }}/{{ $advertisement->mipr_no
                    }}</b>…………………Dated Shillong, the ……<b>{{ Carbon::parse($advertisement->issue_date)->format('jS F,
                    Y') }}</b>....</td>
        </tr>
        <tr>
            <td colspan="2">To,</td>
        </tr>
        <tr>
            <td colspan="2">The Editor/Advertisement Manager:
                .........................................................................................................<br>
            </td>
        </tr>

        <!-- Create a table with two columns: left for the newspapers and dates, right for the instructions -->
        <tr>
            <td style="width: 30%; vertical-align: top;">
                @php
                $groupedNews = $advertisement->assigned_news->groupBy('positively_on');
                @endphp

                @foreach($groupedNews as $date => $newsGroup)
                <div style="margin-top: 15px">
                    @foreach($newsGroup as $news)
                    <div>
                        <b><i style="font-size: 12px;">{{ $news->empanelled->news_name }}</i></b>,
                        <span style="font-size: 12px;">({{ $news->columns }}col x {{ $news->cm }}cm)</span><br>
                    </div>
                    @endforeach
                    <div class="center-box" style="margin-top: 5px;">
                        <div class="inner-box">
                            <span style="font-size: 12px;"><b>To be published positively on {{
                                    Carbon::parse($date)->format('jS F,
                                    Y') }}</b></span>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if(!empty($advertisement->remarks))
                <div class="remarks-box">
                    <div class="remarks-text">
                        {{ $advertisement->remarks }}
                    </div>
                </div>
                @endif
            </td>

            <td style="width: 70%; vertical-align: top; padding-left: 10px;">
                <b>THE ADVERTISEMENT SHOULD BE PUBLISHED AS FOLLOWS:</b><br><br>
                (a) The Headline or heading of advertisement should be printed in type face size not exceeding 14
                points.<br><br>
                (b) Sub-heading of an advertisement should not exceed '12' points type face size.<br><br>
                (c) The content of advertisement except the headlines or heading/Sub-heading should not exceed 12 point
                type face size.<br><br>
                (d) Spacing between the 'Heading' or 'headings' and the contents of advertisement or between its
                paragraph(s) or between paragraph and the designation of the issuing authority should not exceed 3 point
                lead.<br><br>
                (e) The Standard width of the advertisement column should not be less than 4.5 centimetres.<br><br>
                (f) The advertisement if published in column(s) should not exceed
                centimeters.<br>
            </td>
        </tr>

        <!-- Remaining content (footer) -->
        <tr>
            <td colspan="2">
                <b><u>N.B.</u>:-</b><br>
                <ol>
                    <li>Bills for <b><i><u>Government Departments</u></i></b> should be submitted to Directorate of
                        Information & Public Relations, Meghalaya, Shillong for arrangement of payment.</li>
                    <li>Bills for <b><i><u>Public Sector Undertakings, Units/Corporations/Autonomous Bodies/Other
                                    Agencies</u></i></b> of the Government may be addressed to
                        @if($advertisement->payment_by == "D")
                        _____________________________________________
                        @else
                        the <b><i>{{ $advertisement->department->dept_name }}</i></b>
                        @endif
                        for payment and should be routed through DIPR for verification of the rate of advertisement as
                        approved by the Government from time to time.
                    </li>
                </ol>
            </td>
        </tr>
        <tr>
            <td></td>
            <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya,
                Shillong.</td>
        </tr>
        <tr>
            <td colspan="2">Copy to the:-</td>
        </tr>
        <tr>
            <td colspan="2">
                <ol>
                    <li>{{ $advertisement->department->dept_name }} for information and necessary action. This has a
                        reference to letter No. <b><i>{{ $advertisement->ref_no }} Dated {{
                                \Carbon\Carbon::parse($advertisement->ref_date)->format('d.m.Y') }}</i></b></li>
                    <li>Advertisement section, for information and necessary action.</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td class="border-box">
                <div class="bordered-text">
                    {{ $advertisement->department->dept_name }}
                </div>
            </td>
            <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.</td>
        </tr>
    </table>

    </body>
    @endfor


    {{-- For Video/Radio --}}
    @elseif($advertisement->advertisement_type_id == 6)
    @foreach($advertisement->assigned_news as $assignedNews)

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
                <td colspan="2">No. M /Advt./<b>{{ $miprFileNo->mipr_file_no}}/{{ date('y') }}/{{
                        $advertisement->mipr_no
                        }}</b>…………………Dated Shillong, the ……<b>{{
                        Carbon::parse($advertisement->issue_date)->format('jS F, Y') }}</b>....</td>
            </tr>
            <tr>
                <td colspan="2">To,</td>
            </tr>
            <tr>
                <td colspan="2">The Editor/Advertisement Manager:
                    <b>{{ $assignedNews->empanelled->news_name }}</b><br>

                </td>
            </tr>
            <tr>
                <td></td>
                <td width="70%" class="just_align"><b>THE ADVERTISEMENT SHOULD BE PUBLISHED AS FOLLOWS:</b></td>
            </tr>

            <tr>
                <td class="center-box">
                    <div class="inner-box">
                        <b>To be published <br>Positively on<br>{{ $advertisement->positively_on}}</b>
                    </div>
                </td>
                <td class="just_align">(a) The Advertisement for Electronic Media should be broadcasted for
                    <b>{{ $advertisement->seconds }} seconds</b>.
                </td>

            </tr>
            <tr>
                @if(!empty($advertisement->remarks))
                <td class="remarks-box">
                    <div class="remarks-text">
                        {{ $advertisement->remarks }}
                    </div>
                </td>
                @else
                <td></td>
                @endif
            </tr>
            <tr>
                <td colspan="2"><b><u>N.B.</u>:-</b></td>
            </tr>
            <tr>
                <td colspan="2">
                    <ol>
                        <li>Bills for <b><i><u>Government Departments</u></i></b> should be submitted to Directorate of
                            Information & Public Relations, Meghalaya, Shillong for arrangement of payment.</li>
                        <li>Bills for <b><i><u>Public Sector Undertakings, Units/Corporations/Autonomous Bodies/Other
                                        Agencies</u></i></b> of the Government may be addressed to
                            @if($advertisement->payment_by == "D")
                            _____________________________________________
                            @else
                            the <b><i>{{$advertisement->department->dept_name}}</i></b>
                            @endif
                            for payment
                            and should be routed through DIPR for verification of the rate of advertisement as approved
                            by
                            the Government from time to time.</li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.</td>
            </tr>
            <tr>
                <td colspan="2">Copy to the:-</td>
            </tr>
            <tr>
                <td colspan="2">
                    <ol>
                        <li>{{$advertisement->department->dept_name}} for information and necessary action. This has a
                            reference to letter No.
                            <b><i>{{ $advertisement->ref_no }} Dated {{
                                    \Carbon\Carbon::parse($advertisement->ref_date)->format('d.m.Y') }}</i></b>
                        </li>
                        <li>Advertisement section, for information and necessary action.</li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td class="border-box">
                    <div class="bordered-text">
                        {{ $advertisement->department->dept_name}}
                    </div>
                </td>
                <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.</td>
            </tr>
        </table>

    </body>
    @endforeach

    @for ($i = 0; $i < 2; $i++) <body>
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
                <td colspan="2">No. M /Advt./<b>{{ $miprFileNo->mipr_file_no}}/{{ date('y') }}/{{
                        $advertisement->mipr_no
                        }}</b>…………………Dated Shillong, the ……<b>{{
                        Carbon::parse($advertisement->issue_date)->format('jS F, Y') }}</b>....</td>
            </tr>
            <tr>
                <td colspan="2">To,</td>
            </tr>
            <tr>
                <td colspan="2">The Editor/Advertisement Manager:
                    <b>{{ $advertisement->assigned_news->pluck('empanelled.news_name')->implode(', ') }}</b><br>

                </td>
            </tr>
            <tr>
                <td></td>
                <td width="70%" class="just_align"><b>THE ADVERTISEMENT SHOULD BE PUBLISHED AS FOLLOWS:</b></td>
            </tr>
            <tr>
                <td class="center-box">
                    <div class="inner-box">
                        <b>To be published <br>Positively on<br>{{ $advertisement->positively_on}}</b>
                    </div>
                </td>
                <td class="just_align">(a) The Advertisement for Electronic Media should be broadcasted for
                    <b>{{ $advertisement->seconds }} seconds</b>.
                </td>

            </tr>
            <tr>
                @if(!empty($advertisement->remarks))
                <td class="remarks-box">
                    <div class="remarks-text">
                        {{ $advertisement->remarks }}
                    </div>
                </td>
                @else
                <td></td>
                @endif
            </tr>
            <tr>
                <td colspan="2"><b><u>N.B.</u>:-</b></td>
            </tr>
            <tr>
                <td colspan="2">
                    <ol>
                        <li>Bills for <b><i><u>Government Departments</u></i></b> should be submitted to Directorate of
                            Information & Public Relations, Meghalaya, Shillong for arrangement of payment.</li>
                        <li>Bills for <b><i><u>Public Sector Undertakings, Units/Corporations/Autonomous Bodies/Other
                                        Agencies</u></i></b> of the Government may be addressed to
                            @if($advertisement->payment_by == "D")
                            _____________________________________________
                            @else
                            the <b><i>{{$advertisement->department->dept_name}}</i></b>
                            @endif
                            for payment
                            and should be routed through DIPR for verification of the rate of advertisement as approved
                            by
                            the Government from time to time.</li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td></td>
                <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.</td>
            </tr>
            <tr>
                <td colspan="2">Copy to the:-</td>
            </tr>
            <tr>
                <td colspan="2">
                    <ol>
                        <li>{{$advertisement->department->dept_name}} for information and necessary action.
                            This has a reference to letter No.
                            <b><i>{{ $advertisement->ref_no }} Dated {{
                                    \Carbon\Carbon::parse($advertisement->ref_date)->format('d.m.Y') }}</i></b>
                        </li>
                        <li>Advertisement section, for information and necessary action.</li>
                    </ol>
                </td>
            </tr>
            <tr>
                <td class="border-box">
                    <div class="bordered-text">
                        {{ $advertisement->department->dept_name}}
                    </div>
                </td>
                <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.</td>
            </tr>
        </table>
        </body>
        @endfor

        {{-- For Online Media --}}
        @elseif($advertisement->advertisement_type_id == 8)
        @foreach($advertisement->assigned_news as $assignedNews)

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
                    <td colspan="2">No. M /Advt./<b>{{ $miprFileNo->mipr_file_no}}/{{ date('y') }}/{{
                            $advertisement->mipr_no
                            }}</b>…………………Dated Shillong, the ……<b>{{
                            Carbon::parse($advertisement->issue_date)->format('jS F, Y') }}</b>....</td>
                </tr>
                <tr>
                    <td colspan="2">To,</td>
                </tr>
                <tr>
                    <td colspan="2">The Editor/Advertisement Manager:
                        <b>{{ $assignedNews->empanelled->news_name }}</b><br>

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td width="70%" class="just_align"><b>THE ADVERTISEMENT SHOULD BE PUBLISHED AS FOLLOWS:</b></td>
                </tr>
                <tr>
                    <td class="center-box">
                        <div class="inner-box">
                            <b>To be published <br>Positively on<br>{{ $advertisement->positively_on}} <br>(@Rs.{{
                                $amount}}/- inclusive of all Taxes)</b>
                        </div>
                    </td>
                    <td class="just_align">(a) The Advertisement should be uploaded/broadcasted by the
                        <b><i><u>Electronic
                                    Media</u></i></b> in type face size not exceeding 14 points.
                    </td>
                </tr>
                <tr>
                    @if(!empty($advertisement->remarks))
                    <td class="remarks-box">
                        <div class="remarks-text">
                            {{ $advertisement->remarks }}
                        </div>
                    </td>
                    @else
                    <td></td>
                    @endif
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><b><u>N.B.</u>:-</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <ol>
                            <li>Bills for <b><i><u>Government Departments</u></i></b> should be submitted to Directorate
                                of
                                Information & Public Relations, Meghalaya, Shillong for arrangement of payment.</li>
                            <li>Bills for <b><i><u>Public Sector Undertakings, Units/Corporations/Autonomous
                                            Bodies/Other
                                            Agencies</u></i></b> of the Government may be addressed to
                                @if($advertisement->payment_by == "D")
                                _____________________________________________
                                @else
                                the <b><i>{{$advertisement->department->dept_name}}</i></b>
                                @endif
                                for payment
                                and should be routed through DIPR for verification of the rate of advertisement as
                                approved
                                by
                                the Government from time to time.</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Copy to the:-</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <ol>
                            <li>{{$advertisement->department->dept_name}} for information and necessary action. This has
                                a
                                reference to letter No.
                                <b><i>{{ $advertisement->ref_no }} Dated {{
                                        \Carbon\Carbon::parse($advertisement->ref_date)->format('d.m.Y') }}</i></b>
                            </li>
                            <li>Advertisement section, for information and necessary action.</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td class="border-box">
                        <div class="bordered-text">
                            {{ $advertisement->department->dept_name}}
                        </div>
                    </td>
                    <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                    </td>
                </tr>
            </table>

        </body>
        @endforeach

        @for ($i = 0; $i < 2; $i++) <body>
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
                    <td colspan="2">No. M /Advt./<b>{{ $miprFileNo->mipr_file_no}}/{{ date('y') }}/{{
                            $advertisement->mipr_no
                            }}</b>…………………Dated Shillong, the ……<b>{{
                            Carbon::parse($advertisement->issue_date)->format('jS F, Y') }}</b>....</td>
                </tr>
                <tr>
                    <td colspan="2">To,</td>
                </tr>
                <tr>
                    <td colspan="2">The Editor/Advertisement Manager:
                        <b>{{ $advertisement->assigned_news->pluck('empanelled.news_name')->implode(', ') }}</b><br>

                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td width="70%" class="just_align"><b>THE ADVERTISEMENT SHOULD BE PUBLISHED AS FOLLOWS:</b></td>
                </tr>
                <tr>
                    <td class="center-box">
                        <div class="inner-box">
                            <b>To be published <br>Positively on<br>{{ $advertisement->positively_on}} <br>(@Rs.{{
                                $amount}}/- inclusive of all Taxes)</b>
                        </div>
                    </td>
                    <td class="just_align">(a) The Advertisement should be uploaded/broadcasted by the
                        <b><i><u>Electronic
                                    Media</u></i></b> in type face size not exceeding 14 points.
                    </td>
                </tr>

                <tr>
                    @if(!empty($advertisement->remarks))
                    <td class="remarks-box">
                        <div class="remarks-text">
                            {{ $advertisement->remarks }}
                        </div>
                    </td>
                    @else
                    <td></td>
                    @endif
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"><b><u>N.B.</u>:-</b></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <ol>
                            <li>Bills for <b><i><u>Government Departments</u></i></b> should be submitted to Directorate
                                of
                                Information & Public Relations, Meghalaya, Shillong for arrangement of payment.</li>
                            <li>Bills for <b><i><u>Public Sector Undertakings, Units/Corporations/Autonomous
                                            Bodies/Other
                                            Agencies</u></i></b> of the Government may be addressed to
                                @if($advertisement->payment_by == "D")
                                _____________________________________________
                                @else
                                the <b><i>{{$advertisement->department->dept_name}}</i></b>
                                @endif
                                for payment
                                and should be routed through DIPR for verification of the rate of advertisement as
                                approved
                                by
                                the Government from time to time.</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Copy to the:-</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <ol>
                            <li>{{$advertisement->department->dept_name}} for information and necessary action.
                                This has a reference to letter No.
                                <b><i>{{ $advertisement->ref_no }} Dated {{
                                        \Carbon\Carbon::parse($advertisement->ref_date)->format('d.m.Y') }}</i></b>
                            </li>
                            <li>Advertisement section, for information and necessary action.</li>
                        </ol>
                    </td>
                </tr>
                <tr>
                    <td class="border-box">
                        <div class="bordered-text">
                            {{ $advertisement->department->dept_name}}
                        </div>
                    </td>
                    <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                    </td>
                </tr>
            </table>
            </body>
            @endfor
            @endif


</html>