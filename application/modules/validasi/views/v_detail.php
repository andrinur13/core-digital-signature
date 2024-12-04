<!doctype html>
<html lang="id">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


    <title>UADSign - Validasi Sertifikat</title>

    <style>
      body {
          font-family: 'Poppins', sans-serif;
        background-color: #F5F6FA;
        display: flex;
        justify-content: center;  /* Horizontally center */
        align-items: center;      /* Vertically center */
        height: 100vh;            /* Full viewport height */
        margin: 0;                /* Remove default margin */
      }
      
      .card-bordered{
        border-radius: 16px !important;
    }

      .container {
        width: 100%;
        max-width: 900px;         /* Maximum width for the content */
      }

      td {
        padding: 10px !important;
      }
    </style>
  </head>
  <body>
    <div class="container">
        <div class="row justify-content-center mt-4">
          <div class="col-12 col-lg-10">
            <div class="card card-bordered">
              <div class="card-body">
                <div class="d-flex flex-row justify-content-between align-items-center mx-2">
                    <div class="h4 text-center">
                      Validasi Sertifikat
                    </div>
                    <div class="row">
                        <div class="col text-center">
                            <div>
                                <img class="img-fluid" src="/themes/theadmin/img/logo-eoffice.svg" alt=""> <span class="">UADSign</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4 justify-content-center">
                    <div class="col">
                      <div class="table-responsive">
                        <table class="table table-bordered">
                          <tbody>
                            <tr>
                              <td class="fw-bold col-4">Nomor SK</td>
                              <td> <?= $ppg->nomorDokumen ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">Nomor Sertifikat</td>
                              <td> <?= $ppg->nomorPpgMahasiswa ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">Tanggal Sertifikat</td>
                              <td> <?= $ppg->tanggalSertifikat ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">Nama Sertifikat Pendidik</td>
                              <td> <?= $ppg->namaMahasiswa ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">NIM</td>
                              <td> <?= $ppg->nimMahasiswa ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">Program Studi</td>
                              <td> Pendidikan Profesi Guru </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">Bidang Studi</td>
                              <td> <?= $ppg->namaGelarGuru ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">Jabatan Penandatangan</td>
                              <td> <?= $ppg->jabatanPenandatangan ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">Penandatangan</td>
                              <td> <?= $ppg->pejabatanPenandatangan ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">NIP</td>
                              <td> <?= $ppg->nomorJabatanPenandatangan ?> </td>
                            </tr>
                            <tr>
                              <td class="fw-bold">Digital Sertifikat</td>
                              <td> <a class="btn btn-sm btn-primary" href="https://super-app.privy.id/verification/<?= $ppg->idExternalDokumen ?>" title="Link to Privy for certificate validation"><i class="bi bi-link-45deg me-2"></i>Link Ke Privy</a> </td>
                            </tr>
                          </tbody>
                        </table>
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
