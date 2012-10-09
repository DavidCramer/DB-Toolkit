<?php

	switch($type[1]){
		case 'phpcodeblock':
			$Out .= '<pre name="code" class="php">'.$Defaults[$Field].'</pre>';
			$_SESSION['dataform']['OutScripts'] .= "
				dp.SyntaxHighlighter.ClipboardSwf = 'system/dais/plugins/data_form/fieldtypes/text/scripts/clipboard.swf';
				dp.SyntaxHighlighter.HighlightAll('code');
			";
			break;
		case 'textarea':
		case 'textarealarge':
			$Out .= nl2br($Defaults[$Field]);
			break;
		case 'url':
			$Out .= '<a href="'.$Defaults[$Field].'" target="_blank" >'.$Defaults[$Field].'</a>';
			break;
		case 'wysiwyg':
			$Out .= $Defaults[$Field];
			break;
		default:
			$Out .= $Defaults[$Field];
			break;
	};
//$Out = nl2br($Data[$Field]);
?>