<?php 

global $wp_version;

?>
<script type="text/javascript">
//<![CDATA[
window.ZemantaGetAPIKey = function () { 
	return '<?php echo $api_key; ?>'; 
};

window.ZemantaPluginVersion = function () { 
	return '<?php echo $version; ?>'; 
};

window.ZemantaProxyUrl = function () { 
	return '<?php echo admin_url('admin-ajax.php'); ?>'; 
};

window.ZemantaGettySupport = true;

window.ZemantaPluginFeatures = {
<?php 
for($i = 0, $keys = array_keys($features), $len = sizeof($keys); $i < $len; $i++) :
	echo "\t'" . $keys[$i] . "': " . json_encode($features[$keys[$i]]) . ($i < $len-1 ? ',' : '') . "\n";
endfor; 
?>
};
//]]>
</script>

<script type="text/javascript" id="zemanta-loader" src="//static.zemanta.com/plugins/wordpress/loader.js"></script>
