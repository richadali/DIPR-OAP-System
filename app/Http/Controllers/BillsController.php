<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillRequest;
use App\Models\Advertisement;
use App\Models\Amount;
use App\Models\Bill;
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
        // $advertisements = Advertisement::with(['assigned_news.empanelled'])->where('user_id', $user->id)->get();
        $advertisementsQuery = Advertisement::with(['assigned_news.empanelled']);
        if ($role !== 'Admin') {
            $advertisementsQuery->where('user_id', $user->id);
        }
        $advertisements = $advertisementsQuery->get();
        return view('modules.bills.bills')->with(compact('role', 'advertisements'));
    }
    public function ViewContent(Request $request)
    {
        $role   = Auth::user()->role->role_name;
        $user = Auth::user();
        $billsQuery  = Bill::select('b.id', 'a.hod', 'e.news_name', 'a.release_order_no', 'a.release_order_date', 'b.bill_no', 'b.bill_date', 'a.amount', 'b.paid_by')
            ->from('bills as b')
            ->join('advertisement as a', 'a.id', '=', 'b.ad_id')
            ->join('assigned_news as an', function ($join) {
                $join->on('an.advertisement_id', '=', 'a.id');
                $join->on('an.empanelled_id', '=', 'b.empanelled_id');
            })
            ->join('empanelled as e', 'e.id', '=', 'b.empanelled_id');

        // Apply condition based on user role
        if ($role !== 'Admin') {
            $billsQuery->where('a.user_id', $user->id);
        }

        // Execute the query
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
            return response()->json(['dept_letter_no' => $advertisement->dept_letter_no]);
        } else {
            return response()->json(['dept_letter_no' => null], 404);
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
                            'paid_by' => ($request->paid_by),
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
                    $bill->paid_by = $request->paid_by;
                    $bill->empanelled_id = $request->empanelled_id;
                    $bill->user_id = $user->id;
                    $bill->save();

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
        $bills = Bill::with('advertisement')
            ->where('bills.id', '=', $request->id)
            ->where('bills.user_id', $user->id)
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
        $size = $request->size;
        $category = $request->category;

        $amount = Amount::select('amount')
            ->where('ad_category_id', $category)
            ->get();

        $total_amount = $amount[0]->amount * $size;
        return response($total_amount);
    }
}
