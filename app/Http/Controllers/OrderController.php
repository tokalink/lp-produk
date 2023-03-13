<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show($slug=null)
    {
        if($slug)    {
            $kategori = Kategori::where('slug', $slug)->first();
        }else{
            $kategori = Kategori::first();
        }
        $kategoris = Kategori::all();
        $produk = Produk::where('kategori_id', $kategori->id)->get();        
        return view('order', compact('produk', 'kategori', 'kategoris'));
    }
    
}
