<?php
/*
  Plugin Name: DB-Toolkit
  Plugin URI: http://digilab.co.za
  Description: Visual query builder for Pods
  Author: David Cramer
  Version: 2.0.0
  Author URI: http://digilab.co.za
 */

//initilize plugin

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define('DBTOOLKIT_PATH', plugin_dir_path(__FILE__));
define('DBTOOLKIT_URL', plugin_dir_url(__FILE__));
define('DBTOOLKIT_VER', '2.0.0');

// handlebars PHP
include_once DBTOOLKIT_PATH . 'includes/Handlebars/Autoloader.php';
Handlebars\Autoloader::register();
use Handlebars\Handlebars;


include_once DBTOOLKIT_PATH . 'includes/dbtoolkit.php';
// load types
include_once DBTOOLKIT_PATH . 'includes/functions-types.php';
// load panels
include_once DBTOOLKIT_PATH . 'includes/functions-panels.php';

add_action('init', 'dbtoolkit_init_plugin');

function dbtoolkit_init_plugin(){

	// load panel controllers
	$panels = apply_filters( 'dbtoolkit_get_element_panels', array() );
	foreach($panels as $panel){
		if(isset($panel['functions']) && file_exists($panel['functions'])){
			include_once $panel['functions'];
		}
	}

	// setup element processors
	$elements = apply_filters( 'dbtoolkit_get_element_types', array() );
	foreach($elements as $element_type=>$element){
		// processor
		if(!empty($element['processor'])){
			add_filter('dbtoolkit_element_process-' . $element_type, $element['processor'], 1, 2);
		}
	}

	//include_once DBTOOLKIT_PATH . 'includes/query-controller-.php';
}

if(is_admin()){

	// load admin functions
	include_once DBTOOLKIT_PATH . 'includes/functions-admin.php';
	include_once DBTOOLKIT_PATH . 'includes/functions-editor.php';

}else{

	// add active shortcode templates
	add_action( 'init', 'dbtoolkit_define_template_shortcodes' );
	function dbtoolkit_define_template_shortcodes(){
		$elements = dbtoolkit_get_active_elements(array('query_template', 'data_grid'));
		foreach($elements as $element){
			add_shortcode( $element['slug'], 'dbtoolkit_render_shortcode' );
		}	
	}

	function dbtoolkit_render_shortcode($args, $content, $code){
		global $passback_args, $wp_query, $post;

		$elements = dbtoolkit_get_active_elements( array('query_template', 'data_grid') );
		
		foreach($elements as $element){
			if($element['slug'] === $code){
				$config = $element;
				break;
			}
		}
		if(empty($config)){
			return;
		}

		$passback_args = array_merge( (array) $passback_args, (array) $args);

		$element_types = apply_filters( 'dbtoolkit_get_element_types', array() );
		if(empty($element_types[$config['type']])){
			return;
		}
		if(!empty($element_types[$config['type']]['renderer'])){
			add_action( 'dbtoolkit_render_element-'.$config['type'], $element_types[$config['type']]['renderer'], 10, 2);
			ob_start();
			do_action( 'dbtoolkit_render_element-'.$config['type'], $config, $passback_args);
			$html = do_shortcode( ob_get_clean() );
		}else{

			$engine = new Handlebars;
			$data_source = get_option( $config['data_source'] );

			$data = apply_filters( "dbtoolkit_element_process-" . $data_source['type'], array(), $data_source );
			$data['wp_query'] = $wp_query;
			$data['post'] = $post;
			
			$template = dbtoolkit_do_magic_tags( $config['code']['html'] );

			$sdata = array(
				'name' => 'DAVID',
				'entries' => array(
					'book',
					'car'
				)			
			);
			$html = do_shortcode( $engine->render( $template, $data ) );
			if(!empty($config['code']['script'])){
				$html .= "<script type=\"text/javascript\">\r\n" . dbtoolkit_do_magic_tags( $config['code']['script'] ) . "\r\n</script>";
			}
		}
		return $html;
	}

}

// Load DBT Form Processor for CF
include_once DBTOOLKIT_PATH . 'cf_processor/cf_processor.php';