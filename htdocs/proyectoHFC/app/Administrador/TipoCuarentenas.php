<?php

namespace App\Administrador;

use Illuminate\Database\Eloquent\Model;

class TipoCuarentenas extends Model
{

    protected $connection = 'mysql';

    protected $table = 'tipo_cuarentenas';

    const TIPO_AVERIAS = "AVERIAS";
    const TIPO_CRITICOS = "CRITICOS";
    const TIPO_MOVISTAR_TOTAL = "MOVISTAR_TOTAL";
    const TIPO_ALTAS = "ALTAS";

    public $timestamps = false;
    
    protected $fillable = [
        'nombre',
        'estado'
    ];
 

}
