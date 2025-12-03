<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Block;
use App\Models\Cell;
use App\Models\Menu;
use App\Models\Mitra;
use App\Models\Pallet;
use App\Models\Rak;
use App\Models\Shift;
use App\Models\Stok;
use App\Models\Submenu;
use Illuminate\Http\Request;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    public function dataBlock(){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(4, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('dashboard.data_block',[
            'title' => 'Home',
            'block' => Block::where('gudang_id',Session::get('gudang_id'))->with(['cell','cell.rak'])->get(),
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }

    public function detailBlock($id){
        $dt_stok = Stok ::select('stok.*')->selectRaw('(IF(SUM(debit_box)>0,SUM(debit_box),0) - IF(SUM(kredit_box)>0,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak)>0,SUM(debit_pak),0) - IF(SUM(kredit_pak)>0,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg)>0,SUM(debit_kg),0) - IF(SUM(kredit_kg)>0,SUM(kredit_kg),0)) as sisa_kg')->where('block_id',$id)->groupBy('tgl_exp')->groupBy('barang_id')->get();

        return view('dashboard.detail_block',[
            'dt_stok' => $dt_stok,
        ])->render();
    }

    public function detailCell($id){
        $dt_stok = Stok ::select('stok.*')->selectRaw('(IF(SUM(debit_box)>0,SUM(debit_box),0) - IF(SUM(kredit_box)>0,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak)>0,SUM(debit_pak),0) - IF(SUM(kredit_pak)>0,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg)>0,SUM(debit_kg),0) - IF(SUM(kredit_kg)>0,SUM(kredit_kg),0)) as sisa_kg')->where('cell_id',$id)->groupBy('tgl_exp')->groupBy('barang_id')->get();

        return view('dashboard.detail_block',[
            'dt_stok' => $dt_stok,
        ])->render();
    }

    public function detailRak($id){
        $dt_stok = Stok ::select('stok.*')->selectRaw('(IF(SUM(debit_box)>0,SUM(debit_box),0) - IF(SUM(kredit_box)>0,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak)>0,SUM(debit_pak),0) - IF(SUM(kredit_pak)>0,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg)>0,SUM(debit_kg),0) - IF(SUM(kredit_kg)>0,SUM(kredit_kg),0)) as sisa_kg')->where('rak_id',$id)->groupBy('tgl_exp')->groupBy('barang_id')->groupBy('pallet_id')->orderBy('barang_id','ASC')->get();

        return view('dashboard.detail_rak',[
            'dt_stok' => $dt_stok,
        ])->render();
    }

    public function dataStok(){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(5, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        $dt_stok = Stok ::select('stok.*')->selectRaw('(IF(SUM(debit_box) IS NOT NULL,SUM(debit_box),0) - IF(SUM(kredit_box) IS NOT NULL,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak) IS NOT NULL,SUM(debit_pak),0) - IF(SUM(kredit_pak) IS NOT NULL,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg) IS NOT NULL,SUM(debit_kg),0) - IF(SUM(kredit_kg) IS NOT NULL,SUM(kredit_kg),0)) as sisa_kg')->where('gudang_id',Session::get('gudang_id'))->where('checker',1)->groupBy('barang_id')->get();


        $tgl1 = date('Y-m-d', strtotime("-7 day", strtotime(date("Y-m-d"))));
        $tgl2 = date('Y-m-d');
        $stok_perhari = Stok::select('stok.*')->selectRaw("SUM(debit_box) as jml_debit_box, SUM(debit_pak) as jml_debit_pak, SUM(debit_kg) as jml_debit_kg, SUM(kredit_box) as jml_kredit_box, SUM(kredit_pak) as jml_kredit_pak, SUM(kredit_kg) as jml_kredit_kg")->where('gudang_id',Session::get('gudang_id'))->where('tgl','>=',$tgl1)->where('tgl','<=',$tgl2)->groupBy('status')->groupBy('tgl')->get();

        $dt_tgl = Stok::select('tgl')->where('tgl','>=',$tgl1)->where('tgl','<=',$tgl2)->groupBy('tgl')->get();

        $data_periode = [];

        foreach($dt_tgl as $pr){
            $data_periode [] =  date("d-m-Y", strtotime($pr->tgl)) ;
        }

        $dt_pr = json_encode($data_periode);

        //penerimaan
        $data_c_penerimaan = [];
        //box Penerimaan
        $dt_chart = [];
        $dt_chart['label']='Box';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',1)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_debit_box : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_penerimaan [] = $dt_chart;
        //end box penerimaan

        //pak penerimaan
        $dt_chart = [];
        $dt_chart['label']='Pack';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',1)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_debit_pak : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_penerimaan [] = $dt_chart;
        //end pak penerimaan

        //pak penerimaan
        $dt_chart = [];
        $dt_chart['label']='Kg';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',1)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_debit_kg : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_penerimaan [] = $dt_chart;
        //end pak penerimaan
        $penerimaan = json_encode($data_c_penerimaan);
        //end penerimaan



        //pendistribusian
        $data_c_pendistribusian = [];
        //box pendistribusian
        $dt_chart = [];
        $dt_chart['label']='Box';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',2)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_kredit_box : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_pendistribusian [] = $dt_chart;
        //end box pendistribusian

        //pak pendistribusian
        $dt_chart = [];
        $dt_chart['label']='Pack';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',2)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_kredit_pak : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_pendistribusian [] = $dt_chart;
        //end pak pendistribusian

        //pak pendistribusian
        $dt_chart = [];
        $dt_chart['label']='Kg';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',2)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_kredit_kg : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_pendistribusian [] = $dt_chart;
        //end pak pendistribusian
        $pendistribusian = json_encode($data_c_pendistribusian);
        //end pendistribusian




        //hold
        $data_c_hold = [];
        //box hold
        $dt_chart = [];
        $dt_chart['label']='Box';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',3)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_debit_box : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_hold [] = $dt_chart;
        //end box hold

        //pak hold
        $dt_chart = [];
        $dt_chart['label']='Pack';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',3)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_debit_pak : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_hold [] = $dt_chart;
        //end pak hold

        //pak hold
        $dt_chart = [];
        $dt_chart['label']='Kg';
        $dt_jml = [];
        foreach($dt_tgl as $pr){

        $dat_stok = $stok_perhari->where('tgl',$pr->tgl)->where('status',3)->first();
            $dt_jml[] = (int) ($dat_stok ? $dat_stok->jml_debit_kg : 0);
        }

        $rc1 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc2 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
        $rc3 = str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);

        $color = $rc1.$rc2.$rc3;

        $dt_chart['data'] =  $dt_jml;
        $dt_chart['backgroundColor'] = '#'.$color;
        $dt_chart['borderColor'] = '#'.$color;
        $dt_chart['borderWidth'] = 0.5;
        $dt_chart['color'] = 'green';
        $data_c_hold [] = $dt_chart;
        //end pak hold
        $hold = json_encode($data_c_hold);
        //end hold

        return view('dashboard.data_stok',[
            'title' => 'Data Stok',
            'dt_stok' => $dt_stok,
            'penerimaan' => $penerimaan,
            'pendistribusian' => $pendistribusian,
            'hold' => $hold,
            'dt_pr' => $dt_pr,
            'menu' => $menu,
            'submenu' => $submenu
        ]);
    }

    public function returnBarang(){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(6, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('dashboard.return_barang',[
            'title' => 'Input Barang Hold',
            'barang' => Barang::orderBy('nm_barang','ASC')->get(),
            'block' => Block::where('gudang_id',Session::get('gudang_id'))->get(),
            'menu' => $menu,
            'submenu' => $submenu
        ]);
    }


    public function addCartReturnBarang(Request $request){
        
        $pecah_block = explode( "|", $request->block_id );
        $pecah_cell = explode( "|", $request->cell_id );
        $pecah_rak = explode( "|", $request->rak_id );
        $pecah_pallet = explode( "|", $request->pallet_id );
        $pecah_barang = explode( "|", $request->barang_id );

        

        Cart::instance('returnBarang')->add([
            'id' => $pecah_barang[0].$pecah_block[0].$pecah_cell[0].$pecah_rak[0].$request->tgl_exp,
            'name' => $pecah_barang[1],
            'qty' => 1,
            'price' => 1,
            'options' => [
                'block_id' => $pecah_block[0],
                'cell_id' => $pecah_cell[0],
                'rak_id' => $pecah_rak[0],
                'pallet_id' => $pecah_pallet[0],
                'barang_id' => $pecah_barang[0],
                'block' => $pecah_block[1],
                'cell' => $pecah_cell[1],
                'rak' => $pecah_rak[1],
                'pallet' => $pecah_pallet[1],
                'barang' => $pecah_barang[1],

                'tgl_exp_edit' => date("d/M/Y", strtotime($request->tgl_exp)),

                'tgl_exp' => $request->tgl_exp,
                'debit_box' => $request->debit_box,
                'debit_pak' => $request->debit_pak,
                'debit_kg' => $request->debit_kg,
                
            ]
            ]);

    }

    public function getCartReturnBarang(){

        return view('dashboard.get_cart',[
            'cart' => Cart::instance('returnBarang')->content(),
            'count' => Cart::instance('returnBarang')->count(),
            'shift' => Shift::all(),
            'mitra' => Mitra::all(),
        ])->render();
    }

    public function deleteCartReturnBarang($id)
    {
        Cart::instance('returnBarang')->remove($id);

        return true;
    }

    public function saveReturnBarang(Request $request){
        $cart = Cart::instance('returnBarang')->content();

        $kd_gabungan = 'R'.date('dmy').strtoupper(Str::random(5));

        foreach ($cart as $c) {
            Stok::create([
                'kd_gabungan' => $kd_gabungan,
                'barang_id' => $c->options->barang_id,
                'block_id' => $c->options->block_id,
                'cell_id' => $c->options->cell_id,
                'rak_id' => $c->options->rak_id,
                'pallet_id' => $c->options->pallet_id,
                'debit_box' => $c->options->debit_box,
                'debit_pak' => $c->options->debit_pak,
                'debit_kg' => $c->options->debit_kg,
                'status' => 3,
                'user_id' => Auth::user()->id,
                'tgl' => $request->tgl,
                'tgl_exp' => $c->options->tgl_exp,
                'shift_id' => $request->shift_id,
                'mitra_id' => $request->mitra_id,
                'checker' => 1,
                'user_cehcker_id' => Auth::user()->id,
                'gudang_id' => Session::get('gudang_id'),
            ]);
        }

        Cart::instance('returnBarang')->destroy();
        return true;

    }

    public function listStokHold(Request $request){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(7, $dt_akses_submenu, true)){
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

        return view('dashboard.list_stok_hold',[
            'title' => 'List Stok Hold',
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('status',3)->where('tgl','>=',$tgl1)->where('tgl','<=',$tgl2)->get(),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }

    public function deleteStokHold($id){
        Stok::where('id',$id)->delete();
        return redirect()->back()->with('success' , 'Data berhasil dihapus');
    }

    public function generateQr($cell_id){
        $data = [
            'cell' => Cell::where('id',$cell_id)->with(['block','rak','rak.pallet'])->first(),
        ];

        $pdf = Pdf::loadView('dashboard.generate_qr',$data)->setPaper('a4','portrait');

        return $pdf->stream();
    }


    public function getPindahBarang($pallet_id,$tgl_exp,$barang_id,$block_id,$cell_id,$rak_id){
        $stok = Stok ::select('stok.*')->selectRaw('(IF(SUM(debit_box)>0,SUM(debit_box),0) - IF(SUM(kredit_box)>0,SUM(kredit_box),0)) as sisa_box, (IF(SUM(debit_pak)>0,SUM(debit_pak),0) - IF(SUM(kredit_pak)>0,SUM(kredit_pak),0)) as sisa_pak, (IF(SUM(debit_kg)>0,SUM(debit_kg),0) - IF(SUM(kredit_kg)>0,SUM(kredit_kg),0)) as sisa_kg')->where('pallet_id',$pallet_id)->where('tgl_exp',$tgl_exp)->where('barang_id',$barang_id)->where('void',0)->first();

        

        return view('dashboard.pindah_barang',[
            'stok' => $stok,
            'block' => Block::where('gudang_id',Session::get('gudang_id'))->get(),
            'cell' => Cell::where('block_id',$block_id)->get(),
            'rak' => Rak::where('cell_id',$cell_id)->get(),
            'pallet' => Pallet::where('rak_id',$rak_id)->get()
        ])->render();
    }

    public function pindahBarang(Request $request){
        
        if ( ($request->block_id ==  $request->block_id_dulu) && ($request->cell_id ==  $request->cell_id_dulu) && ($request->ral_id ==  $request->ral_id_dulu) && ($request->pallet_id ==  $request->pallet_id_dulu) ) {
            return false;
        } else {

            $kd_gabungan = 'PNDH'.date('dmy').strtoupper(Str::random(5));
            Stok::create([
                'kd_gabungan' => $kd_gabungan,
                'barang_id' => $request->barang_id_dulu,
                'block_id' => $request->block_id,
                'cell_id' => $request->cell_id,
                'rak_id' => $request->rak_id,
                'pallet_id' => $request->pallet_id,
                'debit_box' => $request->debit_box,
                'debit_pak' => $request->debit_pak,
                'debit_kg' => $request->debit_kg,
                'status' => 1,
                'user_id' => Auth::id(),
                'tgl' => date('Y-m-d'),
                'tgl_exp' => $request->tgl_exp_dulu,
                'shift_id' => 1,
                'checker' => 1,
                'user_cehcker_id' => Auth::id(),
                'gudang_id' => Session::get('gudang_id'),
            ]);


            Stok::create([
                'kd_gabungan' => $kd_gabungan,
                'barang_id' => $request->barang_id_dulu,
                'block_id' => $request->block_id_dulu,
                'cell_id' => $request->cell_id_dulu,
                'rak_id' => $request->rak_id_dulu,
                'pallet_id' => $request->pallet_id_dulu,
                'kredit_box' => $request->debit_box,
                'kredit_pak' => $request->debit_pak,
                'kredit_kg' => $request->debit_kg,
                'status' => 2,
                'user_id' => Auth::id(),
                'checker' => 1,
                'user_cehcker_id' => Auth::id(),
                'tgl' => date('Y-m-d'),
                'mitra_id' => 37,
                'shift_id' => 1,
                'tgl_exp' => $request->tgl_exp_dulu,
                'gudang_id' => Session::get('gudang_id'),
            ]);

            return true;
        }
        

    }


}
