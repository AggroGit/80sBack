<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- CSRF Token -->
        <meta name="csrf-token" id="token" content="{{ csrf_token() }}">
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>{{env('APP_NAME')}}</title>
        @yield('cssExtra')
        <link href="{{ asset('css/base.css') }}" rel="stylesheet">
        <link href="{{asset('dashboard/css/styles.css')}}" rel="stylesheet" />
        <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" crossorigin="anonymous"></script>
        <!-- MULTISELECT -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

        <!-- Scripts -->
        @if(!isset($noTypeScript))
          <script src="{{ asset('js/app.js') }}" defer></script>
        @else
        @endif
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand" href="{{url('admin')}}">{{env('APP_NAME')}}</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <!-- <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" />
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form> -->
            <!-- Navbar-->
            <ul class="d-none d-md-inline-block  ml-auto mr-0 mr-md-3 my-2 my-md-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                        <!-- <a class="dropdown-item" href="#">Settings</a>
                        <a class="dropdown-item" href="#">Activity Log</a>
                        <div class="dropdown-divider"></div> -->
                        <a class="dropdown-item" href="{{url('/logout')}}">Logout</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="{{url('/admin/section')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tag"></i></div>
                                Categorías
                            </a>

                            <a class="nav-link" href="{{url('admin/user')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Usuarios
                            </a>
                            <a class="nav-link" href="{{url('admin/product')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-hamburger"></i></div>
                                Productos
                            </a>
                            <a class="nav-link" href="{{url('admin/purchase')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-paperclip"></i></div>
                                Pedidos
                            </a>
                            <a class="nav-link" href="{{url('admin/allergy')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-box"></i></div>
                                Alergias
                            </a>
                            <a class="nav-link" href="{{url('admin/discount')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tag"></i></div>
                                Descuentos
                            </a>
                            <a class="nav-link" href="{{url('admin/schedule')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-clock"></i></div>
                                Horarios
                            </a>
                            <a class="nav-link" href="{{url('admin/business')}}">
                                <div class="sb-nav-link-icon"><i class="fas fa-city"></i></div>
                                Negocio
                            </a>


                        </div>
                    </div>

                </nav>
            </div>
            <div id="layoutSidenav_content" class="">
              <div id="app">
                <main>
                  @yield('content')
                </main>
              </div>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Todos los derechos reservados</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('dashboard/js/scripts.js')}}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('dashboard/assets/demo/chart-area-demo.js')}}"></script>
        <script src="{{asset('dashboard/assets/demo/chart-bar-demo.js')}}"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
        <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
        <script src="{{asset('dashboard/assets/demo/datatables-demo.js')}}"></script>
    </body>
</html>
