window.onload = function() {
	if (window.jQuery) {  
		//checking version
		console.log('JQuery ver '+jQuery.fn.jquery);
		console.log('JQuery ui ver '+jQuery.ui.version);

		// Date picker
		var datepickerParam = {
			'dateFormat':'yy-mm-dd', 
			'firstDay':1, 
			'monthNames':['Januari', 'Februari', 'Mars', 'April', 'Maj', 'Juni', 'Juli', 'Augusti', 'September', 'Oktober', 'November', 'December'],
			'dayNamesMin': ['Sö', 'Må', 'Ti', 'On', 'To', 'Fr', 'Lö'],
			'prevText': 'förra månaden',
			'nextText': 'nästa månad',
		}
		jQuery('.jquery-ui-datepicker').datepicker(datepickerParam);

		//Media uploader
		var mediaUploader
		function image_uploader(id) {
			if( mediaUploader ){
				mediaUploader.open();
				return;
			}
			
			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Välj Bild',
				button: {
					text: 'Välj Bild'
				},
				multiple: false
			});
			
			mediaUploader.on('select', function(){
				attachment = mediaUploader.state().get('selection').first().toJSON();
				console.log(attachment.id);

				jQuery('#'+id).val(attachment.id);
				jQuery('#'+id+'-display').attr('src', attachment.url);
			});
			
			mediaUploader.open();
		}

		//Attatching media uploader to button
		jQuery('.custom-image-upload').click(function (event) { 
			event.preventDefault();
			const id = event.target.dataset.key;
			image_uploader(id);
		});
	} else {
   
	}
}