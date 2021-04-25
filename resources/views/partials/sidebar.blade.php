<div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
              <div class="nav-profile-image">
                <img src="{{ asset('img/usuarios/'.Auth::user()->imagen)}}" alt="profile">
                <span class="login-status online"></span> <!--change to offline or busy as needed-->              
              </div>
              <div class="nav-profile-text d-flex flex-column">
                <span class="font-weight-bold mb-2"> {{ Auth::user()->name }}</span>
                <span class="text-secondary text-small">Vendedor</span>
              </div>
              <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="index.html">
              <span class="menu-title">Dashboard</span>
              <i class="mdi mdi-home menu-icon"></i>
            </a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-title">Almacén</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route ('almacen.index')}}">Almacén</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route ('almacen.entrada.index')}}">Entradas</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route ('almacen.proveedor.index')}}">Proveedores</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route ('almacen.tiposproductos.index')}}">Tipos Producto</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route ('almacen.marcas.index')}}">Marcas</a></li>  
              </ul>
            </div>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="{{route ('almacen.index')}}">
              <span class="menu-title">Almacén</span>
              <i class="mdi mdi-shopping menu-icon"></i>
            </a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="{{route ('ventas.index')}}">
              <span class="menu-title">Ventas</span>
              <i class="mdi mdi-shopping menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-configuracion" aria-expanded="false" aria-controls="ui-basic">
              <span class="menu-title">Configuración</span>
              <i class="menu-arrow"></i>
              <i class="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div class="collapse" id="ui-configuracion">
              <ul class="nav flex-column sub-menu"> 
                <li class="nav-item"> <a class="nav-link" href="{{route ('configuraciones.descuentos.index')}}">Descuentos</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route ('configuraciones.usuarios.index')}}">Usuarios</a></li>
              </ul>
            </div>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="menu-title">Configuración</span>
              <i class="mdi mdi-shopping menu-icon"></i>
            </a>
          </li> -->
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="menu-title">Estadisticas</span>
              <i class="mdi mdi-shopping menu-icon"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="menu-title">Perfil</span>
              <i class="mdi mdi-shopping menu-icon"></i>
            </a>
          </li>
        </ul>
      </nav>