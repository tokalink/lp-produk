<?php

namespace App\Http\Controllers;

use App\Helpers\Whatsapp;
use App\Models\Kategori;
use App\Models\Pembelianproduk;
use App\Models\Produk;
use Illuminate\Http\Request;

class LandingpageController extends Controller
{
    public function index(Request $request, $kategori = null)
    {
        $produk = [];
        if ($request->ajax()) {
            $kategori   = Kategori::where('slug', $kategori)->first();
            $produk     = Produk::where('kategori_id', $kategori->id)->get();
            $data = [
                'kategori' => $kategori,
                'produk' => $produk,
            ];
            return $data;
        }

        if ($kategori) {
            $kategori   = Kategori::where('slug', $kategori)->first();
            $produk     = Produk::where('kategori_id', $kategori->id)->get();
        } else {
            $produk = Produk::get();
        }
        $kategoris  = Kategori::get();
        return view('welcome', compact('produk', 'kategori', 'kategoris'));
    }

    public function formDetail(Request $request)
    {
        $produk = Produk::where('id', $request->id)->first();
        return view('form-detail', compact('produk'));
    }

    public function registrasi(Request $request)
    {
        $phone = $request->phone;
        // jadikan phone number ke format 62 dan hapus semua string selain angka
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $phone = preg_replace('/^0/', '62', $phone);
        $produk                 = Produk::where('id', $request->id_produk)->first();
        $pembelian              = new Pembelianproduk();
        $pembelian->nama        = $request->nama;
        $pembelian->phone       = $phone;
        $pembelian->email       = $request->email;
        $pembelian->alamat      = $request->alamat;
        $pembelian->id_produk   = $produk->id;
        $pembelian->harga       = $produk->harga;
        $pembelian->save();
        Whatsapp::send($phone, "Terima kasih telah melakukan pemesanan produk *" . $produk->nama_produk . "* dengan harga _" . $produk->harga . "_ di selanjutnya admin kami akan menghubungi anda. Terima kasih.");
        Whatsapp::send('120363019295574541@g.us', "Ada pesanan masuk dari *" . $request->nama . "* dengan nomor wa https://wa.me/" . $phone . " untuk produk *" . $produk->nama_produk . "* dengan harga _" . $produk->harga . "_ Ayo segera Follow up");
        return redirect()->back()->with('success', 'Pembelian Berhasil.');
    }
}
