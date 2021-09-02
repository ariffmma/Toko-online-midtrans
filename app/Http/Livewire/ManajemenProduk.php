<?php

namespace App\Http\Livewire;

use App\Models\Belanja;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ManajemenProduk extends Component
{
    public $belanja = [];
    public function mount()
    {
        if(!Auth::user())
        {
            return Redirect()->route('login');
        }
       
    }
    public function destroy($pesanan_id)
    {
        $pesanan = Belanja::find($pesanan_id);
        $pesanan->delete();
    }
    public function render()
    {
        if(Auth::user())
        {
            $this->belanja = Belanja::all();
        }
        return view('livewire.manajemen-produk')
        ->extends('layouts.app')->section('content');
    }
}
