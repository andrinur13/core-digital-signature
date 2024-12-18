<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php if ($this->session->flashdata('message_form')) {
    $msg = $this->session->flashdata('message_form');
?>
<div class="callout callout-<?php echo $msg['status'];?>" role="alert">
  <button type="button" class="close" data-dismiss="callout" aria-label="Close">
    <span>×</span>
  </button>
  <h5><?php echo $msg['title'];?></h5>
  <p><?php echo $msg['message'];?></p>
</div>
<?php } ?>

<div class="card card-bordered">
    <div class="card-header">
        <h4 class="card-title"><strong>Data Sertifikat PPG</strong></h4>
    </div>
    <div class="card-body" id="tbl-container" style="padding:0 !important;">
        <form action="/index.php/sertifikat/Ppg/update/<?= $ppg->dokumenPpgId ?>" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="nomorDokumen">Nomor SK</label>
                    <input type="text" class="form-control" id="nomorDokumen" name="nomorDokumen" placeholder="Masukkan Nomor SK Dokumen" required value="<?= $ppg->nomorDokumen ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Nomor Dokumen PPG</label><br>
                    <input name="nomorPpgMahasiswa" type="text" class="form-control" value="<?= $ppg->nomorPpgMahasiswa ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Tgl Sertifikat PPG</label><br>
                    <input name="tanggalSertifikat" type="date" class="form-control" value="<?= $ppg->tanggalSertifikat ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Nama Mahasiswa</label><br>
                    <input name="namaMahasiswa" type="text" class="form-control" value="<?= $ppg->namaMahasiswa ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">NIM Mahasiswa</label><br>
                    <input name="nimMahasiswa" type="text" class="form-control" value="<?= $ppg->nimMahasiswa ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Kota Lahir Mahasiswa</label><br>
                    <input name="kotaLahir" type="text" class="form-control" value="<?= $ppg->kotaLahir ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Tgl Lahir Mahasiswa</label><br>
                    <input name="tanggalLahir" type="date" class="form-control" value="<?= $ppg->tanggalLahir ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Nama Gelar Guru (Bidang)</label><br>
                    <input name="namaGelarGuru" type="text" class="form-control" value="<?= $ppg->namaGelarGuru ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Dokumen</label><br>
                    <?php if($ppg->pathDokumen): ?>
                    <label for="unitParent" class="col-form-label"> <a target="_blank" href="/<?= $ppg->pathDokumen ?>">Dokumen Existing</a> </label><br>
                    <? endif; ?>
                    <input name="pathDocument" type="file" class="form-control">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Nama Pejabat Penandatangan</label><br>
                    <input name="pejabatanPenandatangan" type="text" class="form-control" value="<?= $ppg->pejabatanPenandatangan ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Jabatan Penandatangan</label><br>
                    <input name="jabatanPenandatangan" type="text" class="form-control" value="<?= $ppg->jabatanPenandatangan ?>">
                </div>

                <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                    <label for="unitParent" class="col-form-label require">Nomor Jabatan Penandatangan</label><br>
                    <input name="nomorJabatanPenandatangan" type="text" class="form-control" value="<?= $ppg->nomorJabatanPenandatangan ?>">
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <div class="form-group col-12 col-lg-6 col-md-12 p-4">
                        <button type="submit" class="btn btn-sm btn-primary"> Update </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
