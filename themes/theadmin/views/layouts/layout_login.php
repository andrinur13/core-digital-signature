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
  <!-- Main container -->
  <main class="main-container">
    <div class="main-content d-flex justify-content-center">
        <div class="row col-12 justify-content-between">

          <div class="col-lg-5 d-flex align-items-center justify-content-lg-end justify-content-center">
            <div class="animated fadeInLeft" id="animationSandbox">
              <div class="row justify-content-center">
                  <div class="col-lg-12">
                    <div class="justify-content-center align-items-center">
                      <img class="mb-4 w-50 d-block mx-auto" src="<?= asset_path('/img/logo-eoffice.svg', '_theme_') ?>">
                    </div>
                    <p class="text-login d-flex justify-content-center">E-Office.</p>
                    <p class="text-center">Membantu Pengelolaan Surat.</p>
                  </div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-6 d-flex align-items-center justify-content-lg-start justify-content-center">
              <div class="animated fadeInUp" id="animationSandbox">
                  <div class="card card-login card-shadowed px-30 py-30 ">
                      <p class="text2 py-4 text-center">Sign in</p>
                      <form class="form-type-round">
                          <div class="form-group">
                              <input type="text" class="form-control" placeholder="Username" id="input-placeholder">
                          </div>
                          <div class="form-group">
                              <input type="password" class="form-control" placeholder="Password" id="password">
                          </div>
                          <div class="form-group flexbox">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" checked>
                                <span class="custom-control-indicator"></span>
                                <span class="custom-control-description">Remember me</span>
                            </label>
                          </div>
                          <hr class="w-30px">
                          <div class="form-group">
                            <a class="btn btn-round btn-block btn-custom" type="submit" href="../dashboard/index.html">Login</a>
                          </div>
                      </form>
                  </div>
              </div>
          </div>

        </div>
    </div>
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