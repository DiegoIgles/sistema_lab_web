<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="{{ asset('images/cps.png') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema Laboratorio')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* En modo PC, ocultar la barra de desplazamiento lateral */
        body {
            overflow-x: hidden;
            min-width: 100%;
        }

        /* Asegurar que el navbar ocupe toda la anchura */
        .navbar {
            width: 100%;
        }

        /* En móviles, permitir desplazamiento horizontal */
        @media (max-width: 992px) {
            body {
                overflow-x: auto;
            }
            .container, .navbar {
                min-width: 100vw;
                overflow-x: auto;
            }
        }

        /* Estilos del navbar */
        .navbar-nav .nav-item {
            margin-right: 10px;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            padding: 12px 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
        }
        .navbar-nav .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Botón de logout */
        .btn-logout {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 8px;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease-in-out;
        }
        .btn-logout:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        /* Imagen del logo */
        .navbar-brand img {
            height: 50px;
            width: auto;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/cps.png') }}" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @auth
                        @php
                            $user = auth()->user();
                            $roleName = $user->role ? strtoupper($user->role->name) : null;
                        @endphp

                        @if ($roleName === 'ADMINISTRADOR')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('users.index') }}">Usuarios</a>
                            </li>
                        @endif

                        @if ($roleName)
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('centros.index') }}">Centros Médicos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('centro_medico_grupo.index') }}">Centros con Grupos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('grupos.index') }}">Grupos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.disponibilidad') }}">Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('citas.create') }}">Crear Citas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.reservas') }}">Reservas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('recomendaciones.index') }}">Recomendaciones</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('bitacora.index') }}">Bitácora</a>
                            </li>
                        @endif
                    @endauth
                </ul>

                <!-- Logout Button & User Name -->
                @auth
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text text-white me-3">
                            {{ auth()->user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-logout">Logout</button>
                        </form>
                    </li>
                </ul>
                @endauth

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
