/**
* wc_add_to_cart()
*
* Handles form submission via AJAX
*
* @param {obj} submitted_form [subitted form element]
*/

(function ($, root, undefined) {

    if($('.wc-header-cart').length > 0) {
        wc_update_header_cart_ajax();
    }

    // Variable product quantities
    $('input[name=wc_variation_quantity]').on('change paste keyup', function(e) {
        var qty_el = $(this);
        var qty_val = $(this).val();

        qty_el.closest('.wc-variation').find('input[name=wc_variation]').attr('data-variation-qty', qty_val);
    });

    // Submit form
    $(document).on('submit', '.wc-add-to-cart--form', function(e) {
        e.preventDefault();

        var submitted_form = $(this);

        wc_add_to_cart(submitted_form);
    });

    function wc_add_to_cart(submitted_form) {

        var cart_data = {};
        var product_type = submitted_form.data('wc-product-type');
        var notice = $('.wc-add-to-cart-notice');
        var message;
        var timeout = null;
        var validate = true;    
    
        // remove errors
        notice.removeClass('display has-errors');
    
        // Terms
        var wc_terms = $('input[name=wc_terms]');
    
        // Variable
        if(product_type === 'variable' || product_type === 'variable-subscription') {
    
            var checked_inputs = $('input[name=wc_variation]:checked');
    
            // Any checked inputs?
            if(checked_inputs.length > 0) {
    
                checked_inputs.each(function(index) {
                    var variation_el = $(this);
                    var variation_id = variation_el.data('variation-id');
                    var variation_child_only = variation_el.data('variation-child-only');
    
                    cart_data[variation_id] = {
                        'product_id': variation_el.data('product-id'),
                        'variation_id': variation_el.data('variation-id'),
                        'quantity': variation_el.data('variation-qty'),
                        'variation_name': variation_el.data('variation-name'),
                        'variation_slug': variation_el.data('variation-slug'),
                        'variation_price': variation_el.data('variation-price'),
                        'variation_payment_plan': variation_el.data('variation-payment-plan')
                    }
    
                    if(checked_inputs.length === 1 && variation_child_only === true) {
    
                        message = 'Child tickets must be purchased with an adult ticket';
                        validate = false;
    
                    }
    
                });
    
            } else { 
    
                message = 'Please select at least one option from the above';
                validate = false;
    
            } // end checked inputs
    
            // Validat terms
            if(wc_terms.length > 0) {
                if(!wc_terms.is(':checked')) {
                    message = 'Please ensure you have read and agree to the<br>Terms &amp; Conditions by ticking the box above';
                    validate = false;
                }
            }
        
        // Simple.
        } else if(product_type === 'simple') {
    
            var simple_el = $('input[name=wc_simple]');
            var product_id = simple_el.data('product-id');
            var product_qty = simple_el.val();
    
            if(product_qty != 0) {
    
                cart_data[product_id] = {
                    'product_id': product_id,
                    'quantity': product_qty
                }
    
            } else { 
    
                message = 'Quantity must be at least 1';
                validate = false;
    
            }
    
            //console.log(cart_data);
        
        }
        
        console.log(cart_data);
        console.log(product_type);
    
        // Everything correct?
        if(validate) {
    
            // Show spinner.
            $('.wc-add-to-cart-button').addClass('disable').html('<i class="fal fa-spinner-third fa-spin"></i>');
    
            // Fire!!!
            $.ajax({
                url: wc_ajax_object.ajaxUrl,
                dataType: 'html',
                type: 'POST',
                contentType: 'application/x-www-form-urlencoded; charset=utf-8',
                data: ({
                    'action' : 'wc_ajax_add_to_cart',
                    'wc_security' : wc_ajax_object.ajaxNonce,
                    'wc_product_type' : product_type,
                    'wc_cart_data' : cart_data
                }),
    
                success: function(data) {
    
                    // add to cart button
                    $('.wc-add-to-cart-button').removeClass('disable').html('Added to basket <i class="fas fa-check-circle"></i>');
    
                    // show continue buttons
                    $('.wc-continue').addClass('on');

                    //wc_update_header_cart_ajax();
    
                }
    
            });
    
        } else {
    
            notice.addClass('display has-errors').html(message);

            clearTimeout(timeout);
    
            timeout = setTimeout(function() {
                notice.removeClass('display has-errors').html('');
            }, 3000);
    
        }
    
    }
    
    
    function wc_update_header_cart_ajax() {
    
        //$('.wc-header-cart').html('<a href="#"><span>&bull; &bull; &bull;</span></a>');
    
        $.ajax({
            url: fl1_ajax_object.ajaxUrl,
            dataType: 'html',
            type: 'POST',
            contentType: 'application/x-www-form-urlencoded; charset=utf-8',
            data: ({
                'action' : 'wc_update_header_cart_ajax',
                'wc_security' : fl1_ajax_object.ajaxNonce
            }),
    
            success: function(data) {
                $('.wc-header-cart').html(data);
            }
    
        });
    
    }

})(jQuery, this);
