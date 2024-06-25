<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAdRequest;
use App\Models\Amount;
use App\Models\AssignedNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Advertisement;

class AdvertisementController extends Controller
{
    public function index()
    {
        $role   = Auth::user()->role->role_name;
        return view('modules.user.advertisement')->with(compact('role'));
    }

    public function ViewContent(Request $request)
    {

        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type', 'advertisement_type'])
            ->where('user_id', auth()->user()->id)
            ->orderBy('advertisement.updated_at', 'DESC')
            ->get();

        foreach ($advertisements as $advertisement) {
            // Get assigned newspapers names
            $newspaperNames = $advertisement->assigned_news->pluck('empanelled.news_name')->implode(', ');

            // Get subject name
            // $subjectName = $advertisement->subject->subject_name;

            // Do whatever you need to do with the data
        }

        return response()->json($advertisements)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function StoreData(StoreAdRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $positivelyDates = json_decode($request->positively, true);

            if (isset($request->id)) { // Edit an advertisement
                $sql_count = Advertisement::where('id', $request->id)->count();

                if ($sql_count > 0) {
                    try {
                        DB::beginTransaction();

                        Advertisement::whereId($request->id)->update([
                            'issue_date' => $request->issue_date,
                            'hod' => $request->department,
                            'cm' => $request->cm,
                            'columns' => $request->columns,
                            'seconds' => $request->seconds,
                            'amount' => $request->amount,
                            'ref_no' => $request->ref_no,
                            'ref_date' => $request->ref_date,
                            'positively_on' => implode(',', $positivelyDates),
                            'remarks' => $request->remarks,
                            'no_of_entries' => $request->insertions,
                            'advertisement_type_id' => $request->advertisementType,
                            'subject_id' => $request->subject,
                            'ad_category_id' => $request->category,
                            'color_id' => $request->color,
                            'page_info_id' => $request->page_info,
                            'dept_letter_no' => $request->letter_no,
                            'updated_at' => now(),
                        ]);

                        $advertisement = Advertisement::findOrFail($request->id);
                        $advertisement->assigned_news()->delete();

                        $newAssignedNews = $request->newspaper;
                        foreach ($newAssignedNews as $assignedNewsId) {
                            $assignedNews = new AssignedNews();
                            $assignedNews->advertisement_id = $advertisement->id;
                            $assignedNews->empanelled_id = $assignedNewsId;
                            $assignedNews->save();
                        }

                        DB::commit();
                        return response()->json(["flag" => "YY"]);
                    } catch (\Exception $e) {
                        DB::rollback();
                        return response()->json(["flag" => "NN"]);
                    }
                } else {
                    return response()->json(["flag" => "NN"]);
                }
            } else {    // Create new advertisement
                try {
                    DB::beginTransaction();
                    $validatedData = $request->validated();

                    $advertisement = new Advertisement();
                    $advertisement->user_id = auth()->user()->id;
                    $advertisement->hod = $request->department;
                    $advertisement->issue_date = $request->issue_date;
                    $advertisement->cm = $request->cm;
                    $advertisement->columns = $request->columns;
                    $advertisement->seconds = $request->seconds;
                    $advertisement->amount = $request->amount;
                    $advertisement->ref_no = $request->ref_no;
                    $advertisement->ref_date = $request->ref_date;
                    $advertisement->positively_on = implode(',', $positivelyDates);
                    $advertisement->remarks = $request->remarks;
                    $advertisement->advertisement_type_id = $request->advertisementType;
                    $advertisement->subject_id = $request->subject;
                    $advertisement->ad_category_id = $request->category;
                    $advertisement->no_of_entries = $request->insertions;
                    $advertisement->color_id = $request->color;
                    $advertisement->page_info_id = $request->page_info;
                    $advertisement->dept_letter_no = $request->letter_no;
                    $advertisement->save();

                    if (isset($validatedData['newspaper']) && is_array($validatedData['newspaper'])) {
                        foreach ($validatedData['newspaper'] as $assignedNewsId) {
                            $assignedNews = new AssignedNews();
                            $assignedNews->advertisement_id = $advertisement->id;
                            $assignedNews->empanelled_id = $assignedNewsId;
                            $assignedNews->save();
                        }
                    }

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
        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type', 'subject', 'ad_category', 'advertisement_type', 'color', 'page_info'])
            ->where('advertisement.id', '=', $request->id)
            ->where('advertisement.user_id', '=', auth()->user()->id)
            ->get();

        return response()->json($advertisements);
    }

    public function DeleteData(Request $request)
    {
        try {
            DB::beginTransaction();
            $sql = Advertisement::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json($e);
        }
    }

    public function getAmount(Request $request)
    {
        $advertisementType = $request->advertisementType;

        if ($advertisementType == 6) {

            $seconds = $request->seconds;

            $query = Amount::where('advertisement_type_id', $advertisementType)
                ->select('amount', 'gst_rate')
                ->first();
            $gstRate = $query->gst_rate ?: 0;
            $amount = $query->amount;
            $base_amount = $amount * $seconds;
            $total_amount = $base_amount * (1 + $gstRate / 100);
        } else {
            $category = $request->category;
            $cm = $request->cm;
            $columns = $request->columns;
            $query = Amount::where('ad_category_id', $category)
                ->where('advertisement_type_id', $advertisementType)
                ->select('amount', 'gst_rate')
                ->first();
            $gstRate = $query->gst_rate ?: 0;
            $amount = $query->amount;
            $base_amount = $amount * $cm * $columns;
            $total_amount = $base_amount * (1 + $gstRate / 100);
        }

        return response()->json([
            'base_amount' => $base_amount,
            'gst_rate' => $gstRate,
            'amount' => $total_amount,
        ]);
    }

    public function normalizeString($str)
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = mb_ereg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace('%', '-', $str);
        return $str;
    }
}
