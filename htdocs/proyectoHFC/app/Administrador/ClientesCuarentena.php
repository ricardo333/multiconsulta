<?php

namespace App\Administrador;

use Illuminate\Database\Eloquent\Model;

class ClientesCuarentena extends Model
{
    protected $connection = 'mysql';

    protected $table = 'clientes_cuarentenas';

    public $timestamps = false;
    
    protected $fillable = [
        'idCuarentenas',
        'idCliente',
        'jefatura',
        'nodo',
        'troba',
        'servicePackageCrmid',
        'scopesGroup'
    ];

    public function cuarentena(){
        return $this->belongsTo(GestionCuarentena::class,'idCuarentenas','id');
    }

      
}
