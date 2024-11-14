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
                                    <option value="<?= encode($jk->jnsId) ?>" ><?= $jk->jnsNama; ?> </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback" id="error_dokJnsId"></span>
                            <div class="form-control-feedback"></div>
                        </div>

                        <div class="form-group">
                            <label  class="require">Nomor Surat Dokumen</label>
                            <input class="form-control" required type="text" name="dokNoSrt">
                            <span class="invalid-feedback" id="error_dokNoSrt"></span>
                        </div>

                        <div class="form-group">
                            <label class="require">Perihal Dokumen</label>
                            <input class="form-control" required type="text" name="dokNama">
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
                        </div>
                </div>
            </div>

            <hr>

            <div class="flexbox">
                <button class="btn btn-secondary disabled" data-wizard="prev">Back</button>
                <button type="submit" class="btn btn-success" >Next</button>
                <button class="btn btn-primary d-none hidden" data-wizard="finish">Finish</button>
            </div>
        <?php form_close(); ?>
    </div>

</div>

