<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Models\Advertisement;
use App\Models\Amount;
use App\Models\Bill;
use App\Models\GstRate;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BillsController extends Controller
{
    public function index()
    {
        $role   = Auth::user()->role->role_name;
        $user = Auth::user();
        $gstRates = GstRate::all();
        $advertisementsQuery = Advertisement::with(['assigned_news.empanelled']);
        // if ($role !== 'Advertisement') {
        //     $advertisementsQuery->where('user_id', $user->id);
        // }
        $advertisements = $advertisementsQuery->get();
        return view('modules.bills.bills')->with(compact('role', 'advertisements', 'gstRates'));
    }

    public function ViewContent(Request $request)
    {
        $role = Auth::user()->role->role_name;
        $user = Auth::user();

        $billsQuery = Bill::with(['advertisement.department', 'empanelled'])
            ->select('b.id', 'd.dept_name', 'e.news_name', 'a.mipr_no', 'a.issue_date', 'b.bill_no', 'b.bill_date', 'a.amount', 'a.payment_by')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id')
            ->join('department as d', 'd.id', '=', 'a.department_id');

        // if ($role !== 'Advertisement') {
        //     $billsQuery->where('a.user_id', $user->id);
        // }

        $billsQuery->orderBy('b.created_at', 'desc');

        $bills = $billsQuery->get();

        return response()->json($bills)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }



    public function getDeptLetterNo(Request $request)
    {
        $adId = $request->ad_id;
        $advertisement = Advertisement::find($adId);

        if ($advertisement) {
            return response()->json(['ref_no' => $advertisement->dept_letter_no]);
        } else {
            return response()->json(['ref_no' => null], 404);
        }
    }

    public function getNewspaper(Request $request)
    {

        $ad_id = $request->ad_id;
        $advertisement = Advertisement::with(['assigned_news.empanelled'])
            ->find($ad_id);

        return response()->json($advertisement)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function getBillDetails(Request $request)
    {
        $ad_id = $request->ad_id;
        $empanelled_id = $request->empanelled_id;
        $bill = Bill::where('bills.ad_id', $ad_id)
            ->where('bills.empanelled_id', $empanelled_id)
            ->get();

        return response()->json($bill)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }
    public function StoreData(StoreBillRequest $request)
    {

        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $user = Auth::user();
            if (isset($request->id)) {   //Edit a Bill
                $sql_count = Bill::where('id', $request->id)->count();

                if ($sql_count > 0) {
                    try {
                        DB::beginTransaction();

                        Bill::whereId($request->id)->update([
                            'bill_no' => ($request->bill_no),
                            'bill_date' => ($request->bill_date),
                            'user_id' => $user->id
                        ]);
                        DB::commit();
                        return response()->json(["flag" => "YY"]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        return response()->json(["flag" => "NN"]);
                    }
                } else {
                    return response()->json(["flag" => "NN"]);
                }
            } else {    //Create new Bill
                try {
                    DB::beginTransaction();
                    $bill = new Bill();
                    $bill->bill_no = $request->bill_no;
                    $bill->bill_date = $request->bill_date;
                    $bill->ad_id = $request->ad_id;
                    $bill->empanelled_id = $request->empanelled_id;
                    $bill->bill_memo_no = $request->bill_memo_no;
                    // Check if GST rate is "NA"
                    if ($request->gst_rate === 'NA') {
                        $bill->gst_rate = null;
                    } else {
                        $bill->gst_rate = $request->gst_rate;
                    }
                    $bill->user_id = $user->id;
                    $bill->save();

                    Advertisement::where('id', $request->ad_id)->update(['status' => 'Billed']);

                    DB::commit();
                    return response()->json(["flag" => "Y"]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json($e);
                }
            }
        }
    }

    public function ShowData(Request $request)
    {
        $user = Auth::user();
        $bills = Bill::with(['advertisement' => function ($query) {
            $query->select('id', 'amount', 'ref_no');
        }])
            ->where('bills.id', '=', $request->id)
            // ->where('bills.user_id', $user->id)
            ->get();
        return response()->json($bills);
    }

    public function DeleteData(Request $request)
    {
        try {
            DB::beginTransaction();
            $sql = Bill::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N"]);
        }
    }

    public function getAmount(Request $request)
    {
        $adId = $request->ad_id;
        $advertisement = Advertisement::find($adId);

        if ($advertisement) {
            return response()->json(['amount' => $advertisement->amount]);
        } else {
            return response()->json(['amount' => null], 404);
        }
    }
}
