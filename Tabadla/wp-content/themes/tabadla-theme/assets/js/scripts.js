jQuery(document).ready(function($) {
    // Handle Swap Request
    $('.btn-swap').on('click', function(e) {
        e.preventDefault();
        
        var $btn = $(this);
        var listingId = $btn.data('id');

        if ($btn.hasClass('loading')) return;

        $btn.addClass('loading').text('Sending...');

        $.ajax({
            url: tabadla_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'tabadla_swap_request',
                listing_id: listingId,
                nonce: tabadla_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $btn.removeClass('loading').addClass('success').text('Request Sent!').prop('disabled', true);
                    alert(response.data.message);
                } else {
                    $btn.removeClass('loading').text('Request Swap');
                    alert(response.data.message);
                }
            },
            error: function() {
                $btn.removeClass('loading').text('Request Swap');
                alert('Something went wrong. Please try again.');
            }
        });
    });

    // Initialize AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Mobile Header Transparency
    $(window).scroll(function() {
        if ($(window).scrollTop() > 50) {
            $('.site-header').css('background', 'rgba(255, 255, 255, 0.95)');
        } else {
            $('.site-header').css('background', 'rgba(255, 255, 255, 0.8)');
        }
    });
});
