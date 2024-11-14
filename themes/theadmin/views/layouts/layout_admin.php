<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- <meta name="description" content="TheAdmin - Responsive admin and web application ui kit">
    <meta name="keywords" content="admin, dashboard, web app, sass, ui kit, ui framework, bootstrap"> -->

    <title><?php echo config_item('app_title') . config_item('title_separator') . $template['title']; ?></title>

    <!-- Styles -->
    
    <link href="<?= asset_path('/css/core.min.css', '_theme_') ?>" rel="stylesheet" />
    <link href="<?= asset_path('/css/app.css', '_theme_') ?>" rel="stylesheet" />
    <link href="<?= asset_path('/css/style.css', '_theme_') ?>" rel="stylesheet" />
    <link href="<?= asset_path('/css/custom.css', '_theme_') ?>" rel="stylesheet" />

    <?php echo $template['partials']['modules_css']; ?>

    <!-- Favicons -->
  <link rel="apple-touch-icon" href="<?= asset_path('/img/logo-eoffice.svgg', '_theme_') ?>" />
  <link rel="icon" href="<?= asset_path('/img/logo-eoffice.svg', '_theme_') ?>" />


      <script src="<?php echo js_path('core.min.js', '_theme_'); ?>"></script>
  </head>

  <body>

    <!-- Preloader -->
    <div class="preloader">
      <div class="spinner-dots">
        <span class="dot1"></span>
        <span class="dot2"></span>
        <span class="dot3"></span>
      </div>
    </div>
 

    <!-- Sidebar -->
    <aside class="sidebar sidebar-light sidebar-expand-lg">
    <header class="sidebar-header justify-content-center">
      <div class="m-1" href=""><img src="<?= asset_path('/img/logo-eoffice.svg', '_theme_') ?>" alt="logo icon" style="width: 32px;"></div>
      <a href="<?php echo site_url('dashboard/Dashboard/index') ?>" class="text1"><?php echo config_item('app_name') ?></a>
    </header>  
    

      <nav class="sidebar-navigation">
        <ul class="menu menu-sm menu-bordery">
         <li class="menu-item">
            <a class="menu-link" href="<?php echo site_url('dashboard/Dashboard/index') ?>">
              <span class="icon fa fa-home"></span>
              <span class="title">Dashboard</span>
            </a>
          </li>
         <li class="menu-category">Menu App</li>

          <?php echo $this->authentication->render_menu('1');?>
        </ul>
      </nav>


      <?php if ($this->authentication->is_logged_in()) {?>
      <div class="align-items-center">
        <div class="col-12">
          <div class="card card-bordered">
            <div class="card-body">
              <div class="d-flex flex-column ">
                <div class="d-flex justify-content-center">
                  <img class="avatar avatar-xl avatar-bordered" src="<?= asset_path('/img/avatar/avatar.jpg', '_theme_') ?>"  alt="...">
                </div>
                <div class="scrollable d-flex flex-column justify-content-center">
                  <span class="fs-15 fw-700 color-primary"><?php echo get_user_real_name(); ?></span>
                  <?php
                  
                  ?>
                  <span class="fs-10 color-primary"><?= get_user_unit_kode();?> (<?= get_user_unit_name();?>)</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      <div class="row justify-content-center card-body">
        <form action="<?php echo site_url('auth/logout') ?>">
          <button type="submit" class="color-primary fw-700 btn" ><i class="fa fa-sign-out"></i>  Logout</button>
        </form>
      </div>
    </div>
    <?php } ?>

    </aside>
    <!-- END Sidebar -->


    <!-- END Topbar -->


  <header class="topbar" id="topbar">
    <div class="topbar-left">
      <span class="topbar-btn sidebar-toggler"><i>&#9776;</i></span>
      <h2 class="fw-700 topbar-title"><?php echo $template['title'];?></h2>
    </div>
    <ol class="breadcrumb breadcrumb-arrow">
            <?php 
            if( !empty( $template['breadcrumbs'] ) ){
              $total_breadcrumbs = count( $template['breadcrumbs'] );
              $count_breadcrumbs = 1;
              foreach($template['breadcrumbs'] as $breadcrumbs){
                  if( $count_breadcrumbs == $total_breadcrumbs ){
                    echo '<li class="breadcrumb-item active">';
                    echo $breadcrumbs['name'];
                  } else {
                    echo '<li class="breadcrumb-item">';
                    echo '<a href="'. site_url($breadcrumbs['uri']) .'" class="text-info">'. $breadcrumbs['name'] .'</a>';
                  }
                  echo '</li>';
                  $count_breadcrumbs++;
              }
            } else {
            ?>
              <li class="breadcrumb-item active">
                  <a href="<?php echo site_url(); ?>"><?php echo config_item('app_name'); ?></a>
              </li>
        <?php } ?>
        </ol>
  </header>

    <main class="main-container">
      <div class="main-content">
         <?php echo $template['body']; ?>
      </div><!--/.main-content -->


      <!-- Footer -->
      <footer class="site-footer">
        <div class="row">
          <div class="col-md-6">
            <p class="text-center text-md-left"> Development By <a href="https://bsi.uad.ac.id">Biro Sistem Informasi</a> &copy; <?= date('Y');?>  Universitas Ahmad Dahlan.</p>
          </div>

          <div class="col-md-6">
            <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
              <li class="nav-item">
                <a class="nav-link" href="../help/articles.html">Documentation</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../help/faq.html">FAQ</a>
              </li>
<!--               <li class="nav-item">
                <a class="nav-link" href="https://themeforest.net/item/theadmin-responsive-bootstrap-4-admin-dashboard-webapp-template/20475359?license=regular&amp;open_purchase_for_item_id=20475359&amp;purchasable=source&amp;ref=thethemeio">Purchase Now</a>
              </li> -->
            </ul>
          </div>
        </div>
      </footer>
      <!-- END Footer -->

    </main>
    <!-- END Main container -->



    <!-- Global quickview -->
    <div id="qv-global" class="quickview" data-url="assets/data/quickview-global.html">
      <div class="spinner-linear">
        <div class="line"></div>
      </div>
    </div>
    <!-- END Global quickview -->


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    <script src="<?php echo js_path('app.min.js', '_theme_'); ?>"></script>
    <script src="<?php echo js_path('script.js', '_theme_'); ?>"></script>



    <?php $this->view('v_notification'); ?>
    
    <?php echo $template['partials']['modules_js']; ?>
     <script type="text/javascript">
      jQuery(document).ready(function() 
      {  
         var $profiler = $('.profiler_menu'); 
         var fieldset = $('#codeigniter_profiler').find('fieldset');
         $profiler.click(function(e){
            that = $(this).attr('href');
            //$(id_profiler).show();
            $.each(fieldset, function(idx, val){
               if($(this).hasClass('show')) {
                  $(this).removeClass('show');
               }
               $(this).hide();
            });

            if(that != 'collapse_all') {
               $(that).addClass('show').show();
            }
            
         });

      });
      </script>
  </body>
</html>
