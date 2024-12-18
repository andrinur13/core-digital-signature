<?php defined('BASEPATH') OR exit('No direct script access alloweds');?>
<style>
    #signature-container {
      display: none;
      position: relative;
      width: 100%;
      max-width: 600px;
      margin: 20px auto;
      border: 2px solid #ccc;
      background-color: #fff;
    }

    #signature-pad {
      border: 2px solid #ccc;
      background-color: #fff;
    }

    #signature-controls {
      text-align: center;
      /* padding: 10px; */
      background-color: #f4f4f4;
    }

    #signature-controls button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 15px;
      margin: 0 5px;
      cursor: pointer;
      border-radius: 5px;
      font-size: 14px;
    }

    #signature-controls button:hover {
      background-color: #0056b3;
    }

    #qr-options {
      display: none;
      text-align: center;
      margin-top: 10px;
    }

    #qr-options button {
      background-color: #007bff;
      color: #fff;
      border: none;
      padding: 10px 15px;
      margin: 0 5px;
      cursor: pointer;
      border-radius: 5px;
      font-size: 14px;
    }

    #qr-options button:hover {
      background-color: #0056b3;
    }

    #pdf-container {
  position: relative;
  width: 80%;
  max-width: 1000px;
}

#pdf-controls {
  display: flex;
  align-items: center;
  background: #333;
  padding: 10px;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 10;
}

#pdf-controls .button {
  background-color: #007bff;
  color: #fff;
  border: none;
  padding: 8px 12px;
  margin: 0 5px;
  cursor: pointer;
  border-radius: 5px;
  font-size: 14px;
}

#pdf-controls .button:hover {
  background-color: #0056b3;
}

#pdf-controls .left-buttons, #pdf-controls .right-buttons {
  display: flex;
  align-items: center;
}

#pdf-controls .page-controls {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-grow: 1;
}

#page-number {
  display: flex;
  align-items: center;
  color: #fff;
}

#page-number input {
  width: 40px;
  text-align: center;
  margin: 0 10px;
  border: 1px solid #fff;
  border-radius: 3px;
  padding: 5px;
  font-size: 14px;
  background: #fff;
  color: #333;
}

#totalPages {
  margin-left: 10px;
  font-size: 14px;
  font-weight: bold;
}

#pdf-render {
  border: 1px solid #ccc;
  position: relative;
  width: 100%;
  height: auto; /* Pastikan canvas menyesuaikan ukuran */
}

#qr-box {
  position: absolute;
  border: 2px dashed red;
  width: 150px;
  height: 150px;
  top: 100px;
  left: 100px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: move;
  z-index: 5;
}

#qr-box img {
  width: 100px;
  height: 100px;
}

.resize-handle {
  position: absolute;
  width: 10px;
  height: 10px;
  background-color: blue;
  bottom: 0;
  right: 0;
  cursor: se-resize;
}

.info {
  margin-top: 10px;
  font-size: 14px;
  color: #333;
}

.coordinates {
  font-weight: bold;
}
  </style>
