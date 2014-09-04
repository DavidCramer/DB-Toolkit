var current_screen_editors = false,
	field_type_templates = {},
	current_loaded_object = {};

function dbt_reveal_tools(obj){
	
	var title 	= jQuery('#dbtoolkit-page-title'),
	caption = jQuery('#dbtoolkit-page-caption');

	jQuery('.dbtoolkit-element-tool').each(function(k,v){
		setTimeout(function(){
			jQuery(v).animate({opacity: 1, marginLeft: 0}, dbt_animation_speed);
		}, (dbt_animation_speed / 2 ) *jQuery(v).index());
	});
	
	if(obj){
		current_loaded_object = obj.rawData;
		if(!obj.rawData.confirm){
			obj.state = current_screen_object.state;
			current_screen_object = obj;
		}
		caption.animate({opacity: 0}, ( dbt_animation_speed / 2 ));
		title.fadeOut(dbt_animation_speed, function(){
			var newtitle = jQuery(this);
			
			newtitle.empty().show();
			setTimeout(function(){
				caption.text(obj.rawData.type).animate({opacity: 1}, ( dbt_animation_speed / 2 ));
			}, ( dbt_animation_speed / 15 )*obj.rawData.name.length );

			for(var c = 0; c < obj.rawData.name.length; c++){
				setTimeout(function(c){
					newtitle.append( obj.rawData.name.substr(c,1) );
				}, ( dbt_animation_speed / 15 )*c, c);
			}

		})
	}
}

function dbt_update_save_state(){
	current_screen_object.state = jQuery('#dbtoolkit-canvas').serialize();
}

function dbt_check_state(el, e){

	// capture editors
	if(typeof current_screen_editors !== 'boolean'){
		for(var editor in current_screen_editors){
			current_screen_editors[editor].save();
		}
	}

	// check state 
	var current_state = jQuery('#dbtoolkit-canvas').serialize();
	if(current_state === current_screen_object.state){
		jQuery(el).data('request', 'dbt_close_editor');		
		jQuery(el).data('template', '#dbtoolkit-admin-menus-tmpl');
		current_screen_object.state = '';
	}
	return true;
}

function dbt_close_editor(el, e){

	if(el.params){
		jQuery('#dbtoolkit-close-editor').trigger('click');
		return;
	}

	dbt_clear_canvas();

	var rawData = {page: "admin", name : "DB-Toolkit", type : jQuery('#dbtoolkit-page-caption').data('version'), state: "" };

	jQuery('.dbtoolkit-editor-panel:visible').hide();
	
	for(var editor in current_screen_editors){
		current_screen_editors[editor].toTextArea();
	}

	current_screen_editors = false;
	current_screen_object = {page : "admin"}

	return rawData;
}

function dbt_get_screen_canvas_data(obj){
	
	if( current_screen_object.rawData ){
		var return_object = current_screen_object.rawData;
		if(obj.trigger.data('menuonly')){
			return_object.menuonly = true
		}
		return return_object;
	}
	return current_screen_object;
}

function dbt_init_editors(obj){

	if(obj.rawData.editors){
		var default_opts = {
			lineNumbers: true,
			matchBrackets: true,
			indentUnit: 4,
			indentWithTabs: true,
			enterMode: "keep",
			tabMode: "shift",
			lineWrapping: true,
			extraKeys: {"Ctrl-Space": "autocomplete"},
		};
		current_screen_editors = {};
		for(var e = 0; e<obj.rawData.editors.length; e++){

			var options = default_opts,
				mode = obj.rawData.editors[e].mode;
			// base mode
			if(obj.rawData.editors[e].mode !== 'text/x-php'){
				CodeMirror.defineMode(obj.rawData.editors[e].slug + "mustache", function(config, parserConfig) {
					var mustacheOverlay = {
						token: mustache
					};
					return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || obj.rawData.editors[e].mode), mustacheOverlay);
				});
				mode = obj.rawData.editors[e].slug + "mustache";			
			}
			
			var options = default_opts;

			if(obj.rawData.editors[e].options){
				options = jQuery.extend({}, options, obj.rawData.editors[e].options);
			}

			//console.log(options);
			// set mode
			options.mode = mode;


			current_screen_editors[obj.rawData.editors[e].slug] = CodeMirror.fromTextArea(document.getElementById("editor_textarea" + obj.rawData.editors[e].slug), options);
			current_screen_editors[obj.rawData.editors[e].slug].on('keyup', tagFields);
		}

	}

}

function dbt_toggle_editor_tab(obj, e){

	var panel = {
		'panel' : obj.trigger.data('panel'),
		'group' : obj.trigger.data('group'),
		'title'	: obj.trigger.data('title')
	}
	return panel;
}

function dbt_switch_editor_tab(obj){

	jQuery('.dbtoolkit-element-tool .wp-filter-link').removeClass('current');
	obj.params.trigger.addClass('current');
	
	jQuery('.dbtoolkit-editor-panel:visible').fadeOut(dbt_animation_speed, function(){
		var panel = jQuery('#' + obj.params.trigger.data('panel') );
		panel.fadeIn(dbt_animation_speed);

		if(panel.data('callback') && typeof window[panel.data('callback')] === 'function'){
			window[panel.data('callback')](obj.params.trigger);
		}
		
	})
}

