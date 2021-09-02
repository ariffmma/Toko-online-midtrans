<?php

namespace App\Http\Livewire;

use App\Models\Belanja;
use App\Models\Produk;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Home extends Component
{
    public $products = [];
    //atribut filter
    public $search,$min,$max;
    public function beli($id)
    {
        if(!Auth::user())
        {
            return Redirect()->route('login');
        }
        //mencari produk
        $produk = Produk::find($id);

        Belanja::create(
            [
                'user_id' => Auth::user()->id,
                'total_harga' => $produk->harga,
                'produk_id' => $produk->id,
                'status' => 0

            ]
            );
            return Redirect()->to('BelanjaUser');
    }

    public function render()
    {

        //filter harga max
        if($this->max)
        {
            $harga_max = $this->max;
        }
        else
        {
            $harga_max = 100000000;
        }

        //filter harga min
        if($this->min)
        {
            $harga_min = $this->min;
        }
        else
        {
            $harga_min = 0;
        }

        //filter search
        if($this->search)
        {
            $this->products = Produk::where('nama','like','%'.$this->search.'%')
                                        ->where('harga','>=',$harga_min)
                                        ->where('harga','<=',$harga_max)
                                        ->get();
        }
        else
        {
            $this->products = Produk::where('harga','>=',$harga_min)
                                    ->where('harga','<=',$harga_max)
                                    ->get();
        }
        
        return view('livewire.home')->extends('layouts.app')->section('content');
    }
}
