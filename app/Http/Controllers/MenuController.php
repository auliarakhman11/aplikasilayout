<?php

namespace App\Http\Controllers;

use App\Models\AksesGudang;
use App\Models\Gudang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index(){

        return view('menu.index',[
            'title' => 'Warehouse',
            // 'gudang' => Gudang::all(),
            'gudang' => AksesGudang::select('gudang.id','gudang.nm_gudang')->leftJoin('gudang','akses_gudang.gudang_id','=','gudang.id')->where('akses_gudang.user_id',Auth::id())->get()
        ]);
    }

    public function addSessionGudang($gudang_id){
        $dt_gudang = Gudang::where('id',$gudang_id)->first();

        if ($dt_gudang) {
            session([
                'gudang_id' => $dt_gudang->id,
                'nm_gudang' => $dt_gudang->nm_gudang
            ]);

            return redirect(route('home'));

        }else{
            return redirect(route('menu'));
        }

        
    }

}
