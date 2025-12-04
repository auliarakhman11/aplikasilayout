<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Block;
use App\Models\Cell;
use App\Models\Menu;
use App\Models\Pallet;
use App\Models\Rak;
use App\Models\Shift;
use App\Models\Stok;
use App\Models\Submenu;
use DateTime;
use Illuminate\Http\Request;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Illuminate\Support\Facades\Session;

class StokMasukController extends Controller
{
    public function inputStokMasuk(){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(1, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('stok_masuk.input_stok_masuk',[
            'title' => 'Stok Masuk ',
            'barang' => Barang::orderBy('nm_barang','ASC')->get(),
            'block' => Block::where('gudang_id','=',Session::get('gudang_id'))->get(),
            'shift' => Shift::all(),
            'menu' => $menu,
            'submenu' => $submenu,

        ]);
    }

    public function checkBlockWh($pallet_id){
        $dt_pallet = Pallet::select('block.gudang_id')
        ->leftJoin('rak','pallet.rak_id','=','rak.id')
        ->leftJoin('cell','rak.cell_id','=','cell.id')
        ->leftJoin('block','cell.block_id','=','block.id')
        ->where('pallet.id',$pallet_id)->first();
        if($dt_pallet->gudang_id != Session::get('gudang_id')){
            return false;
        }else{
            return true;
        }
    }

    public function getCell($block_id){
        $id_block = explode( "|", $block_id );
        $dt_cell = Cell::where('block_id',$id_block[0])->get();

        echo '<option value="">Pilih Cell</option>';
        foreach($dt_cell as $d){
            echo '<option value="'.$d->id.'|'.$d->nm_cell.'">'.$d->nm_cell.'</option>';
        }

    }

    public function getRak($cell_id){
        $id_cell = explode( "|", $cell_id );
        $dt_rak = Rak::where('cell_id',$id_cell[0])->get();

        echo '<option value="">Pilih Lantai</option>';
        foreach($dt_rak as $d){
            echo '<option value="'.$d->id.'|'.$d->nm_rak.'">'.$d->nm_rak.'</option>';
        }

    }

    public function getPallet($rak_id){
        $id_rak = explode( "|", $rak_id );
        $dt_pallet = Pallet::where('rak_id',$id_rak[0])->get();

        echo '<option value="">Pilih Pallet</option>';
        foreach($dt_pallet as $d){
            echo '<option value="'.$d->id.'|'.$d->nm_pallet.'">'.$d->nm_pallet.'</option>';
        }

    }

    public function addCartMasuk(Request $request){
        
        $pecah_block = explode( "|", $request->block_id );
        $pecah_cell = explode( "|", $request->cell_id );
        $pecah_rak = explode( "|", $request->rak_id );
        // $pecah_pallet = explode( "|", $request->pallet_id );
        $pecah_barang = explode( "|", $request->barang_id );

        if (!is_numeric($pecah_barang[0])) {
            return false;
        }

        // $c = Stok::selectRaw("IF(SUM(debit_box) IS NOT NULL, SUM(debit_box), 0) as sisa_debit_box, IF(SUM(debit_pak) IS NOT NULL, SUM(debit_pak), 0) as sisa_debit_pak, IF(SUM(debit_kg) IS NOT NULL, SUM(debit_kg), 0) as sisa_debit_kg, IF(SUM(debit_box) IS NOT NULL, SUM(kredit_box), 0) as sisa_kredit_box, IF(SUM(kredit_pak) IS NOT NULL, SUM(kredit_pak), 0) as sisa_kredit_pak, IF(SUM(kredit_kg) IS NOT NULL, SUM(kredit_kg), 0) as sisa_kredit_kg")->where('gudang_id',Session::get('gudang_id'))->where('block_id',$pecah_block[0])->where('cell_id',$pecah_cell[0])->where('rak_id',$pecah_rak[0])->where('pallet_id',$pecah_pallet[0])->first();

        // if ( ($c->sisa_debit_box - $c->sisa_kredit_box) == 0 && ($c->sisa_debit_pak - $c->sisa_kredit_pak) == 0 && ($c->sisa_debit_kg - $c->sisa_kredit_kg) == 0 ) {
        //     $cek_lokasi = 1;
        // }else{
        //     $cek_lokasi = 0;
        // }

        $cek_lokasi = 1;

        $cek_strip = strpos($request->pallet_id, '-');

        if ($cek_strip) {
            $strip_pallet = explode('-', $request->pallet_id);

            for ($i = $strip_pallet[0]; $i <= $strip_pallet[1] ; $i++) { 
                Cart::instance('masuk')->add([
                'id' => $pecah_barang[0].$pecah_block[0].$pecah_cell[0].$pecah_rak[0].$request->tgl_exp.$request->tgl,
                'name' => $pecah_barang[1],
                'qty' => 1,
                'price' => 1,
                'options' => [
                    'block_id' => $pecah_block[0],
                    'cell_id' => $pecah_cell[0],
                    'rak_id' => $pecah_rak[0],
                    'pallet_id' => $i,
                    'barang_id' => $pecah_barang[0],
                    'block' => $pecah_block[1],
                    'cell' => $pecah_cell[1],
                    'rak' => $pecah_rak[1],
                    'pallet' => 'Pallet '.$i,
                    'barang' => $pecah_barang[1],
                    'tgl_edit' => date("d/M/Y", strtotime($request->tgl)),
                    'tgl_exp_edit' => date("d/M/Y", strtotime($request->tgl_exp)),
                    'tgl' => $request->tgl,
                    'tgl_exp' => $request->tgl_exp,
                    'debit_box' => $request->debit_box,
                    'debit_pak' => $request->debit_pak,
                    'debit_kg' => $request->debit_kg,
                    'shift_id' => $request->shift_id,
                    'cek_lokasi' => $cek_lokasi,
                    
                ]
                ]);
            }

        } else {
            Cart::instance('masuk')->add([
            'id' => $pecah_barang[0].$pecah_block[0].$pecah_cell[0].$pecah_rak[0].$request->tgl_exp.$request->tgl,
            'name' => $pecah_barang[1],
            'qty' => 1,
            'price' => 1,
            'options' => [
                'block_id' => $pecah_block[0],
                'cell_id' => $pecah_cell[0],
                'rak_id' => $pecah_rak[0],
                'pallet_id' => $request->pallet_id,
                'barang_id' => $pecah_barang[0],
                'block' => $pecah_block[1],
                'cell' => $pecah_cell[1],
                'rak' => $pecah_rak[1],
                'pallet' => 'Pallet '.$request->pallet_id,
                'barang' => $pecah_barang[1],
                'tgl_edit' => date("d/M/Y", strtotime($request->tgl)),
                'tgl_exp_edit' => date("d/M/Y", strtotime($request->tgl_exp)),
                'tgl' => $request->tgl,
                'tgl_exp' => $request->tgl_exp,
                'debit_box' => $request->debit_box,
                'debit_pak' => $request->debit_pak,
                'debit_kg' => $request->debit_kg,
                'shift_id' => $request->shift_id,
                'cek_lokasi' => $cek_lokasi,
                
            ]
            ]);
        }
        

        

        

            return true;

    }

    public function getCartMasuk(){

        return view('stok_masuk.get_cart',[
            'cart' => Cart::instance('masuk')->content(),
            'count' => Cart::instance('masuk')->count()
        ])->render();
    }

    public function deleteCartMasuk($id)
    {
        Cart::instance('masuk')->remove($id);

        return true;
    }


    public function saveStokMasuk(){
        $cart = Cart::instance('masuk')->content();

        $kd_gabungan = date('dmy').strtoupper(Str::random(5));

        foreach ($cart as $c) {

            if ($c->options->cek_lokasi == 0) {
                continue;
            }

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
                'status' => 1,
                'user_id' => Auth::user()->id,
                'tgl' => $c->options->tgl,
                'tgl_exp' => $c->options->tgl_exp,
                'shift_id' => $c->options->shift_id,
                'gudang_id' => Session::get('gudang_id'),
            ]);
        }

