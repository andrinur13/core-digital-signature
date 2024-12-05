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
    </div>
    <div class="card-header">
        <div class="btn-toolbar">
            <!-- <a id="add-btn" class="mx-1 btn btn-round btn-label btn-bold btn-primary" href="Ppg/generate_all">
                Generate
                <label><i class="ti-reload"></i></label>
            </a> -->
            
            <a href="Ppg/fetch_all_certificate_local" type="button" id="add-btn" class="my-2 mx-1 btn btn-round btn-label btn-bold btn-primary">
                Fetch Sertifikat Lokal
                <label><i class="ti-plus"></i></label>
            </a>
            <a href="Ppg/generate_all_certificate" type="button" id="add-btn" class="my-2 mx-1 btn btn-round btn-label btn-bold btn-primary">
                Generate Sertifikat Lokal
                <label><i class="ti-plus"></i></label>
            </a>
            <a href="Ppg/generate_privy_all" type="button" id="add-btn" class="my-2 mx-1 btn btn-round btn-label btn-bold btn-primary">
                Generate Sertifikat Privy
                <label><i class="ti-plus"></i></label>
            </a>
            <a href="Ppg/fetch_privy_all" type="button" id="add-btn" class="my-2 mx-1 btn btn-round btn-label btn-bold btn-primary">
                Fetch Sertifikat Privy
                <label><i class="ti-plus"></i></label>
            </a>
            <a href="Ppg/download_privy_all" type="button" id="add-btn" class="my-2 mx-1 btn btn-round btn-label btn-bold btn-primary">
                Unduh Semua Sertifikat Privy
                <label><i class="ti-plus"></i></label>
            </a>
            <button type="button" id="add-btn" class="my-2 mx-1 btn btn-round btn-label btn-bold btn-primary" data-toggle="modal" data-target="#addData">
                Upload Data
                <label><i class="ti-plus"></i></label>
            </button>
            <button type="button" id="add-btn" class="my-2 mx-1 btn btn-round btn-label btn-bold btn-primary" data-toggle="modal" data-target="#addCertificateModal">
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
                    <th>File Signed</th>
                    <th>Doc Token Privy</th>
                    <th>File Privy</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dt_ppgs as $index => $certificate): ?>
                    <tr>
                        <td> <?= $index + 1; ?> </td>
                        <td> <?= $certificate->nomorPpgMahasiswa ?> </td>
                        <td> <?= $certificate->namaMahasiswa ?> </td>
                        <td>
                            <?php if($certificate->linkDokumen): ?>
                            <!-- Update the download link to trigger the modal -->
                            <button class="btn btn-sm btn-primary" 
                                    data-toggle="modal" 
                                    data-target="#certificatePreviewModal" 
                                    data-image="<?= base_url($certificate->linkDokumen) ?>" 
                                    data-download="<?= base_url($certificate->linkDokumen) ?>"> 
                                Lihat Dokumen 
                            </button> 
                            <?php else: ?>
                            <span class="btn btn-sm btn-danger"> Dokumen Belum Upload </span>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if($certificate->pathDokumenSigned): ?>
                            <!-- Update the download link to trigger the modal -->
                            <button class="btn btn-sm btn-primary" 
                                    data-toggle="modal" 
                                    data-target="#certificatePreviewModal" 
                                    data-image="<?= base_url($certificate->pathDokumenSigned) ?>" 
                                    data-download="<?= base_url($certificate->pathDokumenSigned) ?>"> 
                                Lihat Dokumen 
                            </button> 
                            <?php else: ?>
                            <span class="text-danger"> Dokumen Signed Belum Ada </span>
                            <?php endif ?>
                        </td>
                        <td>
                            <span> <?= $certificate->idExternalDokumen ?> </span>
                        </td>
                        <td>
                            <?php if($certificate->pathDokumenSignedByPrivy): ?>
                            <!-- Update the download link to trigger the modal -->
                            <a target="blank" href="/<?= $certificate->pathDokumenSignedByPrivy ?>" class="btn btn-primary btn-sm">Lihat Dokumen</a>
                            <?php else: ?>
                            <span class="text-danger"> Dokumen Signed Privy Belum Ada </span>
                            <?php endif ?>
                        </td>
                        <td>
                            <?php if(!$certificate->pathDokumenSigned) : ?>
                            <a title="Generate PDF" href="Ppg/generate_detail/<?= $certificate->dokumenPpgId ?>" class="btn btn-sm btn-primary"> Generate </a>
                            <a title="Edit Dokumen" href="Ppg/detail/<?= $certificate->dokumenPpgId ?>" class="btn btn-sm btn-secondary"> Edit </a>
                            <?php endif; ?>
                            <?php if(!$certificate->idExternalDokumen) : ?>
                            <a title="Generate PDF" href="Ppg/generate_privy/<?= $certificate->dokumenPpgId ?>" class="btn btn-sm btn-success"> Proses Privy </a>
                            <?php endif ?>
                            <?php if($certificate->idExternalDokumen): ?>
                            <a title="Generate PDF" href="Ppg/fetch_privy/<?= $certificate->dokumenPpgId ?>" class="btn btn-sm btn-primary"> Unduh PDF Privy </a>
                            <?php endif ?>
                            <a title="Hapus" href="/<?= $certificate->dokumenPpgId ?>" class="btn btn-sm btn-danger"> Hapus </a>
                        </td>
                        
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="addData" tabindex="-1" role="dialog" aria-labelledby="addDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDataLabel">Tambah Upload File</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="Ppg/upload_file_excel" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="document">Unggah File Excel</label>
                        <input type="file" class="form-control-file" id="document" name="document" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
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
                        <label for="nomorDokumen">Nomor SK</label>
                        <input type="text" class="form-control" id="nomorDokumen" name="nomorDokumen" placeholder="Masukkan Nomor SK Dokumen" required>
                    </div>
                    <div class="form-group">
                        <label for="nomorPpgMahasiswa">Nomor Dokumen PPG</label>
                        <input type="text" class="form-control" id="nomorPpgMahasiswa" name="nomorPpgMahasiswa" placeholder="Masukkan Nomor Dokumen PPG" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggalSertifikat">Tgl Dokumen Sertifikat</label>
                        <input type="date" class="form-control" id="tanggalSertifikat" name="tanggalSertifikat" placeholder="Masukkan Tgl Dokumen Sertifikat" required>
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
                        <label for="namaGelarGuru">Nama Gelar Guru (Bidang)</label>
                        <input type="text" class="form-control" id="namaGelarGuru" name="namaGelarGuru" placeholder="Ex: Indonesia, Bahasa Inggris" required>
                    </div>
                    <div class="form-group">
                        <label for="pathDokumen">Unggah Dokumen PPG</label>
                        <input type="file" class="form-control-file" id="pathDokumen" name="pathDokumen" required>
                    </div>
                    <div class="form-group">
                        <label for="pejabatanPenandatangan">Pejabat Penandatangan</label>
                        <input type="text" class="form-control" id="pejabatanPenandatangan" name="pejabatanPenandatangan" placeholder="Ex: Prof. Dr. Muchlas. M.T." required>
                    </div>
                    <div class="form-group">
                        <label for="jabatanPenandatangan">Jabatan Penandatangan</label>
                        <input type="text" class="form-control" id="jabatanPenandatangan" name="jabatanPenandatangan" placeholder="Rektor UAD" required>
                    </div>
                    <div class="form-group">
                        <label for="nomorJabatanPenandatangan">Jabatan Penandatangan</label>
                        <input type="text" class="form-control" id="nomorJabatanPenandatangan" name="nomorJabatanPenandatangan" placeholder="196202181987021001" required>
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
                    <!-- PDF Viewer container -->
                    <canvas id="certificate-pdf-viewer" style="width: 100%; height: auto;"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a id="downloadCertificate" href="#" class="btn btn-primary" download>Unduh Sertifikat</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="certificatePrivyModal" tabindex="-1" role="dialog" aria-labelledby="certificatePrivyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="certificatePrivyModalLabel">Lihat Sertifikat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <!-- PDF Viewer container -->
                    <canvas id="certificate-pdf-viewer" style="width: 100%; height: auto;"></canvas>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a id="downloadCertificate" href="#" class="btn btn-primary" download>Unduh Sertifikat</a>
            </div>
        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
    // JavaScript to dynamically set the certificate source and preview PDF
