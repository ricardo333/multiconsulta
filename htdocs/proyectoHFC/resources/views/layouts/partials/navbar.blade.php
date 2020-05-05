 <!-- Navbar -->
 <nav class="main-header navbar navbar-expand bg-success navbar-dark border-bottom" id="main_header">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            @section('top-left-submenus')
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
            </li>
            @show
        </ul>
        
         
          
        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <a class="nav-link m-0 p-0 d-flex align-items-center" data-toggle="dropdown" href="#" >
                <div class="media media align-items-center">
                    <img src="{{url('images')}}/user8-128x128.jpg" alt="User Avatar" class="img-size-32 img-circle mr-2">
                    <div class="media-body">
                        <p class="dropdown-item-title d-flex align-items-center">
                            {{ Auth()->user()->username}}  
                             <span class="float-right text-sm text-success ml-1"><i class="fa fa-circle-o"></i></span>
                        </p>
                    </div>
                  </div>
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right bg-light">
                <span class="dropdown-item dropdown-header">OPCIONES:</span>
                <div class="dropdown-divider"></div>
                <a href="{{route('perfil.usuario.detalle',['usuario'=>Auth()->user()->username])}}" class="dropdown-item">
                    <i class="fa fa-user mr-2" aria-hidden="true"></i> Perfil
                </a>
                <div class="dropdown-divider"></div>
                <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                                              document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out mr-2" aria-hidden="true"></i>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form> Cerrar Sesión
                </a>
              </div>
          </li>
          
          {{--<li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
                class="fa fa-th-large"></i></a>
          </li>--}}
        </ul>
      </nav>
      <!-- /.navbar -->