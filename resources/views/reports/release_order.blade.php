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
            width: 180px;
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
            width: 180px;
        }
    </style>
</head>

{{-- For Messages --}}
@if($advertisement->subject_id == 11)
@for ($i = 0; $i < 3; $i++) <body>
    <table style="width: 100%;">
        <tr>
            <td colspan="2" class="heading" style="text-align: center;">GOVERNMENT OF MEGHALAYA</td>
        </tr>
        <tr>
            <td colspan="2" class="heading" style="text-align: center;">DIRECTORATE OF INFORMATION & PUBLIC
                RELATIONS
            </td>
        </tr>
        <tr>
            <td colspan="2"><br></td>
        </tr>
        <tr>
            <td colspan="2" style="width: 100%;">
                <div style="width: 100%; position: relative;">
                    <span style="float: left;">
                        No. M /Advt./<b>{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{ $advertisement->mipr_no
                            }}</b>
                    </span>
                    <span style="float: right;">
                        Dated Shillong, the <b>{{ Carbon::parse($advertisement->issue_date)->format('jS F, Y')
                            }}</b>
                    </span>
                </div>
            </td>
        </tr>
        <br><br>
        <tr>
            <td style="vertical-align: top">From:</td>
            <td>Director of Information & Public Relations,<br>
                Meghalaya, Shillong
            </td>
        </tr>
        <tr>
            <td colspan="2">To,</td>
        </tr>
        <tr>
            <td></td>
            <td>
                @php
                $groupedNews = $advertisement->assigned_news->groupBy('positively_on');
                @endphp
                Editor,
                @foreach($groupedNews as $date => $newsGroup)
                <div>
                    @foreach($newsGroup as $news)
                    <div>
                        {{ $news->empanelled->news_name }}<br>
                    </div>
                    @endforeach
                    @endforeach
                </div>
            </td>
        </tr><br><br>
        <tr>
            <td style="width: 10%; vertical-align: top;">Sub:</td>
            <td style="width: 90%; vertical-align: top;"><i><b>{{ $advertisement->message_subject }}</b></i><br>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {!! $advertisement->message_body !!}
            </td>
        </tr><br><br>
        <tr>
            <td colspan="2" style="text-align: right; padding-bottom: 30px;">
                <div style="display: inline-block; text-align: center;">
                    Yours faithfully,<br><br><br><br>
                    <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                </div>
            </td>
        </tr>
        @if(!empty($advertisement->message_copy_to))
        <tr>
            <td colspan="2">
                M /Advt./{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{ $advertisement->mipr_no }} - A<br>
                Copy to:-
            </td>
        </tr>
        <tr>
            <td colspan="2">
                {{ $advertisement->message_copy_to }}
            </td>
        </tr>
        <br><br><br>
        <tr>
            <td colspan="2" style="text-align: right;">
                <div style="display: inline-block; text-align: center;">
                    <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                </div>
            </td>
        </tr>
        @endif
    </table>
    </body>
    @endfor
    @else
    {{-- End of Message --}}

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
                <td colspan="2" style="width: 100%;">
                    <div style="width: 100%; position: relative;">
                        <span style="float: left;">
                            No. M /Advt./<b>{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{ $advertisement->mipr_no
                                }}</b>
                        </span>
                        <span style="float: right;">
                            Dated Shillong, the <b>{{ Carbon::parse($advertisement->issue_date)->format('jS F, Y')
                                }}</b>
                        </span>
                    </div>
                </td>
            </tr><br>
            <tr>
                <td colspan="2">To,</td>
            </tr>
            <tr>
                <td colspan="2">The Editor/Advertisement Manager:
                    <b>{{ $newsGroup->first()->empanelled->news_name }}</b><br>
                </td>
            </tr>
            <tr>
                <td style="width: 30%; vertical-align: top;">

                    <div class="center-box" style="margin-top:60px">
                        <div class="inner-box">
                            <b>To be published <br>Positively on<br>
                                @foreach($newsGroup->pluck('positively_on') as $date)
                                {{ \Carbon\Carbon::parse($date)->format('jS F, Y') }}
                                @if(!$loop->last), @endif
                                @endforeach
                            </b>
                        </div>
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
                    (c) The content of advertisement except the headlines or heading/Sub-heading should not exceed 12
                    point
                    type face size.<br><br>
                    (d) Spacing between the 'Heading' or 'headings' and the contents of advertisement or between its
                    paragraph(s) or between paragraph and the designation of the issuing authority should not exceed 3
                    point
                    lead.<br><br>
                    (e) The Standard width of the advertisement column should not be less than 4.5 centimetres.<br><br>
                    (f) The advertisement if publish in <b>{{ $newsGroup->first()->columns }}
                        column(s)</b>
                    should not exceed <b>{{ $newsGroup->first()->cm }}
                        centimeters.
                    </b><br>
                </td>
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
            </tr><br>
            <tr>
                <td></td>
                <td colspan="2" style="text-align: right;">
                    <div style="display: inline-block; text-align: center;">
                        <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                    </div>
                </td>
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
            </tr><br>
            <tr>
                <td class="border-box">
                    <div class="bordered-text">
                        {{ $advertisement->department->dept_name}}
                    </div>
                </td>
                <td colspan="2" style="text-align: right;">
                    <div style="display: inline-block; text-align: center;">
                        <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                    </div>
                </td>
            </tr>
        </table>

    </body>
    @endforeach


    {{-- Office & Department Copy (Print)--}}
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
                <td colspan="2" style="width: 100%;">
                    <div style="width: 100%; position: relative;">
                        <span style="float: left;">
                            No. M /Advt./<b>{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{ $advertisement->mipr_no
                                }}</b>
                        </span>
                        <span style="float: right;">
                            Dated Shillong, the <b>{{ Carbon::parse($advertisement->issue_date)->format('jS F, Y')
                                }}</b>
                        </span>
                    </div>
                </td>
            </tr><br>
            <tr>
                <td colspan="2">To,</td>
            </tr>
            <tr>
                <td colspan="2">The Editor/Advertisement Manager:
                    .........................................................................................................<br>
                </td>
            </tr>
            <tr>
                <td style="width: 40%; vertical-align: top;">
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
                                <span style="font-size: 12px;"><b>To be published positively on <br> {{
                                        Carbon::parse($date)->format('jS F,
                                        Y') }}</br></span>
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

                <td style="width: 60%; vertical-align: top; padding-left: 10px;">
                    <b>THE ADVERTISEMENT SHOULD BE PUBLISHED AS FOLLOWS:</b><br><br>
                    (a) The Headline or heading of advertisement should be printed in type face size not exceeding 14
                    points.<br><br>
                    (b) Sub-heading of an advertisement should not exceed '12' points type face size.<br><br>
                    (c) The content of advertisement except the headlines or heading/Sub-heading should not exceed 12
                    point
                    type face size.<br><br>
                    (d) Spacing between the 'Heading' or 'headings' and the contents of advertisement or between its
                    paragraph(s) or between paragraph and the designation of the issuing authority should not exceed 3
                    point
                    lead.<br><br>
                    (e) The Standard width of the advertisement column should not be less than 4.5 centimetres.<br><br>
                    (f) The advertisement if published in ___ column(s) should not exceed ___
                    centimeters.<br>
                </td>
            </tr>
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
                            for payment and should be routed through DIPR for verification of the rate of advertisement
                            as
                            approved by the Government from time to time.
                        </li>
                    </ol>
                </td>
            </tr><br>
            <tr>
                <td></td>
                <td colspan="2" style="text-align: right;">
                    <div style="display: inline-block; text-align: center;">
                        <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                    </div>
                </td>
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
            </tr><br>
            <tr>
                <td class="border-box">
                    <div class="bordered-text">
                        {{ $advertisement->department->dept_name }}
                    </div>
                </td>
                <td colspan="2" style="text-align: right;">
                    <div style="display: inline-block; text-align: center;">
                        <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                    </div>
                </td>
            </tr>
        </table>

        </body>
        @endfor
        {{-- End of Office & Dept Copy --}}
        {{-- End of Print --}}


        {{-- For Electronic Media --}}
        @elseif($advertisement->advertisement_type_id == 6)
        @foreach($groupedAssignedNews as $empanelledId => $newsGroup)

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
                                No. M /Advt./<b>{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{
                                    $advertisement->mipr_no
                                    }}</b>
                            </span>
                            <span style="float: right;">
                                Dated Shillong, the <b>{{ Carbon::parse($advertisement->issue_date)->format('jS F, Y')
                                    }}</b>
                            </span>
                        </div>
                    </td>
                </tr><br>
                <tr>
                    <td colspan="2">To,</td>
                </tr>
                <tr>
                    <td colspan="2">The Editor/Advertisement Manager:
                        <b>{{ $newsGroup->first()->empanelled->news_name }}</b><br>

                    </td>
                </tr>
                <tr>
                    <td style="width: 30%; vertical-align: top;">

                        <div class="center-box" style="margin-top:20px">
                            <div class="inner-box">
                                <b>To be broadcasted <br>Positively on<br>
                                    @foreach($newsGroup->pluck('positively_on') as $date)
                                    {{ \Carbon\Carbon::parse($date)->format('jS F, Y') }}
                                    @if(!$loop->last), @endif
                                    @endforeach
                                </b>
                            </div>
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
                        <b>THE ADVERTISEMENT SHOULD BE BROADCASTED AS FOLLOWS:</b><br><br>
                        (a) The Advertisement for Electronic Media should be broadcasted for
                        <b>{{ $newsGroup->first()->seconds }} seconds</b><br>
                    </td>
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
                </tr><br>
                <tr>
                    <td></td>
                    <td colspan="2" style="text-align: right;">
                        <div style="display: inline-block; text-align: center;">
                            <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                        </div>
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
                </tr><br>
                <tr>
                    <td class="border-box">
                        <div class="bordered-text">
                            {{ $advertisement->department->dept_name}}
                        </div>
                    </td>
                    <td colspan="2" style="text-align: right;">
                        <div style="display: inline-block; text-align: center;">
                            <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                        </div>
                    </td>
                </tr>
            </table>

        </body>
        @endforeach

        {{-- Office & Department Copy --}}
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
                    <td colspan="2" style="width: 100%;">
                        <div style="width: 100%; position: relative;">
                            <span style="float: left;">
                                No. M /Advt./<b>{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{
                                    $advertisement->mipr_no
                                    }}</b>
                            </span>
                            <span style="float: right;">
                                Dated Shillong, the <b>{{ Carbon::parse($advertisement->issue_date)->format('jS F, Y')
                                    }}</b>
                            </span>
                        </div>
                    </td>
                </tr><br>
                <tr>
                    <td colspan="2">To,</td>
                </tr>
                <tr>
                    <td colspan="2">The Editor/Advertisement Manager:
                        ..............................................................................................................<br>
                    </td>
                </tr>
                <tr>
                    <td style="width: 40%; vertical-align: top;">
                        @php
                        $groupedNews = $advertisement->assigned_news->groupBy('positively_on');
                        @endphp

                        @foreach($groupedNews as $date => $newsGroup)
                        <div style="margin-top: 15px">
                            @foreach($newsGroup as $news)
                            <div>
                                <b><i style="font-size: 12px;">{{ $news->empanelled->news_name }}</i></b>,
                                <span style="font-size: 12px;">({{ $news->seconds }}sec)</span><br>
                            </div>
                            @endforeach
                            <div class="center-box" style="margin-top: 5px;">
                                <div class="inner-box">
                                    <span style="font-size: 12px;"><b>To be broadcasted positively on <br> {{
                                            Carbon::parse($date)->format('jS F,
                                            Y') }}</br></span>
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

                    <td style="width: 60%; vertical-align: top; padding-left: 10px;">
                        <b>THE ADVERTISEMENT SHOULD BE BROADCASTED AS FOLLOWS:</b><br><br>
                        (a) The Advertisement for Electronic Media should be broadcasted for
                        ___ seconds<br>
                    </td>
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
                </tr><br>
                <tr>
                    <td></td>
                    <td colspan="2" style="text-align: right;">
                        <div style="display: inline-block; text-align: center;">
                            <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                        </div>
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
                </tr><br>
                <tr>
                    <td class="border-box">
                        <div class="bordered-text">
                            {{ $advertisement->department->dept_name}}
                        </div>
                    </td>
                    <td colspan="2" style="text-align: right;">
                        <div style="display: inline-block; text-align: center;">
                            <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                        </div>
                    </td>
                </tr>
            </table>
            </body>
            @endfor
            {{-- End of Office & Dept Copy --}}
            {{-- End of Electronic Media --}}

            {{-- For Online Media --}}
            @elseif($advertisement->advertisement_type_id == 8)
            @foreach($groupedAssignedNews as $empanelledId => $newsGroup)

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
                                    No. M /Advt./<b>{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{
                                        $advertisement->mipr_no
                                        }}</b>
                                </span>
                                <span style="float: right;">
                                    Dated Shillong, the <b>{{ Carbon::parse($advertisement->issue_date)->format('jS F,
                                        Y')
                                        }}</b>
                                </span>
                            </div>
                        </td>
                    </tr><br>
                    <tr>
                        <td colspan="2">To,</td>
                    </tr>
                    <tr>
                        <td colspan="2">The Editor/Advertisement Manager:
                            <b>{{ $newsGroup->first()->empanelled->news_name }}</b><br>

                        </td>
                    </tr>
                    <tr>
                        <td style="width: 30%; vertical-align: top;">

                            <div class="center-box" style="margin-top:20px">
                                <div class="inner-box">
                                    <b>To be broadcasted <br>Positively on<br>
                                        @php
                                        $dates = $newsGroup->pluck('positively_on')->sort(); // Ensure dates are sorted
                                        $formattedDates = $dates->map(function($date) {
                                        return \Carbon\Carbon::parse($date)->format('jS F, Y');
                                        });

                                        // Check if dates are consecutive
                                        $areConsecutive = true;
                                        if ($dates->count() > 1) {
                                        $datesArray = $dates->map(fn($date) => \Carbon\Carbon::parse($date))->values();
                                        foreach ($datesArray as $index => $date) {
                                        if ($index > 0 && !$date->isSameDay($datesArray[$index - 1]->copy()->addDay()))
                                        {
                                        $areConsecutive = false;
                                        break;
                                        }
                                        }
                                        }
                                        @endphp
                                        {!! $formattedDates->implode(',<br>') !!}
                                    </b>
                                    <br>
                                    <b>
                                        @if ($dates->count() > 1)
                                        @if ($areConsecutive)
                                        (@Rs.{{ $amount }} per day and 25% for the following days inclusive of all
                                        Taxes)
                                        @else
                                        (@Rs.{{ $amount }} per day inclusive of all Taxes)
                                        @endif
                                        @else
                                        (@Rs.{{ $amount }} inclusive of all Taxes)
                                        @endif
                                    </b>
                                </div>
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
                            <b>THE ADVERTISEMENT SHOULD BE BROADCASTED AS FOLLOWS:</b><br><br>
                            (a) The Advertisement should be uploaded/broadcasted by the
                            <b><i><u>Electronic Media</u></i></b> in type face size not exceeding 14 points.<br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b><u>N.B.</u>:-</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ol>
                                <li>Bills for <b><i><u>Government Departments</u></i></b> should be submitted to
                                    Directorate
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
                    </tr><br>
                    <tr>
                        <td></td>
                        <td colspan="2" style="text-align: right;">
                            <div style="display: inline-block; text-align: center;">
                                <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">Copy to the:-</td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ol>
                                <li>{{$advertisement->department->dept_name}} for information and necessary action. This
                                    has
                                    a
                                    reference to letter No.
                                    <b><i>{{ $advertisement->ref_no }} Dated {{
                                            \Carbon\Carbon::parse($advertisement->ref_date)->format('d.m.Y') }}</i></b>
                                </li>
                                <li>Advertisement section, for information and necessary action.</li>
                            </ol>
                        </td>
                    </tr><br>
                    <tr>
                        <td class="border-box">
                            <div class="bordered-text">
                                {{ $advertisement->department->dept_name}}
                            </div>
                        </td>
                        <td colspan="2" style="text-align: right;">
                            <div style="display: inline-block; text-align: center;">
                                <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                            </div>
                        </td>
                    </tr>
                </table>

            </body>
            @endforeach

            {{-- Office & Department Copy --}}
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
                        <td colspan="2" style="width: 100%;">
                            <div style="width: 100%; position: relative;">
                                <span style="float: left;">
                                    No. M /Advt./<b>{{ $miprFileNo->mipr_file_no }}/{{ date('y') }}/{{
                                        $advertisement->mipr_no
                                        }}</b>
                                </span>
                                <span style="float: right;">
                                    Dated Shillong, the <b>{{ Carbon::parse($advertisement->issue_date)->format('jS F,
                                        Y')
                                        }}</b>
                                </span>
                            </div>
                        </td>
                    </tr><br>
                    <tr>
                        <td colspan="2">To,</td>
                    </tr>
                    <tr>
                        <td colspan="2">The Editor/Advertisement Manager:
                            ..............................................................................................................<br>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 40%; vertical-align: top;">
                            @php
                            $groupedNews = $advertisement->assigned_news->groupBy('positively_on');
                            @endphp

                            @foreach($groupedNews as $date => $newsGroup)
                            <div style="margin-top: 15px">
                                @foreach($newsGroup as $news)
                                <div>
                                    <b><i style="font-size: 12px;">{{ $news->empanelled->news_name }}</i></b>,<br>
                                </div>
                                @endforeach
                                <div class="center-box" style="margin-top: 5px;">
                                    <div class="inner-box">
                                        <span style="font-size: 12px;"><b>To be broadcasted positively on <br>{{
                                                Carbon::parse($date)->format('jS F,
                                                Y') }}</br></span>
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

                        <td style="width: 60%; vertical-align: top; padding-left: 10px;">
                            <b>THE ADVERTISEMENT SHOULD BE BROADCASTED AS FOLLOWS:</b><br><br>
                            (a) The Advertisement should be uploaded/broadcasted by the
                            <b><i><u>Electronic Media</u></i></b> in type face size not exceeding 14 points.<br>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><b><u>N.B.</u>:-</b></td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <ol>
                                <li>Bills for <b><i><u>Government Departments</u></i></b> should be submitted to
                                    Directorate
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
                    </tr><br>
                    <tr>
                        <td></td>
                        <td colspan="2" style="text-align: right;">
                            <div style="display: inline-block; text-align: center;">
                                <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                            </div>
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
                    </tr><br>
                    <tr>
                        <td class="border-box">
                            <div class="bordered-text">
                                {{ $advertisement->department->dept_name}}
                            </div>
                        </td>
                        <td colspan="2" style="text-align: right;">
                            <div style="display: inline-block; text-align: center;">
                                <i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.
                            </div>
                        </td>
                    </tr>
                </table>
                </body>
                @endfor
                {{-- End of Office & Dept Copy --}}
                @endif
                {{-- End of check for advertisement type --}}
                @endif
                {{-- End of check if the advertisement is a message --}}

</html>