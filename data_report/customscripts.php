<h2>Custom Scripts</h2>
        <?php
        if(empty($Element['Content']['_customFooterJavaScript']))
            $Element['Content']['_customFooterJavaScript'] = '';
        
        echo dais_customfield('textarea', 'Footer Scripts', '_customFooterJavaScript', '_customFooterJavaScript', 'list_row1' , $Element['Content']['_customFooterJavaScript'], 'style="height:300px;"', 'Javascript code to be run when page is ready.');
        ?>
<h2>Custom Libraries</h2>
        <a href="#" onclick="dt_addLibrary(); return false;">Add Custom JS Library</a> | <a href="#" onclick="dt_addCSSLibrary(); return false;">Add Custom Style Sheet</a>
        
        <div id="addonLibrary">
            <?php
            if(empty($Element['Content']['_customJSLibrary'])){
                echo dais_addJSLibrary();
            }else{
                foreach($Element['Content']['_customJSLibrary'] as $jsScript){
                    if(!empty($jsScript['source'])){
                        echo dais_addJSLibrary($jsScript['source'], $jsScript['location']);
                    }

                }
            }
            ?>
        </div>
        
        <div id="addonCSSLibrary">
            <?php
            if(empty($Element['Content']['_customCSSSource'])){
                echo dais_addCSSLibrary();
            }else{
                foreach($Element['Content']['_customCSSSource'] as $cssScript){
                    if(!empty($cssScript['source'])){
                        echo dais_addCSSLibrary($cssScript['source']);
                    }
                }
            }
            ?>
        </div>