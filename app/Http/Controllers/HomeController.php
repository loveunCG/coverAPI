<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Model\InsuranceModel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if (! Gate::allows('isActive')) {
        //     Auth::logout();
        //     return redirect('/login');
        // }
        return view('home');
    }
    public function adminMg()
    {
        return view('admin.index');
    }
    public function agentMg()
    {
        return view('agent.index');
    }
    public function customerMg()
    {
        return view('customer.index');
    }

    public function insurance()
    {
        return view('setting.insurance');
    }

    public function company()
    {
        $insurances = InsuranceModel::all();
        return view('setting.company', compact('insurances'));
    }

    /**
     *
     */
    public function jobsMg()
    {
        return view('jobs.index');
    }
}
