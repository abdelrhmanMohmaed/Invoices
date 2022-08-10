<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = Section::active()->get();
        $products = Product::active()->get();
        return view('web.product.index', compact('sections', 'products'));
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
            'product_name' => 'required|unique:products|max:50',
            'section_id' => 'required|exists:sections,id',
            'description' => 'required',
        ], [
            'product_name.required' => 'حقل الاسم مطلوب',
            'product_name.unique' => 'هذا الاسم موجود ب الفعل',
            'product_name.max' => 'لايجب ان يكون طول الاسم اكثر من 50 حرف',
            'description.required' => 'حقل الوصف مطلوب',
        ]);
        try {
            Product::create([
                'product_name' => $request->product_name,
                'section_id' => $request->section_id,
                'description' => $request->description,
            ]);

            return redirect('product')->with('success', 'تم الاضافه بنجاح');
        } catch (\Exception $e) {

            return redirect('product')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $request->validate([
            'product_name' => 'required|unique:products,product_name,' . $id,
            'description' => 'required',
        ], [
            'product_name.required' => 'حقل الاسم مطلوب',
            'product_name.unique' => 'هذا الاسم موجود ب الفعل',
            'product_name.max' => 'لايجب ان يكون طول الاسم اكثر من 50 حرف',
            'description.required' => 'حقل الوصف مطلوب',
        ]);
        // return $id;
        try {
            $section_id = Section::where('section_name', $request->section_name)->first()->id;
            $product = product::findOrFail($id);
            // return  $section_id;
            $product->update([
                'product_name' => $request->product_name,
                'section_id' => $section_id,
                'description' => $request->description,
            ]);

            return redirect('product')->with('success', 'تم التعديل بنجاح');
        } catch (\Exception $e) {
            return $e;
            return redirect('product')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
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
        $product = product::find($request->id);
        if (!$product) return redirect()->route('product')->with('error', 'هذا القسم غير موجود');
        try {
            $product->update([
                'active' => !$product->active,
            ]);
            return redirect('product')->with('success', 'تم الحذف بنجاح');
        } catch (\Exception $e) {
            return $e;
            return redirect('product')->with('error', 'حدث خطا ما برجاء المحاوله مره اخرى');
        }
    }
}
