<?php
/* 
 * Interface insert Widget
 * David Cramer
 */


//return;

/*
 * DB-Toolkit interface insert widget
 */
class interface_insert extends WP_Widget {

	function interface_insert() {
		$widget_ops = array('classname' => 'widget_interface', 'description' =>  'Insert a DB-Toolkit Interface' );
		$this->WP_Widget('interface', 'DB-Toolkit Interface', $widget_ops);
	}

	function widget( $args, $instance ) {
                if(!empty($instance['Interface'])){
                    echo dt_renderInterface($instance['Interface']);
                }
                return;
	}

	function update( $new_instance, $old_instance ) {

                $instance['count'] = $new_instance['count'] ? 1 : 0;
		$instance['dropdown'] = $new_instance['dropdown'] ? 1 : 0;

		return $new_instance;
	}

	function form( $instance ) {

                $instance = wp_parse_args( (array) $instance, array( 'Application' => '', 'Interface' => '') );
                $apps = get_option('dt_int_Apps');
                

        ?>
		<p><label for="<?php echo $this->get_field_id('Application'); ?>">Application</label>
                    <?php

                    
                        
                        if(empty($apps)){
                            echo '<div>There are no apps to select from.</div>';
                        }else{
                            echo '<select class="widefat" id="'.$this->get_field_id('Application').'" name="'.$this->get_field_name('Application').'" onchange="jQuery(\'#interfaceSelector\').remove();">';
                            foreach($apps as $app=>$cfg){
                                $sel = '';
                                if($app == $instance['Application']){
                                    $sel = 'selected="selected"';
                                }
                                echo '<option value="'.$app.'" '.$sel.'>'.$cfg['name'].'</option>';
                            }
                            echo '</select>';

                        }


                    ?>
                    <span class="description">Select an application and click save to continue.</span>
                </p>
                <?php
                if(!empty($instance['Application'])){
                
                        $appConfig = get_option('_'.$instance['Application'].'_app');
                        

                ?>
		<p id="interfaceSelector"><label for="<?php echo $this->get_field_id('Interface'); ?>">Interface</label>
                    <?php


                        if(empty($appConfig['interfaces'])){
                            echo '<div>There are no interfaces int the selected app.</div>';
                        }else{
                            echo '<select class="widefat" id="'.$this->get_field_id('Interface').'" name="'.$this->get_field_name('Interface').'">';
                            foreach($appConfig['interfaces'] as $interface=>$access){
                                $sel = '';
                                if($interface == $instance['Interface']){
                                    $sel = 'selected="selected"';
                                }
                                $cfg = get_option($interface);
                                echo '<option value="'.$interface.'" '.$sel.'>'.$cfg['_ReportDescription'].'</option>';
                            }
                            echo '</select>';

                        }


                    ?>
                </p>
<?php
                }
	}
}
add_action('widgets_init', create_function('', 'return register_widget("interface_insert");'));
?>