<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Responsive admin dashboard and web application ui kit." />
    <meta name="keywords" content="dashboard, index, main" />

    <title><?= $title; ?> &mdash; PP Aisyiyah</title>

    <!-- google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"/>

    <!-- Styles -->
    <link href="<?= asset_path('/css/core.min.css', '_theme_') ?>" rel="stylesheet" />
    <link href="<?= asset_path('/css/app.css', '_theme_') ?>" rel="stylesheet" />
    <link href="<?= asset_path('/css/style.css', '_theme_') ?>" rel="stylesheet" />
    <link href="<?= asset_path('/css/custom.css', '_theme_') ?>" rel="stylesheet" />

    <!-- Favicons -->
    <link rel="apple-touch-icon" href="<?= asset_path('/img/logo-aisyiyah.png', '_theme_') ?>" />
    <link rel="icon" href="<?= asset_path('/img/logo-aisyiyah.png', '_theme_') ?>" />

    <!--  Open Graph Tags -->
    <!-- <meta property="og:title" content="The Admin Template of 2018!" />
    <meta property="og:description" content="TheAdmin is a responsive, professional, and multipurpose admin template powered with Bootstrap 4." />
    <meta property="og:image" content="http://thetheme.io/theadmin/assets/img/og-img.jpg" />
    <meta property="og:url" content="http://thetheme.io/theadmin/" />
    <meta name="twitter:card" content="summary_large_image" /> -->
</head>
<body>

    <!-- Sidebar -->
    <aside class="sidebar sidebar-light sidebar-expand-lg">
        <header class="sidebar-header">
            <div class="p-2" href="../index.html"><img src="../../assets/img/logo-dm.svg" alt="logo icon" style="width: 30px;"></div>
            <a href="../dashboard/index.html" class="text1">DoctorMuda.</a>
        </header>

        <nav class="sidebar-navigation">
            <ul class="menu menu-sm menu-bordery">

                <li class="menu-item <?= $page_active == 'dashboard' ? 'active' : '' ?>">
                <a class="menu-link menu-color" href="<?= site_url('eoffice/Home'); ?>">
                    <span class=" fa fa-home"></span>
                    <span class="title">Dashboard</span>
                </a>
                </li>

                <li class="menu-item <?= $page_active == 'test' ? 'active' : '' ?>">
                <a class="menu-link menu-color" href="<?= site_url('eoffice/Test'); ?>">
                    <span class="ion-person-stalker"></span>
                    <span class="title">Test</span>
                </a>
                </li>

                <li class="menu-item">
                <a class="menu-link menu-color active" href="#">
                    <span class="fa fa-check-square-o"></span>
                    <span class="title">Verifikasi Logbook</span>
                    <span class="arrow"></span>
                </a>

                <ul class="menu-submenu">
                    <li class="menu-item">
                    <a class="menu-link" href="../verifikasi/orientasiBimbingan.html">
                        <span class="dot"></span>
                        <span class="title">Orientasi & Bimbingan</span>
                    </a>
                    </li>

                    <li class="menu-item">
                    <a class="menu-link" href="../verifikasi/daftarKegiatan.html">
                        <span class="dot"></span>
                        <span class="title">Daftar Kegiatan</span>
                    </a>
                    </li>

                    <li class="menu-item">
                    <a class="menu-link" href="../verifikasi/daftarPenyakit.html">
                        <span class="dot"></span>
                        <span class="title">Daftar Penyakit</span>
                    </a>
                    </li>

                    <li class="menu-item">
                    <a class="menu-link" href="../verifikasi/daftarKeterampilan.html">
                        <span class="dot"></span>
                        <span class="title">Keterampilan Klinis</span>
                    </a>
                    </li>
                </ul>
                </li>

                <li class="menu-item">
                <a class="menu-link menu-color" href="#">
                    <span class="fa fa-percent"></span>
                    <span class="title">Penilaian</span>
                    <span class="arrow"></span>
                </a>

                <ul class="menu-submenu">
                    <li class="menu-item">
                    <a class="menu-link" href="../penilaian/nilaiKegiatan.html">
                        <span class="dot"></span>
                        <span class="title">Penilaian Kegiatan</span>
                    </a>
                    </li>

                    <li class="menu-item">
                    <a class="menu-link" href="../penilaian/nilaiOsler.html">
                        <span class="dot"></span>
                        <span class="title">Penilaian OSLER</span>
                    </a>
                    </li>

                    <li class="menu-item">
                    <a class="menu-link" href="../penilaian/nilaiKondite.html">
                        <span class="dot"></span>
                        <span class="title">Penilaian Kondite</span>
                    </a>
                    </li>
                </ul>
                </li>

            </ul>
        </nav>
    </aside>
    <!-- END Sidebar -->

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <span class="topbar-btn sidebar-toggler"><i>&#9776;</i></span>
            <h3 class="topbar-title"><?= $title; ?></h3>
        </div>

        <div class="topbar-right">
            <ul class="topbar-btns">
                <li class="dropdown">
                    <span class="topbar-btn" data-toggle="dropdown"><img class="avatar" src="<?= asset_path('/design/img/avatar/6.jpg', '_theme_') ?>" alt="..."></span>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="<?= site_url('pda/profile'); ?>"><i class="ti-user"></i> Profil</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= site_url('ppa/auth') ?>"><i class="ti-power-off"></i> Logout</a>
                    </div>
                </li>
            </ul>
        </div>
    </header>
    <!-- END Topbar -->

    <!-- Main container -->
    <main class="main-container">
    <?= $template['body']; ?>

    <!-- Footer -->
    <footer class="site-footer">
        <div class="row">
            <div class="col-md-6">
                <p class="text-center text-md-left">
                    Copyright © 2023
                    <a href="http://thetheme.io/theadmin">PP Aisyiyah</a>. All rights
                    reserved.
                </p>
            </div>

            <div class="col-md-6">
                <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
                    <li class="nav-item">
                        <a class="nav-link" href="/help/articles.html">Documentation</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/help/faq.html">FAQ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="https://themeforest.net/item/theadmin-responsive-bootstrap-4-admin-dashboard-webapp-template/20475359?license=regular&open_purchase_for_item_id=20475359&purchasable=source&ref=thethemeio">Purchase
                            Now</a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>
    <!-- END Footer -->

    </main>
    <!-- END Main container -->





    <!-- Scripts -->
    <script src="<?= asset_path('design/js/core.min.js', '_theme_') ?>"></script>
    <script src="<?= asset_path('design/js/app.js', '_theme_') ?>"></script>
    <script src="<?= asset_path('design/js/script.js', '_theme_') ?>"></script>
    <script src="<?= asset_path('design/custom.js', '_theme_') ?>"></script>

</body>
</html>