function dbt_reset_editor(el){

	//for(var editor in current_screen_editors){
		current_screen_editors[el.data('panel')].refresh();
		current_screen_editors[el.data('panel')].focus();
	//}
}

// make an id
function dbt_generate_id(){
	var new_id = "ID-" + Math.random().toString(36).substr(2, 9).toUpperCase();
	if( jQuery('.' + new_id).length ){
		return dbt_generate_id();
	}
	return new_id;
}

// setup fieldtype config
function dbt_setup_fieldtype(obj){
	var type = obj.trigger.val(),
		conf = {id: obj.trigger.data('field')};
	if(field_type_templates[type]){
		return field_type_templates[type](conf);
	}else{
		return field_type_templates._no_config_(conf);
	}
}

// do callbuc for script inits
function dbt_init_fieldtype_switch(obj){
	//color_picker_init
	console.log(window[obj.params.trigger.val() + '_init']);
	if( typeof window[obj.params.trigger.val() + '_init'] === 'function'){
		window[obj.params.trigger.val() + '_init']();
	}
}


// Constant Inits & Binds //
jQuery(function($){

	// element title bind
	$(document).on('keyup change', '#element_name', function(){

		var title = $('#dbtoolkit-page-title'),
		field = $(this);

		title.text( field.val() );
	})

	// precompile fieldtype templates
	var templates = $('.dbtoolkit-field-template');
	for(var t=0; t<templates.length; t++){
		var template = $(templates[t]),
			html 	 = template.html(),
			type 	 = template.data('type');
		
		field_type_templates[type] = Handlebars.compile(html);

	}

	// toggle buttons
	$(document).on('click', '.dbtoolkit-panel-toggle-buttons button', function(e){
		e.preventDefault();
		var clicked = $(this),
			parent 	= clicked.parent(),
			wrap 	= parent.parent();

		parent.children().removeClass('active');
		clicked.addClass('active');

		wrap.find('.dbtoolkit-panel').hide();
		$('#'+clicked.data('panel')).show();

	});

});


function dbt_save_element(el){
	jQuery('#dbtoolkit-canvas').submit();
}


(function() {
	"use strict";

	var Pos         = CodeMirror.Pos;

	function getFields(cm, options) {

		var cur = cm.getCursor(), token = cm.getTokenAt(cur),			
		result = [],
		fields = options.fields;
		switch (options.mode){
			case 'htmlmustache':
			var wrap = {start: "{{", end: "}}"},
			prefix = token.string.slice(2);
			break;
			default:
			var wrap = {start: "", end: "}}"},
			prefix = token.string;
			break;
		}
		for( var field in fields){			
			if (field.indexOf(prefix) == 0 || prefix === '{'){
				if(prefix === '{'){
					wrap.start = '{';
				}
				result.push({text: wrap.start + field + wrap.end, displayText: fields[field]});
			}
		};

		return {
			list: result,
			from: Pos(cur.line, token.start),
			to: Pos(cur.line, token.end)
		};
	}
	CodeMirror.registerHelper("hint", "elementfield", getFields);
})();

function tagFields(cm, e) {
	var cur = cm.getCursor();
	if (!cm.state.completionActive || e.keyCode === 18){			
		var cur = cm.getCursor(), token = cm.getTokenAt(cur), prefix,
		prefix = token.string.slice(0);
		if(prefix){
			if(token.type === 'mustache'){
				var fields = {};
				jQuery('.dbtoolkit-field-slug').each(function(){
					var field = jQuery(this).val();
					fields[field] = field;
				});
				jQuery('.dbtoolkit-asset-slug').each(function(){
					var field = jQuery(this).val();
					fields[field] = field + " (asset)";
				});

				fields["_wpdb_prefix_"] = "wpdb_prefix";
				fields["_wpdb_posts_"] = "wpdb_posts";
				fields['entry_id'] = 'entry_id';
				fields['ip'] = 'ip';
				fields['user:id'] = 'user:id';
				fields['user:user_login'] = 'user:user_login';
				fields['user:firstname'] = 'user:firstname';
				fields['user:lastname'] = 'user:lastname';
				fields['user:user_email'] = 'user:user_email';
				fields['GET:*'] = 'GET:*';
				fields['POST:*'] = 'POST:*';
				fields['REQUEST:*'] = 'REQUEST:*';
				fields['post_meta:*'] = 'post_meta:*';
				fields['embed_post:ID'] = 'embed_post:ID';
				fields['embed_post:post_title'] = 'embed_post:post_title';
				fields['embed_post:permalink'] = 'embed_post:permalink';
				fields['embed_post:post_date'] = 'embed_post:post_date';
				fields['date:Y-m-d H:i:s'] = 'date:Y-m-d H:i:s';
				fields['date:Y/m/d'] = 'date:Y/m/d';
				fields['date:Y/d/m'] = 'date:Y/d/m';
				


				mode = cm.getMode();

				CodeMirror.showHint(cm, CodeMirror.hint.elementfield, {fields: fields, mode: mode.name});

			}
		}
	}
	return;
}
