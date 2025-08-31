<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-01-29
 * Github : github.com/mikhaelfelian
 * description : Event sidebar template for Digital Agency theme
 * This file represents the event sidebar template for the Digital Agency theme.
 */
?>

<!--Widget-->
<div class="widget widget-product-search row">
    <h4 class="widget-title">Pencarian</h4>
    <form action="#" class="input-group product-search">
        <input type="text" class="form-control" placeholder="Ketik kata kunci Anda">
        <span class="input-group-addon"><button type="submit"><i
                    class="fa fa-search"></i></button></span>
    </form>
</div>

<!--Widget-->
<div class="row widget widget-price-filter">
    <div class="price-filter-inner row m0">
        <h4 class="widget-title">Filter Harga</h4>
        <form action="#" class="price-range">
            <div class="slider-range"></div>
            <div class="row price-bar">
                Harga: <input type="text" class="range-amount" readonly><br>
            </div>
            <input type="submit" value="Filter" class="btn btn-default btn-sm">
        </form>
    </div>
</div>

<!--Widget-->
<div class="row widget widget-categories">
    <h4 class="widget-title">Kategori</h4>
    <ul class="nav nav-widget">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $kategori): ?>
                <li>
                    <i class="fa fa-angle-double-right"></i>
                    <a
                        href="<?= base_url('events/' . $kategori->id . '/' .generateSlug($kategori->kategori)) ?>">
                        <?= esc($kategori->kategori) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li><em>Tidak ada kategori</em></li>
        <?php endif; ?>
    </ul>
</div>