<div class="row">
    <div class="col-lg-4">
    <div class="card bordered">
              <h4 class="card-title">Notifications</h4>


              <div class="media">
                <div class="media-body">
                  <p><strong>Notifications</strong></p>
                  <p>Receive notifications from other users</p>
                </div>

                <label class="switch">
                  <input type="checkbox" checked="">
                  <span class="switch-indicator"></span>
                </label>
              </div>


              <div class="media">
                <div class="media-body">
                  <p><strong>Messages</strong></p>
                  <p>Allow other users to send you messages</p>
                </div>

                <label class="switch">
                  <input type="checkbox">
                  <span class="switch-indicator"></span>
                </label>
              </div>


              <div class="media">
                <div class="media-body">
                  <p><strong>Message email</strong></p>
                  <p>Email me when someone message me here</p>
                </div>

                <label class="switch">
                  <input type="checkbox" checked="">
                  <span class="switch-indicator"></span>
                </label>
              </div>


              <div class="media">
                <div class="media-body">
                  <p><strong>Applicant email</strong></p>
                  <p>Email me when an applicant applied for a job</p>
                </div>

                <label class="switch">
                  <input type="checkbox">
                  <span class="switch-indicator"></span>
                </label>
              </div>


              <div class="media">
                <div class="media-body">
                  <p><strong>Job expiration</strong></p>
                  <p>Email me when a position expired</p>
                </div>

                <label class="switch">
                  <input type="checkbox" checked="">
                  <span class="switch-indicator"></span>
                </label>
              </div>

            </div>
    </div>
    <div class="col-lg-8">
        <div class="card card-bordered">
            <div class="card-header">
                <h4 class="card-title"><strong><?= $template['title']; ?></strong></h4>
                <div class="card-header-actions" id="actionButton">
                    <div class="btn-toolbar">
                    
                    </div>
                </div>
                
            </div>
            
            <div class="card-body">
            <div id="pdf-container">
    <div id="pdf-controls">
      <div class="left-buttons">
        <button class="button" id="firstPage">First</button>
        <button class="button" id="prevPage">Prev</button>
      </div>
      
      <div class="page-controls">
        <button class="button" id="lastPage">Last</button>
        <div id="page-number">
          <input type="number" id="pageInput" min="1" value="1" />
          dari
          <span id="totalPages">0</span>
        </div>
        <button class="button" id="nextPage">Next</button>
      </div>
      
      <div class="right-buttons">
        <button class="button" id="toggleSignature">Signature Pad</button>
        <button class="button" id="toggleQR">QR Code</button>
      </div>
    </div>
    
    <canvas id="pdf-render"></canvas>
    
    <!-- Signature Pad -->
    <div id="signature-container">
      <canvas id="signature-pad"></canvas>
      <div id="signature-controls">
        <button id="clearSignature">Clear</button>
        <button id="saveSignature">Save</button>
        <button id="closeSignature">Close</button>
      </div>
    </div>

    <!-- QR Code Options -->
    <div id="qr-options">
      <button id="addQRCode">Add QR Code</button>
    </div>

    <!-- Kotak QR Code yang dapat dipindahkan -->
    <div id="qr-box">
      <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/QR_Code_example.png" alt="QR Code">
      <div class="resize-handle"></div> <!-- Handle untuk resize -->
    </div>

        <div class="info">
            Koordinat terakhir: <span class="coordinates">X: 0, Y: 0</span>
        </div>



            </div>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
