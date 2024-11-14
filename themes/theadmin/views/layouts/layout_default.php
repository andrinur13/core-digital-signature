<!DOCTYPE html>
<html lang="en">
  	<head>
   	<meta charset="utf-8">
   	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<!-- <meta name="description" content="TheAdmin - Responsive admin and web application ui kit">
    	<meta name="keywords" content="admin, dashboard, web app, sass, ui kit, ui framework, bootstrap"> -->

    	<title><?php echo config_item('app_title') . config_item('title_separator') . $template['title']; ?></title>

    <!-- Styles -->
    	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i" rel="stylesheet">
    	<link href="<?php echo css_path('core.min.css', '_theme_'); ?>" rel="stylesheet">
    	<link href="<?php echo css_path('app.min.css', '_theme_'); ?>" rel="stylesheet">
    	<link href="<?php echo css_path('style.css', '_theme_'); ?>" rel="stylesheet">

    	<!-- Favicons -->
    	<link rel="apple-touch-icon" href="<?php echo image_path('apple-touch-icon.png', '_theme_'); ?>">
    	<link rel="icon" href="<?php echo image_path('favicon.png', '_theme_'); ?>">
    	<style rel="stylesheet" type="text/css">
			#codeigniter_profiler {max-width: 90%; min-width: 35em; position: fixed; bottom: 0; width: auto; display: block; font-family: Monaco,Menlo,Consolas,"Courier New",monospace;}
			#codeigniter_profiler > fieldset {display: none; margin: 0!important; }
			#ci_profiler_menu {margin: 0; background: #DDD;}
			#ci_profiler_menu:after {clear: both; content: ""; display: table;}
			#ci_profiler_menu ul {list-style-type: none; margin: 0; padding: 0; float: left;}
			#ci_profiler_menu ul li {display: block; line-height: 15px; float: left; margin: 0 2px; text-align: center;}
			#ci_profiler_menu ul > li > a {padding: 5px 10px 0; text-transform: uppercase; text-decoration: none; display: block; font-weight: bolder;}
			#ci_profiler_menu ul > li > span {font-size: 11px;}
			#ci_profiler_queries table tr td {color: #252525;}
			.profiler_default {border-bottom: 4px solid #333333; } .profiler_default a{color: #333333;}
			.profiler_blue  {border-bottom: 4px solid #0000FF;} .profiler_blue a{ color: #0000FF;}
			.profiler_green {border-bottom: 4px solid #009900; } .profiler_green a {color: #009900;}
			.profiler_magenta {border-bottom: 4px solid #5A0099; } .profiler_magenta a {color: #5A0099;}
			.profiler_brown {border-bottom: 4px solid #990000; } .profiler_brown a {color: #990000;}
		</style>
		<script src="<?php echo js_path('core.min.js', '_theme_'); ?>"></script>
  	</head>

  <body class="topbar-unfix">


    <!-- Topbar -->
    <header class="topbar topbar-expand-lg">
      <div class="container">
        <div class="topbar-left">
          <span class="topbar-btn topbar-menu-toggler"><i>&#9776;</i></span>

          <!-- <div class="topbar-brand">
            <a href="<?php echo site_url();?>">
            	<img class="img-fluid" src="<?php echo image_path('logo_simkat_muda.png', '_theme_'); ?>" style="width:80px;" alt="...">

            </a>
          </div> -->
          <h3 class="topbar-title">BISKOM API</h3>
          <div class="topbar-divider d-none d-md-block"></div>

          <nav class="topbar-navigation">
            <ul class="menu">
            	<?php echo $this->authentication->render_menu('2');?>
            </ul>
          </nav>
        </div>


        	<div class="topbar-right">
	         <?php if ($this->authentication->is_logged_in()) {?>
	         <ul class="topbar-btns">
	            <li class="dropdown">
	              	<span class="topbar-btn" data-toggle="dropdown">
	              		<img class="avatar" src="<?php echo image_path('avatar/1.jpg','_theme_') ?>" alt="...">
	              		<?php echo get_user_real_name(); ?>
	              	</span>
	              	<!-- <span class="title"></span> -->
	              	<div class="dropdown-menu dropdown-menu-right">
	               	<a class="dropdown-item" href="<?php if ($this->session->userdata('user_group')==2) { echo site_url('profile/profile/asesor');} else if($this->session->userdata('user_group')==3) { echo site_url('profile');}?>"><i class="ti-user"></i> Profile</a>
	               	<?php if( $this->authentication->get_backend_user() ){ ?>
	                	<a class="dropdown-item" href="<?php echo site_url('admin/dashboard'); ?>"><i class="ti-settings"></i> ADMIN PANEL</a>
	                	<?php } ?>
	                	<div class="dropdown-divider"></div>
	                	<a class="dropdown-item" href="<?php echo site_url('auth/logout') ?>"><i class="ti-power-off"></i> Logout</a>
	              	</div>
	            </li>
	            <li>
	            	<span class="topbar-btn has-new" data-toggle="quickview" data-target="#qv-notifications"><i class="ti-bell"></i></span>
	            </li>
	         </ul>
	         <?php } ?>
        	</div>
      </div>
    </header>
    <!-- END Topbar -->



    <!-- Main container -->
    <main class="main-container">
      <div class="main-content">
         <div class="container">
            <header class="header no-border">
          <div class="header-bar header-title">
            <h4><?php echo $template['title'];?></h4>
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
          </div>
        </header>
        		<?php echo $template['body']; ?>
        </div>
      </div>





      <!-- Footer -->
      <footer class="site-footer">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <p class="text-center text-sm-left">© 2020 <a href="http://biskom.uad.ac.id"><strong>BISKOM</strong></a> UAD Yogyakarta</p>
            </div>

            <div class="col-md-6">
              <ul class="nav nav-primary nav-dotted nav-dot-separated justify-content-center justify-content-md-end">
                <li class="nav-item">
                  <a class="nav-link" href="#">Documentation</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="http://bimawa.uad.ac.id" target="_blank">BISKOM</a>
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


    <!-- Notifications -->
    <div id="qv-notifications" class="quickview">
      <header class="quickview-header quickview-header-lg">
        <h5 class="quickview-title">Notifications</h5>
        <span class="close"><i class="ti-close"></i></span>
      </header>

      <div class="quickview-body">
        <div class="media-list media-list-hover media-list-divided media-sm">
          <a class="media media-new" href="#">
            <span class="avatar bg-success"><i class="ti-user"></i></span>
            <div class="media-body">
              <p>New user registered</p>
              <time datetime="2017-07-14 20:00">Just now</time>
            </div>
          </a>

          <a class="media" href="#">
            <span class="avatar bg-danger"><i class="ti-package"></i></span>
            <div class="media-body">
              <p>Package lost on the way!</p>
              <time datetime="2017-07-14 20:00">1 hour ago</time>
            </div>
          </a>
        </div>
      </div>

      <footer class="quickview-footer flexbox">
        <div>
          <a href="#">View full archive</a>
        </div>
        <div>
          <a href="#" data-provide="tooltip" title="Mark all as read"><i class="fa fa-circle-o"></i></a>
          <a href="#" data-provide="tooltip" title="Update"><i class="fa fa-repeat"></i></a>
          <a href="#" data-provide="tooltip" title="Settings"><i class="fa fa-gear"></i></a>
        </div>
      </footer>
    </div>


    <!-- END Quickviews -->
    <!--++++++++++++++++++++++++++++++++++++++++++++++++++++++++++-->




    <!-- Scripts -->
    <script src="<?php echo js_path('app.min.js', '_theme_'); ?>"></script>
    <script src="<?php echo js_path('script.js', '_theme_'); ?>"></script>
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

			//Metronic.init(); // init metronic core componets
			//Layout.init(); // init layout
			//ComponentsPickers.init();

		});
		</script>
  </body>
</html>
