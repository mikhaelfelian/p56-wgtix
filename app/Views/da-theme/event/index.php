<?php
/**
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 * Date: 2025-08-29
 * Github : github.com/mikhaelfelian
 * description : Events index page template for Digital Agency theme
 * This file represents the events index page template for the Digital Agency theme.
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>

<!-- Page Header -->
<section class="row page-header">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Events</h1>
                <p>Discover our latest events and activities</p>
            </div>
        </div>
    </div>
</section>

<!-- Events Section -->
<section class="row">
    <div class="container">
        <div class="row">
            <!-- Sidebar Filter -->
            <div class="col-md-3">
                <div class="sidebar-filter">
                    <!-- Search -->
                    <div class="filter-section">
                        <h4>Search</h4>
                        <form action="<?= base_url('events') ?>" method="GET" id="filterForm">
                            <div class="form-group">
                                <input type="text" 
                                       name="keyword" 
                                       class="form-control" 
                                       placeholder="Type your keyword" 
                                       value="<?= esc($keyword ?? '') ?>">
                            </div>
                            <button type="submit" class="btn btn-info btn-block">
                                <i class="fa fa-search"></i> Search
                            </button>
                        </form>
                    </div>

                    <!-- Filter by Price -->
                    <div class="filter-section mt-4">
                        <h4>Filter by Price</h4>
                        <div class="price-range-container">
                            <div class="price-slider">
                                <input type="range" id="minPrice" min="0" max="1000000" value="<?= $this->request->getGet('min_price') ?? 0 ?>" class="slider">
                                <input type="range" id="maxPrice" min="0" max="1000000" value="<?= $this->request->getGet('max_price') ?? 1000000 ?>" class="slider">
                            </div>
                            <div class="price-display mt-2">
                                <span>Price: Rp <span id="minPriceDisplay">0</span> - Rp <span id="maxPriceDisplay">1,000,000</span></span>
                            </div>
                            <button type="button" id="filterPrice" class="btn btn-info btn-block mt-2">
                                Filter
                            </button>
                        </div>
                    </div>

                    <!-- Filter by Category -->
                    <div class="filter-section mt-4">
                        <h4>Categories</h4>
                        <div class="category-list">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="kategori" id="allCategories" value="" <?= empty($this->request->getGet('kategori')) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="allCategories">
                                    All Categories
                                </label>
                            </div>
                            <?php if (isset($categories) && is_array($categories)): ?>
                                <?php foreach ($categories as $category): ?>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="cat<?= $category->id ?>" value="<?= $category->id ?>" <?= $this->request->getGet('kategori') == $category->id ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="cat<?= $category->id ?>">
                                            <?= esc($category->kategori) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Reset Filters -->
                    <div class="filter-section mt-4">
                        <a href="<?= base_url('events') ?>" class="btn btn-secondary btn-block">
                            <i class="fa fa-refresh"></i> Reset Filters
                        </a>
                    </div>
                </div>
            </div>

            <!-- Events Content -->
            <div class="col-md-9">

        <!-- Events Grid -->
        <?php if (!empty($events)): ?>
            <div class="row">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4 col-sm-6 event-item">
                        <div class="img-holder row">
                            <?php if (!empty($event->foto)): ?>
                                <img src="<?= base_url($event->foto) ?>" alt="<?= esc($event->event) ?>" class="event-img">
                            <?php else: ?>
                                <img src="<?= base_url('assets/theme/da-theme/images/events/default-event.jpg') ?>" alt="<?= esc($event->event) ?>" class="event-img">
                            <?php endif; ?>
                            
                                                         <?php 
                             $statusInfo = getEventStatusBadge($event->tgl_masuk);
                             ?>
                             
                             <?php if ($statusInfo['badge'] != 'secondary'): ?>
                                 <div class="event-tag"><?= $statusInfo['text'] ?></div>
                             <?php endif; ?>
                            
                            <div class="hover-box">
                                <div class="btn-holder">
                                    <div class="row m0">
                                        <a href="<?= base_url('events/detail/' . $event->id) ?>" class="btn btn-outline blue">View Details</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <a href="<?= base_url('events/detail/' . $event->id) ?>">
                            <h4 class="event-title"><?= esc($event->event) ?></h4>
                        </a>
                        
                                                 <div class="event-info">
                             <p class="event-date">
                                 <i class="fa fa-calendar"></i> 
                                 <?= formatEventDate($event->tgl_masuk, $event->tgl_keluar) ?>
                             </p>
                             
                             <?php if (!empty($event->lokasi)): ?>
                                 <p class="event-location">
                                     <?= getEventLocationInfo($event->lokasi) ?>
                                 </p>
                             <?php endif; ?>
                             
                             <?php if (!empty($event->wkt_masuk)): ?>
                                 <p class="event-time">
                                     <i class="fa fa-clock-o"></i> 
                                     <?= formatEventTime($event->wkt_masuk, $event->wkt_keluar) ?>
                                 </p>
                             <?php endif; ?>
                             
                             <?php if (!empty($event->jml)): ?>
                                 <p class="event-capacity">
                                     <i class="fa fa-users"></i> 
                                     Kapasitas: <?= getEventCapacityInfo($event->jml)['text'] ?>
                                 </p>
                             <?php endif; ?>
                         </div>
                        
                        <?php if (!empty($event->keterangan)): ?>
                            <p class="event-description">
                                <?= esc(substr($event->keterangan, 0, 100)) ?>
                                <?= strlen($event->keterangan) > 100 ? '...' : '' ?>
                            </p>
                        <?php endif; ?>
                        
                                                 <div class="event-status">
                             <?php 
                             $statusInfo = getEventStatusBadge($event->tgl_masuk);
                             ?>
                             <span class="status-badge <?= $statusInfo['badge'] ?>"><?= $statusInfo['text'] ?></span>
                         </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($pager): ?>
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="text-center">
                            <?= $pager->links('default', 'datheme_pagination') ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <div class="no-events">
                            <i class="fa fa-calendar-times-o fa-3x text-muted"></i>
                            <h3>No Events Found</h3>
                            <p><?= !empty($keyword) ? 'No events match your search criteria.' : 'No events available at the moment.' ?></p>
                            <?php if (!empty($keyword)): ?>
                                <a href="<?= base_url('events') ?>" class="btn btn-primary">View All Events</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<style>
.sidebar-filter {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.filter-section {
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 15px;
    margin-bottom: 15px;
}

.filter-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.filter-section h4 {
    color: #495057;
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 15px;
}

.price-slider {
    position: relative;
    height: 40px;
}

.slider {
    -webkit-appearance: none;
    width: 100%;
    height: 5px;
    border-radius: 5px;
    background: #d3d3d3;
    outline: none;
    opacity: 0.7;
    transition: opacity 0.2s;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
}

.slider:hover {
    opacity: 1;
}

.slider::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #17a2b8;
    cursor: pointer;
}

.slider::-moz-range-thumb {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #17a2b8;
    cursor: pointer;
    border: none;
}

.price-display {
    color: #495057;
    font-weight: 500;
}

.form-check {
    margin-bottom: 8px;
}

.form-check-label {
    color: #495057;
    cursor: pointer;
}
</style>

<script>
$(document).ready(function() {
    // Price slider functionality
    var minPrice = $('#minPrice');
    var maxPrice = $('#maxPrice');
    var minPriceDisplay = $('#minPriceDisplay');
    var maxPriceDisplay = $('#maxPriceDisplay');

    function updatePriceDisplay() {
        var min = parseInt(minPrice.val());
        var max = parseInt(maxPrice.val());
        
        if (min > max) {
            minPrice.val(max);
            min = max;
        }
        
        minPriceDisplay.text(min.toLocaleString('id-ID'));
        maxPriceDisplay.text(max.toLocaleString('id-ID'));
    }

    minPrice.on('input', updatePriceDisplay);
    maxPrice.on('input', updatePriceDisplay);
    
    // Initialize display
    updatePriceDisplay();

    // Filter by price
    $('#filterPrice').click(function() {
        var min = minPrice.val();
        var max = maxPrice.val();
        var currentUrl = new URL(window.location);
        currentUrl.searchParams.set('min_price', min);
        currentUrl.searchParams.set('max_price', max);
        window.location.href = currentUrl.toString();
    });

    // Category filter
    $('input[name="kategori"]').change(function() {
        var categoryId = $(this).val();
        var currentUrl = new URL(window.location);
        
        if (categoryId) {
            currentUrl.searchParams.set('kategori', categoryId);
        } else {
            currentUrl.searchParams.delete('kategori');
        }
        
        window.location.href = currentUrl.toString();
    });

    // Enhanced search form
    $('#filterForm').on('submit', function(e) {
        var keyword = $('input[name="keyword"]').val();
        var currentUrl = new URL(window.location);
        
        if (keyword) {
            currentUrl.searchParams.set('keyword', keyword);
        } else {
            currentUrl.searchParams.delete('keyword');
        }
        
        window.location.href = currentUrl.toString();
        e.preventDefault();
    });
});
</script>

<?php
echo $this->endSection();
?>
