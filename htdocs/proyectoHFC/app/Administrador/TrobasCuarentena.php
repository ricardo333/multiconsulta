<?php

namespace App\Administrador;

use Illuminate\Database\Eloquent\Model;
use App\Administrador\GestionCuarentena;

class TrobasCuarentena extends Model
{

    protected $connection = 'mysql';

    protected $table = 'trobas_cuarentenas';

    public $timestamps = false;
    
    protected $fillable = [
        'idCuarentenas',
        'nodo',
        'troba'
    ];

    public function cuarentena(){
        return $this->belongsTo(GestionCuarentena::class,'idCuarentenas','id');
    }

}
