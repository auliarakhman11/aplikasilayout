<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Cell;
use App\Models\Menu;
use App\Models\Pallet;
use App\Models\Rak;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LayoutController extends Controller
{
    public function index()
    {

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {

            foreach (Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu[] = $d->submenu_id;
            }
        }

        if (!in_array(18, $dt_akses_submenu, true)) {
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu', 'menu.id', '=', 'submenu.menu_id')->whereIn('submenu.id', $dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id', 'ASC')->get();
        $submenu = Submenu::whereIn('id', $dt_akses_submenu)->orderBy('id', 'ASC')->get();

        return view('layout.index', [
            'title' => 'Layout',
            'block' => Block::all(),
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }

    public function addLayout(Request $request)
    {

        $gudang_id = Session::get('gudang_id');
        $jml_fg = $request->jml_fg;
        $jml_lantai = $request->jml_lantai;
        $jml_pallet = $request->jml_pallet;

        $block = Block::create([
            'gudang_id' => $gudang_id,
            'nm_block' => $request->nm_block,
        ]);

        $dt_pallet = [];

        for ($i = 1; $i <= $jml_fg; $i++) {
            $fg = Cell::create([
                'block_id' => $block->id,
                'nm_cell' => 'FG ' . $i
            ]);

            for ($l = 1; $l <= $jml_lantai; $l++) {
                $lantai = Rak::create([
                    'cell_id' => $fg->id,
                    'nm_rak' => 'Lantai ' . $l
                ]);

                for ($p = 0; $p <= $jml_pallet; $p++) {
                    $dt_pallet[] = [
                        'id' => $lantai->id . $p,
                        'rak_id' => $lantai->id,
                        'nm_pallet' => 'Pallet ' . $p
                    ];
                }
            }
        }

        Pallet::insert($dt_pallet);

        return redirect()->back()->with('success', 'Data layout berhasil dibuat');
    }
}
