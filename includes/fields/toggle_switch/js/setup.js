jQuery('document').ready(function($){

	//toggle_option_row

	$('#dbtoolkit-canvas').on('click', '.add-toggle-option', function(e){

		var clicked		= $(this),
			wrapper		= clicked.closest('.dbtoolkit-variable-config-group'),
			toggle_rows	= wrapper.find('.toggle-options'),
			row			= $('#dbtoolkit-options-field-option-tmpl').html(),
			template	= Handlebars.compile( row ),
			key			= "opt" + parseInt( ( Math.random() + 1 ) * 0x100000 ),
			config		= {
				id	: wrapper.prop('id'),
				option	: {}
			};

			console.log(row);

			// add new option
			config.option[key]	=	{				
				value	:	'',
				label	:	'',
				default :	false				
			};


			// place new row
			toggle_rows.append( template( config ) );

			$('.toggle-options').sortable({
				handle: ".dashicons-sort"
			});


	});

	// remove an option row
	$('#dbtoolkit-canvas').on('click', '.toggle-remove-option', function(e){

		$(this).parent().remove();

	});


	// set default option
	$('#dbtoolkit-canvas').on('change', '.toggle_set_default', function(e){

		var option 	= $(this),
			checked	= option.prop('checked');

		if(checked){
			option.closest('.dbtoolkit-config-field-setup').find('.toggle_set_default').prop('checked', false);
			option.prop('checked', true);
		}

	});

	$('.toggle-options').sortable({
		handle: ".dashicons-sort"
	});

});


function toggle_switch_init(id, target){

	jQuery('.toggle-options').sortable({
		handle: ".dashicons-sort"
	});
	
}