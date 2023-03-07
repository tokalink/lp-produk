<?php

namespace App\Console\Commands;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Console\Command;

class impport_infiniti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'infiniti:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        self::import();
    }

    public function import()
    {
        // get json data dari https://infiniti.id/api/layanan   
        $json = file_get_contents('https://infiniti.id/api/layanan');
        $data = json_decode($json, true);
        // dd($data);
        // looping data
        foreach ($data as $row) {
            $kategori = Kategori::where('nama_kategori', $row['name_room'])->where('vendor','Infiniti')->first();
            if(!$kategori){
                $kategori = new Kategori();
                $kategori->nama_kategori = $row['name_room'];
                $kategori->vendor = 'Infiniti';
                $kategori->save();
            }
            $produk = Produk::where('nama_produk', $row['name_packet'])->where('kategori_id', $kategori->id)->first();
            if(!$produk){
                $produk = new Produk();                
            }
            $produk->nama_produk = $row['name_packet'];
            $produk->paket_id = $row['id'];
            $produk->kategori_id = $kategori->id;
            $produk->harga = $row['harga'];                
            $produk->save();
        }
    }
}
