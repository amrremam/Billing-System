<?php

namespace App\Http\Controllers;

use App\Models\invoice;
use App\Models\User;
use App\Models\section;
use App\Models\invoices_details;
use App\Models\invoices_attachments;
use Illuminate\Http\Request;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{

    public function index()
    {
        $invoice = invoice::all();
        return view('invoices.invoices',compact('invoice'));
    }


    public function create()
    {
        $section = section::all();
        return view('invoices.add-invoices', compact('section'));
    }


    public function store(Request $request)
    {
        invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'Unpaid',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        $invoice_id = invoice::latest()->first()->id;
        invoices_details::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            // hena product bas khod balek da kolo hyb2a 7elol moshkelt elproduct
            'Section' => $request->Section,
            'Status' => 'Unpaid',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        if ($request->hasFile('pic')) {

            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new invoices_attachments();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }


        $user = User::first();
        Notification::send($user, new InvoicePaid($invoice_id));

        $user = User::get();
        $invoices = invoice::latest()->first();
        Notification::send($user, new \App\Notifications\AddInvoice($invoices));

        // event(new MyEventClass('hello world'));

        session()->flash('Add', 'Invoice Added Succefully');
        return back();    
    }


    public function show($id)
    {
        $invoices = invoice::where('id', $id)->first();
        return view('invoices.status_update', compact('invoices'));
    }


    public function edit($id)
    {
        $invoices = invoice::where('id', $id)->first();
        $sections = section::all();
        return view('invoices.edit_invoice', compact('sections', 'invoices'));
    }


    public function update(Request $request, invoice $invoice)
    {
        $invoices = invoice::findOrFail($request->invoice_id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        session()->flash('edit', 'invoice Updated Succefully');
        return back();
    }




    public function destroy(Request $request)
    {
        // return $request;
        $id = $request->invoice_id;
        $invoices = invoice::where('id', $id)->first();
        $Details = invoices_attachments::where('invoice_id', $id)->first();

         $id_page =$request->id_page;


        if (!$id_page==2) {

        if (!empty($Details->invoice_number)) {

            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }
        //forceDelete Will deleted It From DB
        $invoices->Delete();
        session()->flash('delete_invoice');
        return redirect('/invoices');

        }

        else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return redirect('/Archive');
        }
    }



    public function getproducts($id)
    {
        $product = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($product);
    }



    public function Status_Update($id, Request $request)
    {
        $invoices = invoice::findOrFail($id);

        if ($request->Status === 'Paid') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            invoices_Details::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');

    }

    public function Invoice_Paid()
    {
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('invoices.paid_invoices',compact('invoices'));
    }

    public function Invoice_unPaid()
    {
        $invoices = Invoice::where('Value_Status',2)->get();
        return view('invoices.unpaid_invoices',compact('invoices'));
    }

    public function Invoice_Partial()
    {
        $invoices = Invoice::where('Value_Status',3)->get();
        return view('invoices.partial_invoices',compact('invoices'));
    }

    public function Print_invoice($id)
    {
        $invoices = invoice::where('id', $id)->first();
        return view('invoices.Print_invoice',compact('invoices'));
    }


    public function MarkAsRead_all (Request $request)
    {

        $userUnreadNotification= auth()->user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }


    }


    public function unreadNotifications_count()
    {
        return auth()->user()->unreadNotifications->count();
    }


    public function unreadNotifications()
    {
        foreach (auth()->user()->unreadNotifications as $notification){

        return $notification->data['title'];
        }
    }


}
