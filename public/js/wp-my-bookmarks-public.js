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
                    'action': 'process_bookmark'
                },
                beforeSend: function() {
                    _this.text = '';
                    _wait_span.innerHTML = ajax_props.wait_text + ' <span class="dashicons dashicons-update"></span> ';
                },
                success: function(result) {
                    _wait_span.innerHTML = result.ok_message;
                    bookmark_el = document.getElementById('#bookmark_' + post_id);
                    bookmark_el.parentNode.removeChild(bookmark_el);
                    setTimeout(function() {
                        _wait_span.innerHTML = '';
                        _this.text = result.text;
                    }, 1000);

                },
                error: function(jqXHR, exception) {
                    console.log('@@@ error: ', jqXHR, exception);
                }
            });
        });

    });


})(jQuery);