<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Categories = Category::all(); // spt select * 
        return view('admin.category', compact('Categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('create', 
        //  ['type' =>'Category'] //buat halamn  create
        // );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' =>'required|string|min:2',
        ],[
            'name.required' => 'Kok kosong.....?',
            'name.min' => '2 aja minimal',
        ]);

        

        Category::create([ //mengisi manual
            'name'=>$request->name]);

        return redirect()->route('admin.category.index')->with('success', 'Category berhasil ditambahkan!');  
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.edit', [
            'type' =>'Category',
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'name' =>'required|string|min:2',
        ]);

        //update product
        $Category = Category::findOrFail($id);
        $Category->update([
            'name'=>$request->name]);

        return redirect()->route('admin.category.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //delete product
        $category->delete();

        //redirect to index
        return redirect()->route('admin.category.index')->with(['success' => 'Data berhasil dihapus!']);
    }
}
