<?php
/**
 * template to be loaded as thickbox to crop image
 */

 ?>
 

<style>
#image-wrapper{
	min-width: 400px;
margin: 0 auto;
text-align: center;
}

#canvas-header, #canvas-footer{
	/*height: 30px;*/
	padding: 12px;
	background: #999;
	color: #fff;
}

#cropping-ratios-bar{
	/*float: left;
	width: 5%;*/
}
#cropping-ratios-bar ul{
	
}
ul#ratios-list, ul#ratios-list li {
  margin: 0;
  padding: 0;
}
ul#ratios-list {
  list-style-type: none;
}
#cropping-ratios-bar ul li{
	text-align: center;
border: 1px solid;
cursor: pointer;
padding: 5px;
float: left;
margin: 5px;
}

#crop-area{
	/*float: right;
	width: 95%;*/
	margin: 0 auto;
	text-aling:center;
}

.selected-ratio{
	background: #FF7700;
	color: #000;
}

#cropp-info{
	width: 75%;
margin: 0 auto;
text-align: center;
}
#cropp-info li{
	text-align: center;
border: 1px solid;
cursor: pointer;
padding: 5px;
margin: 0 auto;
display: inline;
}
</style>
 <div id="image-wrapper">
 	
 	<div id="canvas-header">
 	<div id="cropping-ratios-bar">
 		<ul id="ratios-list">
 			<?php
 			if($ratio){
 				foreach($ratio as $r){
 					echo '<li data-cw="'.$r->width.'" data-ch="'.$r->height.'">'.$r->label.'</li>';
 				}
 			}
 			?>
 		</ul>
    <div style="clear:both;"></div>
 	</div>
 	</div>
 	
 	<div id="crop-area">
	 	<img id="ppom-jcrop-img" src="<?php echo $image_url?>" />
	 	<input type="hidden" value="<?php echo $fileid;?>" id="fileid" />
	 	<input type="hidden" value="<?php echo $image_id;?>" id="image_id" />
	 	<input type="hidden" value="<?php echo $data_name;?>" id="data_name" name="data_name" />
	 	
	 	<p><a javascript=":;" class="button button-primary" id="btn_cropp"><?php _e('Finish cropping', 'ppom');?></a></p>
	 </div>
	 
	 <div id="canvas-footer">
	 	<ul id="cropp-info">
	 		<li id="c-x"></li>
	 		<li id="c-y"></li>
	 		<li id="c-x2"></li>
	 		<li id="c-y2"></li>
	 		<li id="c-w"></li>
	 		<li id="c-h"></li>
	 	</ul>
	 </div>
 	
 	
 </div>
 
 <script type="text/javascript">
 <!--
  jQuery(function($){

	var jcropapi;
    // How easy is this??
    var coords = '';
    $("#ratios-list li:first").addClass("selected-ratio");
    var default_ratio = ppom_get_jcrop_ratio();
    // $('#ppom-jcrop-img').Jcrop();

    $('#ppom-jcrop-img').Jcrop({
    		aspectRatio: 3/4,
    	 	onSelect: showCoords,
          	onChange: showCoords,
          	setSelect:   [ ($('#ppom-jcrop-img').width() / 2) - 70, 
                      ($('#ppom-jcrop-img').height() / 2) - 70, 
                      ($('#ppom-jcrop-img').width() / 2) + 70, 
                      ($('#ppom-jcrop-img').height() / 2) + 70
                     ],
          }, function(){
          	jcropapi = this;
          	$(".jcrop-holder").css("margin", "0 auto");
          });
          
    
    $("#ratios-list li").on("click", function(){
    	
    	$("#ratios-list li").removeClass("selected-ratio");
    	$(this).addClass("selected-ratio");
    	jcropapi.setOptions({aspectRatio:eval( ppom_get_jcrop_ratio() )} );
    	
    });
    
    function ppom_get_jcrop_ratio() {
    	
    	var r = $("#ratios-list li.selected-ratio").attr('data-cw')+"/"+$("#ratios-list li.selected-ratio").attr('data-ch');
    	return r;
    }
          
  	function showCoords(c){
  		coords = c;
  		
  		$("#c-x").text('x: '+parseInt(c.x));
  		$("#c-y").text('y: '+parseInt(c.y));
  		$("#c-x2").text('x2: '+parseInt(c.x2));
  		$("#c-y2").text('y2: '+parseInt(c.y2));
  		$("#c-w").text('w: '+parseInt(c.w));
  		$("#c-h").text('h: '+parseInt(c.h));
  	}
  	
  	$("#btn_cropp").on('click', function(e){
  		e.preventDefault();
  		//console.log(coords);
  		var file_id = $("#fileid").val();
  		var image_id = $("#image_id").val();
  		var data = {coords: coords, image_name:'<?php echo $image_name;?>', 
  					action:'ppom_crop_image', fileid: file_id,
  					img_w: $(".jcrop-holder").find('img:last').width(),
  					img_h: $(".jcrop-holder").find('img:last').height(),
  					data_name: $("#data_name").val(),
  					ratio: $("#ratios-list li:first").text(),
  					product_id: $('#ppom_product_id').val(),
  					ppom_nonce: ppom_input_vars.ppom_validate_nonce,
  		}
  		$.post(ppom_input_vars.ajaxurl, data, function(resp){
  			
  			
  			if( resp.status != undefined && resp.status == 'error' ){
  				alert( resp.message );
  				window.location.reload();
  			}
  			
  			//console.log(resp);
  			document.getElementById(image_id).src = resp.cropped_image;
  			document.getElementById(image_id).className = "cropped-thumb";
  			window.parent.tb_remove();
  			
  			var fileCheck = jQuery('<input checked="checked" name="ppom[fields]['+resp.data_name+']['+file_id+'][cropped]" type="checkbox"/>')
    				                .val(resp.cropped_image)
    				                .css('display','none')
    				                .appendTo($filelist_DIV[resp.data_name]);
  			
  			
  		}, 'json');
  	});

  });
//-->
</script>