$('#certificatePreviewModal').on('show.bs.modal', function (e) {
    var button = $(e.relatedTarget); // Button that triggered the modal
    var pdfUrl = button.data('image'); // Extract PDF URL from data attribute
    var downloadUrl = button.data('download'); // Extract download URL from data attribute

    // Set the download link
    $('#downloadCertificate').attr('href', downloadUrl);

    // Get the canvas element and its context
    var canvas = $('#certificate-pdf-viewer')[0];
    var context = canvas.getContext('2d');

    // Clear any previous content in the canvas
    context.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas content
    canvas.width = canvas.width; // Reset the width
    canvas.height = canvas.height; // Reset the height

    // If the PDF URL exists
    if (pdfUrl) {
        // Attempt to load the PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            // Fetch the first page of the PDF
            pdf.getPage(1).then(function(page) {
                var viewport = page.getViewport({ scale: 1.5 });

                // Set canvas size to match the PDF page
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render the page into the canvas
                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            }).catch(function(error) {
                // If there's an error rendering the PDF page
                console.error('Error rendering PDF page: ', error);
                // Draw the error message on the canvas
                drawTextOnCanvas(context, canvas, 'Dokumen Tidak Ada');
            });
        }).catch(function(error) {
            // If there's an error loading the PDF document
            console.error('Error loading PDF: ', error);
            // Draw the error message on the canvas
            drawTextOnCanvas(context, canvas, 'Dokumen Tidak Ada');
        });
    } else {
        // Handle case when there's no valid PDF URL (file missing or not a PDF)
        drawTextOnCanvas(context, canvas, 'Dokumen Tidak Ada');
    }
});

