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
            <a id="add-btn" class="mx-1 btn btn-round btn-label btn-bold btn-primary" href="Ppg/generate_all">
                Generate
                <label><i class="ti-reload"></i></label>
            </a>
            <a id="add-btn" class="mx-1 btn btn-round btn-label btn-bold btn-primary" href="#">
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
                            <!-- Update the download link to trigger the modal -->
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#imageModal" data-image="<?= base_url($certificate->pathDokumen) ?>"> 
                                View Image 
                            </button> 
                            <?php else: ?>
                            <span class="btn btn-sm btn-danger"> Dokumen Belum Digenerate </span>
                            <?php endif ?>
                        </td>
                        <td>
                            <a href="/<?= $certificate->dokumenPpgId ?>" class="btn btn-sm btn-danger"> Button 1 </a>
                            <a href="/<?= $certificate->dokumenPpgId ?>" class="btn btn-sm btn-danger"> Button 2 </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> <!-- Add 'modal-lg' here -->
        <div class="modal-content">
            <div class="modal-body">
                <!-- Image will be loaded dynamically -->
                <img src="" id="modal-image" class="img-fluid" alt="Certificate Image">
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to dynamically set the image source
    $('#imageModal').on('show.bs.modal', function (e) {
        var imageUrl = $(e.relatedTarget).data('image'); // Get the image URL from the data attribute
        $('#modal-image').attr('src', imageUrl); // Set the image source in the modal
    });
</script>
