<?php
/**
 * Shopping Cart Page
 * Created by: Mikhael Felian Waskito - mikhaelfelian@gmail.com
 */

echo $this->extend('da-theme/layout/main');
echo $this->section('content');
?>

<!--Page Cover-->
<section class="row page-cover" data-bgimage="<?= base_url('/public/assets/theme/da-theme/images/page-cover/5.jpg') ?>">
    <div class="row m0">
        <div class="container">
            <h2 class="page-title"></h2>
        </div>
    </div>
</section>

<!--Cart-->
<section class="row shopping-cart">
    <div class="container">
        <div class="table-responsive">
            <table class="table cart-table">
                <thead>
                    <tr>
                        <th class="col-xs-4">Deskripsi</th>
                        <th class="col-xs-2">Qty</th>
                        <th class="col-xs-2">Harga</th>
                        <th class="col-xs-2">Total</th>
                        <th class="col-xs-2"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="cart-related-boxs row">
                    <h4 class="this-title">Metode Pembayaran</h4>
                    <div id="payment-platforms-list" class="form-box">
                        <!-- Payment platforms will be loaded here -->
                        <p class="text-center text-muted" style="padding: 20px;">
                            <i class="fa fa-spinner fa-spin"></i> Loading payment methods...
                        </p>
                    </div>
                    <div class="row m0 text-center">
                            <button type="button" class="btn btn-primary add-payment-btn"
                                style="margin-right: 10px;"><i class="fa fa-plus"></i> Metode</button>
                                <br/><br/>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="cart-related-boxs row">
                    <h4 class="this-title">Shopping Cart Total</h4>
                    <form action="<?= base_url('cart/store') ?>" method="post" class="input-group cupon-get form-box"
                        id="checkout-form">
                        <dl class="dl-horizontal">
                            <dt>Sub-Total</dt>
                            <dd id="cart-subtotal">Rp 0</dd>
                            <dt>Order Total</dt>
                            <dd id="cart-total">Rp 0</dd>
                        </dl>

                        <!-- Hidden fields for order data -->
                        <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" id="csrf_token">
                        <input type="hidden" name="order_data" id="order_data_input" value="">

                        <div class="row m0 text-right">
                            <button type="button" class="btn btn-primary clear-cart-btn"
                                style="margin-right: 10px;">Clear Cart</button>
                            <button type="submit" class="btn btn-primary checkout-btn">Proceed to Checkout</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Participant Modal -->
<div class="modal fade" id="participantModal" tabindex="-1" role="dialog" aria-labelledby="participantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="participantModalLabel">Data Peserta</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Silakan masukkan nama peserta untuk setiap tiket yang dibeli.
                </div>
                <div id="participant-list">
                    <!-- Participant inputs will be generated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmCheckout">Konfirmasi Checkout</button>
            </div>
        </div>
    </div>
</div>

<?php
echo $this->endSection();
?>

