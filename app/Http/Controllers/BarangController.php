<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Menu;
use App\Models\Satuan;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index(){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(15, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('barang.index',[
            'title' => 'Barang dan Satuan',
            'barang' => Barang::orderBy('nm_barang','ASC')->get(),
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }
    
    public function addBarang(Request $request){
        Barang::create([
            'nm_barang' => $request->nm_barang,
            'kode_barang' => $request->kode_barang,
            'kali_pak' => $request->kali_pak,
            'kali_kg' => $request->kali_kg,
        ]);

        return redirect()->back()->with('success' , 'Data barang berhasil dibuat');
    }

    public function editBarang(Request $request){
        Barang::where('id',$request->id)->update([
            'nm_barang' => $request->nm_barang,
            'kode_barang' => $request->kode_barang,
            'kali_pak' => $request->kali_pak,
            'kali_kg' => $request->kali_kg,
        ]);

        return redirect()->back()->with('success' , 'Data barang berhasil diubah');
    }

}
