<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Menu;
use App\Models\Stok;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LaporanController extends Controller
{
    public function laporanStokMasuk(Request $request){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(11, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();


        if($request->query('tgl1')){
            $tgl1 = $request->query('tgl1');
            $tgl2 = $request->query('tgl2');
        }else{
            $tgl1 = date('Y-m-d', strtotime("-7 day", strtotime(date("Y-m-d"))));
            $tgl2 = date('Y-m-d');
        }

        return view('laporan.stok_masuk',[
            'title' => 'Laporan Stok Masuk',
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('status',1)->where('checker',1)->where('tgl','>=',$tgl1)->where('tgl','<=',$tgl2)->groupBy('kd_gabungan')->get(),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'menu' => $menu,
            'submenu' => $submenu
        ]);
    }

    public function pdfStokMasuk($kd_gabungan){
        $data = [
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('kd_gabungan',$kd_gabungan)->get()
            ];
        $pdf = FacadePdf::loadView('laporan.pdf_stok_masuk',$data)->setPaper('a4','portrait');
        return $pdf->stream();
    }

    public function laporanStokKeluar(Request $request){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(12, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();


        if($request->query('tgl1')){
            $tgl1 = $request->query('tgl1');
            $tgl2 = $request->query('tgl2');
        }else{
            $tgl1 = date('Y-m-d', strtotime("-7 day", strtotime(date("Y-m-d"))));
            $tgl2 = date('Y-m-d');
        }

        return view('laporan.stok_keluar',[
            'title' => 'Laporan Stok Keluar',
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('status',2)->where('checker',1)->where('tgl','>=',$tgl1)->where('tgl','<=',$tgl2)->groupBy('kd_gabungan')->get(),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }

    public function pdfStokKeluar($kd_gabungan){
        $data = [
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('kd_gabungan',$kd_gabungan)->get()
            ];
        $pdf = FacadePdf::loadView('laporan.pdf_stok_keluar',$data)->setPaper('a4','portrait');
        return $pdf->stream();
    }

    public function laporanPenerimaanPengiriman(Request $request){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(13, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        if($request->query('tgl')){
            $tgl = $request->query('tgl');
           
        }else{
            $tgl = date('Y-m-d');
        }

        if($request->query('barang_id')){
            $barang_id = $request->query('barang_id');
           
        }else{
            $barang_id = 'All';
        }


        if ($barang_id != 'All') {
            $stok = Stok::select('stok.*')->selectRaw('SUM(debit_box) as jml_debit_box, SUM(debit_pak) as jml_debit_pak, SUM(debit_kg) as jml_debit_kg, SUM(kredit_box) as jml_kredit_box, SUM(kredit_pak) as jml_kredit_pak, SUM(kredit_kg) as jml_kredit_kg')->where('gudang_id',Session::get('gudang_id'))->where('checker',1)->where('tgl','<=',$tgl)->where('barang_id',$barang_id)->groupBy('barang_id')->groupBy('pallet_id')->groupBy('tgl_exp')->orderBy('barang_id','ASC')->get();
            
        } else {
            $stok = Stok::select('stok.*')->selectRaw('SUM(debit_box) as jml_debit_box, SUM(debit_pak) as jml_debit_pak, SUM(debit_kg) as jml_debit_kg, SUM(kredit_box) as jml_kredit_box, SUM(kredit_pak) as jml_kredit_pak, SUM(kredit_kg) as jml_kredit_kg')->where('gudang_id',Session::get('gudang_id'))->where('checker',1)->where('tgl','<=',$tgl)->groupBy('barang_id')->groupBy('pallet_id')->groupBy('tgl_exp')->orderBy('barang_id','ASC')->get();
        }

        return view('laporan.penerimaan_pengiriman',[
            'title' => 'Laporan Penerimaan dan Pengiriman',
            'stok' => $stok,
            'tgl' => $tgl,
            'barang_id' => $barang_id,
            'barang' => Barang::all(),
            'menu' => $menu,
            'submenu' => $submenu
        ]);

    }

    public function pdfPenerimaanPengiriman(Request $request){

        if($request->query('tgl')){
            $tgl = $request->query('tgl');
           
        }else{
            $tgl = date('Y-m-d');
        }

        if($request->query('barang_id')){
            $barang_id = $request->query('barang_id');
           
        }else{
            $barang_id = 'All';
        }

        if ($barang_id != 'All') {
            $stok = Stok::select('stok.*')->selectRaw('SUM(debit_box) as jml_debit_box, SUM(debit_pak) as jml_debit_pak, SUM(debit_kg) as jml_debit_kg, SUM(kredit_box) as jml_kredit_box, SUM(kredit_pak) as jml_kredit_pak, SUM(kredit_kg) as jml_kredit_kg')->where('gudang_id',Session::get('gudang_id'))->where('checker',1)->where('tgl','<=',$tgl)->where('barang_id',$barang_id)->groupBy('barang_id')->groupBy('pallet_id')->groupBy('tgl_exp')->orderBy('barang_id','ASC')->get();
            
        } else {
            $stok = Stok::select('stok.*')->selectRaw('SUM(debit_box) as jml_debit_box, SUM(debit_pak) as jml_debit_pak, SUM(debit_kg) as jml_debit_kg, SUM(kredit_box) as jml_kredit_box, SUM(kredit_pak) as jml_kredit_pak, SUM(kredit_kg) as jml_kredit_kg')->where('gudang_id',Session::get('gudang_id'))->where('checker',1)->where('tgl','<=',$tgl)->groupBy('barang_id')->groupBy('pallet_id')->groupBy('tgl_exp')->orderBy('barang_id','ASC')->get();
        }

        $data = [
            'stok' => $stok,
            'tgl' => $tgl,    
        ];
        $pdf = FacadePdf::loadView('laporan.pdf_penerimaan_pengiriman',$data)->setPaper('a4','portrait');
        return $pdf->stream();
    }

    public function laporanStokKadaluarsa() {

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(14, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('laporan.stok_kadaluarsa',[
            'title' => 'Laporan Penerimaan dan Pengiriman',
            'stok' => Stok::select('stok.*')->selectRaw('(IF(SUM(debit_box)>0,SUM(debit_box),0) - IF(SUM(kredit_box)>0,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak)>0,SUM(debit_pak),0) - IF(SUM(kredit_pak)>0,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg)>0,SUM(debit_kg),0) - IF(SUM(kredit_kg)>0,SUM(kredit_kg),0)) as sisa_kg')->where('gudang_id',Session::get('gudang_id'))->where('checker',1)->where('tgl_exp','<=',date('Y-m-d'))->groupBy('barang_id')->groupBy('pallet_id')->groupBy('tgl_exp')->orderBy('barang_id','ASC')->get(),
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }

    public function pdfStokKadaluarsa(){
        $data = [
            'stok' => Stok::select('stok.*')->selectRaw('(IF(SUM(debit_box)>0,SUM(debit_box),0) - IF(SUM(kredit_box)>0,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak)>0,SUM(debit_pak),0) - IF(SUM(kredit_pak)>0,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg)>0,SUM(debit_kg),0) - IF(SUM(kredit_kg)>0,SUM(kredit_kg),0)) as sisa_kg')->where('gudang_id',Session::get('gudang_id'))->where('checker',1)->where('tgl_exp','<=',date('Y-m-d'))->groupBy('barang_id')->groupBy('pallet_id')->groupBy('tgl_exp')->orderBy('barang_id','ASC')->get(),    
        ];
        $pdf = FacadePdf::loadView('laporan.pdf_stok_kadaluarsa',$data)->setPaper('a4','portrait');
        return $pdf->stream();
    }



}
