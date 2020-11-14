/*
 *  Image Frame Script file
 */

jQuery(document).ready(function($) {
	var scwpMetaImageFrame;
	$('#meta-image-button').click(function(e) {
		e.preventDefault();

		// If the frame already exists, re-open it.
		if (scwpMetaImageFrame) {
			scwpMetaImageFrame.open();
			return;
		}

		// Sets up the media library frame
		scwpMetaImageFrame = wp.media.frames.scwpMetaImageFrame = wp.media({
			title: $(this).data('uploader-title'),
			button: {text: $(this).data('uploader-button-text')},
			library: {type: 'image'},
		});

		// Runs when an image is selected.
		scwpMetaImageFrame.on('select', function() {

			// Grabs the attachment selection and creates a JSON representation of the model.
			var scwpMediaAttachment = scwpMetaImageFrame.state().get('selection').first().toJSON();

			// Sends the attachment URL to our custom image input field.
			$('#primary-chart-image').attr('value', scwpMediaAttachment.id);
			$('#meta_img').attr('src', scwpMediaAttachment.url).attr('width', '150').attr('height', '150');
			$('.media-modal-icon').click();

		});

		// Opens the media library frame.
		scwpMetaImageFrame.open();

	});

});