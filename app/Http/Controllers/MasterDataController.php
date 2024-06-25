<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreNewspaperTypesRequest;
use App\Http\Requests\StoreRatesRequest;
use App\Models\Subject;
use App\Models\AdCategory;
use App\Models\Amount;
use App\Models\Empanelled;
use App\Models\NewsType;
use App\Models\AdvertisementType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreNewspaperRequest;
use App\Http\Requests\StoreSubjectRequest;
use App\Models\Color;
use App\Models\PageInfo;

class MasterDataController extends Controller
{
    //---------------------EMPANELLED NEWSPAPER
    public function newspaper_index()
    {
        $role   = Auth::user()->role->role_name;
        $newspaperTypes = NewsType::all();

        return view('modules.admin.empanelled')->with(compact('role', 'newspaperTypes'));
    }

    public function ViewNewspaperContent(Request $request)
    {
        $sql = Empanelled::with('news_type')->get();
        $newspaperTypes = NewsType::all();
        return response()->json($sql)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function StoreNewspaperData(StoreNewspaperRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            if (isset($request->id)) {   //Edit an admin
                $sql_count = Empanelled::where('id', $request->id)->count();
                if ($sql_count > 0) {
                    try {
                        DB::beginTransaction();
                        Empanelled::whereId($request->id)->update([
                            'news_name' => $this->normalizeString($request->name),
                            'newspaper_type_id' => $this->normalizeString($request->news_type_id),
                            // 'head_off' => $this->normalizeString($request->head_off),
                            // 'email' => $this->normalizeString($request->email),
                            // 'phone' => $this->normalizeString($request->phone),

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
            } else {    //Create new admin
                try {
                    DB::beginTransaction();
                    $empanelled = new Empanelled();
                    $empanelled->news_name = $this->normalizeString($request->name);
                    $empanelled->newspaper_type_id = $this->normalizeString($request->news_type_id);
                    // $empanelled->head_off = $this->normalizeString($request->head_off);
                    // $empanelled->email=$this->normalizeString($request->email);
                    // $empanelled->phone=$this->normalizeString($request->phone);
                    $empanelled->save();
                    DB::commit();
                    return response()->json(["flag" => "Y"]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return $e;
                    return response()->json(["flag" => "N"]);
                }
            }
        }
    }

    public function ShowNewspaperData(Request $request)
    {
        $empanelled = Empanelled::with(['news_type'])
            ->where('id', $request->id)
            ->get();

        return response()->json($empanelled);
    }

    public function DeleteNewspaperData(Request $request)
    {
        try {
            DB::beginTransaction();
            $sql = Empanelled::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N"]);
        }
    }

    //-----------------------SUBJECT

    public function subject_index()
    {
        $role   = Auth::user()->role->role_name;
        $subjects = Subject::all();
        return view('modules.admin.subject')->with(compact('role', 'subjects'));
    }
    public function ViewSubjectContent(Request $request)
    {
        $sql = Subject::orderBy('subject_name', 'desc')->get();
        return response()->json($sql)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }
    public function StoreSubjectData(StoreSubjectRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            if (isset($request->id)) {   //Edit an admin
                $sql_count = Subject::where('id', $request->id)->count();
                if ($sql_count > 0) {
                    try {
                        DB::beginTransaction();
                        Subject::whereId($request->id)->update([
                            'subject_name' => $this->normalizeString($request->subject)
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
            } else {    //Create new admin
                try {
                    DB::beginTransaction();
                    $adype = new Subject();
                    $adype->subject_name = $this->normalizeString($request->subject);
                    $adype->save();
                    DB::commit();
                    return response()->json(["flag" => "Y"]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return $e;
                    return response()->json(["flag" => "N"]);
                }
            }
        }
    }
    public function ShowSubjectData(Request $request)
    {
        $sql = Subject::select('id', 'subject_name')
            ->where('id', $request->id)
            ->get();
        return response()->json($sql);
    }

    public function DeleteSubjectData(Request $request)
    {
        try {
            DB::beginTransaction();
            $sql = Subject::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N"]);
        }
    }

    //----------------------ADVERTISEMENT TYPES

    public function advertisementTypesIndex()
    {
        $role = Auth::user()->role->role_name;
        $advertisementTypes = AdvertisementType::all();
        return view('modules.admin.advertisement_types')->with(compact('role', 'advertisementTypes'));
    }

    public function ViewAdvertisementTypes(Request $request)
    {
        $sql = AdvertisementType::orderBy('name', 'desc')->get();
        return response()->json($sql)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function StoreAdvertisementTypes(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            if (isset($request->id)) {
                $sql_count = AdvertisementType::where('id', $request->id)->count();
                if ($sql_count > 0) {
                    try {
                        DB::beginTransaction();
                        AdvertisementType::whereId($request->id)->update([
                            'name' => $request->type
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
            } else { // Create new advertisement type
                try {
                    DB::beginTransaction();
                    $adType = new AdvertisementType();
                    $adType->name = $request->name;
                    $adType->save();
                    DB::commit();
                    return response()->json(["flag" => "Y"]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(["flag" => "N"]);
                }
            }
        }
    }

    public function ShowAdvertisementTypes(Request $request)
    {
        $sql = AdvertisementType::select('id', 'name')
            ->where('id', $request->id)
            ->get();
        return response()->json($sql);
    }

    public function DeleteAdvertisementTypes(Request $request)
    {
        try {
            DB::beginTransaction();
            AdvertisementType::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N"]);
        }
    }

    //-----------------------RATES OF ADVERTISEMENT

    public function rates_index()
    {
        $role   = Auth::user()->role->role_name;
        $adCategory = AdCategory::all();
        $advertisementType = AdvertisementType::all();
        //    $adCategory = AdCategory::whereDoesntHave('amount')->get();
        $amount = '';

        return view('modules.admin.rates_of_advertisement')->with(compact('role', 'amount', 'adCategory', 'advertisementType'));
    }
    public function ViewRatesContent(Request $request)
    {
        $sql = Amount::with(['adCategory', 'advertisementType'])->get();
        return response()->json($sql)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function StoreRatesData(StoreRatesRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            $category_count = Amount::where('ad_category_id', $request->ad_category)
                ->where('advertisement_type_id', $request->advertisementType)
                ->count();
            if (isset($request->id)) { // Edit Rate
                $sql_count = Amount::where('id', $request->id)->count();
                if ($sql_count > 0) {
                    try {
                        DB::beginTransaction();
                        Amount::whereId($request->id)->update([
                            'amount' => $this->normalizeString($request->rate),
                            'advertisement_type_id' => $request->advertisementType,
                        ]);
                        DB::commit();
                        return response()->json(["flag" => "YY"]);
                    } catch (\Exception $e) {
                        \Log::error($e->getMessage());
                        DB::rollback();
                        return response()->json(["flag" => "NN"]);
                    }
                } else {
                    return response()->json(["flag" => "NN"]);
                }
            } else if ($category_count > 0) {
                try {
                    DB::beginTransaction();
                    Amount::where('ad_category_id', $request->ad_category)
                        ->where('advertisement_type_id', $request->advertisementType)
                        ->update([
                            'amount' => $this->normalizeString($request->rate),
                        ]);
                    DB::commit();
                    return response()->json(["flag" => "YY"]);
                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                    DB::rollback();
                    return response()->json(["flag" => "NN"]);
                }
            } else { // Store new Rate
                try {
                    DB::beginTransaction();
                    $amount = new Amount();
                    $amount->amount = $this->normalizeString($request->rate);
                    $amount->ad_category_id = $this->normalizeString($request->ad_category);
                    $amount->advertisement_type_id = $this->normalizeString($request->advertisementType);
                    $amount->save();
                    DB::commit();
                    return response()->json(["flag" => "Y"]);
                } catch (\Exception $e) {
                    \Log::error($e->getMessage());
                    DB::rollback();
                    return response()->json(["flag" => "N"]);
                }
            }
        }
    }

    public function ShowRatesData(Request $request)
    {
        $sql = Amount::select('id', 'amount', 'ad_category_id', 'advertisement_type_id','gst_rate')
            ->where('id', $request->id)
            ->with(['adCategory', 'advertisementType'])
            ->get();
        return response()->json($sql);
    }
    public function DeleteRatesData(Request $request)
    {
        try {
            DB::beginTransaction();
            $sql = Amount::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N"]);
        }
    }

    //-----------------------NEWSPPAER TYPES

    public function newspaper_types_index()
    {
        $role   = Auth::user()->role->role_name;
        $news_type = NewsType::all();

        return view('modules.admin.newspaper_types')->with(compact('role', 'news_type'));
    }
    public function ViewNewspaperTypesContent(Request $request)
    {
        $sql = NewsType::orderBy('news_type', 'desc')->get();

        return response()->json($sql)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }
    public function StoreNewspaperTypesData(StoreNewspaperTypesRequest $request)
    {
        $validator = Validator::make($request->all(), $request->rules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            if (isset($request->id)) {   //Edit an admin
                $sql_count = NewsType::where('id', $request->id)->count();
                if ($sql_count > 0) {
                    try {
                        DB::beginTransaction();
                        NewsType::whereId($request->id)->update([
                            'news_type' => $this->normalizeString($request->news_type)
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
            } else {    //Create new admin
                try {
                    DB::beginTransaction();
                    $news_type = new NewsType();
                    $news_type->news_type = $this->normalizeString($request->news_type);
                    $news_type->save();
                    DB::commit();
                    return response()->json(["flag" => "Y"]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return $e;
                    // return response()->json(["flag"=>"N"]);
                }
            }
        }
    }
    public function ShowNewspaperTypesData(Request $request)
    {
        $sql = NewsType::select('id', 'news_type')
            ->where('id', $request->id)
            ->get();
        return response()->json($sql);
    }

    public function DeleteNewspaperTypesData(Request $request)
    {
        try {
            DB::beginTransaction();
            $sql = NewsType::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N"]);
        }
    }


    // ----------COLOR OF ADVERTISEMENT

    public function color_index()
    {
        $role = Auth::user()->role->role_name;
        $colors = Color::all();
        return view('modules.admin.color')->with(compact('role', 'colors'));
    }

    public function ViewColorContent(Request $request)
    {
        $colors = Color::orderBy('color_name', 'desc')->get();
        return response()->json($colors)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function StoreColorData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'color' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            if (isset($request->id)) { // Edit an existing color
                $colorCount = Color::where('id', $request->id)->count();
                if ($colorCount > 0) {
                    try {
                        DB::beginTransaction();
                        Color::whereId($request->id)->update([
                            'color_name' => $request->color
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
            } else { // Create a new color
                try {
                    DB::beginTransaction();
                    $color = new Color();
                    $color->color_name = $request->color;
                    $color->save();
                    DB::commit();
                    return response()->json(["flag" => "Y"]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(["flag" => "N"]);
                }
            }
        }
    }

    public function ShowColorData(Request $request)
    {
        $color = Color::select('id', 'color_name')
            ->where('id', $request->id)
            ->get();
        return response()->json($color);
    }

    public function DeleteColorData(Request $request)
    {
        try {
            DB::beginTransaction();
            Color::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N"]);
        }
    }


    // ----------PAGE INFO OF ADVERTISEMENT

    public function page_info_index()
    {
        $role = Auth::user()->role->role_name;
        $page_info = PageInfo::all();
        return view('modules.admin.page_information')->with(compact('role', 'page_info'));
    }

    public function ViewPageInfoContent(Request $request)
    {
        $page_info = PageInfo::orderBy('page_info_name', 'desc')->get();
        return response()->json($page_info)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function StorePageInfoData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_info' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        } else {
            if (isset($request->id)) { 
                $pageinfoCount = PageInfo::where('id', $request->id)->count();
                if ($pageinfoCount > 0) {
                    try {
                        DB::beginTransaction();
                        PageInfo::whereId($request->id)->update([
                            'page_info_name' => $request->page_info
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
            } else { 
                try {
                    DB::beginTransaction();
                    $page_info = new PageInfo();
                    $page_info->page_info_name = $request->page_info;
                    $page_info->save();
                    DB::commit();
                    return response()->json(["flag" => "Y"]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(["flag" => "N"]);
                }
            }
        }
    }

    public function ShowPageInfoData(Request $request)
    {
        $page_info = PageInfo::select('id', 'page_info_name')
            ->where('id', $request->id)
            ->get();
        return response()->json($page_info);
    }

    public function DeletePageInfoData(Request $request)
    {
        try {
            DB::beginTransaction();
            PageInfo::where('id', $request->id)->delete();
            DB::commit();
            return response()->json(["flag" => "Y"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(["flag" => "N"]);
        }
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
