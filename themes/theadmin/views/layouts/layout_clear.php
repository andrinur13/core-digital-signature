<?php (defined('BASEPATH')) OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo config_item('app_name') . config_item('title_separator') . $template['title']; ?></title>

		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" type="text/css" href="<?php echo css_path('bootstrap.min.css', '_theme_'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo css_path('font-awesome.min.css', '_theme_'); ?>">

		<!-- page specific plugin styles -->

		<!-- text fonts 
		<link rel="stylesheet" href="<?php echo css_path('font-css.css', '_theme_'); ?>" />
		-->
		<link rel="stylesheet" href="<?php echo css_path('fonts/css.css', '_theme_'); ?>" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo css_path('ace.min.css', '_theme_'); ?>" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo css_path('ace-part2.min.css', '_theme_'); ?>" />
		<![endif]-->
		<link rel="stylesheet" type="text/css" href="<?php echo css_path('ace-skins.min.css', '_theme_'); ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo css_path('ace-rtl.min.css', '_theme_'); ?>">

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo css_path('ace-ie.min.css', '_theme_'); ?>" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo js_path('ace-extra.min.js', '_theme_'); ?>"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lte IE 8]>
			<script src="<?php echo js_path('html5shiv.js', '_theme_'); ?>"></script>
			<script src="<?php echo js_path('respond.min.js', '_theme_'); ?>"></script>
		<![endif]-->
		
		<?php echo $template['partials']['modules_css']; ?> 
		
		<!-- basic scripts -->

		<!--[if !IE]> -->
		<script src="<?php echo js_path('jquery.2.1.0.min.js', '_theme_'); ?>"></script>

		<!-- <![endif]-->

		<!--[if IE]>
			<script src="<?php echo js_path('jquery.1.11.0.min.js', '_theme_'); ?>"></script>
		<![endif]-->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo js_path('jquery.min.js', '_theme_'); ?>'>"+"<"+"/script>");
		</script>

		<!-- <![endif]-->

		<!--[if IE]>
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo js_path('jquery1x.min.js', '_theme_'); ?>'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<style>
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
	</head>

	<body class="no-skin">
		<div class="main-container" id="main-container">
			<div class="main-content">
					<?php echo $template['body']; ?>
			</div><!-- /.main-content -->
		</div><!-- /.main-container -->
		<script type="text/javascript">
			if("ontouchend" in document) document.write("<script src='<?php echo js_path('jquery.mobile.custom.min.js', '_theme_'); ?>'>"+"<"+"/script>");
		</script>
		<script src="<?php echo js_path('bootstrap.min.js', '_theme_'); ?>"></script>
		<!-- ace scripts -->
		<script src="<?php echo js_path('ace-elements.min.js', '_theme_'); ?>"></script>
		<script src="<?php echo js_path('ace.min.js', '_theme_'); ?>"></script>
		<?php echo $template['partials']['modules_js']; ?>
		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
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
				
				var $sidebar = $('.sidebar').eq(0);
				if( !$sidebar.hasClass('h-sidebar') ) return;

				$(document).on('settings.ace.top_menu' , function(ev, event_name, fixed) {
					if( event_name !== 'sidebar_fixed' ) return;

					var sidebar = $sidebar.get(0);
					var $window = $(window);

					//return if sidebar is not fixed or in mobile view mode
					var sidebar_vars = $sidebar.ace_sidebar('vars');
					if( !fixed || ( sidebar_vars['mobile_view'] || sidebar_vars['collapsible'] ) ) {
						$sidebar.removeClass('lower-highlight');
						//restore original, default marginTop
						sidebar.style.marginTop = '';

						$window.off('scroll.ace.top_menu')
						return;
					}


					var done = false;
					$window.on('scroll.ace.top_menu', function(e) {

						var scroll = $window.scrollTop();
						scroll = parseInt(scroll / 4);//move the menu up 1px for every 4px of document scrolling
						if (scroll > 17) scroll = 17;


						if (scroll > 16) {			
							if(!done) {
								$sidebar.addClass('lower-highlight');
								done = true;
							}
						}
						else {
							if(done) {
								$sidebar.removeClass('lower-highlight');
								done = false;
							}
						}

						sidebar.style['marginTop'] = (17-scroll)+'px';
					}).triggerHandler('scroll.ace.top_menu');

				}).triggerHandler('settings.ace.top_menu', ['sidebar_fixed' , $sidebar.hasClass('sidebar-fixed')]);

				$(window).on('resize.ace.top_menu', function() {
					$(document).triggerHandler('settings.ace.top_menu', ['sidebar_fixed' , $sidebar.hasClass('sidebar-fixed')]);
				});
			});
		</script>
	</body>
</html>
