 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-light-success elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('administrador')}}" class="brand-link bg-success">
      <img src="{{url('images')}}/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">HFC</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        @auth
          <div class="image">
            <img src="{{url('images')}}/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
          </div> 
          <div class="info">
              <a href="{{route('perfil.usuario.detalle',[Auth()->user()->username])}}" class="d-block">
                  {{ Auth()->user()->username}}  
                  <strong> ( {{ Auth()->user()->role->nombre}} ) </strong>
                </a>
               
          </div> 
        @endauth
        
        
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item has-treeview @routeIs('administrador') menu-open @endif">
            <a href="{{route('administrador')}}" class="nav-link @routeIs('administrador') active @endif ">
              <i class="nav-icon fa fa-dashboard"></i>
              <p>
                Dashboard 
              </p>
            </a> 
          </li> 
          @php
             // dd($menus);
             
          @endphp
          @if (isset($menus))
              @forelse ($menus as $menu)
                {{--<li class="nav-item has-treeview @routeIs($menu->slug) menu-open @endif">Descomentar luego--}}
                <li class="nav-item has-treeview ">
                  {{-- Request::route()->usuario usar para cuando se pasa rutas--}}
                   {{--<a href="{{route($menu->slug)}}" class="nav-link @routeIs($menu->slug) active @endif "> Decomentar luego--}}
                  <a href="{{$menu->ruta}}" class="nav-link">
                    <i class="nav-icon fa fa-angle-right"></i>
                    <p>
                      {{$menu->nombre}}
                    </p>
                  </a>
                </li>
              @empty
                  
              @endforelse
          @endif
         {{-- @if (Auth::user()->HasPermiso('modulo.usuario.index'))
            <li class="nav-item has-treeview @routeIs('modulo.usuario.index')) menu-open @endif">--}}
              {{-- Request::route()->usuario usar para cuando se pasa rutas--}}
              {{--<a href="{{route('modulo.usuario.index')}}" class="nav-link @routeIs('modulo.usuario.index')) active @endif ">
                <i class="nav-icon fa fa-user"></i>
                <p>
                    Usuarios 
                </p>
              </a>
            </li>
          @endif

          @if (Auth::user()->HasPermiso('modulo.rol.index'))
            <li class="nav-item has-treeview @routeIs('modulo.rol.index')) menu-open @endif">
              <a href="{{route('modulo.rol.index')}}" class="nav-link @routeIs('modulo.rol.index')) active @endif ">
                <i class="nav-icon fa fa-user"></i>
                <p>
                    Roles
                
                </p>
              </a>
            </li>
          @endif
          <li class="nav-header">CM</li>
          <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fa fa-edit"></i>
              <p>
                Reportes
                <i class="fa fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="pages/forms/general.html" class="nav-link">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Caidas masivas</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/forms/advanced.html" class="nav-link">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>IPS</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="pages/forms/editors.html" class="nav-link">
                  <i class="fa fa-circle-o nav-icon"></i>
                  <p>Cuarentena</p>
                </a>
              </li>
            </ul>
          </li>
          --}}
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>