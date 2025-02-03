<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Laboratorio')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">CPS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('centros.index') }}">Centros Medico</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('centro_medico_grupo.index') }}">Centros Medicos con grupos</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('relaciones.index') }}">Laboratorios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('relaciones.create') }}">Añadir Laboratorio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('centros.show-eliminar-relacion') }}">Deshabilitar Laboratorio</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('grupos.index') }}">Grupos</a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('grupos.eliminarAsociacionView') }}">Deshabilitar Asociacion </a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.disponibilidad') }}">Citas </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('citas.create') }}">Crear Citas </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reservas') }}">Reservas </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('bitacora.index') }}">Bitacora</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        @yield('content')
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
