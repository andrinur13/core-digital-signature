<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <meta name="description" content="Responsive admin dashboard and web application ui kit. ">
      <meta name="keywords" content="login, signin">

      <title><?php echo config_item('app_title') . config_item('title_separator') . $template['title']; ?></title>

    <!-- Fonts -->
      <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,300i" rel="stylesheet">

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

   <main class="main-container">
    <div class="main-content d-flex justify-content-center">
        <div class="row col-12 justify-content-between">

          <?php echo $template['body']; ?>

        </div>
    </div>
  </main>
    <!-- Scripts -->
    <script src="<?php echo js_path('core.min.js', '_theme_'); ?>"></script>
    <script src="<?php echo js_path('app.min.js', '_theme_'); ?>"></script>
    <script src="<?php echo js_path('script.js', '_theme_'); ?>"></script>
    <script src="<?php echo js_path('custom.js', '_theme_'); ?>"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <?php if ($this->session->flashdata('logout_message')): 
      ?>
            
      <script>
          // Tampilkan alert menggunakan SweetAlert
          Swal.fire({
              title: 'Informasi !',
              text: '<?= $this->session->flashdata('logout_message'); ?>',
              icon: 'success',
              showConfirmButton: false,
              timer: 1500
          });
      </script>
    <?php endif; ?>

  </body>
</html>

