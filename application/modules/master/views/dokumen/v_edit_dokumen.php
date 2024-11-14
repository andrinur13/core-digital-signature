<?php defined('BASEPATH') OR exit('No direct script access alloweds');?>
<div class="card card-outline-primary">
    <div class="card-header border-info">
		<h4 class="card-title"><strong><?= $template['title']; ?></strong></h4>
        <div class="card-header-actions" id="actionButton">
            <div class="btn-toolbar">
           
            </div>
        </div>
        
	</div>
    
    <div class="card-body">
        
        <?php echo form_open_multipart($moduleAdd,  'class="form-horizontal" '); ?>
            <ul class="nav nav-process nav-process-circle">
                <li class="nav-item complete processing">
                    <span class="nav-title">Naskah Dokumen</span>
                    <a class="nav-link active" data-toggle="tab" href="#naskah"></a>
                </li>

                <li class="nav-item">
                    <span class="nav-title">Pemaraf</span>
                    <a class="nav-link" data-toggle="tab" href="#wizard-basic-2"></a>
                </li>

                <li class="nav-item">
                    <span class="nav-title">Penandatangan</span>
                    <a class="nav-link" data-toggle="tab" href="#wizard-basic-3"></a>
                </li>
            </ul>


            <div class="tab-content">
                <div class="tab-pane fade active show" id="naskah" data-provide="validation">
                        <div class="form-group">
                            <label  class="require">Jenis Dokumen</label>
                            <select required name="dokJnsId" id="dokJnsId" required class="form-control" data-provide="selectpicker" >
                            <option value="">-- Jenis Dokumen --</option>
                                <?php
                                    foreach($jenisDokumen as $j => $jk):
                                ?>
                                    <option <?= ($dokumen->dokJnsId == $jk->jnsId) ? 'selected' : ''; ?> value="<?= encode($jk->jnsId) ?>" ><?= $jk->jnsNama; ?> </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback" id="error_dokJnsId"></span>
                            <div class="form-control-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label  class="require">Nomor Surat Dokumen</label>
                            <input class="form-control" required type="text" name="dokNoSrt" value="<?= $dokumen->dokNoSrt; ?>">
                            <span class="invalid-feedback" id="error_dokNoSrt"></span>
                        </div>

                        <div class="form-group">
                            <label class="require">Perihal Dokumen</label>
                            <input class="form-control" required type="text" name="dokNama" value="<?= $dokumen->dokNama; ?>">
                            <span class="invalid-feedback" id="error_dokNama"></span>
                        </div>
                        <div class="form-group">
                            <label class="text-dark require">File Dokumen</label>
                            <div class="input-group file-group">
                            <input required type="text" class="form-control file-value" placeholder="Choose file..." readonly="">
                            <input required type="file" name="file_surat">
                            <span class="input-group-btn">
                                <button class="btn btn-light file-browser" type="button"><i class="fa fa-search"></i> Cari File</button>
                            </span>
                            </div>
                            <span class="invalid-feedback" id="error_file_surat"></span>
                            <small class="form-text">Dokumen hanya yang berekstensi <b>.pdf</b> </small>
                            <span>File Sebelumnya :
                           <?php
                           if (@$dokumen->dokFile != '' && is_file($path . @$dokumen->dokFile)) { ?>
                              <input type="hidden" name="file_uploaded" value="<?php echo @$dokumen->dokFile; ?>">
                              <button type="button" class="btn btn-cyan btn-xs" onclick='show_file("<?php echo @$dokumen->dokFile ?>")'><i class="fa fa-search "></i></button>
                              <!-- <a class="badge badge-info" href="<?php echo base_url($path) . @$dokumen->dokFile; ?>" target="_blank"> <i class="fa fa-search"></i> Lihat Dokumen </a> -->
                           <?php } else { ?>
                              <span class="badge badge-warning"><i class="fa fa-warning"></i> File tidak ditemukan.</span>
                           <?php } ?>
                        </span>
                        </div>
                </div>
            </div>

            <hr>

            <div class="flexbox">
                <button class="btn btn-secondary disabled" data-wizard="prev">Back</button>
                <!-- <button type="submit" class="btn btn-success" >Next</button> -->
                <a class="btn btn-success" href="<?php echo site_url($createStepTwo); ?>">Selanjutnya</a>
            </div>
        <?php form_close(); ?>
    </div>

</div>


<!-- modal dokumen -->

<div class="modal modal-center fade show" id="modalDokumen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <h5 class="modal-title">Lihat Dokumen</h5>
                  <button type="button" class="close" data-dismiss="modal">
                     <span aria-hidden="true">×</span>
                  </button>
               </div>
               <div class="modal-body" id="modalWindowsBody">
                  <div id="docList"></div>

               </div>
               <div class="modal-footer">
                  <button type="button" class="btn btn-bold btn-secondary" data-dismiss="modal">Close</button>
               </div>
            </div>
         </div>
      </div>

      <!-- end modal dokumen -->
<script type="text/javascript">
   function show_file(filename) {
      var html = '';
      var filename = filename;
      var ext = filename.split('.');
      var file_type = ext[1];
      if (file_type == "pdf") {
         html += ' <embed src="<?php echo base_url($path); ?>' + filename + '" width="100%" height="500px" type="application/pdf">';

      } else {
         html += ' <img src="<?php echo base_url($path); ?>' + filename + '" width="100%">';
      }
      document.getElementById("docList").innerHTML = html;
      $('#modalDokumen').modal('show');
      // $('#modalDokumenTitle').text('Lihat Dokumen');

   };
</script>