<div class="d-flex justify-content-center">
    @if (Auth::user()->HasPermiso('submodulo.rol.show'))
        <a href="{{ route('submodulo.rol.show', $id) }}"  class="btn btn-outline-primary btn-sm shadow-sm p-1 mx-1 accionRolShow"><i class="fa fa-eye icon-accion"></i></a>
    @endif
    @if (Auth::user()->HasPermiso('submodulo.rol.edit'))
        <a href="{{ route('submodulo.rol.edit', $id) }}" class="btn btn-outline-success btn-sm shadow-sm p-1 mx-1 accionRolEdit" ><i class="fa fa-pencil icon-accion"></i></a>
    @endif
    @if (Auth::user()->HasPermiso('submodulo.rol.delete'))
        <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm shadow-sm p-1 mx-1 accionRolDelete" 
            data-id="{{$id}}" ><i class="fa fa-trash icon-accion"></i></a>
    @endif
</div>
 