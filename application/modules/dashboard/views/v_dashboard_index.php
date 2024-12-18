<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="row">
<div class="col-12 col-lg-8">
  <div class="card shadow-2">
    <div class="row card-body">

      <div class="col-lg-12"> 
        <h5 class="font-weight-bold">Informasi</h5>
      </div>
      
      <div class="col-6">
        <div class="card card-bordered">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-lg-6 d-flex text-lg-left text-center flex-column align-self-center">
                <span class="fs-15">Jumlah</span>
                <span class="fs-15 fw-700">Units</span>
              </div>
              <div class="col-12 col-lg-6 text-center align-self-center">
                <div class="fs-40 fw-600 color-primary"><?= $jumlah_unit; ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6">
        <div class="card card-bordered">
          <div class="card-body">
            <div class="row">
              <div class="col-12 col-lg-6 d-flex text-lg-left text-center flex-column align-self-center">
                <span class="fs-15">Jumlah</span>
                <span class="fs-15 fw-700">Pengguna</span>
              </div>
              <div class="col-12 col-lg-6 text-center align-self-center">
                <div class="fs-40 fw-600 color-primary2"><?= $jumlah_user; ?></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>
</div>
</div>
