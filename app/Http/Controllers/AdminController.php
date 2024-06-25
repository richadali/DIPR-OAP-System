<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAdminRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $role   = Auth::user()->role->role_name;
        return view('modules.admin.admin')->with(compact('role'));
    }

    public function ViewContent(Request $request){
        $sql = User::whereHas('role', function ($query) {
                    $query->where('id', 2); // role_id for admin is 2
                })->with('role')
                ->get(); 

         return response()->json($sql)->withHeaders([
            'Cache-Control' => 'max-age=15, public',
            'Expires' => gmdate('D, d M Y H:i:s', time() + 15) . ' IST',
        ]);
    }

    public function StoreData(StoreAdminRequest $request){
        $validator = Validator::make($request->all(),$request->rules());

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }
        else {
            if(isset($request->id)){   //Edit an admin
               $sql_count = User::where('id',$request->id)->count();
               if($sql_count>0){
                try{
                    DB::beginTransaction();
                    User::whereId($request->id)->update([
                            'name' => $this->normalizeString($request->name),
                            'designation' => $this->normalizeString($request->designation),
                            'password' => Hash::make($request->password),
                        ]);
                    DB::commit();
                    return response()->json(["flag"=>"YY"]);
                }
                catch(\Exception $e){
                    DB::rollback();
                    return response()->json(["flag"=>"NN"]);
                }
               }
               else {
                return response()->json(["flag"=>"NN"]);
               }
            }
            else {    //Create new admin
                try{
                    DB::beginTransaction();
                    $User = new User();
                    $User->name = $this->normalizeString($request->name);
                    $User->email = $this->normalizeString($request->email);
                    $User->password = Hash::make($request->password);
                    $User->designation=$this->normalizeString($request->designation);
                    $User->role_id=2;
                    $User->save();
                    DB::commit();
                    return response()->json(["flag"=>"Y"]);
                    }
                    catch(\Exception $e){
                        DB::rollback();
                        return response()->json(["flag"=>"N"]);
                    }
            }
        }
    }

    public function ShowData(Request $request) {
        $sql = User::select('id','name','email','designation')
                    ->where('id',$request->id)
                    ->get();
        return response()->json($sql);
    }
    public function DeleteData(Request $request){
        try{
            DB::beginTransaction();
            $sql = User::where('id',$request->id)->delete();
            DB::commit();
            return response()->json(["flag"=>"Y"]);
        }
        catch(\Exception $e){
            DB::rollback();
            return response()->json(["flag"=>"N"]);
        }
    }

    public function normalizeString($str){
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = mb_ereg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace('%', '-', $str);
       return $str;
    }
}
