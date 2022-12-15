<?php

namespace App\Http\Controllers;

use App\Models\section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Ramsey\Uuid\v1;

class SectionController extends Controller
{

    public function index()
    {
        $section = section::all();
        return view('section.section',compact('section'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
        ],[

            'section_name.required' =>'Please Enter Section Name',
            'section_name.unique' =>'The Old Section Name',

        ]);
        
            section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'Created_by' => (Auth::user()->name)
            ]);
            session()->flash('Add', 'Section Add Succefully');
            return redirect('section');

    }


    public function show(section $section)
    {
        //
    }


    public function edit(section $section)
    {
        //
    }


    public function update(Request $request, section $section)
    {
        $id = $request->id;

        $this->validate($request, [

            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ],[

            'section_name.required' => 'Please Input Section name',
            'section_name.unique' =>'Enter Old Section Name',
            'description.required' =>'Please Enter Data',

        ]);

        $section = section::find($id);
        $section->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('edit','Updated Succefully');
        return redirect('/section');
    }


    public function destroy(Request $request)
    {
        $id = $request->id;
        section::find($id)->delete();
        session()->flash('delete','Deleted Succefully');
        return redirect('section');
    }
}
