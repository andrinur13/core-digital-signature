<!doctype html>
<html lang="id">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

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


  <title>UADSign - Validasi Sertifikat</title>


</head>

<body>
  <div class="container">
    <div class="row justify-content-center mt-4">
      <div class="col-12 col-lg-10">
        <div class="card card-bordered">
          <div class="card-body m-4">
            <div class="row">
              <div class="col">

                <div class="mb-4 row">
                  <div class="col-lg-6 h4 order-2 order-md-1 text-center text-lg-left">
                    <span class="text-success">
                      <i class="bi bi-check-circle-fill"></i>
                    </span>
                    Verifikasi Sertifikat
                  </div>
                  <div class="col-lg-6 d-flex mb-4 mb-lg-0 order-1 order-md-2 justify-content-center justify-content-lg-end">
                    <div>
                      <img class="mr-2 img-fluid" style="max-width: 300px;" src="/assets/img/uad-logo.png" alt="">
                    </div>
                  </div>
                </div>

                <div class="mb-4">
                  <span class="">Nama Sertifikat Pendidik</span>
                  <p class="fw-bold mb-2 h4"><?= $ppg->namaMahasiswa ?></p>

                  <span class="">NIM</span>
                  <p class="fw-bold mb-2 h4"><?= $ppg->nimMahasiswa ?></p>

                  <span class="">Bidang Studi</span>
                  <p class="fw-bold mb-2 h4"><?= $ppg->namaGelarGuru ?></p>
                </div>

                <div class="">
                  <div class="row">

                    <div class="col-lg-6">
                      <span class="fw-bold">Nomor SK</span>
                      <p><?= $ppg->nomorDokumen ?></p>
                    </div>
                    <div class="col-lg-6">
                      <span class="fw-bold">Nomor Sertifikat</span>
                      <p><?= $ppg->nomorPpgMahasiswa ?></p>
                    </div>
                    <div class="col-lg-6">
                      <span class="fw-bold">Nomor Kelulusan</span>
                      <p><?= $ppg->nomorKelulusan ?></p>
                    </div>
                    <div class="col-lg-6">
                      <span class="fw-bold">Jabatan Penandatangan</span>
                      <p><?= $ppg->jabatanPenandatangan ?></p>
                    </div>

                    <div class="col-lg-6">
                      <span class="fw-bold">Penandatangan</span>
                      <p><?= $ppg->pejabatanPenandatangan ?></p>
                    </div>
                    <div class="col-lg-6">
                      <span class="fw-bold">NIP</span>
                      <p><?= $ppg->nomorJabatanPenandatangan ?></p>
                    </div>
                    <div class="col-lg-6 mt-4 d-flex justify-content-center justify-content-lg-start">
                      <? if($ppg->idExternalDokumen) : ?>
                      <p class=""><a class="btn btn-sm btn-custom" href="https://stg-web-apps-fe-carstensz.privy.id/verification/<?= $ppg->idExternalDokumen ?>" title="Link to Privy for certificate validation"><i class="bi bi-link-45deg me-2"></i>Digital Sertifikat Privy</a></p>
                      <? endif; ?>
                    </div>

                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>