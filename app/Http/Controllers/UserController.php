<?php

namespace App\Http\Controllers;

use App\Models\AksesGudang;
use App\Models\AksesMenu;
use App\Models\Gudang;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Seksi;
use App\Models\Submenu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $dt_akses_submenu = [];
        if (Auth::user()->aksesMenu) {
            
            foreach(Auth::user()->aksesMenu as $d) {
                $dt_akses_submenu [] = $d->submenu_id;                
            }
        }

        if(!in_array(16, $dt_akses_submenu, true)){
            return redirect(route('block'));
        }

        $menu = Menu::select('menu.*')->leftJoin('submenu','menu.id','=','submenu.menu_id')->whereIn('submenu.id',$dt_akses_submenu)->groupBy('menu.id')->orderBy('menu.id','ASC')->get();
        $submenu = Submenu::whereIn('id',$dt_akses_submenu)->orderBy('id','ASC')->get();

        return view('user.index',[
            'title' => 'User',
            'user' => User::orderBy('role_id','ASC')->with('role')->get(),
            'role' => Role::all(),
            'data_menu' => Menu::all(),
            'menu' => $menu,
            'submenu' => $submenu,
            'gudang' => Gudang::all(),
        ]);
    }

    // public function getDataUser()
    // {
    //     $dt_user = User::query()->orderBy('role_id','ASC')->with(['role']);
    //     return datatables()->of($dt_user)
    //                     ->addColumn('action', function($data){
    //                         $button = '<button type="button" class="btn btn-sm btn-primary edit_user" data-bs-toggle="modal" data-bs-target="#modal_edit_user" user_id="'.$data->id.'"><i class="bx bx-cloud-upload"></i></button>';
    //                         $button .= '&nbsp;&nbsp;';   
    //                         return $button;
    //                     })
    //                     ->rawColumns(['action'])        
    //                     ->addIndexColumn()
    //                     ->make(true);
    // }

    public function addUser()
    {
        $validator = request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed'],
            'role_id' => ['required'],
        ],
        [
            'name.required' => 'Nama tidak boleh kosong',
            'name.string' => 'Nama hanya boleh hufuf dan angka',
            'name.max' => 'Nama maksimal 255 karakter',
            'username.required' => 'Username tidak boleh kosong',
            'username.string' => 'Username hanya boleh hufuf dan angka',
            'username.max' => 'Username maksimal 255 karakter',
            'username.unique' => 'Username yang sama sudah terdaftar',
            'password.required' => 'Password tidak boleh kosong',
            'password.string' => 'Password hanya boleh hufuf dan angka',
            'password.confirmed' => 'Password tidak sama',
            'role_id.required' => 'Role harus diisi',

        ]
    );
            
            
        // if ($validator->fails())
        // {
        //     return response()->json(['errors'=>$validator->errors()->all()]);
        // }

        $user = User::create([
            'name' => request('name'),
            'username' => request('username'),
            'password' => bcrypt(request('password')),
            'role_id' => request('role_id'),
            'aktif' => 1
        ]);

        $submenu_id = request('submenu_id');

        if (!empty($submenu_id)) {
            $dt_submenu = [];
            foreach ($submenu_id as $s) {

                $dt_submenu [] = [
                    'user_id' => $user->id,
                    'submenu_id' => $s,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            AksesMenu::insert($dt_submenu);
        }

        $gudang_id = request('gudang_id');

        if (!empty($gudang_id)) {
            $dt_gudang = [];
            foreach ($gudang_id as $g) {

                $dt_gudang [] = [
                    'user_id' => $user->id,
                    'gudang_id' => $g,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            AksesGudang::insert($dt_gudang);
        }

        

        return redirect()->back()->with('success' , 'Data user berhasil dibuat');

        
    }

    public function gantiPassword()
    {
        return view('user.ganti_password',[
            'title' => 'Ganti Password'
        ]);
    }

    public function editPassword(Request $request)
    {
        if(!(Hash::check($request->old_password,Auth::user()->password))){
            return redirect(route('gantiPassword'))->with('error','Password sekarang tidak cocok');
        }
        $validator = request()->validate([
            'password' => ['required', 'string', 'confirmed'],
            'old_password' => ['required']
            
        ],
        [
            'password.required' => 'Password tidak boleh kososng',
            'password.string' => 'Password hanya boleh hufuf dan angka',
            'password.confirmed' => 'Password tidak sama',
        ]
        );

        User::where('id',Auth::user()->id)->update([
            'password' => bcrypt($request->password)
            ]);

            return redirect(route('gantiPassword'))->with('success','Password berhasil diganti');
    }

    public function getUser($id)
    {
        $data  = User::where('id',$id)->first();
        return response()->json($data);
    }

    public function editUser(Request $request)
    {
        User::where('id',$request->id)->update([
            'name' => $request->name,
            'role_id' => $request->role_id,
            ]);

        AksesMenu::where('user_id',$request->id)->delete();

        $submenu_id = $request->submenu_id;

        if (!empty($submenu_id)) {
            $dt_submenu = [];
            foreach ($submenu_id as $s) {

                $dt_submenu [] = [
                    'user_id' => $request->id,
                    'submenu_id' => $s,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            AksesMenu::insert($dt_submenu);
        }



        AksesGudang::where('user_id',$request->id)->delete();

        $gudang_id = $request->gudang_id;

        if (!empty($gudang_id)) {
            $dt_gudang = [];
            foreach ($gudang_id as $s) {

                $dt_gudang [] = [
                    'user_id' => $request->id,
                    'gudang_id' => $s,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }

            AksesGudang::insert($dt_gudang);
        }
        
        
        return redirect()->back()->with('success' , 'Data user berhasil diubah');

        
    }

}
