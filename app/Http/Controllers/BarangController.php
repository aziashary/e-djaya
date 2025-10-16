<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::with('category')->latest()->paginate(10);
        return view('barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('barang.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|string|max:255',
        'harga_jual' => 'required|numeric|min:0',
    ]);

    // Jika SKU tidak diisi, generate random
    do {
        $sku = 'BRG-' . strtoupper(Str::random(6));
    } while (\App\Models\Barang::where('sku', $sku)->exists());
    

    Barang::create([
        'category_id' => $request->categories_id,
        'nama' => $request->nama,
        'barcode' => $request->barcode,
        'harga_beli' => $request->harga_beli ?? 0,
        'harga_jual' => $request->harga_jual,
        'sku' => $sku,
        'is_active' => 1,
    ]);

    return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
}

    /**
     * Display the specified resource.
     */
    public function show(Barang $barang)
    {
        return view('barang.show', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Barang $barang)
    {
        $categories = Category::where('id', $barang->category_id)
            ->get();
        $allCategories = Category::all();
        return view('barang.edit', compact('barang', 'categories', 'allCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'harga_jual' => 'required|numeric|min:0',
        ]);

        $barang->update([
            'category_id' => $request->categories_id,
            'nama' => $request->nama,
            'sku' => $request->sku,
            'harga_beli' => $request->harga_beli ?? 0,
            'harga_jual' => $request->harga_jual,
            'satuan' => $request->satuan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
