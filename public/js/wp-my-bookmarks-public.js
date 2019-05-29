(function($) {
    'use strict';

    $(function() {

        $('.lm_add_to_bookmarks').on('click', function(e) {
            var _this = this;
            var _wait_span = $(_this).next('.lm_wait')[0];
            var post_id = this.getAttribute('data-id');

            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: ajax_props.ajaxurl,
                dataType: 'json',
                data: {
                    'post_id': post_id,
                    'security': ajax_props.ajax_nonce,
                    'action': 'process_bookmark' //this is the name of the AJAX method called in WordPress
                },
                beforeSend: function() {
                    _this.text = '';
                    _wait_span.innerHTML = ajax_props.wait_text + ' <span class="dashicons dashicons-update"></span> ';
                },
                success: function(result) {
                    _wait_span.innerHTML = result.ok_message;
                    $('#bookmark_' + post_id).remove();
                    setTimeout(function() {
                        _wait_span.innerHTML = '';
                        _this.text = result.text;
                    }, 1000);

                },
                error: function() {
                    console.log('@@@ error: ', result);
                }
            });
        });

    });

    $(window).load(function() {

    });


})(jQuery);