<script>
// Tunggu sampai jQuery tersedia
function initSidebarFunctionality() {
    // Cek apakah jQuery tersedia
    if (typeof $ === 'undefined' || typeof jQuery === 'undefined') {
        console.log('jQuery belum dimuat, mencoba lagi...');
        setTimeout(initSidebarFunctionality, 100);
        return;
    }

    $(document).ready(function() {
        // Fungsi pencarian
        $('.product-search').on('submit', function(e) {
            e.preventDefault();
            var keyword = $(this).find('input[type="text"]').val();
            if (keyword && keyword.trim()) {
                window.location.href = '<?= base_url('events') ?>?keyword=' + encodeURIComponent(keyword.trim());
            } else {
                // Jika kosong, ke halaman events tanpa kata kunci
                window.location.href = '<?= base_url('events') ?>';
            }
        });

        // Fungsi klik tombol cari
        $('.product-search button').on('click', function(e) {
            e.preventDefault();
            $(this).closest('form').submit();
        });

        // Fungsi filter harga - biarkan theme handle slider, kita hanya custom tampilan
        var minPrice = 0;
        var maxPrice = 1000000;
        
        // Tunggu slider theme terinisialisasi, lalu custom tampilannya
        setTimeout(function() {
            if ($('.slider-range').length > 0 && $('.slider-range').hasClass('ui-slider')) {
                // Slider theme sudah siap, custom tampilan
                $('.range-amount').val('Rp 0 - Rp 1.000.000');
                
                // Hapus semua event slide dari theme
                $('.slider-range').off('slide');
                
                // Tambahkan event slide custom dengan format Indonesia
                $('.slider-range').on('slide', function(event, ui) {
                    minPrice = ui.values[0];
                    maxPrice = ui.values[1];
                    $('.range-amount').val('Rp ' + minPrice.toLocaleString('id-ID') + ' - Rp ' + maxPrice.toLocaleString('id-ID'));
                });
                
                // Juga handle event slidechange
                $('.slider-range').on('slidechange', function(event, ui) {
                    minPrice = ui.values[0];
                    maxPrice = ui.values[1];
                    $('.range-amount').val('Rp ' + minPrice.toLocaleString('id-ID') + ' - Rp ' + maxPrice.toLocaleString('id-ID'));
                });
                
                // Set nilai awal dan trigger format
                $('.slider-range').slider('values', [0, 1000000]);
                $('.range-amount').val('Rp 0 - Rp 1.000.000');
                
                // Paksa update tampilan setelah delay singkat
                setTimeout(function() {
                    $('.range-amount').val('Rp 0 - Rp 1.000.000');
                }, 100);
                
                // Pantau perubahan dan pastikan format Rp tetap
                var formatChecker = setInterval(function() {
                    var currentVal = $('.range-amount').val();
                    if (currentVal && currentVal.indexOf('$') !== -1) {
                        // Ganti $ dengan Rp dan perbaiki format
                        var cleanVal = currentVal.replace(/\$/g, '').replace(/[^0-9\-\s]/g, '');
                        var parts = cleanVal.split('-');
                        if (parts.length === 2) {
                            var min = parseInt(parts[0].trim()) || 0;
                            var max = parseInt(parts[1].trim()) || 1000000;
                            $('.range-amount').val('Rp ' + min.toLocaleString('id-ID') + ' - Rp ' + max.toLocaleString('id-ID'));
                        }
                    } else if (!currentVal || currentVal.indexOf('Rp') === -1) {
                        $('.range-amount').val('Rp 0 - Rp 1.000.000');
                    }
                }, 200);
                
                // Hentikan pemantauan setelah 5 detik
                setTimeout(function() {
                    clearInterval(formatChecker);
                }, 5000);
            } else {
                // Fallback: Ganti dengan input sederhana jika slider gagal
                $('.slider-range').html(
                    '<div style="margin-bottom: 10px;">' +
                    '<label style="font-size: 12px; color: #666;">Harga Minimum:</label>' +
                    '<input type="number" id="minPriceInput" value="0" min="0" max="1000000" class="form-control" style="width: 100%; margin-top: 5px; font-size: 12px;">' +
                    '</div>' +
                    '<div style="margin-bottom: 10px;">' +
                    '<label style="font-size: 12px; color: #666;">Harga Maksimum:</label>' +
                    '<input type="number" id="maxPriceInput" value="1000000" min="0" max="1000000" class="form-control" style="width: 100%; margin-top: 5px; font-size: 12px;">' +
                    '</div>'
                );
                
                // Update tampilan harga saat input berubah
                $('#minPriceInput, #maxPriceInput').on('input', function() {
                    minPrice = parseInt($('#minPriceInput').val()) || 0;
                    maxPrice = parseInt($('#maxPriceInput').val()) || 1000000;
                    
                    // Pastikan min tidak lebih besar dari max
                    if (minPrice > maxPrice) {
                        if ($(this).attr('id') === 'minPriceInput') {
                            $(this).val(maxPrice);
                            minPrice = maxPrice;
                        } else {
                            $(this).val(minPrice);
                            maxPrice = minPrice;
                        }
                    }
                    
                    $('.range-amount').val('Rp ' + minPrice.toLocaleString('id-ID') + ' - Rp ' + maxPrice.toLocaleString('id-ID'));
                });
                
                $('.range-amount').val('Rp 0 - Rp 1.000.000');
            }
        }, 500); // Tunggu 500ms agar theme siap

        // Fungsi filter harga
        $('.price-range').on('submit', function(e) {
            e.preventDefault();
            
            // Ambil nilai dari slider atau input
            if ($('.slider-range').hasClass('ui-slider')) {
                // Dari jQuery UI slider
                var values = $('.slider-range').slider('values');
                minPrice = values[0];
                maxPrice = values[1];
            } else {
                // Dari input manual
                minPrice = parseInt($('#minPriceInput').val()) || 0;
                maxPrice = parseInt($('#maxPriceInput').val()) || 1000000;
            }
            
            // Validasi rentang harga
            if (minPrice > maxPrice) {
                alert('Harga minimum tidak boleh lebih besar dari harga maksimum!');
                return;
            }
            
            // Redirect dengan filter harga
            window.location.href = '<?= base_url('events') ?>?min_price=' + minPrice + '&max_price=' + maxPrice;
        });

        // Fungsi link kategori
        $('.nav-widget a').on('click', function(e) {
            var href = $(this).attr('href');
            if (href && href !== '#' && href !== 'javascript:void(0)') {
                // Biarkan default untuk link valid
                return true;
            } else {
                e.preventDefault();
            }
        });

        // Fungsi pencarian dengan tombol Enter
        $('.product-search input[type="text"]').on('keypress', function(e) {
            if (e.which === 13) { // Tombol Enter
                e.preventDefault();
                $(this).closest('form').submit();
            }
        });
    });
}

// Inisialisasi saat DOM siap atau jQuery tersedia
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebarFunctionality);
} else {
    initSidebarFunctionality();
}
</script>
