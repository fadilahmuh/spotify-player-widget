<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index(){
        // $user = User::where('spot_id', '=', Session::get('spot_id'))->first();

        // if (!$user){
        //    return redirect('login');
        // } else {
        //     return view('dashboard.index', compact('user'));
        // };

        if(Auth::check()){
            return view('dashboard.index');
        } else {
            return redirect('login2');
        }

    }
}
