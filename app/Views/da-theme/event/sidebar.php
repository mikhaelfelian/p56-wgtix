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
    <h4 class="widget-title">Search</h4>
    <form action="#" class="input-group product-search">
        <input type="text" class="form-control" placeholder="Type your keyword">
        <span class="input-group-addon"><button type="submit"><i
                    class="fa fa-search"></i></button></span>
    </form>
</div>

<!--Widget-->
<div class="row widget widget-price-filter">
    <div class="price-filter-inner row m0">
        <h4 class="widget-title">Filter by Price</h4>
        <form action="#" class="price-range">
            <div class="slider-range"></div>
            <div class="row price-bar">
                Price: <input type="text" class="range-amount" readonly><br>
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
// Wait for jQuery to be available
function initSidebarFunctionality() {
    // Check if jQuery is available
    if (typeof $ === 'undefined' || typeof jQuery === 'undefined') {
        console.log('jQuery not loaded, retrying...');
        setTimeout(initSidebarFunctionality, 100);
        return;
    }

    $(document).ready(function() {
        // Search functionality
        $('.product-search').on('submit', function(e) {
            e.preventDefault();
            var keyword = $(this).find('input[type="text"]').val();
            if (keyword && keyword.trim()) {
                window.location.href = '<?= base_url('events') ?>?keyword=' + encodeURIComponent(keyword.trim());
            } else {
                // If empty, go to events page without keyword
                window.location.href = '<?= base_url('events') ?>';
            }
        });

        // Search button click functionality
        $('.product-search button').on('click', function(e) {
            e.preventDefault();
            $(this).closest('form').submit();
        });

        // Price range functionality - Let theme handle the slider, we just customize the display
        var minPrice = 0;
        var maxPrice = 1000000;
        
        // Wait for theme's slider to initialize, then customize it
        setTimeout(function() {
            if ($('.slider-range').length > 0 && $('.slider-range').hasClass('ui-slider')) {
                // Theme slider is initialized, customize the display
                $('.range-amount').val('Rp 0 - Rp 1,000,000');
                
                // Remove all existing slide events from theme
                $('.slider-range').off('slide');
                
                // Add our custom slide event with Indonesian formatting
                $('.slider-range').on('slide', function(event, ui) {
                    minPrice = ui.values[0];
                    maxPrice = ui.values[1];
                    $('.range-amount').val('Rp ' + minPrice.toLocaleString('id-ID') + ' - Rp ' + maxPrice.toLocaleString('id-ID'));
                });
                
                // Also handle slidechange event
                $('.slider-range').on('slidechange', function(event, ui) {
                    minPrice = ui.values[0];
                    maxPrice = ui.values[1];
                    $('.range-amount').val('Rp ' + minPrice.toLocaleString('id-ID') + ' - Rp ' + maxPrice.toLocaleString('id-ID'));
                });
                
                // Set initial values and trigger formatting
                $('.slider-range').slider('values', [0, 1000000]);
                $('.range-amount').val('Rp 0 - Rp 1,000,000');
                
                // Force update the display after a short delay
                setTimeout(function() {
                    $('.range-amount').val('Rp 0 - Rp 1,000,000');
                }, 100);
                
                // Monitor for changes and ensure Rp formatting persists
                var formatChecker = setInterval(function() {
                    var currentVal = $('.range-amount').val();
                    if (currentVal && currentVal.indexOf('$') !== -1) {
                        // Replace $ with Rp and fix formatting
                        var cleanVal = currentVal.replace(/\$/g, '').replace(/[^0-9\-\s]/g, '');
                        var parts = cleanVal.split('-');
                        if (parts.length === 2) {
                            var min = parseInt(parts[0].trim()) || 0;
                            var max = parseInt(parts[1].trim()) || 1000000;
                            $('.range-amount').val('Rp ' + min.toLocaleString('id-ID') + ' - Rp ' + max.toLocaleString('id-ID'));
                        }
                    } else if (!currentVal || currentVal.indexOf('Rp') === -1) {
                        $('.range-amount').val('Rp 0 - Rp 1,000,000');
                    }
                }, 200);
                
                // Stop monitoring after 5 seconds
                setTimeout(function() {
                    clearInterval(formatChecker);
                }, 5000);
            } else {
                // Fallback: Replace with simple input fields if slider fails
                $('.slider-range').html(
                    '<div style="margin-bottom: 10px;">' +
                    '<label style="font-size: 12px; color: #666;">Min Price:</label>' +
                    '<input type="number" id="minPriceInput" value="0" min="0" max="1000000" class="form-control" style="width: 100%; margin-top: 5px; font-size: 12px;">' +
                    '</div>' +
                    '<div style="margin-bottom: 10px;">' +
                    '<label style="font-size: 12px; color: #666;">Max Price:</label>' +
                    '<input type="number" id="maxPriceInput" value="1000000" min="0" max="1000000" class="form-control" style="width: 100%; margin-top: 5px; font-size: 12px;">' +
                    '</div>'
                );
                
                // Update price display when inputs change
                $('#minPriceInput, #maxPriceInput').on('input', function() {
                    minPrice = parseInt($('#minPriceInput').val()) || 0;
                    maxPrice = parseInt($('#maxPriceInput').val()) || 1000000;
                    
                    // Ensure min is not greater than max
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
                
                $('.range-amount').val('Rp 0 - Rp 1,000,000');
            }
        }, 500); // Wait 500ms for theme to initialize

        // Price filter functionality
        $('.price-range').on('submit', function(e) {
            e.preventDefault();
            
            // Get values from slider or input fields
            if ($('.slider-range').hasClass('ui-slider')) {
                // Get from jQuery UI slider
                var values = $('.slider-range').slider('values');
                minPrice = values[0];
                maxPrice = values[1];
            } else {
                // Get from input fields
                minPrice = parseInt($('#minPriceInput').val()) || 0;
                maxPrice = parseInt($('#maxPriceInput').val()) || 1000000;
            }
            
            // Validate price range
            if (minPrice > maxPrice) {
                alert('Minimum price cannot be greater than maximum price!');
                return;
            }
            
            // Redirect with price filters
            window.location.href = '<?= base_url('events') ?>?min_price=' + minPrice + '&max_price=' + maxPrice;
        });

        // Category links functionality
        $('.nav-widget a').on('click', function(e) {
            var href = $(this).attr('href');
            if (href && href !== '#' && href !== 'javascript:void(0)') {
                // Let the default behavior happen for valid links
                return true;
            } else {
                e.preventDefault();
            }
        });

        // Add functionality to search on Enter key
        $('.product-search input[type="text"]').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                $(this).closest('form').submit();
            }
        });
    });
}

// Initialize when DOM is ready or jQuery is available
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebarFunctionality);
} else {
    initSidebarFunctionality();
}
</script>