<?= $this->section('js') ?>
<!-- AutoNumeric plugin -->
<script src="<?= base_url('/public/assets/theme/admin-lte-3/plugins/JAutoNumber/autonumeric.js') ?>"></script>
<script>
    $(document).ready(function () {
        // Initialize global CSRF hash
        window.csrf_hash = '<?= csrf_hash() ?>';
        
        // Global cart data storage
        window.cartDataStore = {};

        loadCartItems();
        loadPaymentPlatforms();

        // Load cart items
        function loadCartItems() {
            $.ajax({
                url: '<?= base_url('cart/getItems') ?>',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        displayCartItems(response);
                    } else {
                        showEmptyCart();
                    }
                },
                error: function (xhr, status, error) {
                    console.log('Cart loading error:', xhr.responseText);
                    showEmptyCart();
                }
            });
        }

        // Display cart items using theme's existing table structure
        function displayCartItems(data) {
            var tbody = $('.cart-table tbody');
            tbody.empty();

            if (data.items && data.items.length > 0) {
                $.each(data.items, function (index, item) {
                    var cartData = typeof item.cart_data === 'string' ? JSON.parse(item.cart_data) : item.cart_data;
                    var itemTotal = parseFloat(item.total_price);

                    // Store cart data globally for use in participant modal
                    window.cartDataStore[item.id] = {
                        id: item.id,
                        event_id: item.event_id || 0,
                        price_id: item.price_id || 0,
                        quantity: item.quantity,
                        unit_price: cartData.harga || 0,
                        total_price: item.total_price,
                        event_title: cartData.event_title || '',
                        price_description: cartData.price_description || '',
                        cart_data: cartData
                    };

                    var row = '<tr class="alert fade in" role="alert" data-cart-id="' + item.id + '" data-event-id="' + (item.event_id || 0) + '" data-price-id="' + (item.price_id || 0) + '">';
                    row += '<td>';
                    row += '<div class="media">';
                    if (cartData.event_image) {
                        row += '<div class="media-left">';
                        row += '<a href="#"><img src="' + '<?= base_url("public/file/events/") ?>' + item.event_id + '/' + cartData.event_image + '" alt=""></a>';
                        row += '</div>';
                    }
                    row += '<div class="media-body text-left">';
                    row += cartData.event_title;
                    if (cartData.price_description && cartData.price_description !== cartData.event_title) {
                        row += '<br><small class="text-muted">' + cartData.price_description + '</small>';
                    }
                    if (cartData.event_date) {
                        row += '<br><small class="text-muted"><i class="fa fa-calendar"></i> ' + cartData.event_date + '</small>';
                    }
                    if (cartData.event_location) {
                        row += '<br><small class="text-muted"><i class="fa fa-map-marker"></i> ' + cartData.event_location + '</small>';
                    }
                    row += '</div>';
                    row += '</div>';
                    row += '</td>';
                    row += '<td><input type="number" class="form-control quantity-input" value="' + item.quantity + '" min="1" data-cart-id="' + item.id + '"></td>';
                    row += '<td>Rp ' + number_format(cartData.harga, 0, ',', '.') + '</td>';
                    row += '<td>Rp ' + number_format(itemTotal, 0, ',', '.') + '</td>';
                    row += '<td><button type="button" class="close remove-item-btn" data-cart-id="' + item.id + '" aria-label="Close">remove</button></td>';
                    row += '</tr>';

                    tbody.append(row);
                });

                // Update cart summary in theme's existing elements
                updateCartSummary(data);

            } else {
                showEmptyCart();
                return;
            }

            bindCartEvents();
        }

        // Update cart summary using theme's existing elements
        function updateCartSummary(data) {
            // Use server-provided totals first
            $('#cart-subtotal').text(data.formatted_total);
            $('#cart-total').text(data.formatted_total);

            // Also update individual row totals
            $('.cart-table tbody tr[data-cart-id]').each(function () {
                var row = $(this);
                updateRowTotal(row);
            });

            // Then recalculate to ensure consistency
            updateCartTotals();
        }

        // Show empty cart message
        function showEmptyCart() {
            var tbody = $('.cart-table tbody');
            tbody.empty();
            var emptyRow = '<tr><td colspan="5" class="text-center" style="padding: 50px;">';
            emptyRow += '<i class="fa fa-shopping-cart fa-3x text-muted"></i>';
            emptyRow += '<h4 class="text-muted">Your cart is empty</h4>';
            emptyRow += '<p class="text-muted">Add some events to your cart to see them here.</p>';
            emptyRow += '<a href="<?= base_url('events') ?>" class="btn btn-primary">Browse Events</a>';
            emptyRow += '</td></tr>';
            tbody.append(emptyRow);

            // Reset summary
            $('#cart-subtotal').text('Rp 0');
            $('#cart-total').text('Rp 0');
        }

        // Bind cart events
        function bindCartEvents() {
            // Quantity buttons
            $('.quantity-btn').on('click', function () {
                var cartId = $(this).data('cart-id');
                var action = $(this).data('action');
                var quantityInput = $('input[data-cart-id="' + cartId + '"]');
                var currentQuantity = parseInt(quantityInput.val());
                var newQuantity = action === 'increase' ? currentQuantity + 1 : Math.max(1, currentQuantity - 1);

                updateQuantity(cartId, newQuantity);
            });

            // Quantity input change
            $('.quantity-input').on('change', function () {
                var cartId = $(this).data('cart-id');
                var quantity = Math.max(1, parseInt($(this).val()) || 1);
                $(this).val(quantity);

                // Update the row total immediately
                updateRowTotal($(this).closest('tr'));

                // Update cart summary
                updateCartTotals();

                // Update on server
                updateQuantity(cartId, quantity);
            });

            // Live calculation on input
            $('.quantity-input').on('input', function () {
                var quantity = Math.max(1, parseInt($(this).val()) || 1);
                $(this).val(quantity);

                // Update the row total immediately
                updateRowTotal($(this).closest('tr'));

                // Update cart summary
                updateCartTotals();
            });

            // Remove item
            $('.remove-item-btn').on('click', function () {
                var cartId = $(this).data('cart-id');
                removeItem(cartId);
            });

            // Clear cart
            $('.clear-cart-btn').on('click', function () {
                if (confirm('Are you sure you want to clear your cart?')) {
                    clearCart();
                }
            });

            // Add payment platform row
            $('.add-payment-btn').on('click', function () {
                // Get platforms from the first row's select
                var firstSelect = $('.platform-select').first();
                if (firstSelect.length === 0) return;

                var platforms = [];
                firstSelect.find('option').each(function () {
                    if ($(this).val()) {
                        platforms.push({
                            id: $(this).val(),
                            nama: $(this).data('nama'),
                            jenis: $(this).data('jenis')
                        });
                    }
                });

                var rowIndex = $('.payment-row').length;
                addPaymentRow(platforms, rowIndex);
            });

            // Handle checkout form submission
            $('#checkout-form').on('submit', function (e) {
                e.preventDefault();

                // Check if cart has items
                var cartItems = $('.cart-table tbody tr[data-cart-id]');
                if (cartItems.length === 0) {
                    alert('Your cart is empty');
                    return false;
                }

                // Check if payment methods are selected
                var hasValidPayment = false;
                $('.payment-row').each(function() {
                    var platformId = $(this).find('.platform-select').val();
                    var amount = 0;
                    try {
                        amount = parseFloat($(this).find('.payment-amount').autoNumeric('get')) || 0;
                    } catch(e) {
                        amount = parseFloat($(this).find('.payment-amount').val().replace(/\./g, '').replace(/,/g, '.')) || 0;
                    }
                    
                    if (platformId && amount > 0) {
                        hasValidPayment = true;
                    }
                });
                
                if (!hasValidPayment) {
                    alert('Please select at least one payment method.');
                    return false;
                }

                // Show participant modal
                showParticipantModal();
                return false;
            });
        }

        // Show participant modal
        function showParticipantModal() {
            var participantContainer = $('#participant-list');
            participantContainer.empty();
            
            var participantIndex = 0;
            
            // Get all cart items to determine participants needed
            $('.cart-table tbody tr[data-cart-id]').each(function() {
                var row = $(this);
                var quantity = parseInt(row.find('.quantity-input').val()) || 1;
                var mediaBody = row.find('.media-body');
                
                // Try multiple ways to get the event title
                var eventTitle = mediaBody.contents().first().text().trim();
                if (!eventTitle) {
                    eventTitle = mediaBody.clone().children().remove().end().text().trim();
                }
                if (!eventTitle) {
                    eventTitle = mediaBody.text().split('\n')[0].trim();
                }
                
                var priceDescription = mediaBody.find('small').first().text().trim();
                
                // Debug log
                console.log('Row HTML:', row.html());
                console.log('Media Body HTML:', mediaBody.html());
                console.log('Event Title:', eventTitle);
                console.log('Price Description:', priceDescription);
                
                // Create a more informative label
                var ticketLabel = eventTitle || 'Event';
                if (priceDescription) {
                    ticketLabel += ' (' + priceDescription + ')';
                }
                
                // Get cart data from global store
                var cartId = row.attr('data-cart-id');
                var cartItem = window.cartDataStore[cartId] || {};
                
                var eventId = cartItem.event_id || 0;
                var priceId = cartItem.price_id || 0;
                var unitPrice = cartItem.unit_price || 0;
                var totalPricePerItem = unitPrice; // Price per single item
                
                // Create participant inputs for each quantity
                for (var i = 0; i < quantity; i++) {
                    var html = '<div class="row participant-row" style="margin-bottom: 15px;" ';
                    html += 'data-event-id="' + eventId + '" ';
                    html += 'data-price-id="' + priceId + '" ';
                    html += 'data-unit-price="' + unitPrice + '" ';
                    html += 'data-total-price="' + totalPricePerItem + '" '; // Price per single item
                    html += 'data-quantity="1" '; // Each participant is 1 quantity
                    html += 'data-event-title="' + (cartItem.event_title || '') + '" ';
                    html += 'data-price-description="' + (cartItem.price_description || '') + '" ';
                    html += '>';
                    html += '<div class="col-md-6">';
                    html += '<label><strong>Tiket:</strong> ' + ticketLabel + '</label>';
                    html += '</div>';
                    html += '<div class="col-md-6">';
                    html += '<input type="text" class="form-control rounded-0 participant-name" ';
                    html += 'name="participant_' + participantIndex + '" ';
                    html += 'placeholder="Nama Peserta ' + (i + 1) + '" required>';
                    html += '</div>';
                    html += '</div>';
                    
                    participantContainer.append(html);
                    participantIndex++;
                }
            });
            
            // Show the modal
            $('#participantModal').modal('show');
        }
        
        // Handle confirm checkout from modal
        $('#confirmCheckout').on('click', function() {
            // Validate all participant names are filled
            var allFilled = true;
            $('.participant-name').each(function() {
                if ($(this).val().trim() === '') {
                    allFilled = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            
            if (!allFilled) {
                alert('Silakan isi semua nama peserta.');
                return;
            }
            
            // Hide modal
            $('#participantModal').modal('hide');
            
            // Prepare order data with participants
            var orderData = prepareOrderData();
            
            // Set the order data in hidden field
            $('#order_data_input').val(JSON.stringify(orderData));
            
            // Update CSRF token
            $('#csrf_token').val(window.csrf_hash || '<?= csrf_hash() ?>');
            
            // Submit the form naturally
            document.getElementById('checkout-form').submit();
        });

        // Prepare order data in the format you specified
        function prepareOrderData() {
            // Generate nota number
            var notaNumber = generateNotaNumber();

            // Get subtotal from display
            var subtotalText = $('#cart-subtotal').text();
            var subtotal = parseIndonesianNumber(subtotalText);

            // Prepare cart data
            var cartData = [];
            var cartItems = $('.cart-table tbody tr[data-cart-id]');

            cartItems.each(function () {
                var row = $(this);
                var cartId = row.data('cart-id');
                var quantity = parseInt(row.find('.quantity-input').val()) || 0;
                var priceText = row.find('td:nth-child(3)').text();
                var price = parseIndonesianNumber(priceText);
                var totalText = row.find('td:nth-child(4)').text();
                var total = parseIndonesianNumber(totalText);

                // Get event title from media body
                var eventTitle = row.find('.media-body').contents().first().text().trim();

                cartData.push({
                    cart_id: cartId,
                    event_title: eventTitle,
                    quantity: quantity,
                    unit_price: price,
                    total_price: total
                });
            });

            // Prepare payment platforms from form
            var cartPayments = [];
            $('.payment-row').each(function () {
                var row = $(this);
                var platformId = row.find('.platform-select').val();
                var platformName = row.find('.platform-select option:selected').text();
                var amount = 0;
                try {
                    amount = parseFloat(row.find('.payment-amount').autoNumeric('get')) || 0;
                } catch(e) {
                    amount = parseFloat(row.find('.payment-amount').val().replace(/\./g, '').replace(/,/g, '.')) || 0;
                }
                var note = row.find('.payment-note').val();

                if (platformId && amount > 0) {
                    cartPayments.push({
                        platform_id: platformId,
                        platform: platformName,
                        amount: amount,
                        note: note
                    });
                }
            });

            // Prepare participant list from modal inputs
            var participants = [];
            $('.participant-name').each(function(index) {
                var participantName = $(this).val().trim();
                var participantRow = $(this).closest('.participant-row');
                var label = participantRow.find('label').text();
                
                // Get data from participant row
                var eventId = participantRow.attr('data-event-id') || 0;
                var priceId = participantRow.attr('data-price-id') || 0;
                var unitPrice = parseFloat(participantRow.attr('data-unit-price')) || 0;
                var totalPrice = parseFloat(participantRow.attr('data-total-price')) || 0;
                var quantity = parseInt(participantRow.attr('data-quantity')) || 1;
                
                if (participantName) {
                    participants.push({
                        participant_name: participantName,
                        ticket_info: label.replace('Tiket: ', '').replace(/^<strong>Tiket:<\/strong>\s*/, ''),
                        participant_number: index + 1,
                        event_id: eventId,
                        price_id: priceId,
                        quantity: quantity,
                        unit_price: unitPrice,
                        total_price: totalPrice,
                        event_title: participantRow.attr('data-event-title') || label.replace('Tiket: ', '').replace(/^<strong>Tiket:<\/strong>\s*/, '').split(' (')[0],
                        price_description: participantRow.attr('data-price-description') || (label.includes('(') ? label.split('(')[1].replace(')', '') : '')
                    });
                }
            });

            // Return data in your specified format
            return {
                no_nota: notaNumber,
                subtotal: subtotal,
                subtotal_formatted: 'Rp ' + number_format(subtotal, 0, ',', '.'),
                cart_data: cartData,
                cart_payments: cartPayments,
                participant: participants
            };
        }

        // Generate nota number
        function generateNotaNumber() {
            var now = new Date();
            var year = now.getFullYear();
            var month = String(now.getMonth() + 1).padStart(2, '0');
            var day = String(now.getDate()).padStart(2, '0');
            var hour = String(now.getHours()).padStart(2, '0');
            var minute = String(now.getMinutes()).padStart(2, '0');
            var second = String(now.getSeconds()).padStart(2, '0');

            return 'INV-' + year + month + day + '-' + hour + minute + second;
        }

        // Parse Indonesian number format (Rp 75.000 -> 75000)
        function parseIndonesianNumber(text) {
            // Remove currency symbol and spaces
            var cleanText = text.replace(/[Rp\s]/g, '');

            // In Indonesian format, dots are thousand separators, commas are decimal separators
            // Convert dots (thousands) to empty string, and commas (decimal) to dots
            var numberStr = cleanText.replace(/\./g, '').replace(/,/g, '.');

            // Parse as float
            var result = parseFloat(numberStr) || 0;

            return result;
        }

        // Update row total for a specific row
        function updateRowTotal(row) {
            var quantity = parseInt(row.find('.quantity-input').val()) || 0;
            var priceText = row.find('td:nth-child(3)').text(); // Price column

            // Parse Indonesian number format (Rp 75.000 -> 75000)
            var price = parseIndonesianNumber(priceText);
            var itemTotal = quantity * price;

            // Update the row total column
            row.find('td:nth-child(4)').text('Rp ' + number_format(itemTotal, 0, ',', '.'));
        }

        // Update cart totals by calculating from all rows
        function updateCartTotals() {
            var manualTotal = 0;
            var itemCount = 0;

            $('.cart-table tbody tr[data-cart-id]').each(function () {
                var row = $(this);
                var quantity = parseInt(row.find('.quantity-input').val()) || 0;
                var priceText = row.find('td:nth-child(3)').text(); // Price column

                // Parse Indonesian number format (Rp 75.000 -> 75000)
                var price = parseIndonesianNumber(priceText);
                var itemTotal = quantity * price;
                manualTotal += itemTotal;
                itemCount += quantity;
            });

            // Update totals
            var formattedTotal = 'Rp ' + number_format(manualTotal, 0, ',', '.');
            $('#cart-subtotal').text(formattedTotal);
            $('#cart-total').text(formattedTotal);

            // Update navbar counter
            $('.cart-counter').text(itemCount);
            if (itemCount > 0) {
                $('.cart-counter').show();
            } else {
                $('.cart-counter').hide();
            }
        }

        // Update quantity
        function updateQuantity(cartId, quantity) {
            $.ajax({
                url: '<?= base_url('cart/updateQuantity') ?>',
                type: 'POST',
                dataType: 'json',
                data: function () {
                    var csrfData = {};
                    var csrfToken = '<?= csrf_token() ?>';
                    var csrfHash = window.csrf_hash || '<?= csrf_hash() ?>';
                    csrfData[csrfToken] = csrfHash;
                    return $.extend({
                        cart_id: cartId,
                        quantity: quantity
                    }, csrfData);
                }(),
                success: function (response) {
                    if (response.success) {
                        // Update CSRF token if provided
                        if (response.csrf_hash) {
                            window.csrf_hash = response.csrf_hash;
                        }
                        loadCartItems(); // Reload cart
                        updateCartCounter();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('Failed to update quantity');
                }
            });
        }

        // Remove item
        function removeItem(cartId) {
            $.ajax({
                url: '<?= base_url('cart/remove') ?>',
                type: 'POST',
                dataType: 'json',
                data: function () {
                    var csrfData = {};
                    var csrfToken = '<?= csrf_token() ?>';
                    var csrfHash = window.csrf_hash || '<?= csrf_hash() ?>';
                    csrfData[csrfToken] = csrfHash;
                    return $.extend({
                        cart_id: cartId
                    }, csrfData);
                }(),
                success: function (response) {
                    if (response.success) {
                        // Update CSRF token if provided
                        if (response.csrf_hash) {
                            window.csrf_hash = response.csrf_hash;
                        }
                        loadCartItems(); // Reload cart
                        updateCartCounter();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('Failed to remove item');
                }
            });
        }

        // Clear cart
        function clearCart() {
            $.ajax({
                url: '<?= base_url('cart/clear') ?>',
                type: 'POST',
                dataType: 'json',
                data: function () {
                    var csrfData = {};
                    var csrfToken = '<?= csrf_token() ?>';
                    var csrfHash = window.csrf_hash || '<?= csrf_hash() ?>';
                    csrfData[csrfToken] = csrfHash;
                    return csrfData;
                }(),
                success: function (response) {
                    if (response.success) {
                        // Update CSRF token if provided
                        if (response.csrf_hash) {
                            window.csrf_hash = response.csrf_hash;
                        }
                        loadCartItems(); // Reload cart
                        updateCartCounter();
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function () {
                    toastr.error('Failed to clear cart');
                }
            });
        }

        // Update cart counter in navbar
        function updateCartCounter() {
            $.ajax({
                url: '<?= base_url('cart/getCount') ?>',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        $('.cart-counter').text(response.count);
                        if (response.count > 0) {
                            $('.cart-counter').show();
                        } else {
                            $('.cart-counter').hide();
                        }
                    }
                }
            });
        }

        // Load payment platforms
        function loadPaymentPlatforms() {
            $.ajax({
                url: '<?= base_url('cart/getPlatforms') ?>',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success && response.platforms) {
                        displayPaymentPlatforms(response.platforms);
                    } else {
                        showNoPlatforms();
                    }
                },
                error: function () {
                    showNoPlatforms();
                }
            });
        }

        // Display payment platforms
        function displayPaymentPlatforms(platforms) {
            var container = $('#payment-platforms-list');
            container.empty();

            if (platforms && platforms.length > 0) {
                // Create initial default payment row
                addPaymentRow(platforms, 0); // Add first row by default
            } else {
                showNoPlatforms();
            }
        }

        // Add payment row
        function addPaymentRow(platforms, rowIndex) {
            var container = $('#payment-platforms-list');
            var subtotalText = $('#cart-subtotal').text();
            var subtotal = parseIndonesianNumber(subtotalText);

            var html = '<div class="payment-row" data-row="' + rowIndex + '" style="margin-bottom: 15px;">';
            html += '<div class="row">';

            // Platform selection
            html += '<div class="col-sm-4">';
            html += '<select class="form-control rounded-0 platform-select" name="payment_platform_' + rowIndex + '" data-row="' + rowIndex + '">';
            html += '<option value="">Pilih Platform</option>';

            platforms.forEach(function (platform) {
                var label = platform.nama;
                if (platform.jenis) {
                    label += ' (' + platform.jenis + ')';
                }
                html += '<option value="' + platform.id + '" data-nama="' + platform.nama + '" data-jenis="' + (platform.jenis || '') + '">' + label + '</option>';
            });

            html += '</select>';
            html += '</div>';

            // Amount input
            html += '<div class="col-sm-3">';
            html += '<input type="text" class="form-control rounded-0 payment-amount autonumber" name="payment_amount_' + rowIndex + '" value="' + (rowIndex === 0 ? subtotal : 0) + '" data-row="' + rowIndex + '" placeholder="Jumlah">';
            html += '</div>';

            // Notes input
            html += '<div class="col-sm-4">';
            html += '<input type="text" class="form-control rounded-0 payment-note" name="payment_note_' + rowIndex + '" placeholder="Catatan ..." data-row="' + rowIndex + '">';
            html += '</div>';

            // Remove button
            html += '<div class="col-sm-1">';
            if (rowIndex > 0) {
                html += '<a href="#" class="remove-payment-btn" data-row="' + rowIndex + '" title="Hapus" style="color: #d9534f; font-size: 16px;">';
                html += '<i class="fa fa-trash"></i>';
                html += '</a>';
            }
            html += '</div>';

            html += '</div>';
            html += '</div>';

            container.append(html);

            // Bind events for this row
            bindPaymentRowEvents(rowIndex);
        }

        // Bind payment row events
        function bindPaymentRowEvents(rowIndex) {
            // Initialize autoNumber for this row
            $('.payment-amount[data-row="' + rowIndex + '"]').autoNumeric({
                aSep: '.',
                aDec: ',',
                aSign: '',
                vMin: '0',
                vMax: '999999999',
                mDec: 0
            });
            
            // Remove payment row
            $('.remove-payment-btn[data-row="' + rowIndex + '"]').on('click', function (e) {
                e.preventDefault();
                $('.payment-row[data-row="' + rowIndex + '"]').remove();
                updatePaymentTotals();
            });

            // Update totals when amount changes
            $('.payment-amount[data-row="' + rowIndex + '"]').on('input change keyup', function () {
                updatePaymentTotals();
            });
        }

        // Update payment totals
        function updatePaymentTotals() {
            var totalPayments = 0;
            var validPayments = 0;

            $('.payment-amount').each(function () {
                var amount = 0;
                try {
                    // Get value from autoNumeric
                    amount = parseFloat($(this).autoNumeric('get')) || 0;
                } catch(e) {
                    // Fallback if autoNumeric not initialized
                    amount = parseFloat($(this).val().replace(/\./g, '').replace(/,/g, '.')) || 0;
                }
                var platformSelected = $(this).closest('.payment-row').find('.platform-select').val();

                if (amount > 0 && platformSelected) {
                    totalPayments += amount;
                    validPayments++;
                }
            });

            var cartTotal = parseIndonesianNumber($('#cart-total').text());

            // Remove existing validation indicators
            $('.payment-validation-info').remove();

            // Add simple payment summary
            var cartTotal = parseIndonesianNumber($('#cart-total').text());
            var difference = totalPayments - cartTotal;

            if (validPayments > 0 && Math.abs(difference) > 1) {
                var summaryHtml = '<div class="payment-validation-info row m0" style="margin-top: 10px;">';
                summaryHtml += '<small class="text-muted">';
                if (difference > 1) {
                    summaryHtml += '<i class="fa fa-info-circle"></i> Kelebihan: Rp ' + number_format(Math.abs(difference), 0, ',', '.');
                } else {
                    summaryHtml += '<i class="fa fa-warning"></i> Kurang: Rp ' + number_format(Math.abs(difference), 0, ',', '.');
                }
                summaryHtml += '</small></div>';
                $('#payment-platforms-list').after(summaryHtml);
            }
        }

        // Show no platforms message
        function showNoPlatforms() {
            $('#payment-platforms-list').html('<p class="text-muted">No payment platforms available</p>');
        }

        // Helper function to format numbers
        function number_format(number, decimals, dec_point, thousands_sep) {
            number = (number + '').replace(',', '').replace(' ', '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    });
</script>
<?= $this->endSection() ?>