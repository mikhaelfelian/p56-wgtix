<?= $this->extend(theme_path('main')) ?>

<?= $this->section('css') ?>
<!-- Bootstrap Switch CSS -->
<link rel="stylesheet"
    href="<?= base_url('public/assets/theme/admin-lte-3/plugins/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css') ?>">
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card rounded-0">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title"><?= $title ?></h3>
                    </div>
                    <div class="col-md-6">
                        <ol class="breadcrumb float-sm-right mb-0 bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('admin/events') ?>">Events</a></li>
                            <li class="breadcrumb-item active">Harga Event</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Event Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Informasi Event</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td width="120"><strong>Nama Event:</strong></td>
                                <td><?= esc($event->event) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td><?= date('d-m-Y', strtotime($event->tgl_masuk)) ?> -
                                    <?= date('d-m-Y', strtotime($event->tgl_keluar)) ?></td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi:</strong></td>
                                <td><?= esc($event->lokasi) ?: '-' ?></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h5 id="formTitle">Tambah Harga Baru</h5>
                        <?= form_open('admin/events/store-price', ['class' => 'form-horizontal', 'id' => 'priceForm']) ?>
                        <input type="hidden" name="id_event" value="<?= $event->id ?>">
                        <input type="hidden" name="id" id="price_id">

                        <div class="form-group">
                            <label for="harga">Harga <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp</span>
                                </div>
                                <input type="text" name="harga" id="harga" class="form-control rounded-0"
                                    placeholder="Masukkan harga (contoh: 100000)" required>
                            </div>
                            <?php if (session('errors.harga')): ?>
                                <small class="text-danger"><?= session('errors.harga') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-control rounded-0"
                                placeholder="Keterangan (opsional)">
                        </div>

                        <div class="form-group">
                            <label for="statusSwitch">Status <span class="text-danger">*</span></label>
                            <div>
                                <input type="checkbox" name="status" id="statusSwitch" data-bootstrap-switch
                                    data-size="mini" data-on-color="info" data-off-color="danger" data-on-text="Aktif"
                                    data-off-text="Tidak Aktif" checked>
                            </div>
                            <?php if (session('errors.status')): ?>
                                <small class="text-danger"><?= session('errors.status') ?></small>
                            <?php endif ?>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary rounded-0" id="submitBtn">
                                <i class="fas fa-plus"></i> Simpan
                            </button>
                            <button type="button" class="btn btn-secondary rounded-0" id="cancelBtn"
                                style="display:none;" onclick="resetForm()">
                                <i class="fas fa-times"></i> Batal
                            </button>
                        </div>
                        <?= form_close() ?>
                    </div>
                </div>

                <!-- Pricing Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>Keterangan</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($prices)): ?>
                                <?php foreach ($prices as $key => $price): ?>
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td>
                                            <?= esc($price->keterangan) ?: '-' ?>
                                            <br>
                                            <small class="text-muted">
                                                <?= tgl_indo8($price->created_at) ?>
                                            </small>
                                        </td>
                                        <td>
                                            <strong><?= format_angka($price->harga) ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= ($price->status == '1') ? 'success' : 'danger' ?>">
                                                <?= ($price->status == '1') ? 'Aktif' : 'Tidak Aktif' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-warning btn-sm rounded-0" title="Edit"
                                                    onclick="editPrice(<?= $price->id ?>, <?= $price->harga ?>, '<?= esc($price->keterangan) ?>', <?= $price->status ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a href="<?= base_url("admin/events/toggle-price-status/$price->id") ?>"
                                                    class="btn btn-<?= ($price->status == '1') ? 'secondary' : 'success' ?> btn-sm rounded-0"
                                                    title="<?= ($price->status == '1') ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                                    onclick="return confirm('Apakah anda yakin ingin mengubah status harga ini?')">
                                                    <i class="fas fa-<?= ($price->status == '1') ? 'ban' : 'check' ?>"></i>
                                                </a>
                                                <a href="<?= base_url("admin/events/delete-price/$price->id") ?>"
                                                    class="btn btn-danger btn-sm rounded-0" title="Hapus"
                                                    onclick="return confirm('Apakah anda yakin ingin menghapus harga ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada harga yang ditambahkan</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>

                <!-- Back Button -->
                <div class="mt-3">
                    <a href="<?= base_url('admin/events') ?>" class="btn btn-primary rounded-0">
                        &laquo; Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<!-- Bootstrap Switch JS -->
<script
    src="<?= base_url('public/assets/theme/admin-lte-3/plugins/bootstrap-switch/js/bootstrap-switch.min.js') ?>"></script>

<script>
    $(document).ready(function () {
        // Initialize Bootstrap Switch
        $('[data-bootstrap-switch]').bootstrapSwitch({
            size: 'mini',
            onColor: 'info',
            offColor: 'danger',
            onText: 'Aktif',
            offText: 'Tidak Aktif'
        });

        // Initialize AutoNumeric for price inputs with Indonesian Rupiah formatting
        $('#harga').autoNumeric('init', {
            aSep: '.',           // Thousand separator (dot for Indonesian format)
            aDec: ','           // Decimal separator (comma for Indonesian format)
        });

        // Handle form submission
        $('#priceForm').on('submit', function (e) {
            // Get the numeric value from AutoNumeric before submission
            var numericValue = $('#harga').autoNumeric('get');

            // Create hidden input with numeric value
            if (!$(this).find('#harga_numeric').length) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'harga_numeric',
                    value: numericValue,
                    id: 'harga_numeric'
                }).appendTo(this);
            } else {
                $(this).find('#harga_numeric').val(numericValue);
            }

            // Handle switch status - ensure enum values 0 or 1
            var statusValue = $('#statusSwitch').bootstrapSwitch('state') ? '1' : '0';

            // Remove any existing status hidden input
            $(this).find('#statusHidden').remove();

            // Add hidden input with proper enum value
            $('<input>').attr({
                type: 'hidden',
                name: 'status',
                value: statusValue,
                id: 'statusHidden'
            }).appendTo(this);
        });
    });

    function editPrice(priceId, harga, keterangan, status) {
        // Set form to edit mode
        $('#price_id').val(priceId);
        $('#harga').autoNumeric('set', harga);
        $('#keterangan').val(keterangan);
        $('#statusSwitch').bootstrapSwitch('state', status == 1);

        // Update UI
        $('#formTitle').text('Edit Harga Event');
        $('#submitBtn').html('<i class="fas fa-save"></i> Update');
        $('#cancelBtn').show();

        // Scroll to form
        $('html, body').animate({
            scrollTop: $('#priceForm').offset().top - 100
        }, 500);
    }

    function resetForm() {
        // Reset form to insert mode
        $('#priceForm')[0].reset();
        $('#price_id').val('');
        $('#harga').autoNumeric('set', '');
        $('#statusSwitch').bootstrapSwitch('state', true);

        // Update UI
        $('#formTitle').text('Tambah Harga Baru');
        $('#submitBtn').html('<i class="fas fa-plus"></i> Simpan');
        $('#cancelBtn').hide();
    }
</script>
<?= $this->endSection() ?>