        Cart::instance('masuk')->destroy();
        return true;

    }

    public function checkerMasuk(){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(2, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('stok_masuk.checker_masuk',[
            'title' => 'Checker Masuk',
            'checker' => Stok::groupBy('kd_gabungan')->where('gudang_id',Session::get('gudang_id'))->where('checker',0)->where('status',1)->get(),
            'menu' => $menu,
            'submenu' => $submenu
        ]);
    }

    public function detailMasuk($kd_gabungan){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(2, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('stok_masuk.detail_masuk',[
            'title' => 'Detail Checker',
            'checker' => Stok::where('gudang_id',Session::get('gudang_id'))->where('checker',0)->where('kd_gabungan',$kd_gabungan)->orderBy('id','DESC')->get(),
            'menu' => $menu,
            'submenu' => $submenu,

        ]);
    }

    public function pdfDetailMasuk($kd_gabungan){
        $data = [
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('checker',0)->where('kd_gabungan',$kd_gabungan)->orderBy('id','DESC')->get()
            ];
        $pdf = FacadePdf::loadView('stok_masuk.pdf_detail_masuk',$data)->setPaper('a4','portrait');
        return $pdf->stream();
    }

    public function addChecker(Request $request){
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

        return redirect(route('checkerMasuk'))->with('success' , 'Data barang berhasil dibuat');
    }


    public function importStokMasuk(Request $request){
        
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx;
        $spreadsheet = $reader->load($_FILES['file_excel']['tmp_name']);
        
        $sheet = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $numrow = 1;

        $barang = Barang::all();
        $block = Block::where('gudang_id',Session::get('gudang_id'))->get();
        $cell = Cell::all();
        $rak = Rak::all();
        $pallet = Pallet::all();

        
            foreach ($sheet as $row) {

                if ($row['A'] == "" ||  $row['B'] == "" ||  $row['C'] == "" ||  $row['D'] == "" ||  $row['E'] == "" ||  $row['F'] == "" ||  $row['G'] == "" ||  $row['H'] == "" ||  $row['I'] == "" )
                    continue;

                // $datetime = DateTime::createFromFormat('Y-m-d', $row['A']);
                if ($numrow > 1) {

                    

                    $row_a = $row['A'];
                    $row_c = 'Block '.$row['C'];
                    $row_d = 'Cell '.$row['D'];
                    $row_e = 'Lantai '.$row['E'];
                    $row_f = 'Pallet '.$row['F'];

                    $dt_barang = $barang->where('nm_barang',$row['A'])->first();

                    $dt_block = $block->where('nm_block',$row_c)->first();

                    if($dt_block){
                        $dt_cell = $cell->where('block_id',$dt_block->id)->where('nm_cell',$row_d)->first();
                    }else{
                        $dt_cell = false;
                    }

                    if($dt_cell){
                        $dt_rak = $rak->where('cell_id',$dt_cell->id)->where('nm_rak',$row_e)->first();
                    }else{
                        $dt_rak = false;
                    }

                    if($dt_rak){
                        $dt_pallet = $pallet->where('rak_id',$dt_rak->id)->where('nm_pallet',$row_f)->first();
                    }else{
                        $dt_pallet = false;
                    }

                    

                    if(!$dt_block || !$dt_cell || !$dt_rak || !$dt_pallet || !$dt_barang){
                        continue;
                    }

                    $tgl_exp = date("Y-m-d", strtotime($row['B']));

                    $c = Stok::selectRaw("IF(SUM(debit_box) IS NOT NULL, SUM(debit_box), 0) as sisa_debit_box, IF(SUM(debit_pak) IS NOT NULL, SUM(debit_pak), 0) as sisa_debit_pak, IF(SUM(debit_kg) IS NOT NULL, SUM(debit_kg), 0) as sisa_debit_kg, IF(SUM(debit_box) IS NOT NULL, SUM(kredit_box), 0) as sisa_kredit_box, IF(SUM(kredit_pak) IS NOT NULL, SUM(kredit_pak), 0) as sisa_kredit_pak, IF(SUM(kredit_kg) IS NOT NULL, SUM(kredit_kg), 0) as sisa_kredit_kg")->where('gudang_id',Session::get('gudang_id'))->where('block_id',$dt_block->id)->where('cell_id',$dt_cell->id)->where('rak_id',$dt_rak->id)->where('pallet_id',$dt_pallet->id)->first();

                    if ( ($c->sisa_debit_box - $c->sisa_kredit_box) == 0 && ($c->sisa_debit_pak - $c->sisa_kredit_pak) == 0 && ($c->sisa_debit_kg - $c->sisa_kredit_kg) == 0 ) {
                        $cek_lokasi = 1;
                    }else{
                        $cek_lokasi = 0;
                    }

                    Cart::instance('masuk')->add([
                            'id' => $dt_barang->id.$dt_block->id.$dt_cell->id.$dt_rak->id.$tgl_exp,
                            'name' => $dt_barang->nm_barang,
                            'qty' => 1,
                            'price' => 1,
                            'options' => [
                                'block_id' => $dt_block->id,
                                'cell_id' => $dt_cell->id,
                                'rak_id' => $dt_rak->id,
                                'pallet_id' => $dt_pallet->id,
                                'barang_id' => $dt_barang->id,
                                'block' => $dt_block->nm_block,
                                'cell' => $dt_cell->nm_cell,
                                'rak' => $dt_rak->nm_rak,
                                'pallet' => $dt_pallet->nm_pallet,
                                'barang' => $dt_barang->nm_barang,
                                'tgl_edit' => date("d/M/Y", strtotime($request->tgl)),
                                'tgl_exp_edit' => date("d/M/Y", strtotime($tgl_exp)),
                                'tgl' => $request->tgl,
                                'tgl_exp' => $tgl_exp,
                                'debit_box' => $row['G'],
                                'debit_pak' => $row['H'],
                                'debit_kg' => $row['I'],
                                'shift_id' => $request->shift_id,
                                'cek_lokasi' => $cek_lokasi
                                
                            ]
                        ]);

                }
                $numrow++; // Tambah 1 setiap kali looping
            }

            return redirect()->back()->with('success' , 'Data berhasil dibuat');

    }


    public function downloadFormat()
    {
        return response()->download('/home/u376106710/domains/cpibandung.com/public_html/aplikasilayout/img/format.xlsx');
    }

    public function listStokMasuk(Request $request){

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(3, $dt_akses_submenu, true)){
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

        return view('stok_masuk.list_stok_masuk',[
            'title' => 'List Stok Masuk',
            'stok' => Stok::where('gudang_id',Session::get('gudang_id'))->where('status',1)->where('tgl','>=',$tgl1)->where('tgl','<=',$tgl2)->get(),
            'tgl1' => $tgl1,
            'tgl2' => $tgl2,
            'menu' => $menu,
            'submenu' => $submenu
        ]);
    }

    public function deleteStokMasuk($id){
        Stok::where('id',$id)->delete();
        return redirect()->back()->with('success' , 'Data berhasil dihapus');
    }

    public function getStokMasuk($stok_id){
        $stok = Stok::where('id',$stok_id)->first();
        return view('stok_masuk.get_stok_masuk',[
            'stok' => $stok,
            'barang' => Barang::all(),
            'block' => Block::where('gudang_id',Session::get('gudang_id'))->get(),
            'cell' => Cell::where('block_id',$stok->block_id)->get(),
            'rak' => Rak::where('cell_id',$stok->cell_id)->get(),
            'pallet' => Pallet::where('rak_id',$stok->rak_id)->get(),

        ]);
    }

    public function editStokMasuk(Request $request){
        Stok::where('id',$request->id)->update([
            'barang_id' => $request->barang_id,
            'tgl_exp' => $request->tgl_exp,
            'block_id' => $request->block_id,
            'cell_id' => $request->cell_id,
            'rak_id' => $request->rak_id,
            'pallet_id' => $request->pallet_id,
            'debit_box' => $request->debit_box,
            'debit_kg' => $request->debit_kg,
            'debit_pak' => $request->debit_pak,
        ]);

        return redirect()->back()->with('success' , 'Data mitra berhasil diubah');
    }

    public function getDataPallet($pallet_id){
        $dt_pallet = Pallet::select('pallet.id','pallet.rak_id','rak.cell_id','cell.block_id', 'pallet.nm_pallet', 'rak.nm_rak', 'cell.nm_cell', 'block.nm_block')
        ->leftJoin('rak','pallet.rak_id','=','rak.id')
        ->leftJoin('cell','rak.cell_id','=','cell.id')
        ->leftJoin('block','cell.block_id','=','block.id')
        ->where('pallet.id',$pallet_id)->first();

        $data = [
            'pallet_id' => $dt_pallet->id,
            'rak_id' => $dt_pallet->rak_id,
            'cell_id' => $dt_pallet->cell_id,
            'block_id' => $dt_pallet->block_id,

            'nm_pallet' => $dt_pallet->nm_pallet,
            'nm_rak' => $dt_pallet->nm_rak,
            'nm_cell' => $dt_pallet->nm_cell,
            'nm_block' => $dt_pallet->nm_block,
            
        ];

        return response()->json($data);
    }

    public function inputPallet(){
        //Block A
            $dt_block = Block::create([
                'gudang_id' => 4,
                'nm_block' => 'Block A'
            ]);

            for($count_cell = 3; $count_cell>=1; $count_cell--){
                $dt_cell = Cell::create([
                    'block_id' => $dt_block->id,
                    'nm_cell' => 'Cell '.$count_cell
                ]);

                for($count_rak = 1; $count_rak<=4; $count_rak++){
                    $dt_rak = Rak::create([
                        'cell_id' => $dt_cell->id,
                        'nm_rak' => 'Lantai '.$count_rak
                    ]);

                    for($count_pallet = 1; $count_pallet<=4; $count_pallet++){
                        Pallet::create([
                            'rak_id' => $dt_rak->id,
                            'nm_pallet' => 'Pallet '.$count_pallet
                        ]);
                    }

                }

            }

        
        //end Block A

        //Block B
        $dt_block = Block::create([
            'gudang_id' => 4,
            'nm_block' => 'Block B'
        ]);

        for($count_cell = 3; $count_cell>=1; $count_cell--){
            $dt_cell = Cell::create([
                'block_id' => $dt_block->id,
                'nm_cell' => 'Cell '.$count_cell
            ]);

            for($count_rak = 1; $count_rak<=4; $count_rak++){
                $dt_rak = Rak::create([
                    'cell_id' => $dt_cell->id,
                    'nm_rak' => 'Lantai '.$count_rak
                ]);

                for($count_pallet = 1; $count_pallet<=4; $count_pallet++){
                    Pallet::create([
                        'rak_id' => $dt_rak->id,
                        'nm_pallet' => 'Pallet '.$count_pallet
                    ]);
                }

            }

        }
        //end Block B

        //Block C
        $dt_block = Block::create([
            'gudang_id' => 4,
            'nm_block' => 'Block C'
        ]);

        for($count_cell = 3; $count_cell>=1; $count_cell--){
            $dt_cell = Cell::create([
                'block_id' => $dt_block->id,
                'nm_cell' => 'Cell '.$count_cell
            ]);

            for($count_rak = 1; $count_rak<=4; $count_rak++){
                $dt_rak = Rak::create([
                    'cell_id' => $dt_cell->id,
                    'nm_rak' => 'Lantai '.$count_rak
                ]);

                for($count_pallet = 1; $count_pallet<=4; $count_pallet++){
                    Pallet::create([
                        'rak_id' => $dt_rak->id,
                        'nm_pallet' => 'Pallet '.$count_pallet
                    ]);
                }

            }

        }
        //end Block C

        //Block D
        $dt_block = Block::create([
            'gudang_id' => 4,
            'nm_block' => 'Block D'
        ]);

        for($count_cell = 3; $count_cell>=1; $count_cell--){
            $dt_cell = Cell::create([
                'block_id' => $dt_block->id,
                'nm_cell' => 'Cell '.$count_cell
            ]);

            for($count_rak = 1; $count_rak<=4; $count_rak++){
                $dt_rak = Rak::create([
                    'cell_id' => $dt_cell->id,
                    'nm_rak' => 'Lantai '.$count_rak
                ]);

                for($count_pallet = 1; $count_pallet<=4; $count_pallet++){
                    Pallet::create([
                        'rak_id' => $dt_rak->id,
                        'nm_pallet' => 'Pallet '.$count_pallet
                    ]);
                }

            }

        }
        //end Block D

        //Block E
        // $dt_block = Block::create([
        //     'gudang_id' => 3,
        //     'nm_block' => 'Block E'
        // ]);

        // for($count_cell = 15; $count_cell>=1; $count_cell--){
        //     $dt_cell = Cell::create([
        //         'block_id' => $dt_block->id,
        //         'nm_cell' => 'Cell '.$count_cell
        //     ]);

        //     for($count_rak = 1; $count_rak<=4; $count_rak++){
        //         $dt_rak = Rak::create([
        //             'cell_id' => $dt_cell->id,
        //             'nm_rak' => 'Lantai '.$count_rak
        //         ]);

        //         for($count_pallet = 1; $count_pallet<=4; $count_pallet++){
        //             Pallet::create([
        //                 'rak_id' => $dt_rak->id,
        //                 'nm_pallet' => 'Pallet '.$count_pallet
        //             ]);
        //         }

        //     }

        // }
        //end Block E

        //Block F
        // $dt_block = Block::create([
        //     'gudang_id' => 3,
        //     'nm_block' => 'Block F'
        // ]);

        // for($count_cell = 15; $count_cell>=1; $count_cell--){
        //     $dt_cell = Cell::create([
        //         'block_id' => $dt_block->id,
        //         'nm_cell' => 'Cell '.$count_cell
        //     ]);

        //     for($count_rak = 1; $count_rak<=4; $count_rak++){
        //         $dt_rak = Rak::create([
        //             'cell_id' => $dt_cell->id,
        //             'nm_rak' => 'Lantai '.$count_rak
        //         ]);

        //         for($count_pallet = 1; $count_pallet<=4; $count_pallet++){
        //             Pallet::create([
        //                 'rak_id' => $dt_rak->id,
        //                 'nm_pallet' => 'Pallet '.$count_pallet
        //             ]);
        //         }

        //     }

        // }
        //end Block F

        //Block G
        // $dt_block = Block::create([
        //     'gudang_id' => 3,
        //     'nm_block' => 'Block G'
        // ]);

        // for($count_cell = 1; $count_cell<=7; $count_cell++){
        //     $dt_cell = Cell::create([
        //         'block_id' => $dt_block->id,
        //         'nm_cell' => 'Cell '.$count_cell
        //     ]);

        //     for($count_rak = 1; $count_rak<=4; $count_rak++){
        //         $dt_rak = Rak::create([
        //             'cell_id' => $dt_cell->id,
        //             'nm_rak' => 'Lantai '.$count_rak
        //         ]);

        //         for($count_pallet = 1; $count_pallet<=4; $count_pallet++){
        //             Pallet::create([
        //                 'rak_id' => $dt_rak->id,
        //                 'nm_pallet' => 'Pallet '.$count_pallet
        //             ]);
        //         }

        //     }

        // }
        //end Block G

        //Block H
        // $dt_block = Block::create([
        //     'gudang_id' => 3,
        //     'nm_block' => 'Block H'
        // ]);

        // for($count_cell = 1; $count_cell<=8; $count_cell++){
        //     $dt_cell = Cell::create([
        //         'block_id' => $dt_block->id,
        //         'nm_cell' => 'Cell '.$count_cell
        //     ]);

        //     for($count_rak = 1; $count_rak<=4; $count_rak++){
        //         $dt_rak = Rak::create([
        //             'cell_id' => $dt_cell->id,
        //             'nm_rak' => 'Lantai '.$count_rak
        //         ]);

        //         for($count_pallet = 1; $count_pallet<=4; $count_pallet++){
        //             Pallet::create([
        //                 'rak_id' => $dt_rak->id,
        //                 'nm_pallet' => 'Pallet '.$count_pallet
        //             ]);
        //         }

        //     }

        // }
        //end Block H

        return 'Ya';


    }




}
