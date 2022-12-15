<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Models\invoices_details;
use App\Models\invoices_attachments;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class InvoicesDetailsController extends Controller
{

    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(invoices_details $invoices_details)
    {
        //
    }


    public function edit($id)
    {
        $invoices = invoice::where('id',$id)->first();
        $details  = invoices_Details::where('id_Invoice',$id)->get();
        $attachments  = invoices_attachments::where('invoice_id',$id)->get();

        return view('invoices.details-invoice',compact('invoices','details','attachments'));

    }


    public function update(Request $request, invoices_details $invoices_details)
    {
        //
    }


    public function destroy(Request $request)
    {
        $invoices = invoices_attachments::findOrFail($request->id_file);
        $invoices->delete();
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        session()->flash('delete', 'Attachments Deleted Succefully');
        return back();
    }

    //  public function get_file($invoice_number,$file_name)

    // {
    //     $contents= Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
    //     return response()->download( $contents);
    // }



    // public function open_file($invoice_number,$file_name)

    // {
    //     $files = Storage::disk('public_uploads')->getDriver()->getAdapter()->applyPathPrefix($invoice_number.'/'.$file_name);
    //     return response()->file($files);
    // }
}
