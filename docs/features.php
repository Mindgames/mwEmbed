<?php 
$featureSet = include( 'featureList.php' );
$fullFeaturePath = htmlspecialchars( $_REQUEST['path'] );
list( $featureKey, $featureSubKey )  =  explode('/',  $fullFeaturePath);

if( ! isset( $featureSet[$featureKey ] ) ){
	echo "feature set path ". $featureKey . " not found "; 
	return ;
} else{
	$feature = $featureSet[ $featureKey ];
}
// Output the title: 
if( $featureSubKey ){ ?>
	<span id="hps-<?php echo $fullFeaturePath; ?>">&nbsp;</span>
<?php  
} else {
?>
<h2 id="hps-<?php echo $fullFeaturePath; ?>"><?php echo $feature['title'] ?></h2>
<p> <?php echo $feature['desc'] ?></p>
<?php 
}
?>
<script>
	var iframeLoadCount =0; 
	function handleLoadedIframe( id ){
		$('#loading_' + id ).remove();
		iframeLoadCount++;
		doSync = true;
		if( iframeLoadCount == <?php  echo count( $feature['testfiles'] ) ?> ){
			// done loading get correct offset for hash
			var aNode = $('body').find('a[name="' + location.hash.replace('#', '') +'"]')[0];
			if( aNode ){
				aNode.scrollIntoView();
			}
		}
	}
	var doSync = false;
	function sycnIframeContentHeight(){
		doSync = true;
	}
	setInterval( function(){
		if( doSync ){
			doSync = false;
			$('iframe').each(function(){
				$( this ).css(
					'height', 
					$( $( this )[0].contentWindow.document ).height()
				)
			});
		}
	}, 200 );
</script>
<?php 
function outputFeatureIframe($testFile){
	$iframeId = 'ifid_' . $testFile['hash'];
	?>
	<br>
	<a name="<?php echo $testFile['hash'] ?>" href="../modules/<?php echo  $testFile['path']; ?>" target="_new" >
		<span style="text-transform: lowercase; padding-top: 50px; margin-top: -50px;font-size:x-small"> <?php echo $testFile['title'] ?> test page >>> </span>
	</a>
	<br>
	<iframe style="border:none;width:100%;height:0px" 
		id="<?php echo $iframeId ?>" 
		onload="handleLoadedIframe('<?php echo $iframeId ?>')" 
		src="../modules/<?php echo $testFile['path'] ?>">
	</iframe>
	<span id="loading_<?php echo $iframeId ?>">Loading <?php echo $testFile['hash']?><span class="blink">...</span> </span> 
	<?php 
}

	
// output all the features for that path: 
foreach( $feature['testfiles'] as $testFile ){
	// check if we are only outputing the $featureSubKey
	if( $featureSubKey ){
		if( $testFile['hash'] ==$featureSubKey ){
			outputFeatureIframe( $testFile );
		}
	} else{
		outputFeatureIframe( $testFile );
	}
}
