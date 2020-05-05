<div class="d-flex justify-content-center">
    @if (Auth::user()->HasPermiso('submodulo.empresa.show'))
        <a href="{{ route('submodulo.empresa.show', $id) }}"  class="btn btn-outline-primary btn-sm shadow-sm p-1 mx-1 accionEmpresaShow"><i class="fa fa-eye icon-accion"></i></a>
    @endif
    @if (Auth::user()->HasPermiso('submodulo.empresa.edit'))
        <a href="{{ route('submodulo.empresa.edit', $id) }}" class="btn btn-outline-success btn-sm shadow-sm p-1 mx-1 accionEmpresaEdit" ><i class="fa fa-pencil icon-accion"></i></a>
    @endif
    @if (Auth::user()->HasPermiso('submodulo.empresa.delete'))
        <a href="javascript:void(0)" class="btn btn-outline-danger btn-sm shadow-sm p-1 mx-1 accionEmpresaDelete" 
            data-id="{{$id}}" ><i class="fa fa-trash icon-accion"></i></a>
    @endif
</div>
 