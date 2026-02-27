<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Klinik Hewan')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }
        .select2-container--bootstrap-5 .select2-dropdown {
            border-color: #dee2e6;
        }
        .add-new-option {
            background: #f8f9fa;
            border-top: 2px solid #0d6efd;
            padding: 10px;
            margin: 0;
            cursor: pointer;
        }
        .add-new-option:hover {
            background: #e9ecef;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-heart-pulse"></i> Klinik Hewan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        @if(auth()->user()->role == 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="manajemenDropdown" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-gear"></i> Manajemen
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pegawai.index') }}">
                                            <i class="bi bi-person-badge"></i> Pegawai
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dokter-hewan.index') }}">
                                            <i class="bi bi-hospital"></i> Dokter Hewan
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                        
                        @if(in_array(auth()->user()->role, ['admin', 'pegawai']))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pemilik-hewan.index') }}">
                                    <i class="bi bi-people"></i> Pemilik Hewan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('hewan.index') }}">
                                    <i class="bi bi-award"></i> Hewan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('pendaftaran.index') }}">
                                    <i class="bi bi-clipboard-check"></i> Pendaftaran
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-cash-coin"></i> Pembayaran
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pembayaran.pending') }}">
                                            <i class="bi bi-clock-history"></i> Belum Dibayar
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pembayaran.index') }}">
                                            <i class="bi bi-receipt"></i> Riwayat Pembayaran
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(in_array(auth()->user()->role, ['admin', 'dokter']))
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-clipboard2-pulse"></i> Pemeriksaan
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pemeriksaan.index') }}">
                                            <i class="bi bi-list-task"></i> Daftar Tunggu
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pemeriksaan.riwayat') }}">
                                            <i class="bi bi-clock-history"></i> Riwayat Pemeriksaan
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        @if(in_array(auth()->user()->role, ['admin', 'pegawai', 'dokter']))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('obat.index') }}">
                                    <i class="bi bi-capsule"></i> Obat
                                </a>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <span class="dropdown-item-text small">
                                        <strong>Role:</strong> {{ ucfirst(auth()->user()->role) }}
                                    </span>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <footer class="bg-light text-center py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Klinik Hewan. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @stack('scripts')
</body>
</html>
