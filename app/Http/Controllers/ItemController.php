<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Category;
class ItemController extends Controller
{
    /**
     * Display a listing of the resourc e.
     */
    public function index()
    {
        // $Items = Item::all();
        $Items = Item::with('category')->get(); // mengambil data item beserta kategori
        $Categories = Category::all(); // mengambil semua data kategori
        return view('item', compact('Items', 'Categories')); //biar create satu file
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('item', [
            'type' => 'Item',  //jika create di beda folder
            'categories' => $Categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'categories_id' => 'required|exists:categories,id',
        ]);

        Item::create([ //milih data manual
            'name' => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
            'categories_id' => $request->categories_id,
        ]);

        // Item::create($request->all()); //otomatis milih semua data

        return redirect()->route('item.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $Categories = Category::all();
        return view('edit', [
            'type' =>'Item',
            'Item' => $item,
            'categories' => $Categories
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'name' =>'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'categories_id' => 'required|exists:categories,id',
        ]);

        //update product
        $item = Item::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('item.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('item.index')->with('success', 'Item deleted successfully.');
    }
}
