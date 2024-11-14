<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
// print_r($data);
?>
<div class="modal-body form-type-round">
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Jenis Surat</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $data['jnsrtNama']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Perihal Surat</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $data['srtPerihal']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label">Nomor Surat</label>
      <div class="col-8 col-lg-9">
         <p class="col-form-label">: <?php echo $data['srtNomorSurat']; ?></p>
      </div>
   </div>
   <div class="form-group row">
      <label class="col-4 col-lg-3 col-form-label" for="input-2">File</label>
      <div class="col-8 col-lg-9">
         <div class="d-flex flex-row align-items-start">
            <p class="col-form-label">:</p>
            <?php
            if (is_file($this->config->item('upload_path') . $data['srtFile']) && $data['srtFile'] != '') { ?>
               <div class="card card-bordered ml-2">
                  <div class="media align-items-center">
                     <i class="fa fa-file-text fs-20 text-success"></i>
                     <p class="font-weight-bold">
                        <a target="_blank" href="<?php echo site_url($module . 'view_by_file/' . $data['srtFile']); ?>" class="" title="Lihat File"> Lihat File</a>
                     </p>
                  </div>
               </div>
            <?php } else {
               echo '<div class="card card-bordered ml-2"><div class="media align-items-center"><span class="fa fa-file-text fs-20 text-danger"></span> File tidak ditemukan.</div></div>';
            }
            ?>
         </div>
      </div>
   </div>
   <div class="card card-bordered card-body">
      <ol class="timeline timeline-activity timeline-sm timeline-content-right w-100 py-20 pr-20">
         <li class="timeline-block">
            <div class="timeline-point">
               <span class="avatar avatar-lg bg-<?php echo $color_selesai; ?>"><i class="fa fa-check-circle fa-lg"></i></span>
            </div>
            <div class="row timeline-content">
               <div class="col-lg-3">
                  <a class="btn btn-sm btn-bold btn-round btn-flat btn-<?php echo $color_selesai; ?>" href="#">SELESAI</a>
               </div>
               <div class="col-lg-1">
                  <p>&nbsp;</p>
               </div>
               <div class="col-lg-8">
                  <time datetime=""><?php echo $time_selesai; ?></time>
                  <p class="text-<?php echo $color_selesai; ?>"><?php echo $text_selesai; ?></p>
               </div>
               <p>&nbsp;</p>
            </div>
         </li>

         <li class="timeline-block">
            <div class="timeline-point">
               <span class="avatar avatar-lg bg-<?php echo $color_dibalas; ?>"><i class="fa fa-mail-reply-all fa-lg"></i></span>
            </div>
            <div class="row timeline-content">
               <div class="col-lg-3">
                  <a class="btn btn-sm btn-bold btn-round btn-flat btn-<?php echo $color_dibalas; ?>" href="#">PEMBUATAN SURAT</a>
               </div>
               <div class="col-lg-1">
                  <p>&nbsp;</p>
               </div>
               <div class="col-lg-8">
                  <time datetime=""><?php echo $time_dibalas; ?></time>
                  <p class="text-<?php echo $color_dibalas; ?>"><?php echo $text_dibalas; ?></p>
               </div>
               <p>&nbsp;</p>
            </div>
         </li>

         <!-- <li class="timeline-block">
            <div class="timeline-point">
               <span class="avatar avatar-lg bg-<?php echo $color_arahan; ?>"><i class="fa fa-comment fa-lg"></i></span>
            </div>
            <div class="row timeline-content">
               <div class="col-lg-3">
                  <a class="btn btn-sm btn-bold btn-round btn-flat btn-<?php echo $color_arahan; ?>" href="#">ARAHAN</a>
               </div>
               <div class="col-lg-1">
                  <p>&nbsp;</p>
               </div>
               <div class="col-lg-8">
                  <time datetime=""><?php echo $time_arahan; ?></time>
                  <p class="text-<?php echo $color_arahan; ?>"><?php echo $text_arahan; ?></p>
               </div>
               <p>&nbsp;</p>
            </div>
         </li> -->

         <li class="timeline-block">
            <div class="timeline-point">
               <span class="avatar avatar-lg bg-<?php echo $color_tindakan; ?>"><i class="fa fa-hand-pointer-o fa-lg"></i></span>
            </div>
            <div class="row timeline-content">
               <div class="col-lg-3">
                  <a class="btn btn-sm btn-bold btn-round btn-flat btn-<?php echo $color_tindakan; ?>" href="#">TINDAK LANJUT</a>
               </div>
               <div class="col-lg-1">
                  <p>&nbsp;</p>
               </div>
               <div class="col-lg-8">
                  <time datetime=""><?php echo $time_tindakan; ?></time>
                  <p class="text-<?php echo $color_tindakan; ?>"><?php echo $text_tindakan; ?></p>
               </div>
               <p>&nbsp;</p>
            </div>
         </li>

         <li class="timeline-block">
            <div class="timeline-point">
               <span class="avatar avatar-lg bg-<?php echo $color_dibaca; ?>"><i class="fa fa-file-text fa-lg"></i></span>
            </div>
            <div class="row timeline-content">
               <div class="col-lg-3">
                  <a class="btn btn-sm btn-bold btn-round btn-flat btn-<?php echo $color_dibaca; ?>" href="#">DIBACA</a>
               </div>
               <div class="col-lg-1">
                  <p>&nbsp;</p>
               </div>
               <div class="col-lg-8">
                  <time datetime=""><?php echo $time_dibaca; ?></time>
                  <p class="text-<?php echo $color_dibaca; ?>"><?php echo $text_dibaca; ?></p>
               </div>
               <p>&nbsp;</p>
            </div>
         </li>
      </ol>
   </div>
</div>