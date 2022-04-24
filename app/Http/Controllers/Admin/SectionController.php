<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use Session;

class SectionController extends Controller
{
    public function sections(){
        Session::put('page', 'sections'); 
        $sections = Section::get()->toArray();
        // dd($sections); die;
        return view('admin.sections.sections')->with(compact('sections'));
    }

    public function updateSectionStatus(Request $request){
        if($request->ajax()){
            $data= $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status'] == "Active"){
                $status = 0;
            }else{
                $status = 1;
            }

            Section::where('id', $data['section_id'])->update(['status'=> $status]);
            return response()->json(['status' =>$status, 'section_id' => $data['section_id']]);
        }
    }

    public function deleteSection($id){
        // Delete Section
        Section::where('id', $id)->delete();
        $message = "Section deleted Successfully";
        return redirect()->back()->with('success_message', $message);
    }

    public function addEditSection(Request $request, $id = null){
        Session::put('page', 'sections'); 
        if($id == ""){
            $title = "Add Section";
            $section = new Section;
            $message = "Section added succesfully";
        }else{
            $title = "Edit Section";
            $section = Section::find($id);
            $message = "Sectiion updated successfully";
        }

        if($request->isMethod('post')){
            $data = $request->all();
            echo "<pre>"; print_r($data); die;
        }
        return view('admins.sections.add_edit_section')->with(compact('title', 'section'));
    }
}
