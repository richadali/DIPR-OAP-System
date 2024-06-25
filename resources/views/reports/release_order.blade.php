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
            text-align: center;
            padding: 5px;
        }

        .center-box .inner-box {
            display: inline-block;
            background-color: black;
            color: white;
            padding: 5px;
            border: 1px solid #000;
        }
        .border-box {
            padding: 10px;
        }

        .border-box .bordered-text {
            display: inline-block;
            border: 1px solid #000;
            padding: 5px;
        }
    </style>
</head>
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
            <td colspan="2">No. M /Advt./…………<b>{{ $advertisement->ref_no }}</b>…………………Dated Shillong, the ……<b>{{
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
            <td></td>
            <td class="just_align">(a) The Headline or heading of advertisement should be printed in type face size not
                exceeding 14 points.</td>
        </tr>
        <tr>
            <td></td>
            <td class="just_align">(b) Sub-heading of an advertisement should not exceed '12' points type face size.</td>
        </tr>
        <tr>
            <td></td>
            <td class="just_align">(c) The content of advertisement except the headlines or heading/Sub-heading should
                not exceed 12 point type face size.</td>
        </tr>
        <tr>
            <td></td>
            <td class="just_align">(d) Spacing between the 'Heading' or 'headings' and the contents of advertisement or
                between its paragraph(s) or between paragraph and the designation of the issuing authority should not
                exceed 3 point lead.</td>
        </tr>
        <tr>
            <td></td>
            <td class="just_align">(e) The Standard width of the advertisement column should not be less than 4.5
                centimetres. </td>
        </tr>
        <tr>
            <td class="center-box">
                <div class="inner-box">
                    <b>To be published <br>Positively on<br>{{ $advertisement->positively_on}}</b>
                </div>
            </td>
            <td class="just_align">(f) The advertisement if publish in a <b>single column</b> should not exceed.........
                centimeters or if publish in <b>double column</b> should not exceed ........ centimeters or if
                publish in <b>three column</b> should not exceed ........ centimeters or if publish in <b>four
                    column</b> should not exceed ......... centimeters or if publish in ..... <b>columns</b> should not
                exceed ........ centimeters.</td>
        </tr>
        <tr>
            <td></td>
            <td class="just_align">(g) The Advertisement for Electronic Media should be broadcasted for
                ...................................... <b>seconds</b>.</td>
        </tr>
        <tr>
            <td></td>
            <td class="just_align">(h) The Advertisement should be uploaded/broadcasted by the <b><i><u>Electronic
                            Media</u></i></b> in type face size not exceeding 14 points.</td>
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
                                    Agencies</u></i></b> of the Government may be addressed to the
                        ............................................................
                        .............................................. for payment
                        and should be routed through DIPR for verification of the rate of advertisement as approved by
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
                    <li>Director of Printing & Stationary, Shillong for publications of the Advertisement in Gazette of
                        Meghalaya.</li>
                    <li>Department/Office/Public Sector Undertaking/Units/Corporations/Autonomous Bodies/Other Agencies
                        concerned for information and necessary action. This has a reference to letter No.
                        <b>{{ $advertisement->dept_letter_no }}</b></li>
                    <li>Advertisement section, for information and necessary action.</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td class="border-box">
                <div class="bordered-text">
                    {{ $advertisement->hod}}
                </div>
            </td>
            <td align="center"><i>for</i> Director of Information and Public Relations,<br>Meghalaya, Shillong.</td>
        </tr>
    </table>

</body>
@endforeach
</html>