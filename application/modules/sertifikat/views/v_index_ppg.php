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
            <button type="button" id="add-btn" class="mx-1 btn btn-round btn-label btn-bold btn-primary" data-toggle="modal" data-target="#addCertificateModal">
                Tambah Sertifikat
                <label><i class="ti-plus"></i></label>
            </button>
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
                            <button class="btn btn-sm btn-primary" 
                                    data-toggle="modal" 
                                    data-target="#certificatePreviewModal" 
                                    data-image="<?= base_url($certificate->pathDokumen) ?>" 
                                    data-download="<?= base_url($certificate->pathDokumen) ?>"> 
                                Lihat Sertifikat
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
<div class="modal fade" id="addCertificateModal" tabindex="-1" role="dialog" aria-labelledby="addCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCertificateModalLabel">Tambah Sertifikat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="Ppg/add_certificate" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomorDokumen">Nomor Dokumen UAD</label>
                        <input type="text" class="form-control" id="nomorDokumen" name="nomorDokumen" placeholder="Masukkan Nomor Dokumen Internal UAD" required>
                    </div>
                    <div class="form-group">
                        <label for="nomorPpgMahasiswa">Nomor Dokumen PPG</label>
                        <input type="text" class="form-control" id="nomorPpgMahasiswa" name="nomorPpgMahasiswa" placeholder="Masukkan Nomor Dokumen PPG" required>
                    </div>
                    <div class="form-group">
                        <label for="namaMahasiswa">Nama Mahasiswa</label>
                        <input type="text" class="form-control" id="namaMahasiswa" name="namaMahasiswa" placeholder="Masukkan Nama Mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label for="nimMahasiswa">NIM Mahasiswa</label>
                        <input type="text" class="form-control" id="nimMahasiswa" name="nimMahasiswa" placeholder="Masukkan NIM Mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label for="kotaLahir">Kota Lahir Mahasiswa</label>
                        <input type="text" class="form-control" id="kotaLahir" name="kotaLahir" placeholder="Masukkan Kota Lahir Mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggalLahir">Tgl Lahir Mahasiswa</label>
                        <input type="date" class="form-control" id="tanggalLahir" name="tanggalLahir" placeholder="Masukkan Tgl Lahir Mahasiswa" required>
                    </div>
                    <div class="form-group">
                        <label for="namaGelarGuru">Nama Gelar Guru</label>
                        <input type="text" class="form-control" id="namaGelarGuru" name="namaGelarGuru" placeholder="Ex: Indonesia, Bahasa Inggris" required>
                    </div>
                    <div class="form-group">
                        <label for="photoPath">Unggah Foto Profil</label>
                        <input type="file" class="form-control-file" id="photoPath" name="photoPath" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggalSigned">Tgl Signed Dokumen</label>
                        <input type="date" class="form-control" id="tanggalSigned" name="tanggalSigned" placeholder="Masukkan Tgl Lahir Mahasiswa" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Certificate Preview -->
<div class="modal fade" id="certificatePreviewModal" tabindex="-1" role="dialog" aria-labelledby="certificatePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificatePreviewModalLabel">Lihat Sertifikat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <!-- Sertifikat akan ditampilkan di sini -->
                    <img id="modal-certificate-image" src="" alt="Sertifikat" class="img-fluid">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a id="downloadCertificate" href="#" class="btn btn-primary" download>Unduh Sertifikat</a>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to dynamically set the certificate source and download link
    $('#certificatePreviewModal').on('show.bs.modal', function (e) {
        var button = $(e.relatedTarget); // Button that triggered the modal
        var imageUrl = button.data('image'); // Extract image URL from data attribute
        var downloadUrl = button.data('download'); // Extract download URL from data attribute

        // Set the image and download link
        $('#modal-certificate-image').attr('src', imageUrl);
        $('#downloadCertificate').attr('href', downloadUrl);
    });
</script>
