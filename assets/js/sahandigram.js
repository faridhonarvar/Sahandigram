jQuery(document).ready(function($) {
    // لایک کردن
    $('.sahandigram-like').on('click', function() {
        var $this = $(this);
        var post_id = $this.data('post-id');
        
        $.ajax({
            url: sahandigram_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'sahandigram_like',
                post_id: post_id,
                nonce: sahandigram_ajax.nonce
            },
            success: function(response) {
                $this.find('.like-count').text(response.data.likes);
                $this.find('i').toggleClass('far fas liked');
            }
        });
    });

    // ذخیره کردن
    $('.sahandigram-save').on('click', function() {
        var $this = $(this);
        var post_id = $this.data('post-id');
        
        $.ajax({
            url: sahandigram_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'sahandigram_save',
                post_id: post_id,
                nonce: sahandigram_ajax.nonce
            },
            success: function() {
                $this.find('i').toggleClass('far fas saved');
            }
        });
    });

    // اشتراک‌گذاری
    $('.sahandigram-share > i').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $options = $(this).siblings('.share-options');
        $options.toggle();
    });

    // بستن منوی اشتراک با کلیک بیرون
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.sahandigram-share').length) {
            $('.share-options').hide();
        }
    });
});