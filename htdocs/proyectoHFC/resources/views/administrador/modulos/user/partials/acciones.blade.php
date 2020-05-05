<div class="d-flex justify-content-center">
        @if (Auth::user()->HasPermiso('submodulo.usuario.show'))
        <a href="{{ route('submodulo.usuario.show', $id) }}"  class="btn btn-outline-primary btn-sm shadow-sm p-1 mx-1 accionUsuarioShow"><i class="fa fa-eye icon-accion"></i></a>
        @endif
        @if (Auth::user()->HasPermiso('submodulo.usuario.edit'))
        <a href="{{ route('submodulo.usuario.edit', $id) }}" class="btn btn-outline-success btn-sm shadow-sm p-1 mx-1 accionUsuarioEdit" ><i class="fa fa-pencil icon-accion"></i></a>
        @endif
        @if (Auth::user()->HasPermiso('submodulo.usuario.delete'))
        <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm shadow-sm p-1 mx-1 accionUsuarioDelete" 
                data-id="{{$id}}" ><i class="fa fa-trash icon-accion"></i></a>
        @endif
</div>
 