<script>
     document.addEventListener('DOMContentLoaded', function() {
        const canvasSignature = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvasSignature);
        // Clear signature pad for testing
        // function resizeCanvas() {
        //     canvasSignature.width = canvasSignature.parentElement.offsetWidth;
        //     canvasSignature.height = canvasSignature.parentElement.offsetHeight;
            signaturePad.clear(); // Bersihkan signature pad setelah resize
        // }
         // Atur ukuran canvas saat jendela diubah ukurannya
        // window.addEventListener('resize', resizeCanvas);
        // resizeCanvas();

        // Debugging: Check canvas size
        console.log(`Canvas width: ${canvasSignature.width}, height: ${canvasSignature.height}`);

        // Toggle antara Signature Pad dan QR Code
        document.getElementById('toggleSignature').addEventListener('click', function() {
            document.getElementById('signature-container').style.display = 'block';
            document.getElementById('qr-options').style.display = 'none';
        });

        document.getElementById('toggleQR').addEventListener('click', function() {
            document.getElementById('signature-container').style.display = 'none';
            document.getElementById('qr-options').style.display = 'block';
        });

        document.getElementById('clearSignature').addEventListener('click', function() {
            signaturePad.clear();
        });

        document.getElementById('closeSignature').addEventListener('click', function() {
            signaturePad.clear();
            document.getElementById('signature-container').style.display = 'none';
        });
        document.getElementById('saveSignature').addEventListener('click', function() {
            if (signaturePad.isEmpty()) {
            alert('Please provide a signature first.');
            } else {
            const dataURL = signaturePad.toDataURL();
            // Do something with the dataURL, e.g., save it to the server
            console.log(dataURL);
            }
        });

        document.getElementById('addQRCode').addEventListener('click', function() {
            alert('Add QR Code functionality');
            // Implement QR Code addition logic here
        });

        // Debugging: Check if drawing is working
        signaturePad.onBegin = () => console.log('Drawing started.');
        signaturePad.onEnd = () => console.log('Drawing ended.');

     });
    
     const url = '<?= base_url($path_dokumen.$detail->dokFile); ?>';  // Ganti dengan path file PDF Anda

    let pdfDoc = null,
        pageNum = 1,
        canvas = document.getElementById('pdf-render'),
        ctx = canvas.getContext('2d'),
        pageRendering = false;

    // Muat PDF
    pdfjsLib.getDocument(url).promise.then((pdfDoc_) => {
      pdfDoc = pdfDoc_;
      document.getElementById('totalPages').textContent = pdfDoc.numPages;
      renderPage(pageNum);
    });

    // Fungsi untuk merender halaman PDF
    function renderPage(num) {
      pageRendering = true;
      pdfDoc.getPage(num).then((page) => {
        const viewport = page.getViewport({ scale: 1 });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderContext = {
          canvasContext: ctx,
          viewport: viewport
        };

        page.render(renderContext).promise.then(() => {
          pageRendering = false;
        });
      });
    }

    // Fungsi untuk mengirim koordinat dan menghasilkan PDF dengan QR code
    function sendCoordinatesToServer(pdfX, pdfY) {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "save_pdf_with_qr.php", true); // Ganti dengan path PHP yang benar
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          console.log('PDF berhasil disimpan:', xhr.responseText);
          alert(xhr.responseText);
        }
      };
      xhr.send("x=" + pdfX + "&y=" + pdfY + "&page=" + pageNum);

        alert('PDF berhasil disimpan:', xhr.responseText);
    }

    // Drag & Drop untuk kotak QR
    const qrBox = document.getElementById('qr-box');
    let isDragging = false;
    let offsetX, offsetY;

    qrBox.addEventListener('mousedown', (e) => {
      if (e.target.classList.contains('resize-handle')) return; // Jangan mulai drag jika sedang resize
      isDragging = true;

      const rect = qrBox.getBoundingClientRect();
      
      // Hitung offset posisi klik relatif terhadap pusat kotak
      offsetX = e.clientX - rect.left - rect.width / 2;
      offsetY = e.clientY - rect.top - rect.height / 2;

      qrBox.style.cursor = 'grabbing';
    });

    document.addEventListener('mousemove', (e) => {
      if (isDragging) {
        const canvasRect = canvas.getBoundingClientRect();
        const xPos = e.clientX - canvasRect.left - qrBox.offsetWidth / 2;
        const yPos = e.clientY - canvasRect.top - qrBox.offsetHeight / 2;

        qrBox.style.left = `${xPos}px`;
        qrBox.style.top = `${yPos}px`;
      }
    });

    document.addEventListener('mouseup', () => {
      if (isDragging) {
        isDragging = false;
        qrBox.style.cursor = 'move';
        const qrBoxRect = qrBox.getBoundingClientRect();
        const canvasRect = canvas.getBoundingClientRect();
        const qrBoxX = qrBoxRect.left - canvasRect.left;
        const qrBoxY = qrBoxRect.top - canvasRect.top;

        // Kirim koordinat setelah kotak dipindahkan
        sendCoordinatesToServer(qrBoxX, qrBoxY);
      }
    });

    // Resize kotak QR
    const resizeHandle = document.querySelector('.resize-handle');
    let isResizing = false;

    resizeHandle.addEventListener('mousedown', (e) => {
      isResizing = true;
      e.stopPropagation();
    });

    document.addEventListener('mousemove', (e) => {
      if (isResizing) {
        const qrRect = qrBox.getBoundingClientRect();
        const newWidth = e.pageX - qrRect.left;
        const newHeight = e.pageY - qrRect.top;
        qrBox.style.width = `${newWidth}px`;
        qrBox.style.height = `${newHeight}px`;
      }
    });

    document.addEventListener('mouseup', () => {
      if (isResizing) {
        isResizing = false;
        const qrBoxRect = qrBox.getBoundingClientRect();
        const canvasRect = canvas.getBoundingClientRect();
        const qrBoxX = qrBoxRect.left - canvasRect.left;
        const qrBoxY = qrBoxRect.top - canvasRect.top;

        // Kirim koordinat setelah ukuran kotak diubah
        sendCoordinatesToServer(qrBoxX, qrBoxY);
      }
    });

    // Tombol navigasi
    document.getElementById('nextPage').addEventListener('click', () => {
      if (pageNum >= pdfDoc.numPages) return;
      pageNum++;
      renderPage(pageNum);
      document.getElementById('pageInput').value = pageNum;
    });

    document.getElementById('prevPage').addEventListener('click', () => {
      if (pageNum <= 1) return;
      pageNum--;
      renderPage(pageNum);
      document.getElementById('pageInput').value = pageNum;
    });

    document.getElementById('firstPage').addEventListener('click', () => {
      if (pageNum > 1) {
        pageNum = 1;
        renderPage(pageNum);
        document.getElementById('pageInput').value = pageNum;
      }
    });

    document.getElementById('lastPage').addEventListener('click', () => {
      if (pageNum < pdfDoc.numPages) {
        pageNum = pdfDoc.numPages;
        renderPage(pageNum);
        document.getElementById('pageInput').value = pageNum;
      }
    });

    document.getElementById('pageInput').addEventListener('change', () => {
      const inputPage = parseInt(document.getElementById('pageInput').value, 10);
      if (inputPage >= 1 && inputPage <= pdfDoc.numPages) {
        pageNum = inputPage;
        renderPage(pageNum);
      }
    });
  </script>

