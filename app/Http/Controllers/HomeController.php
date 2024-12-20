<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Empanelled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Advertisement;
use App\Models\Sidebar;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $role = Auth::user()->role->role_name;
        $users = User::where([['id', '!=', 1], ['id', '!=', 2]])->count();
        $bills = Bill::count();
        $advertisements = Advertisement::count();
        $sidebars = Sidebar::with('subMenus')->get();
        $department = Advertisement::count('department_id');

        // Fetch the top 3 newspapers based on advertisement count in the last 7 days
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $topNewspapers = Empanelled::withCount(['assigned_news as advertisement_count' => function ($query) use ($sevenDaysAgo) {
            $query->where('created_at', '>=', $sevenDaysAgo);
        }])
            ->orderBy('advertisement_count', 'desc')
            ->take(3) // Limit to top 3
            ->get();

        // Fetch empanelled counts
        $empanelledCounts = Empanelled::with(['assigned_news' => function ($query) {
            $query->select('empanelled_id', 'advertisement_id')->groupBy('empanelled_id', 'advertisement_id');
        }])->get();

        // Store the role in the session
        session()->put('role', $role);

        // Pass the data to the view
        return view('home')->with(compact('role', 'users', 'advertisements', 'sidebars', 'bills', 'department', 'topNewspapers', 'empanelledCounts'));
    }
}
