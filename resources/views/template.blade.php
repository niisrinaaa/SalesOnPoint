<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Toko Online')</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    @stack('styles') {{-- untuk custom style di child view --}}

    <style>
        /* Custom style internal juga masih bisa di sini */
        .product-card {
            position: relative;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            width: 220px;
            margin: 10px;
            background: white;
            transition: box-shadow 0.3s ease;
        }

        .product-card:hover {
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.15);
        }

        .new-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #0d6efd;
            color: white;
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 5px;
            z-index: 2;
        }

        .price-circle {
            position: absolute;
            bottom: 10px;
            left: 10px;
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
            padding: 5px 12px;
            border-radius: 50px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .icon-set {
            position: absolute;
            right: 10px;
            bottom: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .icon-set i {
            color: #333;
            background: #f0f0f0;
            padding: 6px;
            border-radius: 50%;
            cursor: pointer;
        }

        .product-card:hover .price-circle,
        .product-card:hover .icon-set {
            opacity: 1;
        }

        .rating {
            color: gold;
            font-size: 16px;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <h3 style="left-margin: 3px">Bajak Robux</h3>

                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <header class="w-full text-sm mb-6 not-has-[nav]:hidden">
                            @if (Route::has('login'))
                                <nav class="flex items-center justify-end gap-4">
                                    @auth
                                        <li class="nav-item dropdown no-arrow">
                                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown"
                                                data-toggle="dropdown">
                                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right shadow">
                                                <a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile</a>
                                                <a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i> Settings</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
                                                </a>
                                            </div>
                                        </li>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark">Login</a>
                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}" class="btn btn-sm btn-outline-secondary">Register</a>
                                        @endif
                                    @endauth
                                </nav>
                            @endif
                        </header>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white py-3">
            <div class="container my-auto">
                <div class="text-center mb-2">
                    <span>&copy; Bajak Robux 2025</span>
                </div>
                <div class="text-center small text-muted">
                    <strong>Kelompok:</strong><br>
                    Ketua: Rivean<br>
                    Front-end: Sekar<br>
                    Back-end: Melodia, Nisrina
                </div>
            </div>
        </footer>

            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">Klik "Logout" jika kamu ingin keluar dari sesi sekarang.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <input type="submit" class="btn btn-primary" value="Logout">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    @stack('scripts') {{-- untuk custom script di child view --}}

</body>
</html>
