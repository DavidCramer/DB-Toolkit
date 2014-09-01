<?php


add_action( "wp_ajax_dbt_load_data_sources", "dbtoolkit_load_data_sources" );
function dbtoolkit_load_data_sources(){

	$autoload = '';
	if(!empty($_POST['source'])){
		$autoload = ' data-autoload="true"';
	}

	echo '<select name="data_source" class="dbtoolkit-trigger"'.$autoload.' data-event="change" data-action="dbt_load_data_source_structure" data-template="#dbtoolkit-panel-data-field-tmpl" data-target="#ce-data-source">';
	echo '<option></option>';
	$types = apply_filters( 'dbtoolkit_get_element_types', array() );
	foreach($types as $type_slug=>$type){
		if($type['type'] === 'data'){
			$data_sources = dbtoolkit_get_active_elements($type_slug);
			foreach($data_sources as $source_id=>$source){
				$sel = '';
				if($_POST['source'] == $source_id){
					$sel = 'selected="selected"';
				}
				echo '<option value="'.$source_id.'" '.$sel.'>'.$source['name'].'</option>';
			}
		}
	}
	echo '</select>';
	exit;
}


add_action( "wp_ajax_dbt_load_data_source_structure", "dbtoolkit_load_data_source_structure" );
function dbtoolkit_load_data_source_structure(){
	global $wpdb;
	if(empty($_POST['data_source'])){
		exit;
	}

	$types = apply_filters( 'dbtoolkit_get_element_types', array() );

	$config = get_option($_POST['data_source']);
	
	if(empty($types[$config['type']]['structure'])){
		wp_send_json( array('error' => 'no structure callback') );
	}

	add_filter('dbtoolkit_get_data_source_structure-'.$config['type'], $types[$config['type']]['structure'], 10, 2);

	$structure = apply_filters('dbtoolkit_get_data_source_structure-'.$config['type'], array(), $config);



	wp_send_json( array('fields' => $structure ) );

}

function dbtoolkit_get(){

}

function dbtoolkit_run_sql($data, $config){
	global $wpdb;

	$sql = dbtoolkit_do_magic_tags( $config['code']['sql'] );

	$data = array(
		'page'				=>	1,
		'pages'				=>	1,
		'total_entries'		=>	1,
		'entries_per_page'	=>	1,
		'entries'			=>	array()
	);
	$rawdata = $wpdb->get_results( $sql, ARRAY_A );
	if(!empty($rawdata)){
		$data['entries'] = $rawdata;
	}

	return $data;

}


function dbtoolkit_do_magic_tags($value, $bk = 0){
	global $wpdb, $passback_args;

	preg_match_all("/\{\{(.+?)\}\}/", $value, $magics);
	if(!empty($magics[1])){
		foreach($magics[1] as $magic_key=>$magic_tag){

			$magic = explode(':', $magic_tag, 2);

			if(count($magic) == 2){
				switch (strtolower( $magic[0]) ) {
					case 'arg':
						if( isset($passback_args[$magic[1]])){
							$magic_tag = $passback_args[$magic[1]];
						}else{
							$magic_tag = null;
						}						
						break;
					case 'get':
						if( isset($_GET[$magic[1]])){
							$magic_tag = $_GET[$magic[1]];
						}else{
							$magic_tag = null;
						}
						break;
					case 'post':
						if( isset($_POST[$magic[1]])){
							$magic_tag = $_POST[$magic[1]];
						}else{
							$magic_tag = null;
						}
						break;
					case 'request':
						if( isset($_REQUEST[$magic[1]])){
							$magic_tag = $_REQUEST[$magic[1]];
						}else{
							$magic_tag = null;
						}
						break;
					case 'template':
						$elements = dbtoolkit_get_active_elements('query_template');

						foreach($elements as $element){
							if($element['slug'] === $magic[1]){
								$magic_tag = $element['code']['html'];
								break;
							}
							
						}
						break;
					case 'variable':
						// TODO
						break;
					case 'date':
						$magic_tag = date($magic[1]);
						break;
					case 'user':
						if(is_user_logged_in()){
							$user = get_userdata( get_current_user_id() );
							if(isset( $user->data->{$magic[1]} )){
								$magic_tag = $user->data->{$magic[1]};
							}else{
								if(strtolower($magic[1]) == 'id'){
									$magic_tag = $user->ID;
								}else{
									$magic_tag = get_user_meta( $user->ID, $magic[1], true );
								}
							}
						}else{
							$magic_tag = null;
						}
						break;
					case 'embed_post':
						global $post;

						if(is_object($post)){
							if(isset( $post->{$magic[1]} )){
								$magic_tag = $post->{$magic[1]};
							}else{

								// extra post data
								switch ($magic[1]) {
									case 'permalink':
										$magic_tag = get_permalink( $post->ID );
										break;

								}

							}
						}else{
							$magic_tag = null;
						}
						break;
					case 'post_meta':
						global $post;

						if(is_object($post)){
							$post_metavalue = get_post_meta( $post->ID, $magic[1] );
							if( false !== strpos($magic[1], ':') ){
								$magic[3] = explode(':', $magic[1]);
							}
							if(empty($post_metavalue)){
								$magic_tag = null;
							}else{									
								if(empty($magic[3])){
									$magic_tag = implode(', ', $post_metavalue);
								}else{
									$outmagic = array();
									foreach ($magic[3] as $subkey => $subvalue) {
										foreach( (array) $post_metavalue as $subsubkey=>$subsubval){
											if(isset($subsubval[$subvalue])){
												$outmagic[] = $post_metavalue;
											}												
										}											
									}
									$magic_tag = implode(', ', $outmagic);
								}
							}
						}else{
							$magic_tag = null;
						}
						break;

				}
			}else{
				switch ($magic_tag) {
					case '_wpdb_prefix_':
						$magic_tag = $wpdb->prefix;
						break;					
					case 'entry_id':
						$magic_tag = $wpdb->insert_id;
						break;
					case 'ip':
						$magic_tag = $_SERVER['REMOTE_ADDR'];
						break;
				}
			}

			$filter_value = apply_filters('dbtoolkit_do_magic_tag', $magic_tag, $magics[0][$magic_key]);
			if('{{'.$filter_value.'}}' !== $magics[0][$magic_key]){
				$value = str_replace($magics[0][$magic_key], $filter_value, $value);
			}
			if( $magics[1][$magic_key] === $value){
				// return to normal

				$value = $magics[0][$magic_key];
			}
		}
	}

	return $value;
}