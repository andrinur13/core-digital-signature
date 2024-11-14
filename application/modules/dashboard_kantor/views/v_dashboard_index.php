<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="row">
        <div class="col-12 col-lg-8">
  <div class="card shadow-2">
    <div class="row card-body">
      <div class="col-lg-12"> 
        <h5 class="font-weight-bold">Task Surat Masuk</h5>
      </div>
      
      <div class="col-6">
        <div class="card card-bordered">
          <div class="card-body">
            <div class="pb-4 text-center">
              <div class="card-bordered bg-color-primary card-body">
                <span class="fa fa-envelope-o fs-30 text-white"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6 text-center">
                <div class="fs-40 fw-600 color-primary"><?= $total_surat_masuk; ?></div>
              </div>
              <div class="col-12 col-lg-6 d-flex text-lg-left text-center flex-column">
                <span class="fs-15">Belum</span>
                <span class="fs-15 fw-700">Dibaca</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-6">
        <div class="card card-bordered">
          <div class="card-body">
          <div class="pb-4 text-center">
              <div class="card-bordered bg-color-primary2 card-body">
                <span class="fa fa-file-text-o fs-30 text-white"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-lg-6 text-center">
                <div class="fs-40 fw-600 color-primary2"><?= $total_surat_masuk_belum_proses; ?></div>
              </div>
              <div class="col-12 col-lg-6 d-flex text-lg-left text-center flex-column">
                <span class="fs-15">Belum</span>
                <span class="fs-15 fw-700">Ditindak</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="col-12 col-lg-4">
  <div class="card shadow-2">
    <div class="row card-body">
      <div class="col-lg-12"> 
        <h5 class="font-weight-bold">Task Surat Keluar</h5>
      </div>
      
      <div class="col-12">
        <div class="card card-bordered">
          <div class="card-body">
            <div class="pb-4 text-center">
              <div class="card-bordered bg-dark card-body">
                <span class="fa fa-file-o fs-30 text-white"></span>
              </div>
            </div>
            <div class="row">
              <div class="col-6 text-center">
                <div class="fs-40 fw-600 text-dark"><?= $total_surat_keluar; ?></div>
              </div>
              <div class="col-6 d-flex text-left flex-column">
                <span class="fs-15">surat</span>
                <span class="fs-15 fw-700">Draft</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
<!-- 
<div class="col-12 col-lg-8">
  <div class="card shadow-2">
    <div class="row card-body">

      <div class="col-lg-12"> 
        <h5 class="font-weight-bold">Task Surat Masuk</h5>
      </div>
      
      <div class="col-6">
        <div class="card card-bordered">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-lg-12 text-center">
                <img src="<?= asset_path('/img/surat.svg', '_theme_') ?>" class="shadow-custom" style= "width: 150px">
              </div>
              <div class="col-12 col-lg-6 d-flex text-lg-left text-center flex-column align-self-center">
                <span class="fs-15">belum</span>
                <span class="fs-15 fw-700">dibaca</span>
              </div>
              <div class="col-12 col-lg-6 text-center align-self-center">
                <div class="fs-40 fw-600 color-primary">9</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="card card-bordered">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-lg-12 text-center">
                <img src="<?= asset_path('/img/folder.svg', '_theme_') ?>" class="shadow-custom" style= "width: 150px">
              </div>
              <div class="col-12 col-lg-6 d-flex text-lg-left text-center flex-column align-self-center">
                <span class="fs-15">belum</span>
                <span class="fs-15 fw-700">ditindak</span>
              </div>
              <div class="col-12 col-lg-6 text-center align-self-center">
                <div class="fs-40 fw-600 color-primary2">999</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>

<div class="col-12 col-lg-4">
  <div class="card shadow-2">
    <div class="row card-body">

      <div class="col-12"> 
        <h5 class="font-weight-bold">Task Surat Keluar</h5>
      </div>
      
      <div class="col-12">
        <div class="card card-bordered">
          <div class="card-body">
            <div class="row">
              <div class="col-3 col-lg-12 text-center">
                <img src="<?= asset_path('/img/note.svg', '_theme_') ?>" class="shadow-custom" style= "width: 150px">
              </div>
              <div class="col-4 col-lg-6 d-flex text-left flex-column align-self-center">
                <span class="fs-15">daftar</span>
                <span class="fs-15 fw-700">draft</span>
              </div>
              <div class="col-5 col-lg-6 text-center align-self-center">
                <div class="fs-40 fw-600 color-seondary">999</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div> -->
</div>