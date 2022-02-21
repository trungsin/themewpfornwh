(function ($) {
    "use strict";

    var custom_theme_file_frame;

    jQuery(function ($) {

    var ajaxurl = infinity_blog_admin.ajax_url;
    var InfinityBlogNonce = infinity_blog_admin.ajax_nonce;


    // Dismiss notice
    $('.twp-custom-setup').click(function(){
        
        var data = {
            'action': 'infinity_blog_notice_dismiss',
            '_wpnonce': InfinityBlogNonce,
        };
 
        $.post(ajaxurl, data, function( response ) {

            $('.twp-infinity-blog-notice').hide();
            
        });

    });

    // Getting Start action
    $('.twp-install-active').click(function(){

        $(this).closest('.twp-infinity-blog-notice').addClass('twp-installing');

        var data = {
            'action': 'infinity_blog_install_plugins',
            '_wpnonce': InfinityBlogNonce,
        };
 
        $.post(ajaxurl, data, function( response ) {

            window.location.href = response;
            
        });

    });

    $('.theme-recommended-plugin .recommended-plugin-status').click(function(){
        
        var id = $(this).closest('.about-items-wrap').attr('id');

        $(this).addClass('twp-activating-plugin')
        var PluginName = $(this).closest('.theme-recommended-plugin').find('h2').text();
        var PluginStatus = $(this).attr('plugin-status');
        var PluginFile = $(this).attr('plugin-file');
        var PluginFolder = $(this).attr('plugin-folder');
        var PluginSlug = $(this).attr('plugin-slug');
        var pluginClass = $(this).attr('plugin-class');

        var data = {
            'single': true,
            'PluginStatus': PluginStatus,
            'PluginFile': PluginFile,
            'PluginFolder': PluginFolder,
            'PluginSlug': PluginSlug,
            'PluginName': PluginName,
            'pluginClass': pluginClass,
            'action': 'infinity_blog_install_plugins',
            '_wpnonce': InfinityBlogNonce,
        };
 
        $.post(ajaxurl, data, function( response ) {
            
            var active = infinity_blog_admin.active
            var deactivate = infinity_blog_admin.deactivate
            $('#'+id+' .recommended-plugin-status').empty();

            if( response == 'Deactivated' ){
                
                $('#'+id+' .theme-recommended-plugin').removeClass('recommended-plugin-active');
                $('#'+id+' .recommended-plugin-status').removeClass('twp-plugin-active');
                $('#'+id+' .recommended-plugin-status').addClass('twp-plugin-deactivate');
                $('#'+id+' .recommended-plugin-status').html(active);
                $('#'+id+' .recommended-plugin-status').attr('plugin-status','deactivate');

            }else if( response == 'Activated' ){
                
                $('#'+id+' .theme-recommended-plugin').addClass('recommended-plugin-active');
                $('#'+id+' .recommended-plugin-status').removeClass('twp-plugin-deactivate');
                $('#'+id+' .recommended-plugin-status').addClass('twp-plugin-active');
                $('#'+id+' .recommended-plugin-status').html(deactivate);
                $('#'+id+' .recommended-plugin-status').attr('plugin-status','active');

            }else{
                
                $('#'+id+' .theme-recommended-plugin').removeClass('recommended-plugin-active');
                $('#'+id+' .recommended-plugin-status').removeClass('twp-plugin-not-install');
                $('#'+id+' .recommended-plugin-status').addClass('twp-plugin-active');
                $('#'+id+' .recommended-plugin-status').html(active);
                $('#'+id+' .recommended-plugin-status').attr('plugin-status','deactivate');

            }

            $('.recommended-plugin-status').removeClass('twp-activating-plugin');
            
        });
    });
    
        // Uploads.
        jQuery(document).on('click', 'input.select-img', function (event) {

            var $this = $(this);

            event.preventDefault();

            var CustomThemeImage = wp.media.controller.Library.extend({
                defaults: _.defaults({
                    id: 'custom-theme-insert-image',
                    title: $this.data('uploader_title'),
                    allowLocalEdits: false,
                    displaySettings: true,
                    displayUserSettings: false,
                    multiple: false,
                    library: wp.media.query({type: 'image'})
                }, wp.media.controller.Library.prototype.defaults)
            });

            // Create the media frame.
            custom_theme_file_frame = wp.media.frames.custom_theme_file_frame = wp.media({
                button: {
                    text: jQuery(this).data('uploader_button_text')
                },
                state: 'custom-theme-insert-image',
                states: [
                    new CustomThemeImage()
                ],
                multiple: false
            });

            // When an image is selected, run a callback.
            custom_theme_file_frame.on('select', function () {

                var state = custom_theme_file_frame.state('custom-theme-insert-image');
                var selection = state.get('selection');
                var display = state.display(selection.first()).toJSON();
                var obj_attachment = selection.first().toJSON();
                display = wp.media.string.props(display, obj_attachment);

                var image_field = $this.siblings('.img');
                var imgurl = display.src;

                // Copy image URL.
                image_field.val(imgurl);
                image_field.trigger('change');
                // Show in preview.
                var image_preview_wrap = $this.siblings('.image-preview-wrap');
                var image_html = '<img src="' + imgurl + '" alt="" style="width:200px;height:200px;" />';
                image_preview_wrap.html(image_html);
                // Show Remove button.
                var image_remove_button = $this.siblings('.btn-image-remove');
                image_remove_button.css('display', 'inline-block');
            });

            // Finally, open the modal.
            custom_theme_file_frame.open();
        });

        // Remove image.
        jQuery(document).on('click', 'input.btn-image-remove', function (e) {

            e.preventDefault();
            var $this = $(this);
            var image_field = $this.siblings('.img');
            image_field.val('');
            var image_preview_wrap = $this.siblings('.image-preview-wrap');
            image_preview_wrap.html('');
            $this.css('display', 'none');
            image_field.trigger('change');

        });

    });

})(jQuery);