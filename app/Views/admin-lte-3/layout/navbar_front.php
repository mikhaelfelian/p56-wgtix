<nav class="main-header navbar navbar-expand-md navbar-dark bg-primary">
  <div class="container">
    <a href="<?= base_url() ?>" class="navbar-brand">
      <img src="<?= base_url($Pengaturan->logo ?? 'public/assets/theme/admin-lte-3/dist/img/AdminLTELogo.png') ?>" alt="<?= esc($Pengaturan->judul_app ?? 'Event Registration') ?> Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-bold"><?= esc($Pengaturan->judul_app ?? 'Event Registration') ?></span>
    </a>

    <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse order-3" id="navbarCollapse">
      <!-- Left navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a href="<?= base_url() ?>" class="nav-link">
            <i class="fas fa-home mr-1"></i>Beranda
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('frontend') ?>" class="nav-link">
            <i class="fas fa-user-plus mr-1"></i>Daftar Event
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" data-toggle="modal" data-target="#infoModal">
            <i class="fas fa-info-circle mr-1"></i>Informasi
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link" data-toggle="modal" data-target="#contactModal">
            <i class="fas fa-phone mr-1"></i>Kontak
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('admin') ?>" class="nav-link">
            <i class="fas fa-cog mr-1"></i>Admin
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!-- Information Modal -->
<div class="modal fade" id="infoModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">
          <i class="fas fa-info-circle mr-2"></i>
          Informasi Event
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <h6><i class="fas fa-calendar-alt mr-2"></i>Jadwal Event</h6>
            <p>Event akan dilaksanakan pada tanggal yang telah ditentukan. Silakan pilih kategori event yang sesuai dengan kemampuan Anda.</p>
            
            <h6><i class="fas fa-map-marker-alt mr-2"></i>Lokasi</h6>
            <p>Venue Event Center<br>Jl. Event Center No. 123, Jakarta</p>
          </div>
          <div class="col-md-6">
            <h6><i class="fas fa-running mr-2"></i>Kategori Event</h6>
            <ul class="list-unstyled">
              <li><strong>5K Umum:</strong> Untuk peserta umum dengan jarak 5 kilometer</li>
              <li><strong>10K Pelajar:</strong> Untuk pelajar dengan jarak 10 kilometer</li>
              <li><strong>21K Semi Marathon:</strong> Untuk peserta berpengalaman</li>
            </ul>
          </div>
        </div>
        
        <hr>
        <h6><i class="fas fa-clipboard-list mr-2"></i>Persyaratan</h6>
        <ul>
          <li>Usia minimal 17 tahun</li>
          <li>Membawa identitas asli</li>
          <li>Mengikuti briefing sebelum event</li>
          <li>Menggunakan pakaian dan sepatu yang nyaman</li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">
          <i class="fas fa-phone mr-2"></i>
          Informasi Kontak
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-4">
          <i class="fas fa-headset text-success" style="font-size: 3rem;"></i>
        </div>
        
        <h6><i class="fas fa-users mr-2"></i>Panitia Event</h6>
        <div class="row">
          <div class="col-md-6">
            <p><i class="fas fa-phone mr-2"></i>+62 812-3456-7890</p>
            <p><i class="fas fa-envelope mr-2"></i>info@event.com</p>
          </div>
          <div class="col-md-6">
            <p><i class="fas fa-map-marker-alt mr-2"></i>Jl. Event Center No. 123, Jakarta</p>
            <p><i class="fas fa-clock mr-2"></i>Senin - Jumat: 08:00 - 17:00 WIB</p>
          </div>
        </div>
        
        <hr>
        <h6><i class="fas fa-info-circle mr-2"></i>Jam Operasional</h6>
        <p>Senin - Jumat: 08:00 - 17:00 WIB</p>
        <p>Sabtu: 08:00 - 12:00 WIB</p>
        <p>Minggu: Tutup</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>