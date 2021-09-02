<?php

namespace App\Http\Livewire;

use App\Models\Belanja;
use App\Models\Produk;
use Livewire\Component;
use Kavist\RajaOngkir\RajaOngkir;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Expr\FuncCall;

class TambahOngkir extends Component
{
    public $belanja;
    private $apiKey = '41562e742e226960ffc1938f7dbe56a0';
    public $provinsi_id, $kota_id, $jasa, $daftarProvinsi, $daftarKota, $nama_jasa;
    public $result = [];
    public function mount($id)
    {
        if(!Auth::user())
        {
            return Redirect()->route('login');
        }
        $this->belanja = Belanja::find($id);

        //admin tidak bisa menambah ongkir
        if($this->belanja->user_id != Auth::user()->id)
        {
            return redirect()->to('ManajemenProduk');
        }
    }

    public function getOngkir()
    {
        //validasi
        if(!$this->provinsi_id || !$this->kota_id || !$this->jasa)
        {
            return;
        }
        //mengambil data produk
        $produk = Produk::find($this->belanja->produk_id);
        //mengambil ongkir
        $rajaOngkir = new RajaOngkir($this->apiKey);
        $cost = $rajaOngkir->ongkosKirim([
            'origin' => 445, //kota solo
            'destination' => $this->kota_id,
            'weight' => $produk->berat,
            'courier' => $this->jasa
        ])->get();

        //pengecekan
        //dd($cost);

        //nama_jasa
        $this->nama_jasa = $cost[0]['name'];
        
        foreach ($cost[0]['costs'] as $row)
        {
            $this->result[] = array(
                'description' => $row['description'],
                'biaya' => $row['cost'][0]['value'],
                'etd' => $row['cost'][0]['etd'],
            );
        }
    }

    public Function save_ongkir($biaya_pengiriman)
    {
        $this->belanja->total_harga += $biaya_pengiriman;
        $this->belanja->status = 1;
        $this->belanja->jenis_service_pengiriman = $this->jasa;
        $this->belanja->update();

        //redirect ke belanja

        return redirect()->to('BelanjaUser');
    }

    public function render()
    {
        $rajaOngkir = new RajaOngkir($this->apiKey);
        $this->daftarProvinsi = $rajaOngkir->provinsi()->all();
        
        if($this->provinsi_id)
        {
           $this->daftarKota = $rajaOngkir->kota()->dariProvinsi($this->provinsi_id)->get();
        }
        return view('livewire.tambah-ongkir')->extends('layouts.app')->section('content');
    }
}
