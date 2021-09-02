<?php

namespace App\Http\Livewire;

use App\Models\Belanja;
use Livewire\Component;
use PhpParser\Node\Arg;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class BelanjaUser extends Component
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
            $this->belanja = Belanja::where('user_id',Auth::user()->id)->get();
        }
        return view('livewire.belanja-user')
        ->extends('layouts.app')->section('content');
    }
}
