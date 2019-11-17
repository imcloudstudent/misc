<?php
$region='%region%';
$bucket='%bucket%';
$tmp='';
$categories=NULL;
$table_name = 'AWS-Services';
require 'aws-autoloader.php';
date_default_timezone_set('UTC');
try {
	$sdk = new Aws\Sdk([
    		'region'	=> $region,
		'version'	=> 'latest'
	]);

	$dynamodb = $sdk->createDynamoDb();
	$response = $dynamodb->describeTable(array('TableName' => $table_name));
	$categories = $dynamodb->scan(array(	'TableName' => $table_name, 
						'AttributeValueList' => array(array('S' => 'Category'))
	));
}
catch(Exception $e) {
	$tmp=$e->getMessage();
	$categories=NULL;
}

echo '<html><head>';
if(is_null($categories)==0){
	echo '<link rel="stylesheet" href="http://' . $bucket  . '.s3.amazonaws.com/jquery/jquery-ui.min.css?v=1" />';
	echo '<script type="text/javascript" src="http://' . $bucket  . '.s3.amazonaws.com/jquery/jquery-2.2.1.min.js"></script>';
	echo '<script type="text/javascript" src="http://' . $bucket  . '.s3.amazonaws.com/jquery/jquery-ui.min.js"></script>';
}
echo '  <style>';
echo '  body{font-size: 1em; font-family:"Trebuchet MS";margin:0;overflow:hidden;}';
echo '  div.header {height:60px;top:0;width:100%;margin:0 auto;padding-top:40px;background-color:#eeeeee;color:#cc0000;font-size:24px; text-align:center;border-bottom:1px solid #fff;min-width:1200px}';
echo '  div.content {margin:0 auto;width:400px;position:relative;top:-25px;}';
echo '  .shadow {-webkit-box-shadow:3px 1px 3px 1px #aaa;box-shadow:3px 1px 3px 1px #aaa}';
echo '  .shadow-bottom {-webkit-box-shadow: 0 0 5px 0 #aaa;box-shadow:0 0 5px 0 #aaa}';
echo '  .shadow-top {-webkit-box-shadow:5px 0 0 0 #aaa;box-shadow:5px 0 0 0 #aaa}';
echo '  #accordion{position:relative;}';
echo '  .ui-accordion-content div.item{font-family:"Trebuchet MS";font-size:12px;padding:1px 3px 0 3px;}';
echo '  .ui-accordion-content div.item div.acr{float:left;width:30px;text-align:left;padding-left:10px;padding-top:4px;}';
echo '  .ui-accordion-content div.item div.name{float:left;padding-left:10px;text-align:left;padding-top:4px;}';
echo '  .ui-accordion-content div.item div.clear{clear:both;}';
echo '  .ui-accordion-content div.item div.icon {float:left;width:35px;}';
echo '  .ui-accordion-content div.item div.icon img{max-height:24px;max-width:28px;}';
echo '  img.logo{position: absolute;top: 0;left: 0;display: block;float: left;height: 100px;}';
echo '  div.foot{height:30px;padding-top:5px;z-index:99;position:absolute;text-align:center;width:100%;bottom:0px;left:0;color:#fff;background-color:#808080;border-top:1px solid #cc0000;min-width:400px}';
echo '  div.error-msg{text-align:center;margin 0 auto;position:relative;top:20%;}';
echo '  a.ui-button {position:absolute;top:30px;right:30px;border: solid 1px #fff;padding: 15px;background-color: #ddd;}';
echo '  a.ui-button:hover{border-color:#ddd;background-color:#eee; }';
echo '  </style>';
if(is_null($categories)==0){
	echo '	<script>
			$(function() {
				$( "#accordion" )
					.accordion({heightStyle: "panel",collapsible: true, active: false, header: "> div.accordion-group > div.ui-accordion-header"})
					.sortable({
					        axis: "y",
					        handle: "div.ui-accordion-header",
					        stop: function( event, ui ) {
          						ui.item.children( "div.ui-accordion-header" ).triggerHandler( "focusout" );
           						$(this).accordion( "refresh" ); 					       	
						
						},
						update: function(event, ui){ $("div.accordion-group").first().fadeTo("slow", 1);}
						
					});

				$("div.ui-accordion-header").click(function() {
					var $moveItem = $(this).closest("div.accordion-group");
					$moveItem.fadeTo("slow", "0.4", function(){
					
						$("#accordion").sortable("option","update")(null, {
							item: $("div.accordion-group").first().before($moveItem)
  						});
					});
				});
			});
		</script>';
}
echo '</head><body>';
echo '<div class="header shadow-bottom">Training & Certification | Architecting on AWS | Lab 1</div><a href="challenge-me.php" class="ui-button ui-corner-all shadow">challenge me</a>';
echo '<div class="error-msg">'.$tmp.'</div>';
echo '<div class="foot shadow">Amazon Web Services | Training & Certification</div>';
if(is_null($categories)==0){
	echo '<img class="logo" src="http://' .  $bucket . '.s3.amazonaws.com/jquery/aws.png" />';
	echo '<div class="content">';
	echo '<div id="accordion" class="ui-accordion ui-corner-all shadow">';

	foreach ($categories['Items'] as $key => $catvalue) {
        	if($tmp<>$catvalue['Category']['S']){
                	echo '<div class="accordion-group"><div class="ui-accordion-header">'. $catvalue['Category']['S']  .'</div>';

                	$response = $dynamodb->query([
                        	'TableName' => 'AWS-Services',
	                        'KeyConditionExpression' => 'Category = :v_cat',
        	                'ExpressionAttributeValues' =>  [
                	                ':v_cat' => ['S' => $catvalue['Category']['S']]
                        	]
                	]);

	                echo '<div class="ui-accordion-content">';
        	        foreach ($response['Items'] as $key => $value) {
                	        echo '<div class="item"><div class="icon"><img src="http://' . $bucket . '.s3.amazonaws.com/images/' . $value['Icon']['S'] . '.png" /></div><div class="acr">' .  $value['Acronym']['S'] . '</div><div class="name">'  . $value['Name']['S'] .  '</div><div class="clear"></div></div>';
                	}
	                echo '</div></div>';
        	}
	        $tmp=$catvalue['Category']['S'];
	}
	echo '</div><div class="clear"></div></div>';
}
echo '</body></html>';
?>
