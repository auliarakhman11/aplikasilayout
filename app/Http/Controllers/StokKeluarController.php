<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Menu;
use App\Models\Mitra;
use App\Models\Shift;
use App\Models\Stok;
use App\Models\Submenu;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Session;

class StokKeluarController extends Controller
{
    public function inputStokKeluar(){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(8, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('stok_keluar.input_stok_keluar',[
            'title' => 'Stok Keluar',
            'barang' => Barang::orderBy('nm_barang','ASC')->get(),
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }

    public function getDetailBarang($barang_id){
        $dtBarang = Stok::select('stok.*')->selectRaw("dt_stok.sisa_box, dt_stok.sisa_pak, dt_stok.sisa_kg")
        ->leftJoin(
            DB::raw("(SELECT barang_id, pallet_id, tgl_exp, (IF(SUM(debit_box)>0,SUM(debit_box),0) - IF(SUM(kredit_box)>0,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak)>0,SUM(debit_pak),0) - IF(SUM(kredit_pak)>0,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg)>0,SUM(debit_kg),0) - IF(SUM(kredit_kg)>0,SUM(kredit_kg),0)) as sisa_kg FROM stok WHERE barang_id = $barang_id AND void = 0  GROUP BY barang_id, pallet_id, tgl_exp) dt_stok"), 
            function($join)
                         {
                            $join->on('stok.barang_id', '=', 'dt_stok.barang_id');
                            $join->on('stok.pallet_id', '=', 'dt_stok.pallet_id');
                            $join->on('stok.tgl_exp', '=', 'dt_stok.tgl_exp');
                         }
        )
        ->where('gudang_id',Session::get('gudang_id'))->where('stok.barang_id',$barang_id)->where('void',0)->where('dt_stok.sisa_box','>',0)->where('dt_stok.sisa_pak','>',0)->where('dt_stok.sisa_kg','>',0)->groupBy('pallet_id')->groupBy('tgl_exp')->orderBy('tgl_exp','ASC')->with(['barang','block','cell','rak','pallet'])->get();

        // $dtBarang = Stok::where('barang_id',$barang_id)->get();
        
        return view('stok_keluar.pilih_barang',[
            'dtBarang' => $dtBarang
        ])->render();
    }

    public function getDetailBarangQR($pallet_id){
        $dtBarang = Stok::select('stok.*')->selectRaw("dt_stok.sisa_box, dt_stok.sisa_pak, dt_stok.sisa_kg")
        ->leftJoin(
            DB::raw("(SELECT barang_id, pallet_id, tgl_exp, (IF(SUM(debit_box)>0,SUM(debit_box),0) - IF(SUM(kredit_box)>0,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak)>0,SUM(debit_pak),0) - IF(SUM(kredit_pak)>0,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg)>0,SUM(debit_kg),0) - IF(SUM(kredit_kg)>0,SUM(kredit_kg),0)) as sisa_kg FROM stok WHERE pallet_id = $pallet_id AND void = 0  GROUP BY barang_id, pallet_id, tgl_exp) dt_stok"), 
            function($join)
                         {
                            $join->on('stok.barang_id', '=', 'dt_stok.barang_id');
                            $join->on('stok.pallet_id', '=', 'dt_stok.pallet_id');
                            $join->on('stok.tgl_exp', '=', 'dt_stok.tgl_exp');
                         }
        )
        ->where('gudang_id',Session::get('gudang_id'))->where('stok.pallet_id',$pallet_id)->where('void',0)->where('dt_stok.sisa_box','>',0)->where('dt_stok.sisa_pak','>',0)->where('dt_stok.sisa_kg','>',0)->groupBy('barang_id')->groupBy('pallet_id')->groupBy('tgl_exp')->orderBy('tgl_exp','ASC')->with(['barang','block','cell','rak','pallet'])->get();

        // $dtBarang = Stok::where('barang_id',$barang_id)->get();
        
        return view('stok_keluar.pilih_barang',[
            'dtBarang' => $dtBarang
        ])->render();
    }

    public function addCartKeluar(Request $request){
        
        // $pecah_block = explode( "|", $request->block_id );
        // $pecah_cell = explode( "|", $request->cell_id );
        // $pecah_rak = explode( "|", $request->rak_id );
        // $pecah_barang = explode( "|", $request->barang_id );

        $barang_id = $request->barang_id;
        $block_id = $request->block_id;
        $cell_id = $request->cell_id;
        $rak_id = $request->rak_id;
        $pallet_id = $request->pallet_id;
        $barang = $request->barang;
        $block = $request->block;
        $cell = $request->cell;
        $rak = $request->rak;
        $pallet = $request->pallet;
        $tgl_exp = $request->tgl_exp;
        $kredit_box = $request->kredit_box;
        $kredit_pak = $request->kredit_pak;
        $kredit_kg = $request->kredit_kg;

        for($count = 0; $count<count($barang_id); $count++){

            Cart::instance('keluar')->add([
                'id' => $barang_id[$count].$block_id[$count].$cell_id[$count].$rak_id[$count].$tgl_exp[$count],
                'name' => $barang[$count].$block[$count].$cell[$count].$rak[$count].$tgl_exp[$count],
                'qty' => 1,
                'price' => 1,
                'options' => [
                    'block_id' => $block_id[$count],
                    'cell_id' => $cell_id[$count],
                    'rak_id' => $rak_id[$count],
                    'pallet_id' => $pallet_id[$count],
                    'barang_id' => $barang_id[$count],
                    'block' => $block[$count],
                    'cell' => $cell[$count],
                    'rak' => $rak[$count],
                    'pallet' => $pallet[$count],
                    'barang' => $barang[$count],
                    'tgl_exp' => $tgl_exp[$count],
                    'kredit_box' => $kredit_box[$count],
                    'kredit_pak' => $kredit_pak[$count],
                    'kredit_kg' => $kredit_kg[$count],
                    ]
                ]);
        }

        

    }

    public function getCartKeluar(){

        
        return view('stok_keluar.get_cart',[
            'cart' => Cart::instance('keluar')->content(),
            'count' => Cart::instance('keluar')->count(),
            'mitra' => Mitra::all(),
            'shift' => Shift::all(),
        ])->render();
    }

    public function deleteCartKeluar($id)
    {
        Cart::instance('keluar')->remove($id);

        return true;
    }


    public function saveStokKeluar(Request $request){
        $cart = Cart::instance('keluar')->content();

        $kd_gabungan = 'K'.date('dmy').strtoupper(Str::random(5));

        foreach ($cart as $c) {
            Stok::create([
                'kd_gabungan' => $kd_gabungan,
                'barang_id' => $c->options->barang_id,
                'block_id' => $c->options->block_id,
                'cell_id' => $c->options->cell_id,
                'rak_id' => $c->options->rak_id,
                'pallet_id' => $c->options->pallet_id,
                'kredit_box' => $c->options->kredit_box,
                'kredit_pak' => $c->options->kredit_pak,
                'kredit_kg' => $c->options->kredit_kg,
                'status' => 2,
                'user_id' => Auth::user()->id,
                'tgl' => $request->tgl,
                'mitra_id' => $request->mitra_id,
                'shift_id' => $request->shift_id,
                'tgl_exp' => $c->options->tgl_exp,
                'gudang_id' => Session::get('gudang_id'),
            ]);
        }

        Cart::instance('keluar')->destroy();
        return true;

    }

    public function checkerKeluar(){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(9, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('stok_keluar.checker_keluar',[
            'title' => 'Checker Keluar',
            'checker' => Stok::groupBy('kd_gabungan')->where('gudang_id',Session::get('gudang_id'))->where('checker',0)->where('status',2)->get(),
            'menu' => $menu,
            'submenu' => $submenu
        ]);
    }

    public function detailKeluar($kd_gabungan){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(9, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('stok_keluar.detail_keluar',[
            'title' => 'Detail Checker',
            'checker' => Stok::where('gudang_id',Session::get('gudang_id'))->where('checker',0)->where('kd_gabungan',$kd_gabungan)->orderBy('id','DESC')->get(),
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }

    public function pdfDetailKeluar($kd_gabungan){
        $data = [
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('checker',0)->where('kd_gabungan',$kd_gabungan)->orderBy('id','DESC')->get()
            ];
        $pdf = FacadePdf::loadView('stok_keluar.pdf_detail_keluar',$data)->setPaper('a4','portrait');
        return $pdf->stream();
    }

    public function addCheckerKeluar(Request $request){
        $terima = $request->terima;
        $tolak = $request->tolak;
        $ket_checker = $request->ket_checker;

        if ($terima) {
            for($count = 0; $count<count($terima); $count++){
                Stok::where('id',$terima[$count])->update([
                    'checker' => 1,
                    'user_cehcker_id' => Auth::user()->id,
                ]);
            }
        }

        if ($tolak) {
            for($count = 0; $count<count($tolak); $count++){
                Stok::where('id',$tolak[$count])->update([
                    'checker' => 1,
                    'user_cehcker_id' => Auth::user()->id,
                    'ket_checker' => $ket_checker[$count],
                    'void' => 1
                ]);
            }
        }
        

        // $dt_tolak = Stok::where('kd_gabungan',$request->kd_gabungan)->where('checker',0)

        return redirect(route('checkerKeluar'))->with('success' , 'Data barang berhasil dibuat');
    }

    public function listStokKeluar(Request $request){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(10, $dt_akses_submenu, true)){
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

        return view('stok_keluar.list_stok_keluar',[
            'title' => 'List Stok Keluar',
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('status',2)->where('tgl','>=',$tgl1)->where('tgl','<=',$tgl2)->get(),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'menu' => $menu,
            'submenu' => $submenu
        ]);
    }

    public function deleteStokKeluar($id){
        Stok::where('id',$id)->delete();
        return redirect()->back()->with('success' , 'Data berhasil dihapus');
    }

    
}
