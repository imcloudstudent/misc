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
	echo '<link rel="stylesheet" href="http://' . $bucket  . '.s3.amazonaws.com/jquery/jquery-ui.min.css?v=1" />
		<script type="text/javascript" src="http://' . $bucket  . '.s3.amazonaws.com/jquery/jquery-2.2.1.min.js"></script>
		<script type="text/javascript" src="http://' . $bucket  . '.s3.amazonaws.com/jquery/jquery-ui.min.js"></script>';
}
echo '  <style>
	  body{font-size:.75em; font-family:"Trebuchet MS";margin:0;height:100%;overflow:hidden;}
	  div.header {height:60px;top:0;position:fixed;width:100%;margin:0 auto;padding-top:40px;background-color:#eeeeee;color:#cc0000;font-size:2em;text-align:center;border-bottom:1px solid #fff;min-width:1200px;}
	  div.content {margin:0 auto;position:relative;min-width:900px;overflow:auto;bottom:-30px;top:125px;overflow:auto;max-height:75%;}
	  div.content div {-webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;}
	  .complete{position:absolute;right:-5px;top:-30px;height:100px;z-index:99;}
	  .shadow {-webkit-box-shadow:3px 1px 3px 1px #aaa;box-shadow:3px 1px 3px 1px #aaa;}
	  .shadow-bottom {-webkit-box-shadow: 0 0 5px 0 #aaa;box-shadow:0 0 5px 0 #aaa;}
	  .shadow-top {-webkit-box-shadow:5px 0 0 0 #aaa;box-shadow:5px 0 0 0 #aaa;}
	  
	  #challenge-grid{position:relative;right:30px;max-width:70%;width:70%;float:right;border:solid 1px #ddd;padding:10px 0 10px 0;}
	  #challenge-grid .ui-widget-content{position:relative;float:left;margin:1%;padding:3px;width:30%;text-align:left;min-height:50px%;min-width:295px;border:solid 1px #ddd;}
	  #challenge-grid .drop-target{font-family:"Trebuchet MS";color:#95B9C7;font-size:1em;text-align:center;padding-top:8px;border:dashed 2px #95B9C7;height:24px;margin:3px 2px 2px 2px;background-color:#E0FFFF;}
          #challenge-grid .hover-target{font-family:"Trebuchet MS";color:#95B9C7;font-size:1em;text-align:center;padding-top:8px;border:dashed 2px #FFFF00;height:24px;margin:3px 2px 2px 2px;background-color:#FFF;}
	  #challenge-grid .dropped{border-color:#4CC417;background-color:fff;}

	  #challenge-tokens{position:relative;left:30px;float:left;max-width:20%;width:20%;min-width:295px;border:solid 1px #ddd;padding:10px;}
	  div.token{background-color:#fff;font-family:"Trebuchet MS";font-size:.8em;padding:3px;border:solid 1px #cc0000;display:block;height:30px;min-width:285px;cursor:move;}
	  div.token:hover{background-color:#ddd;-webkit-box-shadow:3px 1px 3px 1px #aaa;box-shadow:3px 1px 3px 1px #aaa;}
	  div.token div.acr{float:left;width:30px;text-align:left;padding-left:5px;padding-top:4px;}
	  div.token div.name{float:left;padding-left:5px;text-align:left;padding-top:4px;}
	  div.token div.clear{clear:both;}
	  div.token div.icon {float:left;width:35px;}
	  div.token div.icon img{max-height:24px;max-width:28px;}
	  img.logo{position:absolute;top:0;left:0;display:block;float:left;height:100px;}
	  div.foot{height:30px;z-index:99;padding-top:5px;position:fixed;font-size:1.5em;text-align:center;width:100%;bottom:0;left:0;color:#fff;background-color:#808080;border-top:1px solid #cc0000;min-width:400px;}
	  #dialog,#dialog2{z-index:999;background-color:#fff;font-size:2em;display:none;text-align:center;}
	  img.d-logo{opacity:0.1;}
	  div.error-msg{text-align:center;margin 0 auto;position:relative;top:20%;}
	  .ui-widget-header{padding: 2px 2px 2px 5px;cursor:move;border:solid 1px #cc0000;}
          .ui-widget-overlay {overflow:hidden;}
 	  .ui-dialog-titlebar-close {display: none;}
	  .ui-button:hover{color:#cc0000;}
	</style>';
if(is_null($categories)==0){
	echo '	<script>
			$(function() {
				$("#dialog").dialog({
					position:{ my: "top", at: "top", of: $("div.content") },
					resizable: false,
					width:700,
					autoOpen:false,
      					modal: true,
					title: "Smart is Beautiful",
					hide: {
        					effect: "explode",
        					duration: 1000
					},				
					buttons: {
						"PLAY": function() {
						$( this ).fadeTo("fast",0.1, function(){$(this).dialog( "close" );});
						}
					},
					open: function(event, ui){
						$(this).show()
							.find("img.d-logo").fadeTo("slow", 1.0);
					}
				}).dialog("open");

				$(".ui-draggable").sortable({
                                                axis: "y",
                                                handle: ".ui-draggable",
                                                stop: function( event, ui ) {
                                                        ui.item.children( "div.ui-draggable" ).triggerHandler( "focusout" );
                                                }
                                        });
				$(".ui-widget").sortable({
                                                axis: "x,y",
                                                handle: ".ui-widget-header",
                                                stop: function( event, ui ) {
                                                        ui.item.children(".ui-widget" ).triggerHandler( "focusout" );
                                                }
                                        });

				
				$( "div.ui-droppable").droppable({
				      	drop: function(event, ui){
						$(event.target).addClass("dropped");
						var $drag=ui.draggable; 
						$target=$("#"+$("#challenge-grid").find(event.target).attr("id"));
						$target.before($drag);
						$drag.removeClass("ui-draggable").attr("style","");
						if($("#challenge-tokens").find("." + $target.attr("id")).length==0){
							$("<img />").attr({class: "complete", src:"http://' . $bucket  . '.s3.amazonaws.com/jquery/complete.png"}).appendTo($target.closest(".ui-widget-content").find(".ui-widget-header"));
							$target.fadeTo("fast", 0.1, function(){$(this).remove(); });
						}
						else{
							$target.fadeTo("fast", 0.1, function(){$(this).removeClass("dropped"); $(this).fadeTo("fast", 1); });
						}
						if($("#challenge-tokens").find(".ui-draggable").length==0){
								$("#challenge-tokens").fadeOut("slow");
								$("#challenge-grid").css({width:"95%",maxWidth:"95%"});
								$("#dialog2").dialog({
        			                                position:{ my: "top", at: "top", of: $("div.content") },
                                			        resizable: false,
			                                        width:700,
                        			                autoOpen:false,
			                                        modal: true,
                        			                title: "Smart is Beautiful",
			                                        hide: {
                        			                        effect: "explode",
                                                			duration: 1000
			                                        },
                        			                buttons: {
                                                			"REVIEW": function() {
			                                                $( this ).fadeTo("fast",0.1, function(){$(this).dialog( "close" );});
			                                                }
                        			                },
			                                        open: function(event, ui){
                        			                        $(this).show()
                                                			        .find("img.d-logo").fadeTo("slow", 1.0);
                                        			}
				                                }).dialog("open");

						
						}
					},
					accept: function( event, ui ) {
						return event.hasClass($(this).attr("id"));
					}
				});

				$("div.ui-draggable").draggable({	
							revert: "invalid",
							cursor: "move",
							grid: [ 10, 10 ],
							start: function(event, ui){$(".ui-droppable").addClass("hover-target").removeClass("drop-target");},
							stop: function(event, ui) {$(".ui-droppable").removeClass("hover-target").addClass("drop-target");}

					});
			});
		</script>';
}
echo '</head><body>
	<div id="dialog" ui-dialog-title="Smart is Beautiful"><img class="d-logo" src="http://' .  $bucket . '.s3.amazonaws.com/jquery/aws.png" /><div>challenge your knowledge</div></div>
	<div id="dialog2" ui-dialog-title="Smart is Beautiful"><img class="d-logo" src="http://' .  $bucket . '.s3.amazonaws.com/jquery/aws.png" /><div>challenge complete</div></div>
	<div class="header shadow-bottom">Training & Certification | Challenge Me</div>
	<div class="error-msg">'.$tmp.'</div>
	<div class="foot shadow">Amazon Web Services | Training & Certification</div>';
if(is_null($categories)==0){
echo	'<img class="logo" src="http://' .  $bucket . '.s3.amazonaws.com/jquery/aws.png" />
	<div class="content">
	<div id="challenge-grid" class="ui-corner-all shadow ui-widget"><div class="ui-widget-header">AWS Services Categories</div>';

	foreach ($categories['Items'] as $key => $catvalue) {
        	if($tmp<>$catvalue['Category']['S']){
echo                	'<div class="ui-widget-content"><div class="ui-widget-header">'. $catvalue['Category']['S']  .'</div><div id="grid-' .   str_replace("&", "and", str_replace(" ","-", $catvalue['Category']['S'])) . '" class="drop-target ui-corner-all ui-droppable">DROP SERVICE HERE</div></div>';
                }
	        $tmp=$catvalue['Category']['S'];
	}
echo		'</div>';
        $response = $dynamodb->scan(['TableName' => 'AWS-Services']);
echo        	'<div id="challenge-tokens" class="ui-corner-all shadow ui-widget"><div class="ui-widget-header">AWS Services</div>';
	shuffle($response['Items']);
	foreach ($response['Items'] as $key => $value) {
echo		'<div class="token ui-draggable grid-' . str_replace("&", "and", str_replace(" ","-", $value['Category']['S'])) . '"><div class="icon"><img src="http://' . $bucket . '.s3.amazonaws.com/images/' . $value['Icon']['S'] . '.png" /></div><div class="name">'  . $value['Name']['S'] .  '</div><div class="acr">' . (strlen($value['Acronym']['S'])==0 ? '' :'(') .  $value['Acronym']['S'] . (strlen($value['Acronym']['S']) ==0 ? '' : ')') . '</div><div class="clear"></div></div>';
	}
 
echo		'</div><div class="clear"></div></div>';
}
echo	'</body></html>';
?>
