<?php

namespace App\Http\Controllers;

use App\Models\invoices_attachments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class InvoicesAttachmentsController extends Controller
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
        $this->validate($request, [

            'file_name' => 'mimes:pdf,jpeg,png,jpg',
    
            ], [
                'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
            ]);
            
            $image = $request->file('file_name');
            $file_name = $image->getClientOriginalName();
    
            $attachments =  new invoices_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $request->invoice_number;
            $attachments->invoice_id = $request->invoice_id;
            $attachments->Created_by = Auth::user()->name;
            $attachments->save();
               
            // move pic
            $imageName = $request->file_name->getClientOriginalName();
            $request->file_name->move(public_path('Attachments/'. $request->invoice_number), $imageName);
            
            session()->flash('Add', 'Attachment Added Succefully');
            return back();
    }


    public function show(invoices_attachments $invoices_attachments)
    {
        //
    }


    public function edit(invoices_attachments $invoices_attachments)
    {
        //
    }


    public function update(Request $request, invoices_attachments $invoices_attachments)
    {
        //
    }


    public function destroy(invoices_attachments $invoices_attachments)
    {
        //
    }
}
