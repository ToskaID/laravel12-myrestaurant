<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller 
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items= Item::orderBy('name', 'ASC')->get();
        return view('admin.item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('cat_name', 'asc')->get();

        // Return the view to create a new item
        return view('admin.item.create', compact('categories')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required|boolean',
        ],
        [
            'name.required' => 'Nama item wajib diisi.',
            'description.string' => 'Deskripsi item harus berupa teks.',
            'price.required' => 'Harga item wajib diisi.',
            'category_id.required' => 'Kategori item wajib dipilih.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'is_active.required' => 'Status item wajib dipilih.',
        ]);

        //handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img_item_upload'), $imageName);
            $validatedData['image'] = $imageName;
           
        }

        Item::create($validatedData);

        return redirect()->route('items.index')->with('success', 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::orderBy('cat_name', 'asc')->get();

        // Return the view to edit the item
        return view('admin.item.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'required|boolean',
        ],
        [
            'name.required' => 'Nama item wajib diisi.',
            'description.string' => 'Deskripsi item harus berupa teks.',
            'price.required' => 'Harga item wajib diisi.',
            'category_id.required' => 'Kategori item wajib dipilih.',
            'image.image' => 'File yang diunggah harus berupa gambar.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            'is_active.required' => 'Status item wajib dipilih.',
        ]);

        //handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('img_item_upload'), $imageName);
            $validatedData['image'] = $imageName;
           
        }
        

        $item->update($validatedData);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

       public function updateStatus($id)
    {
        $item = Item::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        return redirect()->route('items.index')->with('success', 'Item status updated successfully.');
    }
}
