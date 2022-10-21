<?php

namespace App\Http\Controllers;

use App\Models\Consultorios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Const_;

class ConsultoriosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(auth()->user()->rol != "Administrador" && auth()->user()->rol != "Secretaria"){
            return redirect('inicio');
        }

        $consultorios = Consultorios::all();
        return view('modulos.Consultorios')->with('consultorios', $consultorios);
    }

    
    public function store(Request $request)
    {
        Consultorios::create(['consultorio' => request('consultorio')]);
        return redirect('consultorios');
    }

    
    public function update(Request $request)
    {
        DB::table('consultorios')->where('id', request('id'))->update(['consultorio' => request('consultorioE')]);

        return redirect('consultorios');
    }

    public function destroy($id)
    {
        DB::table('consultorios')->whereId($id)->delete();
        return redirect('consultorios');
    }

    /* Visualizar consultorios como pacientes */
    public function verConsultorios()
    {
       $consultorios = Consultorios::all();
       
       return view('modulos.Ver-Consultorios', compact('consultorios'));
    }
}
