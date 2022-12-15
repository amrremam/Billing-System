<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

class ProductController extends Controller
{

    public function index()
    {
        $section = section::all();
        $product = Product::all();
        return view('section.products',compact('section','product'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        Product::create([
            'Product_name' => $request->Product_name,
            //khod balek el P hena cap
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);
        session()->flash('Add', 'Product Added Succefully');
        return redirect('/products');
    }



    public function show(Product $product)
    {
        //
    }


    public function edit(Product $product)
    {
        //
    }


    public function update(Request $request, Product $product)
    {
        $id = section::where('section_name', $request->section_name)->first()->id;

        $Product = Product::findOrFail($request->pro_id);
 
        $Product->update([
        'Product_name' => $request->Product_name,
        'description' => $request->description,
        'section_id' => $id,
        ]);
 
        session()->flash('Edit', 'Edited Succefully');
        return back();
    }


    public function destroy(Request $request)
    {
        // $Product = Product::findOrFail($request->pro_id);
        // $Product->delete();
        // session()->flash('delete', 'Product Deleted Succefully');
        return $request;
    }
}
