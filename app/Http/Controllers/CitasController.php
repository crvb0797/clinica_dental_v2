<?php

namespace App\Http\Controllers;

use App\Models\Citas;
use App\Models\Consultorios;
use App\Models\Doctores;
use App\Models\Pacientes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CitasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        if (auth()->user()->rol == "Doctor" && $id != auth()->user()->id) {
            return redirect('inicio');
        }

        $pacientes = Pacientes::all();
        $horarios = DB::select('select * from horarios where id_doctor = '.$id);
        $citas = Citas::all()->where('id_doctor', $id);
        $doctor = Doctores::find($id);


        return view('modulos.Citas', compact('horarios', 'pacientes', 'citas', 'doctor'));
    }

    public function HorarioDoctor(Request $request)
    {
        $datos = request();

        DB::table('horarios')->insert(['id_doctor' => auth()->user()->id, 'horaInicio' => $datos["horaInicio"], 'horaFin' => $datos["horaFin"]]);

        return redirect('citas/'.auth()->user()->id);
    }

    public function EditarHorario(Request $request)
    {
        $datos = request();

        DB::table('horarios')->where('id', $datos['id'])->update(['horaInicio' => $datos['horaInicioE'], 'horaFin' => $datos['horaFinE']]);

        return redirect('citas/'.auth()->user()->id);
    }
   

    public function CrearCita(Request $id_doctor)
    {
        Citas::create(['id_doctor' => request('id_doctor'), 'id_paciente' => request('id_paciente'), 'FyHinicio' => request('FyHinicio'), 'FyHfinal' => request('FyHfinal')]);

        return redirect('citas/'.request('id_doctor'));
    }


    public function destroy(Citas $citas)
    {
        DB::table('citas')->whereId(request('idCita'))->delete();

        return redirect('citas/'.request('idDoctor'));
    }

    public function historial()
    {

        if (auth()->user()->rol != "Paciente") {
            return view('modulos.inicio');
        }else{
            $citas = Citas::all()->where('id_paciente', auth()->user()->id);
            $doctores = User::all()->where('rol', 'Doctor');
            $consultorios = Consultorios::all();
            return view("modulos.Historial", compact('citas', 'doctores', 'consultorios'));
        }

    }
}
