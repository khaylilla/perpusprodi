@extends('admin.layout')

@section('page-title', 'Scan Absen')

@section('content')
<style>
  /* ======= (style tetap sama seperti sebelumnya) ======= */
  .info-boxes { display: flex; flex-wrap: wrap; gap: 25px; margin-bottom: 25px; }
  .info-box { background: linear-gradient(135deg, #f7931e, #ffb347); color: white; border-radius: 20px; width: 250px; padding: 20px; box-shadow: 0 6px 18px rgba(0,0,0,0.15); display: flex; align-items: center; justify-content: space-between; text-decoration: none; transition: transform 0.25s ease; }
  .info-box:hover { transform: translateY(-5px); box-shadow: 0 10px 22px rgba(0,0,0,0.2); }
  .info-box i { font-size: 36px; opacity: 0.85; }
  .info-box-content h5 { margin: 0; font-weight: 700; font-size: 16px; }
  .info-box-content p { font-size: 13px; margin-top: 5px; }

  .scan-container { display: flex; flex-direction: column; gap: 30px; background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 6px 18px rgba(0,0,0,0.12); max-width: 600px; margin: 0 auto; }

  .scan-box { border: 3px dashed #f7931e; border-radius: 16px; padding: 30px; text-align: center; transition: 0.3s; position: relative; }
  .scan-box:hover { background: #fff7ef; }
  .scan-box h5 { font-weight: 700; margin-bottom: 15px; }

  .scan-input { display: flex; justify-content: center; align-items: center; gap: 12px; flex-wrap: wrap; margin-top: 15px; }
  .toggle-btn { background: linear-gradient(135deg, #f7931e, #ffb347); border: none; color: white; padding: 10px 18px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; }
  .toggle-btn:hover { transform: scale(1.05); }

  video { width: 100%; max-width: 400px; border-radius: 12px; border: 3px solid #f7931e; margin-top: 10px; }
  .video-wrapper { position: relative; display: inline-block; }

  .scanner-frame { position: absolute; top: 50%; left: 50%; width: 200px; height: 200px; transform: translate(-50%, -50%); border-radius: 16px; pointer-events: none; overflow: hidden; border: 4px solid #f7931e; box-shadow: 0 0 20px rgba(247,147,30,0.4), 0 0 30px rgba(247,147,30,0.3); animation: pulse-frame 2.5s infinite ease-in-out; }

  @keyframes pulse-frame {
    0%,100% { box-shadow: 0 0 20px rgba(247,147,30,0.4), 0 0 30px rgba(247,147,30,0.3); }
    50% { box-shadow: 0 0 35px rgba(247,147,30,0.6), 0 0 45px rgba(247,147,30,0.5); }
  }

  .laser-line { position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: #f7931e; box-shadow: 0 0 15px #f7931e, 0 0 25px rgba(247,147,30,0.5); animation: laser-move 2s linear infinite, laser-glow 1.5s ease-in-out infinite alternate; }

  @keyframes laser-move { 0% { top: 0; } 50% { top: calc(100% - 4px); } 100% { top: 0; } }
  @keyframes laser-glow { 0% { opacity: 0.7; } 50% { opacity: 1; } 100% { opacity: 0.7; } }

  .info-scan { margin-top: 12px; font-size: 15px; font-weight: 600; color: #333; }
  .info-scan span { font-weight: 700; color: #f7931e; }

  .submit-btn { text-align: center; margin-top: 20px; }
  .submit-btn button { background: linear-gradient(135deg, #f7931e, #ffb347); border: none; color: white; font-weight: 700; padding: 14px 40px; border-radius: 10px; font-size: 16px; box-shadow: 0 6px 14px rgba(0,0,0,0.12); transition: all 0.3s; }
  .submit-btn button:hover { transform: translateY(-3px); box-shadow: 0 10px 18px rgba(0,0,0,0.2); }
</style>

<div class="container-fluid">
  <div class="info-boxes">
    <a href="{{ route('admin.absen.scan') }}" class="info-box">
      <div class="info-box-content">
        <h5>Scan Absen</h5>
        <p>Scan kartu anggota</p>
      </div>
      <i class="bi bi-qr-code-scan"></i>
    </a>

    <a href="{{ route('admin.dataabsen') }}" class="info-box">
      <div class="info-box-content">
        <h5>Data Absen</h5>
        <p>Lihat riwayat absensi</p>
      </div>
      <i class="bi bi-table"></i>
    </a>

    <a href="{{ route('admin.kartu.generate') }}" class="info-box">
      <div class="info-box-content">
        <h5>Generate Kartu</h5>
        <p>Buat kartu anggota</p>
      </div>
      <i class="bi bi-credit-card-2-back-fill"></i>
    </a>
  </div>

  <div class="scan-container">
    <div class="scan-box">
      <h5>Scan QR/NPM Anggota</h5>
      <button class="toggle-btn" onclick="toggleScan()">Gunakan Kamera</button>
      <div class="scan-input">
        <input type="file" accept="image/*" onchange="handleFile(event)">
        <input type="text" id="barcodeAnggota" placeholder="Atau input manual NPM...">
      </div>
      <div class="info-scan">Nama: <span id="namaAnggota">-</span></div>
      <div class="video-wrapper">
        <video id="videoAnggota" autoplay playsinline hidden></video>
        <div class="scanner-frame" id="frameAnggota" hidden>
          <div class="laser-line"></div>
        </div>
      </div>
      <canvas id="canvasAnggota" hidden></canvas>

      <div class="submit-btn">
        <button onclick="simpanAbsen()">Simpan Absen</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
let currentStream;
let scanInterval;

function toggleScan(){
  const video = document.getElementById("videoAnggota");
  const frame = document.getElementById("frameAnggota");
  const button = document.querySelector(".scan-box .toggle-btn");

  if(!video.hidden){
    stopCamera();
    video.hidden = true;
    frame.hidden = true;
    button.textContent = "Gunakan Kamera";
    return;
  }

  navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
    .then(stream => {
      video.srcObject = stream;
      currentStream = stream;
      video.hidden = false;
      frame.hidden = false;
      button.textContent = "Batalkan Kamera";
      startScanning();
    })
    .catch(()=>{ alert("Kamera tidak dapat diakses"); });
}

function startScanning(){
  const video = document.getElementById("videoAnggota");
  const canvas = document.getElementById("canvasAnggota");
  const ctx = canvas.getContext("2d");
  const button = document.querySelector(".scan-box .toggle-btn");

  scanInterval = setInterval(() => {
    if(video.readyState === video.HAVE_ENOUGH_DATA){
      canvas.height = video.videoHeight;
      canvas.width = video.videoWidth;
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      const code = jsQR(ctx.getImageData(0,0,canvas.width,canvas.height).data, canvas.width, canvas.height);
      if(code){
        document.getElementById("barcodeAnggota").value = code.data;
        updateInfo(code.data);

        // otomatis tutup kamera setelah scan berhasil
        stopCamera();
        video.hidden = true;
        document.getElementById("frameAnggota").hidden = true;
        button.textContent = "Gunakan Kamera";
      }
    }
  }, 300);
}

function stopCamera(){
  if(currentStream){
    currentStream.getTracks().forEach(track => track.stop());
    currentStream = null;
  }
  if(scanInterval){
    clearInterval(scanInterval);
    scanInterval = null;
  }
}

function handleFile(event){
  const file = event.target.files[0]; 
  if(!file) return;
  const reader = new FileReader();
  reader.onload = function(e){
    const img = new Image();
    img.onload = function(){
      const canvas = document.createElement("canvas");
      const ctx = canvas.getContext("2d");
      canvas.width = img.width;
      canvas.height = img.height;
      ctx.drawImage(img,0,0);
      const code = jsQR(ctx.getImageData(0,0,img.width,img.height).data, img.width, img.height);
      if(code){
        document.getElementById("barcodeAnggota").value = code.data;
        updateInfo(code.data);
      } else {
        alert("Barcode tidak ditemukan.");
      }
    };
    img.src = e.target.result;
  };
  reader.readAsDataURL(file);
}

function updateInfo(npm){
  fetch(`/admin/absen/get-user/${npm}`)
    .then(res=>res.json())
    .then(data=>{
      if(data.nama){
        document.getElementById("namaAnggota").textContent = data.nama;

        // cek masa aktif
        const created = new Date(data.created_at);
        const expired = new Date(created);
        expired.setFullYear(expired.getFullYear()+2);
        if(new Date() > expired){
          document.getElementById("namaAnggota").textContent += " (Kartu Tidak Aktif)";
        }
      } else {
        document.getElementById("namaAnggota").textContent = "Tidak ditemukan";
      }
    });
}

function simpanAbsen() {
  const npm = document.getElementById("barcodeAnggota").value.trim();
  const nama = document.getElementById("namaAnggota").textContent.trim();
  
  if(!npm){ 
    Swal.fire({icon:'warning', title:'Oops!', text:'Silakan scan atau masukkan NPM anggota!'}); 
    return; 
  }

  // cek masa aktif
  fetch(`/admin/absen/get-user/${npm}`)
    .then(res=>res.json())
    .then(data=>{
      if(!data.nama){
        Swal.fire({icon:'error', title:'Gagal!', text:'Anggota tidak ditemukan.', confirmButtonColor:'#f7931e'});
        return;
      }

      const created = new Date(data.created_at);
      const expired = new Date(created);
      expired.setFullYear(expired.getFullYear()+2);
      if(new Date() > expired){
        Swal.fire({icon:'error', title:'Kartu Tidak Aktif', text:'Kartu anggota sudah habis masa aktif. Generate ulang di admin.', confirmButtonColor:'#f7931e'});
        return;
      }

      // Ambil tanggal dari device
      const tanggal = new Date().toISOString().split('T')[0]; // format YYYY-MM-DD

      fetch("{{ route('admin.absen.scan.store') }}", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ npm, nama: data.nama, tanggal })
      })
      .then(res => res.json().then(data => ({status: res.status, body: data})))
      .then(({status, body}) => {
        if(status===200){
          Swal.fire({
            icon:'success', 
            title:'Berhasil!', 
            text:body.message, 
            confirmButtonColor:'#f7931e'
          }).then(() => {
            window.location.href = "{{ route('admin.dataabsen') }}";
          });
        } else {
          Swal.fire({icon:'error', title:'Gagal!', text:body.message, confirmButtonColor:'#f7931e'});
        }
      })
      .catch(() => { 
        Swal.fire({icon:'error', title:'Gagal', text:'Gagal mengirim data ke server.', confirmButtonColor:'#f7931e'}); 
      });

    });
}
</script>
@endsection
