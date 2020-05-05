<div class="d-flex justify-content-center">
        @if (Auth::user()->HasPermiso('submodulo.arbol-decision.pasos.show'))
        <a href="{{ route('submodulo.arbol-decision.pasos.show', $id) }}"  class="btn btn-outline-primary btn-sm shadow-sm p-1 mx-1 accionArbolpasosShow"><i class="fa fa-eye icon-accion"></i></a>
        @endif
        
</div>
 