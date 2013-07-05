<?php
/*
 * app launcher! ye baby
 */
//vardump($app);
global $wpdb;
$user = wp_get_current_user();
if(!empty($_GET['sub'])){
    $_GET['renderinterface'] = $_GET['sub'];
}
$Interface = get_option($_GET['renderinterface']);
if(empty($app) && !empty($_GET['renderinterface'])){
    $app= get_option('_'.sanitize_title($Interface['_Application']).'_app');
}
if(is_array($app)){
    foreach($app['interfaces'] as $interface=>$access){
        $cfg = get_option($interface);

        if($access == 'null'){
            $access = 'read';
        }
        if(!empty($user->allcaps[$access])){
            if(!empty($cfg['_ItemGroup'])){
                if(!empty($cfg['_interfaceName'])){
                    $menus[$cfg['_ItemGroup']][$cfg['ID']] = $cfg['_interfaceName'];
                }else{

                    $menus[$cfg['_ItemGroup']][$cfg['ID']] = $cfg['_ReportDescription'];
                }
            }else{
                if(!empty($cfg['_interfaceName'])){
                    $menus[$cfg['_interfaceName']] = $cfg['ID'];
                }
            }
        }
    }
}
?>
<h2 id="appTitle"><?php echo $app['name']; ?></h2>
<?php
    // get link of docked:
    if(!empty($app['docked'])){
        $Link = 'admin.php?page=';
    }else{
        $Link = 'admin.php?page=dbt_builder&renderinterface=';
    }

    if(!empty($menus)){
        ksort($menus);
        
        echo '<div class="appnav_toolbar">';
            echo '<ul class="tools_widgets">';
                foreach($menus as $menu=>$group){
                    if(is_array($group)){
                        echo '<li class="root_item"><a class="parent hasSubs">'.$menu.'</a>';
                            echo '<ul id="'.sanitize_title($menu).'" style="visibility: hidden; display: block;">';
                            foreach($group as $interface=>$label){
                                echo '<li><a href="'.$Link.$interface.'">'.$label.'</a></li>';
                            }
                            echo '</ul>';
                        echo '</li>';
                    }else{
                        //vardump($app);
                        if($group == $app['landing']){
                            if(!empty($Interface['_Application'])){
                                 if(!empty($app['docked'])){
                                    $group = 'app_'.$Interface['_Application'];
                                 }
                            }else{
                                $group = $_GET['page'];
                            }
                            //vardump($interface);
                        }
                        echo '<li class="root_item"><a href="'.$Link.$group.'" class="parent">'.$menu.'</a></li>';
                    }
                }                
            echo '</ul>';
            echo '<div style="clear:both;"></div>';
        echo '</div>';
    }
    if(!empty($areas)){
        echo '<div class="subsubsub">';
        echo implode(' | ',$areas);
        echo '</div>';
        echo '<div style="clear:both;"></div>';
    }
    /*
?>
        <div class="appnav_toolbar">
            <ul class="tools_widgets">
                <li class="root_item"><a class="parent hasSubs">Processors</a>
                    <ul id="" style="visibility: hidden; display: block;">

                        <li><a>A Link</a></li>
                        <li><a class="child HasSubs">A Link</a>
                            <ul id="" style="visibility: hidden; display: block;">
                                <li class="title"><a>A nother sub link</a></li>
                            </ul>
                        </li>
                        <li><a>A Link</a></li>
                        <li><a>A Link</a></li>
                        <li><a>A Link</a></li>

                    </ul>
                </li>
                <li class="root_item"><a class="parent">Processors</a></li>
            </ul>
            <div style="clear:both;"></div>
        </div>

<?php
     * 
     */
   
if(!empty($_GET['renderinterface'])){
    $noedit = true;
    include DB_TOOLKIT.'dbtoolkit_admin.php';
}else{
    // load landing
    if(!empty($app['landing'])){
        $_GET['renderinterface'] = $app['landing'];
    }else{
        if(!empty($app['interfaces'])){
            foreach($app['interfaces'] as $intf=>$val){
                $_GET['renderinterface'] = $intf;
                break;
            }
        }
    }
    $noedit = true;
    include DB_TOOLKIT.'dbtoolkit_admin.php';
}
?>


<?php
$_SESSION['dataform']['OutScripts'] .= "

    // activate menus
    jQuery('.tools_widgets ul').css({
        display: \"none\"
    });
    jQuery('.tools_widgets li').hover(function(){
        jQuery(this).find('ul:first').css({
            visibility: \"visible\",
            display: \"none\"
        }).fadeIn(250);
    },function(){
        jQuery(this).find('ul:first').css({
            visibility: \"hidden\"
        });
    });
";
?>