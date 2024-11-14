<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- <meta name="description" content="TheAdmin - Responsive admin and web application ui kit">
      <meta name="keywords" content="admin, dashboard, web app, sass, ui kit, ui framework, bootstrap"> -->

  <title><?php echo config_item('app_title') . config_item('title_separator') . $template['title']; ?></title>

  <!-- google fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet" />

  <!-- Styles -->
  <?php echo $template['partials']['modules_css']; ?>
  <link href="<?php echo css_path('core.min.css', '_theme_'); ?>" rel="stylesheet">
  <link href="<?php echo css_path('app.min.css', '_theme_'); ?>" rel="stylesheet">
  <link href="<?php echo css_path('custom.css', '_theme_'); ?>" rel="stylesheet">
  <link href="<?php echo css_path('profiler.css', '_theme_'); ?>" rel="stylesheet">

  <!-- Favicons -->
  <link rel="apple-touch-icon" href="<?php echo image_path('apple-touch-icon.png', '_theme_'); ?>">
  <link rel="icon" href="<?php echo image_path('favicon.png', '_theme_'); ?>">

  <script src="<?php echo js_path('core.min.js', '_theme_'); ?>"></script>
</head>

<body class="topbar-unfix">

  <!-- Sidebar -->
  <aside class="sidebar sidebar-light sidebar-expand-lg">
    <div id="sidebar-container">
      <header class="sidebar-header">
        <div class="p-2" href="<?php echo site_url(); ?>"><img src="<?php echo image_path('logo-dm.svg', '_theme_'); ?>" alt="logo icon" style="width: 30px;"></div>
        <a href="<?php echo site_url(); ?>" class="text1">DoctorMuda.</a>
      </header>
      <nav class="sidebar-navigation">
        <ul class="menu menu-sm menu-bordery">
          <?php echo $this->authentication->render_menu('1'); ?>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Topbar -->
  <header class="topbar">
    <div class="topbar-left">
      <span class="topbar-btn sidebar-toggler"><i>&#9776;</i></span>

      <!-- <div class="p-2" href="../index.html"><img src="<?php echo image_path('logo-dm.svg', '_theme_'); ?>" alt="logo icon" style="width: 30px;"></div>
      <a href="<?php echo site_url(); ?>" class="text1">DoctorMuda.</a> -->

      <!-- <div class="topbar-divider d-none d-xl-block"></div> -->

      <!-- <nav class="topbar-navigation">
        <ul class="menu">
          <?php echo $this->authentication->render_menu('1'); ?>
        </ul>
      </nav> -->
    </div>

    <?php if ($this->authentication->is_logged_in()) { ?>
      <div class="topbar-right">
        <ul class="topbar-btns">
          <li class="dropdown">
            <span class="topbar-btn" data-toggle="dropdown">
              <img class="avatar" src="<?php echo image_path('avatar/1.jpg', '_theme_') ?>" alt="...">
            </span>
            <!-- <span class="title">Test</span> -->
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href=""><i class="ti-user"></i> <?php echo get_user_real_name(); ?></a>
              <div class="dropdown-divider"></div>
              <?php
              $usr_grp = $this->authentication->any_user_group_exist();
              if (!is_null($usr_grp)) {
                foreach ($usr_grp as $ug) {
              ?>

                  <a class="dropdown-item" href="<?php echo site_url('auth/Auth/change_group/' . encode($ug['id_group'])); ?>"><i class="ti-desktop"></i> <?php echo $ug['name_group']; ?> <?php echo ($ug['id_group'] == get_user_group() ? '<i class="fa fa-check-circle ml-2 text-info fs-16"></i>' : ''); ?> </a>

                <?php } ?>
                <div class="dropdown-divider"></div>
              <?php } ?>
              <a class="dropdown-item" href="<?php echo site_url('auth/logout') ?>"><i class="ti-power-off"></i> Keluar</a>
            </div>
          </li>
          <!-- <li>
                <span class="topbar-btn has-new" data-toggle="quickview" data-target="#qv-notifications"><i class="ti-bell"></i></span>
              </li> -->
        </ul>
      </div>
    <?php } ?>
    <!-- </div> -->
  </header>
  <!-- END Topbar -->



  <!-- Main container -->
  <main class="main-container">
    <!-- <div class="main-content"> -->
    <!-- <div class="container"> -->
    <!-- <header class="header no-border">
            <div class="header-bar header-title">
               <h4><?php echo $template['title']; ?></h4>
            </div>
            </header> -->
    <?php echo $template['body']; ?>
    <!-- </div> -->
    <!-- </div> -->





    <!-- Footer -->
    <footer class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-md-8">
            <p class="text-center text-sm-left" style="font-size:10px;">Copyright &copy; 2021 - <?= date('Y'); ?> <strong><a href="https://bsi.uad.ac.id" target='_blank'>Biro Sistem Informasi</a></strong></p>
          </div>

          <div class="col-md-4">
            <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
              <li class="nav-item">
                <a class="nav-link" href="#" target="_blank">Informasi</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#" target="_blank">Dokumentasi</a>
              </li>
              <!-- <li class="nav-item">
                  <a class="nav-link" href="#">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Contact</a>
                </li> -->
            </ul>
          </div>
        </div>
      </div>
    </footer>
    <!-- END Footer -->


  </main>





  <!--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->
  <!-- Quickviews -->



  <!-- END Quickviews -->
  <!--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->

  <script src="<?php echo js_path('app.min.js', '_theme_'); ?>"></script>
  <script src="<?php echo js_path('script.js', '_theme_'); ?>"></script>
  <script src="<?php echo js_path('profiler.js', '_theme_'); ?>"></script>

  <?php echo $template['partials']['modules_js']; ?>

</body>

</html>