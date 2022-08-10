<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::active()->get();
        return view('web.sections.index', compact('sections'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'section_name' => 'required|unique:sections|max:50',
            'description' => 'required',
        ], [
            'section_name.required' => 'حقل الاسم مطلوب',
            'section_name.unique' => 'هذا الاسم موجود ب الفعل',
            'section_name.max' => 'لايجب ان يكون طول الاسم اكثر من 50 حرف',
            'description.required' => 'حقل الوصف مطلوب',
        ]);
        try {
            Section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => Auth::user()->name,
            ]);

            return redirect('sections')->with('success', 'تم الاضافه بنجاح');
        } catch (\Exception $e) {

            return redirect('sections')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->id = $request->id;
        $request->validate([
            'section_name' => 'required|max:50|unique:sections,section_name,' . $id,
            'description' => 'required',
        ], [
            'section_name.required' => 'حقل الاسم مطلوب',
            'section_name.unique' => 'هذا الاسم موجود ب الفعل',
            'section_name.max' => 'لايجب ان يكون طول الاسم اكثر من 50 حرف',
            'description.required' => 'حقل الوصف مطلوب',
        ]);

        try {
            $section = Section::find($id);
            $section->update([
                'section_name' => $request->section_name,
                'description' => $request->description,
            ]);

            return redirect('sections')->with('success', 'تم التعديل بنجاح');
        } catch (\Exception $e) {
            return $e;
            return redirect('sections')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
    }
    public function status(Request $request)
    {
        $section = Section::find($request->id);
        if (!$section) return redirect()->route('sections')->with('error', 'هذا القسم غير موجود');
        try {
            $section->update([
                'active' => !$section->active,
            ]);
            return redirect('sections')->with('success', 'تم الحذف بنجاح');
        } catch (\Exception $e) {
            return redirect('sections')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }
}
