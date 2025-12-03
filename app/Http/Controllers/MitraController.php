<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Mitra;
use App\Models\Submenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MitraController extends Controller
{
    public function index()
    {

        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {

            foreach (Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu[] = $d->submenu_id;
            }
        }

        if (!in_array(17, $dt_akses_submenu, true)) {
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu', 'menu.id', '=', 'submenu.menu_id')->whereIn('submenu.id', $dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id', 'ASC')->get();
        $submenu = Submenu::whereIn('id', $dt_akses_submenu)->orderBy('id', 'ASC')->get();

        return view('mitra.index', [
            'title' => 'Mitra',
            'mitra' => Mitra::orderBy('nm_mitra', 'ASC')->get(),
            'menu' => $menu,
            'submenu' => $submenu,
        ]);
    }

    public function addMitra(Request $request)
    {


        Mitra::create([
            'nm_mitra' => $request->nm_mitra
        ]);

        return redirect()->back()->with('success', 'Data mitra berhasil dibuat');
    }

    public function editMitra(Request $request)
    {


        Mitra::where('id', $request->id)->update([
            'nm_mitra' => $request->nm_mitra
        ]);

        return redirect()->back()->with('success', 'Data mitra berhasil diubah');
    }
}
