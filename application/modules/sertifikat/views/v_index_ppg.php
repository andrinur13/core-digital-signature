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

<div class="card card-outline-primary">
	<div class="card-header">
		<h4 class="card-title"><strong>Data Sertifikat PPG</strong></h4>
      	<div class="btn-toolbar">
            <a id="add-btn" class="mx-1 btn btn-round btn-label btn-bold btn-primary" data-original-title="Tambah data unit kerja." data-rel="tooltip" data-placement="bottom" href="Ppg/generate_all">
				Generate
				<label><i class="ti-reload"></i></label>
			</a>
			<a id="add-btn" class="mx-1 btn btn-round btn-label btn-bold btn-primary" data-original-title="Tambah data unit kerja." data-rel="tooltip" data-placement="bottom" href="#">
				Tambah Sertifikat
				<label><i class="ti-plus"></i></label>
			</a>
        </div>
	</div>
	<div class="card-body" id="tbl-container" style="padding:0 !important;">
		<table id="t-unit-kerja" class="table table-separated table-striped tab" width="100%">
			<thead class="bg-color-primary1">
				<tr>
					<th>No</th>
                    <th>No Dokumen</th>
					<th>Mahasiswa</th>
                    <th>File</th>
					<th>Aksi</th>
				</tr>
			</thead>
            <tbody>
                <?php foreach ($dt_ppgs as $index => $certificate): ?>
                    <tr>
                        <td> <?= $index + 1; ?> </td>
                        <td> <?= $certificate->nomorDokumen ?> </td>
                        <td> <?= $certificate->namaMahasiswa ?> </td>
                        <td> 
                            <?php if($certificate->pathDokumen): ?>
                            <a href="/<?= $certificate->pathDokumen ?>" class="btn btn-sm btn-primary"> Download </a> 
                            <?php else: ?>
                            <span class="btn btn-sm btn-danger"> Dokumen Belum Digenerate </span>
                            <?php endif ?>
                        </td>
                        <td>
                            <a href="/<?= $certificate->dokumenPpgId ?>" class="btn btn-sm btn-danger"> Pdf </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
		</table>
	</div>
</div>