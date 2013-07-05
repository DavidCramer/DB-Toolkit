<?php
	$Types = loadFolderContents(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes');
	foreach($Types[0] as $Type){
		if(file_exists(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/footer.php')){
			include(WP_PLUGIN_DIR.'/db-toolkit/data_form/fieldtypes/'.$Type[1].'/footer.php');
		}
	}

?><?php
if(!empty($_SESSION['DF_Post'])){
	$Messages = implode('<br />', $_SESSION['DF_Post']);
	$ReturnID = false;
	$ElementID = false;
	if(!empty($_SESSION['DF_Post_returnID'])){
		$ReturnID = $_SESSION['DF_Post_returnID'];		
		$ElementID = $_SESSION['DF_Post_EID'];
	}
?>
<script>
jQuery(document).ready(function(){
	df_dialog('<?php echo $Messages; ?>', '<?php echo $ReturnID; ?>', '<?php echo $ElementID; ?>');
//	setTimeout($.unblockUI, 2000);
});
</script>
<?php
	unset($_SESSION['DF_Post']);
	unset($_SESSION['DF_Post_returnID']);
	unset($_SESSION['DF_Post_EID']);
}
?>
<?php
if(!empty($_SESSION['dataform']['OutScripts'])){
?>
<script>
jQuery(document).ready(function(){
	<?php
		echo $_SESSION['dataform']['OutScripts'];
		unset($_SESSION['dataform']['OutScripts']);
	?>
});
</script>
<?php
}
?>