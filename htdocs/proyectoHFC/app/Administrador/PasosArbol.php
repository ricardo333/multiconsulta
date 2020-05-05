<?php

namespace App\Administrador;

use Illuminate\Database\Eloquent\Model;

class PasosArbol extends Model
{

    protected $connection = 'arbol_decisiones';

    protected $table = 'pasosArbol';

    protected $fillable = [
        'nombre',
        'detalle',
        'posicion',
        'pasoAnterior',
        'tablaSiguiente',
        'tablaAnterior'   
    ];
}
