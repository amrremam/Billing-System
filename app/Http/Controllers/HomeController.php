<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoice;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $sum = invoice::sum('Total');
        $count = invoice::count();
        return view('home',compact('sum','count'));
    }

}
