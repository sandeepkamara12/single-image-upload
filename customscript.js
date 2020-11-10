(function( $ ) {
	'use strict';

	$(function() {
		
		$('#upload_image').click(open_custom_media_window);

		function open_custom_media_window() {
			if (this.window === undefined) {
				this.window = wp.media({
					title: 'Insert Image',
					library: {type: 'image'},
					multiple: false,
					button: {text: 'Insert Image'}
				});

				var self = this;
				this.window.on('select', function() {
					var response = self.window.state().get('selection').first().toJSON();

					$('.wp_attachment_id').val(response.id);
					$('.image').attr('src', response.sizes.thumbnail.url);
                                        $('.image').show();
				});
			}

			this.window.open();
			return false;
		}
	});
})( jQuery );


(function( $ ) {
	'use strict';

	$(function() {
		
		$('#multiple_upload_image').click(open_custom_media_window);

		function open_custom_media_window() {
			if (this.window === undefined) {
				this.window = wp.media({
					title: 'Insert Image',
					library: {type: 'image'},
					multiple: true,
					button: {text: 'Insert Image'}
				});

				var self = this;
				this.window.on('select', function() {
					var response = self.window.state().get('selection').toJSON();
					$(".item-wrap .item-inner-wrap").html("");
                    for ( var i = 0; i < response.length; i++ ) {
						$(".item-wrap .item-inner-wrap").append('<img width="50" height="50" class="multiple_image" src="' + response[i].url + '"><input type="hidden" name="multiple_attachment_id[]" class="wp_multiple_attachment_id" value="' + response[i].id + '" />');                       
					}
				});
			}

			this.window.open();
			return false;
		}
	});
})( jQuery );