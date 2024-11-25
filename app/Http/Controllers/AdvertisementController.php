<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreAdRequest;
use App\Models\Amount;
use App\Models\AssignedNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Empanelled;
use App\Models\Advertisement;
use Carbon\Carbon;

class AdvertisementController extends Controller
{
    public function index()
    {
        $role   = Auth::user()->role->role_name;
        return view('modules.user.advertisement')->with(compact('role'));
    }

    public function ViewContent(Request $request)
    {

        $advertisements = Advertisement::with(['assigned_news.empanelled.news_type', 'advertisement_type', 'department'])
            // ->where('user_id', auth()->user()->id)
            ->orderBy('advertisement.mipr_no', 'DESC')
            ->get();

        foreach ($advertisements as $advertisement) {
            // Get assigned newspapers names
            $newspaperNames = $advertisement->assigned_news->pluck('empanelled.news_name')->implode(', ');
        }

        return response()->json($advertisements)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function getCurrentMIPRNo()
    {
        $finYear = $this->getCurrentFinancialYear();

        DB::beginTransaction();
        try {
            // Check if a row exists for the current financial year
            $miprRow = DB::table('mipr_no')->where('fin_year', $finYear)->first();

            if (is_null($miprRow)) {
                // If no row exists, insert a new row with MIPR number set to '0001'
                $miprNo = '0001';
                DB::table('mipr_no')->insert([
                    'mipr_no' => $miprNo,
                    'fin_year' => $finYear
                ]);
                // No previous MIPR number exists in this case
                $lastMiprNo = '0000';
            } else {
                // If a row exists, get the current MIPR number
                $miprNo = $miprRow->mipr_no;
                // Calculate the last MIPR number
                $lastMiprNo = str_pad((int)$miprNo - 1, 4, '0', STR_PAD_LEFT);
            }

            DB::commit();
            return response()->json([
                'mipr_no' => $miprNo,
                'last_mipr_no' => $lastMiprNo
            ]);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error('Error in getCurrentMIPRNo: ', ['exception' => $th]);
            return response()->json(['error' => 'Failed to get MIPR number.'], 500);
        }
    }




    public function StoreData(StoreAdRequest $request)
    {
        // Log the entire request data to check the incoming payload
        \Log::debug($request->all());

        // Validate the request data
        $validatedData = $request->validated();

        // Retrieve the newspaper data from the request
        $newspaperData = $validatedData['newspaper'] ?? null;

        // Check if the newspaper data is provided and is in the correct format
        if ($newspaperData) {
            // Decode the JSON string into an array
            $newspaperData = json_decode($newspaperData, true);

            // Check if the decoded data is an array
            if (is_array($newspaperData)) {
                DB::beginTransaction();

                try {
                    if (isset($request->id)) { // Edit an advertisement
                        $sql_count = Advertisement::where('id', $request->id)->count();

                        if ($sql_count > 0) {
                            // Update existing advertisement record
                            Advertisement::whereId($request->id)->update([
                                'issue_date' => $request->issue_date,
                                'department_id' => $request->department,
                                'cm' => $request->cm,
                                'columns' => $request->columns,
                                'seconds' => $request->seconds,
                                'ref_no' => $request->ref_no,
                                'ref_date' => $request->ref_date,
                                'positively_on' => $request->positively,  // Store as single date
                                'remarks' => $request->remarks,
                                'advertisement_type_id' => $request->advertisementType,
                                'subject_id' => $request->subject,
                                'ad_category_id' => $request->category,
                                'color_id' => $request->color,
                                'page_info_id' => $request->page_info,
                                'payment_by' => $request->payment_by,
                                'mipr_no' => $request->mipr_no,
                                'updated_at' => now(),
                            ]);

                            // Clear previous newspaper assignments
                            $advertisement = Advertisement::findOrFail($request->id);
                            $advertisement->assigned_news()->delete();

                            // Assign new newspapers along with the 'positively_on' date
                            foreach ($newspaperData as $assignedNews) {
                                $assignedNewsRecord = new AssignedNews();
                                $assignedNewsRecord->advertisement_id = $advertisement->id;
                                $assignedNewsRecord->empanelled_id = $assignedNews['newspaper_id']; // Assuming 'newspaper_id' exists
                                $assignedNewsRecord->positively_on = $assignedNews['positively']; // Save the 'positively_on' date
                                $assignedNewsRecord->save();
                            }

                            DB::commit();
                            return response()->json(["flag" => "YY"]);
                        } else {
                            DB::rollback();
                            return response()->json(["flag" => "NN"]);
                        }
                    } else { // Create new advertisement
                        // Create a new advertisement record
                        $advertisement = new Advertisement();
                        $advertisement->user_id = auth()->user()->id;
                        $advertisement->department_id = $request->department;
                        $advertisement->issue_date = $request->issue_date;
                        $advertisement->cm = $request->cm;
                        $advertisement->columns = $request->columns;
                        $advertisement->seconds = $request->seconds;
                        $advertisement->ref_no = $request->ref_no;
                        $advertisement->ref_date = $request->ref_date;
                        $advertisement->remarks = $request->remarks;
                        $advertisement->advertisement_type_id = $request->advertisementType;
                        $advertisement->subject_id = $request->subject;
                        $advertisement->ad_category_id = $request->category;
                        $advertisement->color_id = $request->color;
                        $advertisement->page_info_id = $request->page_info;
                        $advertisement->payment_by = $request->payment_by;
                        $advertisement->mipr_no = $request->mipr_no;
                        $advertisement->save();

                        // Assign newspapers if provided
                        foreach ($newspaperData as $assignedNews) {
                            $assignedNewsRecord = new AssignedNews();
                            $assignedNewsRecord->advertisement_id = $advertisement->id;
                            $assignedNewsRecord->empanelled_id = $assignedNews['newspaper_id']; // Assuming 'newspaper_id' exists
                            $assignedNewsRecord->positively_on = $assignedNews['positively']; // Save the 'positively_on' date
                            $assignedNewsRecord->save();
                        }

                        // Increment the MIPR number after saving the advertisement
                        $finYear = $this->getCurrentFinancialYear();
                        $miprRow = DB::table('mipr_no')->where('fin_year', $finYear)->first();
                        if (!is_null($miprRow)) {
                            $currentMiprNo = (int)$miprRow->mipr_no;
                            $newMiprNo = str_pad($currentMiprNo + 1, 4, '0', STR_PAD_LEFT);
                            DB::table('mipr_no')->where('fin_year', $finYear)->update(['mipr_no' => $newMiprNo]);
                        }

                        DB::commit();
                        return response()->json(["flag" => "Y"]);
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    \Log::error("Error occurred: " . $e->getMessage());
                    return response()->json(["error" => $e->getMessage()], 500);
                }
            } else {
                // If the newspaper data is not in a valid format
                \Log::error("Invalid newspaper data: " . $validatedData['newspaper']);
                return response()->json(['error' => 'Invalid newspaper data'], 422);
            }
        } else {
            // If the newspaper data is missing
            \Log::error("No newspaper data found in the request.");
            return response()->json(['error' => 'No newspaper data found'], 422);
        }
    }



    public function ShowData(Request $request)
    {
        $advertisements = Advertisement::with(['department', 'assigned_news.empanelled.news_type', 'subject', 'ad_category', 'advertisement_type', 'color', 'page_info'])
            ->where('advertisement.id', '=', $request->id)
            // ->where('advertisement.user_id', '=', auth()->user()->id)
            ->get();

        return response()->json($advertisements);
    }

    public function getAdvertisementDetails($id)
    {
        $advertisement = Advertisement::with(['department', 'advertisement_type', 'assigned_news.empanelled'])
            ->findOrFail($id);

        return response()->json($advertisement);
    }

    public function DeleteData(Request $request)
    {
        try {
            DB::beginTransaction();

            $advertisement = Advertisement::find($request->id);

            if ($advertisement) {
                $hasBill = DB::table('bill_table')
                    ->where('advertisement_id', $advertisement->id)
                    ->exists();

                if ($hasBill) {
                    Log::info("Advertisement ID {$advertisement->id} has associated bills and cannot be deleted.");
                    return response()->json(['flag' => 'N', 'message' => 'The Advertisement has a Bill associated with it. It cannot be deleted!']);
                }


                $advertisement->status = "Cancelled";
                $advertisement->cancellation_reason = $request->input('cancellation_reason');
                $advertisement->save();

                DB::commit();
                return response()->json(["flag" => "Y"]);
            } else {
                return response()->json(["flag" => "N", "message" => "Advertisement not found."]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N", "message" => $e->getMessage()]);
        }
    }



    public function getAmount(Request $request)
    {
        $advertisementType = $request->advertisementType;

        if ($advertisementType == 6) {

            $seconds = $request->seconds;

            $ratePerSecond = Amount::where('advertisement_type_id', $advertisementType)
                ->value('amount');
            $total_amount = $ratePerSecond * $seconds;
        } else if ($advertisementType == 8) {

            $total_amount = Amount::where('advertisement_type_id', $advertisementType)
                ->value('amount');
        } else {
            $category = $request->category;
            $cm = $request->cm;
            $columns = $request->columns;
            $amount = Amount::where('ad_category_id', $category)
                ->where('advertisement_type_id', $advertisementType)
                ->value('amount');
            $total_amount = $amount * $cm * $columns;
        }

        return response()->json($total_amount);
    }

    public function getNewspapers(Request $request)
    {
        $typeId = $request->input('type_id');
        $sevenDaysAgo = Carbon::now()->subDays(7);

        $newspapers = Empanelled::withCount(['assigned_news as advertisement_count' => function ($query) use ($sevenDaysAgo) {
            $query->where('created_at', '>=', $sevenDaysAgo);
        }])
            ->get();

        $response = $newspapers->map(function ($newspaper) {
            return [
                'id' => $newspaper->id,
                'name' => $newspaper->news_name,
                'advertisement_count' => $newspaper->advertisement_count,
            ];
        });

        return response()->json($response);
    }

    public function updateAdvertisementStatus(Request $request)
    {
        $advertisement = Advertisement::find($request->id);
        if ($advertisement) {
            $advertisement->is_published = $request->is_published;
            $advertisement->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
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

    public function getCurrentFinancialYear()
    {
        $currentMonth = date('n');
        $currentYear = date('Y');
        $financialYearStart = ($currentMonth >= 4) ? $currentYear : ($currentYear - 1);
        $financialYearEnd = $financialYearStart + 1;
        return $financialYearStart . '-' . $financialYearEnd;
    }
}
