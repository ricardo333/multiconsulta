<?php

namespace App\Administrador;

use App\Administrador\TrobasCuarentena;
use Illuminate\Database\Eloquent\Model;
use App\Administrador\ClientesCuarentena;

class GestionCuarentena extends Model
{
    
    protected $connection = 'mysql';

    protected $table = 'gestion_cuarentena';

    public $timestamps = false;

    const PUBLICACION_ACTIVA = "Activo";
    const PUBLICACION_INACTIVA = "Inactivo";
    const ESTADO_ACTIVO = "Activo";
    const ESTADO_INACTIVO = "Inactivo";
    
    protected $fillable = [
        'nombre',
        'jefatura',
        'clientes',
        'trobas',
        'servicePackageCrmid',
        'scopesGroup',
        'estado',
        'cuadroMando',
        'tipo',
        'fechaInicio',
        'fechaFin',
        'fechaRegistro'
    ];

    public function clientesCuarentenas(){
        return $this->hasMany(ClientesCuarentena::class,'idCuarentenas','id');
    }

    public function trobasCuarentenas(){
        return $this->hasMany(TrobasCuarentena::class,'idCuarentenas','id');
    }
 
}
