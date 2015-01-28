<?php
if (!is_user_logged_in())
{
	return;
}
else
{
	switch($gmb_role)
	{
		case "administrator":
			$user_role_permission = "manage_options";
		break;
		case "editor":
			$user_role_permission = "publish_pages";
		break;
		case "author":
			$user_role_permission = "publish_posts";
		break;
	}
	if (!current_user_can($user_role_permission))
	{
		return;
	}
	else
	{
		$update_polyline = wp_create_nonce("polyline_update");
		
		if(file_exists(MAP_BK_PLUGIN_DIR ."/lib/get-map-settings.php"))
		{
			include_once MAP_BK_PLUGIN_DIR ."/lib/get-map-settings.php";
		}
		?>
		<form id="frm_edit_polyline" class="layout-form" style="max-width:1000px;">
			<div class="fluid-layout">
				<div class="layout-span12">
					<div class="widget-layout">
						<div class="widget-layout-title">
							<h4>
								<?php _e("Polyline Settings", map_bank); ?>
							</h4>
						</div>
						<div class="widget-layout-body">
							<div class="fluid-layout">
								<input type="button" onclick="proceed_to_back();" id="ux_btn_action" name="ux_btn_action" class="btn btn-danger" value="<?php _e("Back to Manage Map", map_bank); ?>"/>
								<input type="submit" id="ux_btn_action" name="ux_btn_action" value= "<?php isset ($_REQUEST["pline_id"]) ? _e("Update Polyline", map_bank) : _e("Add Polyline", map_bank); ?>" class="btn btn-danger" style="float:right;"/>
							</div>
							<div class="separator-doubled" style="margin-bottom: 20px;"></div> 
							<div class="fluid-layout">
								<div class="layout-span12 responsive">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Formatted Address", map_bank); ?> :
											<span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e("Formatted Address are used for points of interest and geocodes as below, plus a slight variant is used for streets.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<input type="text" id="geocomplete" name="geocomplete" class="layout-span12" value="<?php echo stripcslashes(htmlspecialchars_decode($map_location_title));?>" placeholder="<?php _e("Enter the Location Title here", map_bank); ?>"/>
										</div>
									</div>
								</div>
							</div>
							<div class="fluid-layout" >
								<div class="layout-span6 responsive">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Polyline Color", map_bank); ?> :
											<span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e("Here you can set color for your polyline.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>	
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<input type="text" class="layout-span10" id="ux_clr_polyline_color" name="ux_clr_polyline_color" onblur="polyline_onblur();" onclick="ux_clr_font_color_label_setting();" value="<?php echo $map_polyline_color_update;?>" style="background-color:<?php echo $map_polyline_color_update;?> ;color:#fff;"/>
											<img onclick="ux_clr_font_color_label_setting();" style="vertical-align: middle;margin-left: 5px;" src="<?php echo  plugins_url("/assets/images/color.png",dirname(dirname(__FILE__))); ?>"/>
											<div id="clr_font_color"></div>
										</div>
									</div>
								</div>
								<div class="layout-span6 responsive">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Polyline Opacity", map_bank); ?> :
											<span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e("Here you can set opacity for your polyline.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<input type="text" id="ux_txt_polyline_opacity" onblur="polyline_onblur()" class="layout-span12" maxlength="4" onkeyup="this.value = minmax(this.value" onkeypress ="return OnlyDigitsDots(event);" name="ux_txt_polyline_opacity" value="<?php echo $map_polyline_opacity_update;?>"/>
										</div>
									</div>
								</div>
							</div>
							<div class="fluid-layout" >
								<div class="layout-span6 responsive">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Polyline Thickness", map_bank); ?> :
											<span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e("Here you can set Thickness for your polyline.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>	
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<input type="text" id="ux_txt_polyline_thicknes" onblur="polyline_onblur()" class="layout-span12" onkeypress ="return OnlyNumbers(event);" name="ux_txt_polyline_thicknes" value="<?php echo $map_polyline_thickness_update;?>"/>
										</div>
									</div>
								</div>
								<div class="layout-span6 responsive">
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Polyline Type", map_bank); ?> :
											<span class="error">*</span> 
											<span class="hovertip" data-original-title ="<?php _e("Here you can set Type for your polyline.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<select id="ux_ddl_polyline_type" name="ux_ddl_polyline_type" class="layout-span12" onchange="polyline_onblur();">
												<option value="0"><?php _e("Solid", map_bank); ?></option>
												<option value="1"><?php _e("Dotted", map_bank); ?></option>
												<option value="2"><?php _e("Dashed", map_bank); ?></option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="fluid-layout" >
								<div class="layout-span12 responsive">	
									<div class="layout-control-group">
										<label class="layout-control-label-location layout-control-label"><?php _e("Polyline Data", map_bank); ?> : 
											<span class="error">*</span>
											<span class="hovertip" data-original-title ="<?php _e("Polyline Data is the set of co-ordinates representing your polyline on the map.",map_bank) ;?>">
												<img class="tooltip_img" src="<?php echo MAP_BK_TOOLTIP?>"/>
											</span>
										</label>
										<div class="layout-controls-location custom-layout-controls-map-location">
											<textarea readonly="readonly" id="ux_txt_polyline_data" rows="4" name="ux_txt_polyline_data" class="layout-span12"></textarea>
										</div>
									</div>
								</div>
								<input type="hidden" id="ux_txt_latitude" class="layout-span12" name="ux_txt_latitude" value="<?php echo $map_location_latitude;?>"/>
								<input type="hidden" id="ux_txt_longitude" class="layout-span12" name="ux_txt_longitude" value="<?php echo $map_location_longitude;?>"/>
							</div>
							<div class="fluid-layout">
								<div style="min-height: 310px;">
									<?php 
										if(file_exists(MAP_BK_PLUGIN_DIR ."/views/themes.php"))
										{
											include_once MAP_BK_PLUGIN_DIR ."/views/themes.php";
										}	
									?>
									<div id="map_canvas" style="width: 100%; height: 345px; border:4px solid #e0dede; margin-top:10px;"></div>
								</div>
								<div class="fluid-layout">
									<input type="button" onclick="proceed_to_back();" id="ux_btn_action" name="ux_btn_action" class="btn btn-danger" value="<?php _e("Back to Manage Map", map_bank); ?>"/>
									<input type="submit" id="ux_btn_action" name="ux_btn_action" value= "<?php isset ($_REQUEST["pline_id"]) ? _e("Update Polyline", map_bank) : _e("Add Polyline", map_bank); ?>" class="btn btn-danger" style="float:right; margin-top: 10px;"/>
									<input type="button" id="ux_btn_clear_polyline" onclick="clearpolyline();" name="ux_btn_clear_polyline" class="btn btn-danger" value="<?php _e("Clear polyline", map_bank); ?>" style="float:right;display:block; margin-top: 10px; margin-right: 5px;"/>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>		
		</form>
		<script type="text/javascript">
	
			var line;
			var manage_polylines_array = [];
			
			jQuery(document).ready(function()
			{
				jQuery("#ux_ddl_polyline_type").val("<?php echo isset($map_polyline_type_update) ? $map_polyline_type_update : "0" ;?>");
				initialize();
				jQuery("#step_2").addClass("current");
				jQuery("#step_3").addClass("current");
				jQuery("#step_4").addClass("current");
				jQuery("#step_5").addClass("current");
				jQuery("#manage_map").addClass("nav-tab-active");
				jQuery(".hovertip").tooltip({placement: "right"});
			});
			
			jQuery("#frm_edit_polyline").validate
			({	
				rules:
				{	
					ux_clr_polyline_color:
					{ 
						required: true
					},
					ux_txt_polyline_thicknes:
					{
						required: true,
						digits:true
					},
					ux_txt_polyline_data:
					{
						required: true
					}
				},
				errorPlacement: function(error, element)
				{
					jQuery(element).css("background-color","#FFCCCC");
					jQuery(element).css("border","1px solid red");
				},
				submitHandler: function(form)
				{
					overlay();
					var map_id = "<?php echo $map_id; ?>";
					var polyline_id = "<?php echo $_REQUEST["pline_id"]; ?>";
					jQuery.post(ajaxurl, jQuery("#frm_edit_polyline").serialize() +"&polyline_id="+polyline_id+"&param=update_polyline_db&action=add_map_library&_wpnonce=<?php echo $update_polyline;?>", function()
					{
						setTimeout(function () {
							jQuery(".loader_opacity").remove();
							jQuery(".opacity_overlay").remove();
						}, 2000);
						window.location.href = "admin.php?page=manage_map&map_id="+map_id;
					});
				}
			});
			
			function overlay()
			{
				var overlay_opacity = jQuery("<div class=\"opacity_overlay\"></div>");
				jQuery("body").append(overlay_opacity);
				var overlay = jQuery("<div class=\"loader_opacity\"><div class=\"processing_overlay\"></div></div>");
				jQuery("body").append(overlay);
			}
			
			function OnlyDigitsDots(evt) 
			{
				var theEvent = evt || window.event;
				var key = theEvent.keyCode || theEvent.which;
				key = String.fromCharCode( key );
				var regex = /[0-9]|\./;
				if( !regex.test(key) ) 
				{
					theEvent.returnValue = false;
					if(theEvent.preventDefault) theEvent.preventDefault();
				}
			}
			
			function OnlyNumbers(evt) 
			{
				var charCode = (evt.which) ? evt.which : event.keyCode;
				return (charCode > 47 && charCode < 58) || charCode == 127 || charCode == 8;
			}
			
			function proceed_to_back()
			{
				overlay();
				setTimeout(function () {
					jQuery(".loader_opacity").remove();
					jQuery(".opacity_overlay").remove();
				}, 2000);
				window.location.href = "admin.php?page=manage_map";
			}
			
			function minmax(value) 
			{
				if(parseInt(value) < 0 || isNaN(value)) 
					return 0; 
				else if(value > 1) 
					return 1; 
				else return value;
			}
			
			function ux_clr_font_color_label_setting()
			{
				jQuery("#clr_font_color").farbtastic("#ux_clr_polyline_color").slideDown();
				jQuery("#ux_clr_polyline_color").focus();
			}
			jQuery("#ux_clr_polyline_color").blur(function(){jQuery("#clr_font_color").slideUp()});
			function polyline_onblur()
			{
				jQuery("#ux_txt_polyline_data").empty();
				initialize();
			}
			function error_message_close()
			{
				jQuery("#top-error").remove();
			}
			
		</script>
		<?php
		if(file_exists(MAP_BK_PLUGIN_DIR ."/views/map-preview.php"))
		{
			include_once MAP_BK_PLUGIN_DIR ."/views/map-preview.php";
		}
	}
}
?>