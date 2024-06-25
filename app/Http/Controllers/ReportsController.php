<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Advertisement;
use App\Models\Bill;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ReleaseOrderNo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportsController extends Controller
{
    //ISSUE REGISTER
    public function indexIssueRegister()
    {
        $role   = Auth::user()->role->role_name;
        return view('modules/reports/issue_register')->with(compact('role'));
    }

    public function printIssueRegister(Request $request, $from, $to)
    {
        $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');

        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type'])
            ->whereBetween('advertisement.issue_date', [$fromDate, $toDate])
            ->orderBy('advertisement.id')
            ->get();
        foreach ($advertisements as $advertisement) {
            $advertisement->issue_date = \Carbon\Carbon::parse($advertisement->issue_date);
            // $advertisement->positively_on = \Carbon\Carbon::parse($advertisement->positively_on);
        }
        $pdf = PDF::loadView('reports.issue_register', compact('advertisements'));

        return $pdf->stream('issue_register.pdf', array('Attachment' => 0));
    }

    public function ViewIssueRegister()
    {
        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type'])->get();

        return response()->json($advertisements)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function GetIssueRegister(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');


        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type', 'subject'])
            ->whereBetween('advertisement.issue_date', [$fromDate, $toDate])
            ->get();

        return response()->json($advertisements)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    //BILLING REGISTER
    public function indexBillingRegister()
    {
        $role   = Auth::user()->role->role_name;
        return view('modules/reports/billing_register')->with(compact('role'));
    }

    public function printBillingRegister(Request $request, $from, $to)
    {
        $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');

        $bills = Bill::select('b.id', 'a.hod', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date', 'a.amount', 'b.paid_by')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('assigned_news as an', function ($join) {
                $join->on('an.advertisement_id', '=', 'a.id');
                $join->on('an.empanelled_id', '=', 'b.empanelled_id');
            })
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->whereBetween('b.bill_date', [$fromDate, $toDate])
            ->where('b.paid_by', 'D')
            ->orderBy('b.bill_date')
            ->get();
        foreach ($bills as $bill) {
            $bill->bill_date = \Carbon\Carbon::parse($bill->bill_date);
            $bill->release_order_date = \Carbon\Carbon::parse($bill->release_order_date);
            $bill->amount = number_format($bill->amount, 2);
        }
        $pdf = PDF::loadView('reports.billing_register', compact('bills'));

        return $pdf->stream('billing_register.pdf', array('Attachment' => 0));
    }

    public function ViewBillingRegister()
    {
        $bills = Bill::select('b.id', 'a.hod', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date', 'a.amount', 'b.paid_by')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('assigned_news as an', function ($join) {
                $join->on('an.advertisement_id', '=', 'a.id');
                $join->on('an.empanelled_id', '=', 'b.empanelled_id');
            })
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->where('b.paid_by', 'D')
            ->get();
        foreach ($bills as $bill) {
            $bill->amount = number_format($bill->amount, 2);
        }
        return response()->json($bills)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }
    public function GetBillingRegister(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');


        $bills = Bill::with(['advertisement.assigned_news.empanelled'])
            ->whereBetween('bills.bill_date', [$fromDate, $toDate])
            ->where('bills.paid_by', 'D')
            ->get();

        return response()->json($bills)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    //NON DIPR BILL REGISTER
    public function indexNonDIPRRegister()
    {
        $role   = Auth::user()->role->role_name;
        return view('modules/reports/non_DIPR_register')->with(compact('role'));
    }

    public function printNonDIPRRegister(Request $request, $from, $to)
    {
        $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');

        $bills = Bill::select('b.id', 'a.hod', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date',  'a.amount', 'b.paid_by')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('assigned_news as an', function ($join) {
                $join->on('an.advertisement_id', '=', 'a.id');
                $join->on('an.empanelled_id', '=', 'b.empanelled_id');
            })
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->whereBetween('b.bill_date', [$fromDate, $toDate])
            ->where('b.paid_by', 'O')
            ->orderBy('b.bill_date')
            ->get();
        foreach ($bills as $bill) {
            $bill->bill_date = \Carbon\Carbon::parse($bill->bill_date);
            $bill->release_order_date = \Carbon\Carbon::parse($bill->release_order_date);
            $bill->amount = number_format($bill->amount, 2);
        }
        $pdf = PDF::loadView('reports.non_DIPR_register', compact('bills'));

        return $pdf->stream('billing_register.pdf', array('Attachment' => 0));
    }

    public function ViewNonDIPRRegister()
    {
        $bills = Bill::select('b.id', 'a.hod', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date','a.amount', 'b.paid_by')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('assigned_news as an', function ($join) {
                $join->on('an.advertisement_id', '=', 'a.id');
                $join->on('an.empanelled_id', '=', 'b.empanelled_id');
            })
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->where('paid_by', '<>', 'D')
            ->get();
        foreach ($bills as $bill) {
            $bill->amount = number_format($bill->amount, 2);
        }

        return response()->json($bills)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }
    public function GetNonDIPRRegister(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');


        $bills = Bill::with(['advertisement.assigned_news.empanelled'])
            ->whereBetween('bills.bill_date', [$fromDate, $toDate])
            ->where('paid_by', '<>', 'D')
            ->get();


        return response()->json($bills)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    //RELEASE ORDER

    // public function releaseOrder($id)
    // {
    //     $releaseObj = ReleaseOrderNo::all();
    //     $fin_year = $this->getCurrentFinancialYear();
    //     $advertisement = Advertisement::findOrFail($id);
    //     DB::beginTransaction();
    //     try {
    //         if ($releaseObj->isEmpty()) {
    //             $RONo = 1;
    //             DB::INSERT("INSERT INTO release_order_no VALUES(?, ?)", array( $RONo, $fin_year));
    //             $advertisement->update(['release_order_no' => $RONo,'release_order_date' => Carbon::parse(now())]);  
    //         } else {
    //             $releaseOrderNo = $advertisement->release_order_no;
    //             if (empty($releaseOrderNo)) {
    //                 $MaxRONo = ReleaseOrderNo::where('fin_year', $fin_year)->max('release_order_no');
    //                 $RONo = $MaxRONo + 1;
    //                 ReleaseOrderNo::where('fin_year', $fin_year)->update(['release_order_no' => $RONo]);

    //                 $advertisement->update(['release_order_no' => $RONo]);  
    //             } 

    //         }
    //         $advertisement = Advertisement::with(['subject', 'assigned_news.empanelled', 'ad_category'])->find($id);
    //         DB::commit();
    //         $pdf = PDF::loadView('reports.release_order', compact('advertisement'));
    //         $pdfFileName = 'Release_Order_' . $advertisement->id . '.pdf';
    //         return $pdf->stream($pdfFileName, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . $pdfFileName . '"']);

    //     } catch (\Throwable $th) {
    //         DB::rollback();
    //         return $th;
    //     }

    // }


    public function releaseOrder($id)
    {
        $fin_year = $this->getCurrentFinancialYear();
        $advertisement = Advertisement::findOrFail($id);
        DB::beginTransaction();
        try {
            // Check if a row exists for the current financial year
            $releaseOrderRow = ReleaseOrderNo::where('fin_year', $fin_year)->first();
            $date = Carbon::now()->format('Y-m-d');

            if (is_null($releaseOrderRow)) {
                // If no row exists, insert a new row with release order number set to 1
                $RONo = 1;
                DB::table('release_order_no')->insert([
                    'release_order_no' => $RONo,
                    'fin_year' => $fin_year
                ]);
                if (is_null($advertisement->release_order_no)) {
                    $advertisement->update([
                        'release_order_no' => $RONo,
                        'release_order_date' => $date
                    ]);
                }
            } else {
                // If a row exists, update the release order number for the advertisement
                $releaseOrderNo = $advertisement->release_order_no;
                if (is_null($releaseOrderNo)) {
                    $MaxRONo = ReleaseOrderNo::where('fin_year', $fin_year)->max('release_order_no');
                    Log::info('Max RONo Retrieved: ', ['MaxRONo' => $MaxRONo]);
                    $RONo = $MaxRONo + 1;
                    ReleaseOrderNo::where('fin_year', $fin_year)->update(['release_order_no' => $RONo]);
                    $advertisement->update([
                        'release_order_no' => $RONo,
                        'release_order_date' => $date
                    ]);
                }
            }

            // Load advertisement with related data
            $advertisement = Advertisement::with(['subject', 'assigned_news.empanelled', 'ad_category'])->find($id);
            DB::commit();

            // Generate PDF
            $pdf = PDF::loadView('reports.release_order', compact('advertisement'));
            $pdfFileName = 'Release_Order_' . $advertisement->id . '.pdf';
            return $pdf->stream($pdfFileName, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . $pdfFileName . '"']);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error in releaseOrder: ', ['exception' => $th]);
            return response()->json(['error' => 'Failed to release order.'], 500);
        }
    }





    public function forwardingLetter($id)
    {
        $bill = Bill::with(['advertisement'])->find($id);

        $bill->advertisement->ref_date = Carbon::parse($bill->advertisement->ref_date);
        $bill->bill_date = Carbon::parse($bill->bill_date);
        $words = $this->NumberToWords($bill->advertisement->amount);

        $pdf = PDF::loadView('reports.forwarding_letter', compact('bill', 'words'));
        $pdfFileName = 'Forwarding_Letter' . $bill->id . '.pdf';
        return $pdf->stream($pdfFileName, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . $pdfFileName . '"']);
    }
    public function getCurrentFinancialYear()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');
        $financialYearStart = ($currentMonth >= 4) ? $currentYear : ($currentYear - 1);
        $financialYearEnd = $financialYearStart + 1;
        return $financialYearStart . '-' . $financialYearEnd;
    }

    public function NumberToWords($x)
    {

        $negative = 'negative ';
        $string = $fraction = null;

        if (strpos($x, '.') !== false) {

            list($x, $fraction) = explode('.', $x);
        }

        $nwords = array(
            "Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven",
            "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
            "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
            "Nineteen", "Twenty", 30 => "Thirty", 40 => "Forty",
            50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eighty",
            90 => "Ninety"
        );

        if (!is_numeric($x)) {
            $w = '#';
        } else if (fmod($x, 1) != 0) {
            $w = '#';
        } else {

            if ($x < 0) {
                return $negative . app("App\Http\Controllers\Commonfunc")->NumberToWords(abs($x));
            } else {
                $w = '';
            }

            if ($x < 21) {

                $w .= $nwords[$x];
            } else if ($x < 100) {
                $w .= $nwords[10 * floor($x / 10)];
                $r = fmod($x, 10);
                if ($r > 0) {
                    $w .= '-' . $nwords[$r];
                }
            } else if ($x < 1000) {
                $w .= $nwords[floor($x / 100)] . ' Hundred';
                $r = fmod($x, 100);
                if ($r > 0) {
                    $w .= ' and ' . $this->NumberToWords($r);
                }
            } else if ($x < 100000) {

                $w .= $this->NumberToWords(floor($x / 1000)) . " Thousand";

                $r = fmod($x, 1000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $w .= 'and ';
                    }
                    $w .= $this->NumberToWords($r);
                }
            } else if ($x < 10000000) {

                if (floor($x / 100000) == 1) {
                    $w .= $this->NumberToWords(floor($x / 100000)) . ' Lakh';
                } else {
                    $w .= $this->NumberToWords(floor($x / 100000)) . ' Lakhs';
                }

                $r = fmod($x, 100000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $w .= 'and ';
                    }
                    $w .= $this->NumberToWords($r);
                }
            } else {
                $w .= $this->NumberToWords(floor($x / 10000000)) . ' Crore(s)';
                $r = fmod($x, 10000000);
                if ($r > 0) {
                    $w .= ' ';
                    if ($r < 100) {
                        $w .= 'and ';
                    }
                    $w .= $this->NumberToWords($r);
                }
            }
        }

        $decones = array(
            '01' => "One",
            '02' => "Two",
            '03' => "Three",
            '04' => "Four",
            '05' => "Five",
            '06' => "Six",
            '07' => "Seven",
            '08' => "Eight",
            '09' => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen",
        );
        $ones = array(
            0 => " ",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen",
        );
        $tens = array(
            0 => "",
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety",
        );
        $hundreds = array(
            "Hundred",
            "Thousand",
            "Million",
            "Billion",
            "Trillion",
            "Quadrillion",
        );
        $dictionary = array(
            0 => 'Zero',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
            4 => 'Four',
            5 => 'Five',
            6 => 'Six',
            7 => 'Seven',
            8 => 'Eight',
            9 => 'Nine',
            10 => 'Ten',
            11 => 'Eleven',
            12 => 'Twelve',
            13 => 'Thirteen',
            14 => 'Fourteen',
            15 => 'Fifteen',
            16 => 'Sixteen',
            17 => 'Seventeen',
            18 => 'Eighteen',
            19 => 'Nineteen',
            20 => 'Twenty',
            30 => 'Thirty',
            40 => 'Fourty',
            50 => 'Fifty',
            60 => 'Sixty',
            70 => 'Seventy',
            80 => 'Eighty',
            90 => 'Ninety',
            100 => 'Hundred',
            1000 => 'Thousand',
            1000000 => 'Million',
            1000000000 => 'Billion',
            1000000000000 => 'Trillion',
            1000000000000000 => 'Quadrillion',
            1000000000000000000 => 'Quintillion',
        );

        if ($fraction > 0) {

            $string = " and ";

            if ($fraction < 20) {

                $string .= $decones[$fraction];
            } elseif ($fraction < 100) {

                $string .= $tens[substr($fraction, 0, 1)];

                $string .= " " . $ones[substr($fraction, 1, 1)];
            }

            $string = $string . " paise";
        } else {
            $string = ' ';
        }

        $w = $w . '' . $string;

        return $w;
    }
}
