<?php

namespace App\Http\Controllers;

use App\Exports\BillingRegisterExport;
use App\Exports\IssueRegisterExport;
use App\Exports\NonDIPRBillingRegisterExport;
use Illuminate\Support\Facades\Log;
use App\Models\Advertisement;
use App\Models\Amount;
use App\Models\Bill;
use App\Models\Department;
use App\Models\DepartmentCategory;
use App\Models\Empanelled;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\ReleaseOrderNo;
use App\Models\MiprFileNo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

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

        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type', 'department', 'subject'])
            ->whereBetween('advertisement.issue_date', [$fromDate, $toDate])
            ->orderBy('advertisement.id')
            ->get();

        foreach ($advertisements as $advertisement) {
            $advertisement->issue_date = \Carbon\Carbon::parse($advertisement->issue_date);

            // Group assigned news by newspaper
            $advertisement->grouped_rows = $advertisement->assigned_news->groupBy(function ($news) {
                return $news->empanelled->news_name;
            })->map(function ($group, $newspaper) {
                $positivelyOnDates = $group->pluck('positively_on')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d-m-Y'))->implode(', ');

                $sizes = $group->map(function ($news) {
                    if (!empty($news->cm) && !empty($news->columns)) {
                        return "{$news->cm}x{$news->columns}";
                    } elseif (!empty($news->seconds)) {
                        return "{$news->seconds}s";
                    }
                    return '';
                })->unique()->implode(', ');

                return [
                    'newspaper' => $newspaper,
                    'positively_on' => $positivelyOnDates,
                    'no_of_insertions' => $group->count(),
                    'sizes' => $sizes,
                ];
            });
        }

        $pdf = PDF::loadView('reports.issue_register', compact('advertisements'));

        return $pdf->stream('issue_register.pdf', array('Attachment' => 0));
    }


    public function exportIssueRegisterToExcel(Request $request, $from, $to)
    {
        $fromDate = \Carbon\Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d');
        $toDate = \Carbon\Carbon::createFromFormat('d-m-Y', $to)->format('Y-m-d');

        // Fetch advertisements with relationships
        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type', 'department', 'subject'])
            ->whereBetween('advertisement.issue_date', [$fromDate, $toDate])
            ->orderBy('advertisement.id')
            ->get();

        // Debug: Log advertisements data
        \Log::info('Advertisements Data:', $advertisements->toArray());

        // Flatten advertisement data for Excel
        $flattenedData = [];

        foreach ($advertisements as $advertisement) {
            // Group assigned news by newspaper
            $groupedNews = $advertisement->assigned_news->groupBy(function ($news) {
                return $news->empanelled->news_name ?? 'Unknown Newspaper';
            });

            foreach ($groupedNews as $newspaper => $group) {
                // Initialize size_seconds
                $size_seconds = '';

                foreach ($group as $assignedNews) {
                    // Debug: Log assigned news data
                    \Log::info('Assigned News Data:', $assignedNews->toArray());

                    // Calculate size_seconds based on available data
                    if (!empty($assignedNews->cm) && !empty($assignedNews->columns)) {
                        $size_seconds = $assignedNews->cm . ' x ' . $assignedNews->columns;
                    } elseif (!empty($assignedNews->seconds)) {
                        $size_seconds = $assignedNews->seconds . ' s';
                    } else {
                        $size_seconds = ''; // Fallback value
                    }
                }

                // Add flattened data for Excel
                $flattenedData[] = [
                    'mipr_no' => $advertisement->mipr_no,
                    'issue_date' => \Carbon\Carbon::parse($advertisement->issue_date)->format('d-m-Y'),
                    'dept_name' => $advertisement->department->dept_name ?? 'Unknown Department',
                    'size_seconds' => $size_seconds,
                    'subject' => $advertisement->subject->subject_name ?? '',
                    'ref_no_date' => $advertisement->ref_no . ' Dt. ' . \Carbon\Carbon::parse($advertisement->ref_date)->format('d-m-Y'),
                    'newspaper' => $newspaper,
                    'positively_on' => $group->pluck('positively_on')->map(fn($date) => \Carbon\Carbon::parse($date)->format('d-m-Y'))->implode(', '),
                    'no_of_insertions' => $group->count(),
                    'remarks' => $advertisement->remarks ?? '',
                ];
            }
        }

        // Debug: Log the flattened data
        \Log::info('Flattened Data:', $flattenedData);

        // Convert flattened data to a collection and pass it to the export class
        $flattenedData = collect($flattenedData);

        // Return Excel download response
        return Excel::download(new IssueRegisterExport($flattenedData), 'issue_register.xlsx');
    }




    public function ViewIssueRegister()
    {
        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type', 'department'])->get();

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


        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type', 'subject', 'department'])
            ->whereBetween('advertisement.issue_date', [$fromDate, $toDate])
            ->get();

        return response()->json($advertisements)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    // BILLING REGISTER
    public function indexBillingRegister()
    {
        $role = Auth::user()->role->role_name;
        $departments = Department::all();
        $newspapers = Empanelled::all();
        return view('modules/reports/billing_register')->with(compact('role', 'newspapers', 'departments'));
    }

    public function printBillingRegister(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $departmentId = $request->department;
        $newspaperId = $request->newspaper;

        // Fetch distinct rows for newspapers and advertisements
        $bills = Bill::select('b.created_at', 'b.ad_id', 'b.empanelled_id', 'b.id', 'd.dept_name', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date', \DB::raw('CAST(b.total_amount AS NUMERIC(15,2)) AS total_amount'), 'a.payment_by', 'a.mipr_no', 'a.issue_date')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->join('department as d', 'd.id', '=', 'a.department_id')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('b.bill_date', [\Carbon\Carbon::parse($from), \Carbon\Carbon::parse($to)]);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('a.department_id', $departmentId);
            })
            ->when($newspaperId, function ($query, $newspaperId) {
                return $query->where('b.empanelled_id', $newspaperId);
            })
            ->where('a.payment_by', 'D')
            ->orderBy('b.bill_date')
            ->distinct()
            ->get();

        // Group sizes for each bill entry
        foreach ($bills as $bill) {
            $bill->created_at = \Carbon\Carbon::parse($bill->created_at);
            $bill->issue_date = \Carbon\Carbon::parse($bill->issue_date);
            $bill->bill_date = \Carbon\Carbon::parse($bill->bill_date);
            $bill->total_amount = number_format($bill->total_amount, 2);

            // Fetch and group sizes for the current bill
            $sizes = \DB::table('assigned_news as an')
                ->select('an.columns', 'an.cm', 'an.seconds')
                ->where('an.advertisement_id', $bill->ad_id)
                ->where('an.empanelled_id', $bill->empanelled_id)
                ->get()
                ->map(function ($size) {
                    if (!empty($size->cm) && !empty($size->columns)) {
                        return $size->cm . 'x' . $size->columns;
                    } elseif (!empty($size->seconds)) {
                        return $size->seconds . ' seconds';
                    }
                    return null;
                })
                ->filter()
                ->implode('<br>');


            $bill->sizes = $sizes;
        }

        $grandTotalAmount = $bills->sum(function ($bill) {
            return (float) str_replace(',', '', $bill->total_amount);
        });

        // Load the PDF view
        $pdf = PDF::loadView('reports.billing_register', compact('bills', 'grandTotalAmount'));

        return $pdf->stream('billing_register.pdf', array('Attachment' => 0));
    }



    public function exportBillingRegisterToExcel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $departmentId = $request->department;
        $newspaperId = $request->newspaper;

        // Fetch distinct rows for newspapers and advertisements
        $bills = Bill::select('b.created_at', 'b.ad_id', 'b.empanelled_id', 'b.id', 'd.dept_name', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date', \DB::raw('CAST(b.total_amount AS NUMERIC(15,2)) AS total_amount'), 'a.payment_by', 'a.mipr_no', 'a.issue_date')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->join('department as d', 'd.id', '=', 'a.department_id')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('b.bill_date', [\Carbon\Carbon::parse($from), \Carbon\Carbon::parse($to)]);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('a.department_id', $departmentId);
            })
            ->when($newspaperId, function ($query, $newspaperId) {
                return $query->where('b.empanelled_id', $newspaperId);
            })
            ->where('a.payment_by', 'D')
            ->orderBy('b.bill_date')
            ->distinct()
            ->get();

        // Group sizes for each bill entry
        foreach ($bills as $bill) {
            $bill->created_at = \Carbon\Carbon::parse($bill->created_at);
            $bill->issue_date = \Carbon\Carbon::parse($bill->issue_date);
            $bill->bill_date = \Carbon\Carbon::parse($bill->bill_date);
            $bill->total_amount = number_format($bill->total_amount, 2);

            // Fetch and group sizes for the current bill
            $sizes = \DB::table('assigned_news as an')
                ->select('an.columns', 'an.cm', 'an.seconds')
                ->where('an.advertisement_id', $bill->ad_id)
                ->where('an.empanelled_id', $bill->empanelled_id)
                ->get()
                ->map(function ($size) {
                    if (!empty($size->cm) && !empty($size->columns)) {
                        return $size->cm . 'x' . $size->columns;
                    } elseif (!empty($size->seconds)) {
                        return $size->seconds;
                    }
                    return null;
                })
                ->filter()
                ->implode(', ');

            $bill->sizes = $sizes;
        }

        // Calculate grand total
        $grandTotalAmount = $bills->sum(function ($bill) {
            return (float) str_replace(',', '', $bill->total_amount);
        });

        // Pass data to Excel export
        return Excel::download(new BillingRegisterExport($bills, $grandTotalAmount), 'billing_register.xlsx');
    }



    public function ViewBillingRegister()
    {
        $bills = Bill::with(['advertisement.department', 'advertisement.subject', 'empanelled'])
            ->whereHas('advertisement', function ($query) {
                $query->where('payment_by', 'D');
            })
            ->orderBy('bill_date', 'desc')
            ->get();

        return response()->json($bills)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }




    public function GetBillingRegister(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $departmentId = $request->department;
        $newspaperId = $request->newspaper;

        $bills = Bill::with(['advertisement.department', 'advertisement.subject', 'empanelled'])
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('bill_date', [\Carbon\Carbon::parse($from), \Carbon\Carbon::parse($to)]);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->whereHas('advertisement', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                });
            })
            ->when($newspaperId, function ($query, $newspaperId) {
                return $query->where('empanelled_id', $newspaperId);
            })
            ->whereHas('advertisement', function ($query) {
                $query->where('payment_by', 'D');
            })
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
        $departments = Department::all();
        $newspapers = Empanelled::all();
        return view('modules/reports/non_DIPR_register')->with(compact('role', 'newspapers', 'departments'));
    }

    public function printNonDIPRRegister(Request $request, $from, $to)
    {
        $from = $request->from;
        $to = $request->to;
        $departmentId = $request->department;
        $newspaperId = $request->newspaper;

        $bills = Bill::select('b.created_at', 'b.ad_id', 'b.empanelled_id', 'b.id', 'd.dept_name', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date', \DB::raw('CAST(b.total_amount AS NUMERIC(15,2)) AS total_amount'), 'a.payment_by', 'a.mipr_no', 'a.issue_date')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->join('department as d', 'd.id', '=', 'a.department_id')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('b.bill_date', [\Carbon\Carbon::parse($from), \Carbon\Carbon::parse($to)]);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('a.department_id', $departmentId);
            })
            ->when($newspaperId, function ($query, $newspaperId) {
                return $query->where('b.empanelled_id', $newspaperId);
            })
            ->where('a.payment_by', 'C')
            ->orderBy('b.bill_date')
            ->distinct()
            ->get();

        // Group sizes for each bill entry
        foreach ($bills as $bill) {
            $bill->created_at = \Carbon\Carbon::parse($bill->created_at);
            $bill->issue_date = \Carbon\Carbon::parse($bill->issue_date);
            $bill->bill_date = \Carbon\Carbon::parse($bill->bill_date);
            $bill->total_amount = number_format($bill->total_amount, 2);

            // Fetch and group sizes for the current bill
            $sizes = \DB::table('assigned_news as an')
                ->select('an.columns', 'an.cm', 'an.seconds')
                ->where('an.advertisement_id', $bill->ad_id)
                ->where('an.empanelled_id', $bill->empanelled_id)
                ->get()
                ->map(function ($size) {
                    if (!empty($size->cm) && !empty($size->columns)) {
                        return $size->cm . 'x' . $size->columns;
                    } elseif (!empty($size->seconds)) {
                        return $size->seconds . ' seconds';
                    }
                    return null;
                })
                ->filter()
                ->implode('<br>');


            $bill->sizes = $sizes;
        }

        $grandTotalAmount = $bills->sum(function ($bill) {
            return (float) str_replace(',', '', $bill->total_amount);
        });

        // Load the PDF view
        $pdf = PDF::loadView('reports.non_DIPR_register', compact('bills', 'grandTotalAmount'));

        return $pdf->stream('non_DIPR_register.pdf', array('Attachment' => 0));
    }

    public function exportNonDIPRRegisterToExcel(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $departmentId = $request->department;
        $newspaperId = $request->newspaper;

        // Fetch distinct rows for newspapers and advertisements
        $bills = Bill::select('b.created_at', 'b.ad_id', 'b.empanelled_id', 'b.id', 'd.dept_name', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date', \DB::raw('CAST(b.total_amount AS NUMERIC(15,2)) AS total_amount'), 'a.payment_by', 'a.mipr_no', 'a.issue_date')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->join('department as d', 'd.id', '=', 'a.department_id')
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('b.bill_date', [\Carbon\Carbon::parse($from), \Carbon\Carbon::parse($to)]);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->where('a.department_id', $departmentId);
            })
            ->when($newspaperId, function ($query, $newspaperId) {
                return $query->where('b.empanelled_id', $newspaperId);
            })
            ->where('a.payment_by', 'C')
            ->orderBy('b.bill_date')
            ->distinct()
            ->get();

        // Group sizes for each bill entry
        foreach ($bills as $bill) {
            $bill->created_at = \Carbon\Carbon::parse($bill->created_at);
            $bill->issue_date = \Carbon\Carbon::parse($bill->issue_date);
            $bill->bill_date = \Carbon\Carbon::parse($bill->bill_date);
            $bill->total_amount = number_format($bill->total_amount, 2);

            // Fetch and group sizes for the current bill
            $sizes = \DB::table('assigned_news as an')
                ->select('an.columns', 'an.cm', 'an.seconds')
                ->where('an.advertisement_id', $bill->ad_id)
                ->where('an.empanelled_id', $bill->empanelled_id)
                ->get()
                ->map(function ($size) {
                    if (!empty($size->cm) && !empty($size->columns)) {
                        return $size->cm . 'x' . $size->columns;
                    } elseif (!empty($size->seconds)) {
                        return $size->seconds;
                    }
                    return null;
                })
                ->filter()
                ->implode(', ');

            $bill->sizes = $sizes;
        }

        // Calculate grand total
        $grandTotalAmount = $bills->sum(function ($bill) {
            return (float) str_replace(',', '', $bill->total_amount);
        });

        // Pass data to Excel export
        return Excel::download(new NonDIPRBillingRegisterExport($bills, $grandTotalAmount), 'non_DIPR_register.xlsx');
    }

    public function ViewNonDIPRRegister()
    {
        $bills = Bill::with(['advertisement.department', 'advertisement.subject', 'empanelled'])
            ->whereHas('advertisement', function ($query) {
                $query->where('payment_by', 'C');
            })
            ->orderBy('bill_date', 'desc')
            ->get();

        return response()->json($bills)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }
    public function GetNonDIPRRegister(Request $request)
    {
        $from = $request->from;
        $to = $request->to;
        $departmentId = $request->department;
        $newspaperId = $request->newspaper;

        $bills = Bill::with(['advertisement.department', 'advertisement.subject', 'empanelled'])
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('bill_date', [\Carbon\Carbon::parse($from), \Carbon\Carbon::parse($to)]);
            })
            ->when($departmentId, function ($query, $departmentId) {
                return $query->whereHas('advertisement', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                });
            })
            ->when($newspaperId, function ($query, $newspaperId) {
                return $query->where('empanelled_id', $newspaperId);
            })
            ->whereHas('advertisement', function ($query) {
                $query->where('payment_by', 'C');
            })
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
            $releaseOrderRow = ReleaseOrderNo::where('fin_year', $fin_year)->first();
            $date = Carbon::now()->format('Y-m-d');

            if (is_null($releaseOrderRow)) {
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

            $advertisement = Advertisement::with([
                'subject',
                'assigned_news.empanelled',
                'ad_category'
            ])->find($id);

            // Group assigned_news by empanelled_id
            $groupedAssignedNews = $advertisement->assigned_news->groupBy('empanelled_id');

            $miprFileNo = MiprFileNo::latest()->first();
            $amount = Amount::where('advertisement_type_id', $advertisement->advertisement_type_id)->value('amount');

            DB::commit();

            $pdf = PDF::loadView('reports.release_order', compact('advertisement', 'miprFileNo', 'amount', 'groupedAssignedNews'));
            $pdfFileName = 'Release_Order_MIPR_' . $advertisement->mipr_no . '.pdf';
            return $pdf->stream($pdfFileName, ['Content-Type' => 'application/pdf', 'Content-Disposition' => 'inline; filename="' . $pdfFileName . '"']);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error in releaseOrder: ', ['exception' => $th]);
            return response()->json(['error' => 'Failed to release order.'], 500);
        }
    }




    public function forwardingLetter($id)
    {
        $newspaperName = request()->query('newspaper');
        $bill = Bill::with(['advertisement'])->find($id);


        $bill->advertisement->ref_date = Carbon::parse($bill->advertisement->ref_date);
        $bill->bill_date = Carbon::parse($bill->bill_date);

        $totalAmount = $bill->total_amount;

        $words = $this->NumberToWords($totalAmount);

        $miprFileNo = MiprFileNo::latest()->first();

        $pdf = PDF::loadView('reports.forwarding_letter', compact('bill', 'words', 'miprFileNo', 'newspaperName', 'totalAmount'));
        $pdfFileName = 'Forwarding_Letter' . $bill->id . '.pdf';

        return $pdf->stream($pdfFileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $pdfFileName . '"'
        ]);
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

        // Ensure $x is treated as a float
        $x = (float)$x;

        // Extract integer and fractional parts
        $integerPart = floor($x);
        $fractionPart = $x - $integerPart;

        $nwords = array(
            "Zero",
            "One",
            "Two",
            "Three",
            "Four",
            "Five",
            "Six",
            "Seven",
            "Eight",
            "Nine",
            "Ten",
            "Eleven",
            "Twelve",
            "Thirteen",
            "Fourteen",
            "Fifteen",
            "Sixteen",
            "Seventeen",
            "Eighteen",
            "Nineteen",
            "Twenty",
            30 => "Thirty",
            40 => "Forty",
            50 => "Fifty",
            60 => "Sixty",
            70 => "Seventy",
            80 => "Eighty",
            90 => "Ninety"
        );

        if ($x < 0) {
            return $negative . $this->NumberToWords(abs($x));
        }

        // Convert integer part
        if ($integerPart < 21) {
            $string = $nwords[$integerPart];
        } elseif ($integerPart < 100) {
            $string = $nwords[10 * floor($integerPart / 10)];
            $remainder = $integerPart % 10;
            if ($remainder > 0) {
                $string .= '-' . $nwords[$remainder];
            }
        } elseif ($integerPart < 1000) {
            $string = $nwords[floor($integerPart / 100)] . ' Hundred';
            $remainder = $integerPart % 100;
            if ($remainder > 0) {
                $string .= ' and ' . $this->NumberToWords($remainder);
            }
        } elseif ($integerPart < 100000) {
            $string = $this->NumberToWords(floor($integerPart / 1000)) . " Thousand";
            $remainder = $integerPart % 1000;
            if ($remainder > 0) {
                $string .= ' ' . $this->NumberToWords($remainder);
            }
        } elseif ($integerPart < 10000000) {
            $string = $this->NumberToWords(floor($integerPart / 100000)) . ' Lakh';
            $remainder = $integerPart % 100000;
            if ($remainder > 0) {
                $string .= ' ' . $this->NumberToWords($remainder);
            }
        } else {
            $string = $this->NumberToWords(floor($integerPart / 10000000)) . ' Crore';
            $remainder = $integerPart % 10000000;
            if ($remainder > 0) {
                $string .= ' ' . $this->NumberToWords($remainder);
            }
        }

        // Process fractional part
        if ($fractionPart > 0) {
            $fractionValue = round($fractionPart * 100); // Convert to whole number (e.g., 0.75 -> 75)
            $string .= ' and ' . $this->NumberToWords($fractionValue) . ' Paise';
        }

        return $string;
    }
}
