<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="Responsive admin dashboard and web application ui kit." />
  <meta name="keywords" content="dashboard, index, main" />

  <title><?= $title; ?> | Eoffice</title>

  <!-- Bootstrap icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" />

  <!-- Styles -->
  <link href="<?= asset_path('/css/core.min.css', '_theme_') ?>" rel="stylesheet" />
  <link href="<?= asset_path('/css/app.css', '_theme_') ?>" rel="stylesheet" />
  <link href="<?= asset_path('/css/style.css', '_theme_') ?>" rel="stylesheet" />
  <link href="<?= asset_path('/css/custom.css', '_theme_') ?>" rel="stylesheet" />

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="<?= asset_path('/img/logo-eoffice.svgg', '_theme_') ?>" />
  <link rel="icon" href="<?= asset_path('/img/logo-eoffice.svg', '_theme_') ?>" />

</head>

<body>
  <!-- Sidebar -->
  <aside class="sidebar sidebar-light sidebar-expand-lg">
    <header class="sidebar-header justify-content-center">
      <div class="m-1" href="../index.html"><img src="<?= asset_path('/img/logo-eoffice.svg', '_theme_') ?>" alt="logo icon" style="width: 32px;"></div>
      <a href="../dashboard/index.html" class="text1">E-Office.</a>
    </header>

    <nav class="sidebar-navigation">
      <ul class="menu menu-sm menu-bordery">

        <li class="menu-item <?= $page_active == 'dashboard' ? 'active' : '' ?>">
          <a class="menu-link menu-color" href="<?= site_url('staff/Dashboard'); ?>">
            <i class="bi bi-grid"></i>
            <span class="title">Dashboard</span>
          </a>
        </li>


        <li class="menu-item <?= $page_active == 'suratmasuk' ? 'active open' : '' ?>">
          <a class="menu-link menu-color" href="#">
            <i class="bi bi-envelope-exclamation"></i>
            <span class="title">Surat Masuk</span>
            <span class="arrow"></span>
          </a>

          <ul class="menu-submenu">
            <li class="menu-item <?= $title == 'Disposisi Staff' ? 'active' : '' ?>">
              <a class="menu-link" href="<?= site_url('staff/SuratMasuk/disposisistaff'); ?>">
                <span class="dot"></span>
                <span class="title">Disposisi Staff</span>
              </a>
            </li>

          </ul>
        </li>

        <li class="menu-item <?= $page_active == 'suratkeluar' ? 'active open' : '' ?>">
          <a class="menu-link menu-color">
            <i class="bi bi-envelope-paper"></i>
            <span class="title">Surat Keluar</span>
            <span class="arrow"></span>
          </a>

          <ul class="menu-submenu">
            <li class="menu-item <?= $title == 'Konsep Surat' ? 'active' : '' ?>">
              <a class="menu-link" href="<?= site_url('staff/SuratKeluar/konsepsurat'); ?>">
                <span class="dot"></span>
                <span class="title">Konsep Surat</span>
              </a>
            </li>

          </ul>
        </li>

      </ul>
    </nav>

    <div class="align-items-center">
      <div class="col-12">
        <div class="card card-bordered">
          <div class="card-body">
            <div class="d-flex flex-column ">
              <div class="d-flex justify-content-start">
                <img class="avatar avatar-xl avatar-bordered" src="<?= asset_path('/img/avatar/avatar.jpg', '_theme_') ?>"  alt="...">
              </div>
              <div class="scrollable d-flex flex-column justify-content-start">
                <span class="fs-15 fw-700 color-primary">Nama Lengkap Pengguna</span>
                <span class="fs-10 color-primary">2000018201 (Biro Sistem Informasi)</sp>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center card-body">
        <form action="/logout">
          <button type="submit" class="color-primary fw-700 btn"><i class="fa fa-sign-out"></i>  Logout</button>
        </form>
      </div>
    </div>
  </aside>
  <!-- END Sidebar -->

  <!-- Topbar -->
  <header class="topbar" id="topbar">
    <div class="topbar-left">
      <span class="topbar-btn sidebar-toggler"><i>&#9776;</i></span>
      <h2 h2 class="fw-700 topbar-title"><?= $title; ?></h2>
    </div>
  </header>
  <!-- END Topbar -->

  <!-- Main container -->
  <main class="main-container">
    <div class="main-content">
      <div class="row">
        <?= $template['body']; ?>
      </div>
    </div>

    <!-- Footer -->
    <footer class="site-footer">
      <div class="row">
        <div class="col-md-6">
          <p class="text-center text-md-left">
            Copyright © 2024
            <a href="http://thetheme.io/theadmin">E-Office</a>. All rights
            reserved.
          </p>
        </div>
      </div>
    </footer>
    <!-- END Footer -->

  </main>
  <!-- END Main container -->

  <!-- Scripts -->
  <script src="<?= asset_path('/js/core.min.js', '_theme_') ?>"></script>
  <script src="<?= asset_path('/js/app.js', '_theme_') ?>"></script>
  <script src="<?= asset_path('/js/script.js', '_theme_') ?>"></script>
  
  <!-- Transparant topbar saat scroll-->
  <script>
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
      if (document.body.scrollTop > 50 || document.documentElement.scrollTop > 50) {
        document.getElementById("topbar").classList.add("topbar-trans");
      } else {
        document.getElementById("topbar").classList.remove("topbar-trans");
      }
    }
  </script>

</body>

</html>