// JavaScript to dynamically set the certificate source and preview PDF for Privy
$('#certificatePrivyModal').on('show.bs.modal', function (e) {
    var button = $(e.relatedTarget); // Button that triggered the modal
    var pdfUrl = button.data('image'); // Extract PDF URL from data attribute
    var downloadUrl = button.data('download'); // Extract download URL from data attribute

    // Set the download link
    $('#downloadCertificate').attr('href', downloadUrl);

    // Get the canvas element and its context
    var canvas = $('#certificate-pdf-viewer')[0];
    var context = canvas.getContext('2d');

    // Clear any previous content in the canvas
    context.clearRect(0, 0, canvas.width, canvas.height); // Clear the canvas content
    canvas.width = canvas.width; // Reset the width
    canvas.height = canvas.height; // Reset the height

    // If the PDF URL exists
    if (pdfUrl) {
        // Attempt to load the PDF
        pdfjsLib.getDocument(pdfUrl).promise.then(function(pdf) {
            // Fetch the first page of the PDF
            pdf.getPage(1).then(function(page) {
                var viewport = page.getViewport({ scale: 1.5 });

                // Set canvas size to match the PDF page
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render the page into the canvas
                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
            }).catch(function(error) {
                // If there's an error rendering the PDF page
                console.error('Error rendering PDF page: ', error);
                // Draw the error message on the canvas
                drawTextOnCanvas(context, canvas, 'Dokumen Tidak Ada');
            });
        }).catch(function(error) {
            // If there's an error loading the PDF document
            console.error('Error loading PDF: ', error);
            // Draw the error message on the canvas
            drawTextOnCanvas(context, canvas, 'Dokumen Tidak Ada');
        });
    } else {
        // Handle case when there's no valid PDF URL (file missing or not a PDF)
        drawTextOnCanvas(context, canvas, 'Dokumen Tidak Ada');
    }
});

// Function to draw the "Dokumen Tidak Ada" text on the canvas
function drawTextOnCanvas(context, canvas, text) {
    // Set text style
    context.font = '24px Arial';
    context.fillStyle = '#ff0000'; // Red color for the error message
    context.textAlign = 'center';
    context.textBaseline = 'middle';

    // Draw the text on the canvas
    context.clearRect(0, 0, canvas.width, canvas.height); // Clear any previous content
    context.fillText(text, canvas.width / 2, canvas.height / 2); // Center the text on the canvas
}

// Reset the canvas when the modal is closed
$('#certificatePreviewModal').on('hidden.bs.modal', function () {
    // Get the canvas element and its context
    var canvas = $('#certificate-pdf-viewer')[0];
    var context = canvas.getContext('2d');

    // Clear the canvas drawing context and reset dimensions
    context.clearRect(0, 0, canvas.width, canvas.height); // Clear the content
    canvas.width = canvas.width; // Reset width
    canvas.height = canvas.height; // Reset height
});

</script>
