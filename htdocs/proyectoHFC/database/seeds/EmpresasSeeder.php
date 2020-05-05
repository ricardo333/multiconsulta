<?php

use App\Administrador\Empresa;
use Illuminate\Database\Seeder;

class EmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa = new Empresa;
        $empresa->nombre = "CAMPERU";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "COBRAPERU";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "COTENER";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "DIGITEX";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "DOMINION";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "EZENTIS";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "FRACTALIA";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "HDEC";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "IRISCENE";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "ITALTEL";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "LARI";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "ATENTO";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "TDP";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "TGESTIONA";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "VPTSA";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "INGETEC";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "SEGU";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "ERICSON";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "INDRA";
        $empresa->save();
        $empresa = new Empresa;
        $empresa->nombre = "KONECTA";
        $empresa->save();
    }
}
