<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\section;
use App\Models\invoice;
class Customers_Report extends Controller
{
    public function index(){

    $sections = section::all();
    return view('reports.customers_report',compact('sections'));

    }


    public function Search_customers(Request $request){

// Search Without Date    
    if ($request->Section && $request->product && $request->start_at =='' && $request->end_at=='') {

        $invoices = invoice::select('*')->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
        $sections = section::all();
        return view('reports.customers_report',compact('sections'))->withDetails($invoices);
    
    }

  // Search With Date
    else {
    $start_at = date($request->start_at);
    $end_at = date($request->end_at);
    $invoices = invoice::whereBetween('invoice_Date',[$start_at,$end_at])->where('section_id','=',$request->Section)->where('product','=',$request->product)->get();
    $sections = section::all();
    return view('reports.customers_report',compact('sections'))->withDetails($invoices);

    }
    }
}