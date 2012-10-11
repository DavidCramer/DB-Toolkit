<?php

global $footerscripts, $page, $post;

    if(empty($_GET['interface'])){
        return;
    }
    if(empty($Config)){
        $Config = get_option($_GET['interface']);        
    }    
    $linkURL = get_permalink($Config['_basePost']);
    
        
    $cols = array();
    if(!empty($Config['_IndexType'])){
        foreach($Config['_IndexType'] as $Field=>$Index){
            if(!empty($Index['Visibility'])){
                $cols[] = $Field;
            }
        }
    }

    if(!empty($_GET['mode']) || $Config['_ViewMode'] == 'form'){
        if(!empty($_GET['mode'])){            
            if($_GET['mode'] == 'form'){
                include DBT_PATH.'modes/form.php';
            }
            if($_GET['mode'] == 'edit'){                
                include DBT_PATH.'modes/form.php';
            }
            if($_GET['mode'] == 'view'){
                include DBT_PATH.'modes/view.php';
            }
        }else{
            if($Config['_ViewMode'] == 'form'){
                include DBT_PATH.'modes/form.php';
            }
        }
    }else{
        if(!empty($post)){
            $post->post_name = $Config['_ReportDescription'];
            $post->post_title = $Config['_ReportDescription'];
        }

        $Count = dbt_buildQuery($Config, 'count');
        if(empty($Config['_Items_Per_Page'])){
            $numPages = 1;
        }else{
            $numPages = ceil($Count/$Config['_Items_Per_Page']);
        }
        $tense = 'items';
        if($Count === 1){
            $tense = 'item';
        }
        $currentPage=1;
        $nextClass = '';
        $prevClass = '';
        if(!empty($_GET['_npage'])){
            $currentPage = $_GET['_npage'];
        }
        if($currentPage >= $numPages){
            $currentPage = $numPages;
            $nextClass = 'disabled';
        }
        if($currentPage <= 1){
            $prevClass = 'disabled';
        }
        $nextPage = $currentPage+1;
        $prevPage = $currentPage-1;
        $lastPage = $numPages;

              //dbt_buildQuery($Config, $Format = 'data', $SortField = false, $SortDir = false, $getOverride = false, $page=false, $primary = false)
        $Data = dbt_buildQuery($Config, 'data', false, false, false, $currentPage );
        
        if(empty($Data)){
            $Data['__noResults'] = true;
        }
        // Build Toolbar
        if(is_admin()){
            echo "<form action=\"admin.php?page=app_builder&action=render&interface=".$Config['_ID']."\" method=\"GET\">\n";
        }else{            
            echo "<form action=\"".$linkURL."\" method=\"POST\">\n";
        }
            if(is_admin ()){
                include DBT_PATH.'libs/toolbar.php';
            }else{
                include DBT_PATH.'libs/fronttoolbar.php';
            }

            // Build Filters
            if(is_admin ()){
                include DBT_PATH.'libs/filters.php';
            }else{
                include DBT_PATH.'libs/frontfilters.php';
            }

            // Build List
            if(empty($Config['_useListTemplate'])){
                include DBT_PATH.'modes/list.php';
            }else{
                include DBT_PATH.'modes/template.php';
            }

            if(!empty($Config['_showFooter'])){
                if(is_admin ()){
                    include DBT_PATH.'libs/footer.php';
                }else{
                    include DBT_PATH.'libs/frontfooter.php';
                }
            }
        echo "</form>\n";
    }
    
   ?>