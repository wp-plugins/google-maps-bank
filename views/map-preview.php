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
		?>
		<style type="text/css">
		.map_canvas_class_<?php echo $map_id;?>_<?php echo $unique_id;?>
		{
			max-width: none !important;
			border:<?php echo  $border_width."px"." ".$border_style." ".$border_color; ?>;
			width:<?php echo $map_width . "". $map_width_type; ?>;
			height:<?php echo  $map_height. "". $map_height_type; ?>;
		}
		
		.scrollFix 
		{
			line-height: 1.35;
			overflow: hidden;
			word-wrap: break-word;
		}
		</style>
		<?php 
		if(isset($_REQUEST["map_themes"]))
		{
			$map_themes = $_REQUEST["map_themes"];
			$map_type_data = $_REQUEST["map_type"];
			switch($map_type_data)
			{
				case "1":
					$map_type = "ROADMAP";
					break;
				case "2":
					$map_type = "TERRAIN";
					break;
				case "3":
					$map_type = "HYBRID";
					break;
				case "4":
					$map_type = "SATELLITE";
					break;
			}
		}
		if(file_exists(MAP_BK_PLUGIN_DIR ."/views/themes.php"))
		{
			include_once MAP_BK_PLUGIN_DIR ."/views/themes.php";
		}
		?>
		<script type="text/javascript">
		
			function initialize() 
			{
				var layer;
				var polygon_color = jQuery("#ux_clr_Polygon_color").val();
				var line_color = jQuery("#ux_clr_font_color").val();
				var line_opacity = jQuery("#ux_txt_line_opacity").val();
				var polygon_opacity = jQuery("#ux_txt_polygon_opacity").val();
				var polyline_color = jQuery("#ux_clr_polyline_color").val();
				var polyline_opacity = jQuery("#ux_txt_polyline_opacity").val();
				var polyline_thicknes = jQuery("#ux_txt_polyline_thicknes").val();
				var polyline_border_type = jQuery("#ux_ddl_polyline_type").val();
				var polyline_data = jQuery("#ux_txt_polyline_data").val();

				var rdl_nearest_location = jQuery("input:radio[name=ux_rdl_nearest_location]:checked").val();
				var rdl_scale_control = jQuery("input:radio[name=ux_rdl_scale_control]:checked").val();
				var rdl_map_draggable = jQuery("input:radio[name=ux_rdl_map_draggable]:checked").val();
				var rdl_pan_control = jQuery("input:radio[name=ux_rdl_pan_control]:checked").val();
				var rdl_map_control = jQuery("input:radio[name=ux_rdl_map_control]:checked").val();
				var rdl_overview = jQuery("input:radio[name=ux_rdl_overview]:checked").val();
				var rdl_street_view = jQuery("input:radio[name=ux_rdl_street_view]:checked").val();
				
				var bounds = new google.maps.LatLngBounds();
				geocoder = new google.maps.Geocoder();
				<?php
				if(isset($_REQUEST["page"]))
				{
					switch(esc_attr($_REQUEST["page"]))
					{
						case "gmb_create_new_map": 
							?>
							var latitude = <?php echo isset($map_location_latitude) ? $map_location_latitude : "35.38453628611739";?>; 
							var longitude = <?php echo isset($map_location_longitude) ? $map_location_longitude : "-97.03259696914063";?>;
							<?php
						break;
						case "gmb_add_location":
							?>
							var latitude = jQuery("#lat").val(); 
							var longitude = jQuery("#lng").val();
							<?php
						break;
						default:
							?>
							var latitude = jQuery("#ux_txt_latitude").val();
							var longitude = jQuery("#ux_txt_longitude").val();
							<?php
						break;
						case "gmb_adv_settings":
							?>
							var latitude = <?php echo $map_location_latitude;?>;
							var longitude = <?php echo $map_location_longitude ;?>; 
							<?php
						break;
						case "gmb_edit_location":
							?>
							var latitude = jQuery("#lat").val(); 
							var longitude = jQuery("#lng").val();
							<?php
						break;
						case "gmb_edit_adv_settings":
							?>
							var latitude = <?php echo $map_location_latitude;?>;
							var longitude = <?php echo $map_location_longitude ;?>; 
							<?php
						break;
					}
				}
				if(isset($_REQUEST["page"]))
				{
					switch(esc_attr($_REQUEST["page"]))
					{
						case "gmb_adv_settings":
							?>
							var latlng = new google.maps.LatLng(latitude, longitude);
							var mapOptions = 
							{
								scrollwheel: true,
								zoomControl: true,
								zoomControlOptions: 
								{
									style: google.maps.ZoomControlStyle.LARGE
								},
								zoom: 12,
								center: latlng,
								styles : <?php echo isset($map_themes) ? $map_themes:"default1" ;?>,
								mapTypeId: google.maps.MapTypeId.<?php echo isset($map_type) ? $map_type : "ROADMAP";?>,
								streetViewControl:(rdl_street_view == 1) ? true : false,
								overviewMapControl:(rdl_overview == 1) ? true : false,
								panControl: (rdl_pan_control == 1) ? true : false,
								draggable: (rdl_map_draggable == 1) ? true : false,
								scaleControl: (rdl_scale_control == 1) ? true : false,
								visible:true,
								mapTypeControl:(rdl_map_control == 1) ? true : false
							}
							map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
						<?php
						break;
						case "gmb_edit_adv_settings":
							?>
							var latlng = new google.maps.LatLng(latitude, longitude);
							var mapOptions = 
							{
								scrollwheel: true,
								zoomControl: true,
								zoomControlOptions: 
								{
									style: google.maps.ZoomControlStyle.LARGE
								},
								zoom: 12,
								center: latlng,
								styles : <?php echo isset($map_themes) ? $map_themes:"default1" ;?>,
								mapTypeId: google.maps.MapTypeId.<?php echo isset($map_type) ? $map_type : "ROADMAP";?>,
								streetViewControl:(rdl_street_view == 1) ? true : false,
								overviewMapControl:(rdl_overview == 1) ? true : false,
								panControl: (rdl_pan_control == 1) ? true : false,
								draggable: (rdl_map_draggable == 1) ? true : false,
								scaleControl: (rdl_scale_control == 1) ? true : false,
								visible:true,
								mapTypeControl:(rdl_map_control == 1) ? true : false
							}
							map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
						<?php
						break;
						default:
						?>
							var latlng = new google.maps.LatLng(latitude, longitude);
							var mapOptions = 
							{
								scrollwheel: true,
								zoomControl: true,
								zoomControlOptions: 
								{
									style: google.maps.ZoomControlStyle.LARGE
								},
								zoom: 12,
								center: latlng,
								styles : <?php echo isset($map_themes) ? $map_themes:"default1" ;?>,
								mapTypeId: google.maps.MapTypeId.<?php echo isset($map_type) ? $map_type : "ROADMAP";?>,
								overviewMapControl:true,
								panControl: true,
								draggable: true,
								scaleControl: true,
								visible:true,
								mapTypeControl:true,
								streetViewControl:false
							}
							map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
						<?php
						break;
					}
				}
				if(isset($_REQUEST["page"]))
				{
					switch(esc_attr($_REQUEST["page"]))
					{
						case "gmb_create_new_map":
							if(isset($marker) && empty($marker))
							{
							?>
								marker = new google.maps.Marker(
								{
									position: latlng,
									map: map,
									draggable:false,
									animation: google.maps.Animation.DROP,
								});
							<?php
							}
						break;
						case "gmb_add_location":
							?>
							marker_location = new google.maps.Marker(
							{
								position: latlng,
								map: map,
								draggable:true,
								animation: google.maps.Animation.DROP,
							});
							
							var input = document.getElementById("geocomplete");
							var autocomplete = new google.maps.places.Autocomplete(input, 
							{
								types: ["geocode"]
							});
							autocomplete.bindTo("bounds", map);
							google.maps.event.addListener(autocomplete, "place_changed", function (event)
							{
								var place = autocomplete.getPlace();
								if (place.geometry.viewport) 
								{
									map.fitBounds(place.geometry.viewport);
								}
								else
								{
									map.setCenter(place.geometry.location);
								}
								moveMarker(place.name, place.geometry.location);
								geocodePosition(marker_location.getPosition());
								jQuery("#lat").val(place.geometry.location.lat());
								jQuery("#lng").val(place.geometry.location.lng());
							});
							google.maps.event.addListener(marker_location, "dragend", function(event) 
							{
								geocodePosition(marker_location.getPosition());
								map.panTo(marker_location.getPosition());
								jQuery("#lat").val(event.latLng.lat());
								jQuery("#lng").val(event.latLng.lng());
							});
							function moveMarker(placeName, latlng)
							{
								marker_location.setPosition(latlng);
							}
							<?php
						break;
						case "gmb_add_marker":
							?>
							var input = document.getElementById("geocomplete_marker");
							var autocomplete = new google.maps.places.Autocomplete(input,
							{
								types: ["geocode"]
							});
							autocomplete.bindTo("bounds", map);
							google.maps.event.addListener(autocomplete, "place_changed", function (event)
							{
								var place = autocomplete.getPlace();
								if (place.geometry.viewport)
								{
									map.fitBounds(place.geometry.viewport);
								}
								else
								{
									map.setCenter(place.geometry.location);
								}
								jQuery("#ux_txt_latitude").val(place.geometry.location.lat());
							
								jQuery("#ux_txt_longitude").val(place.geometry.location.lng());
							});
							function moveMarker(placeName, latlng)
							{
								marker.setPosition(latlng);
							}
							<?php
						break;
						case "gmb_add_polygon":
							?>
							var input = document.getElementById("geocomplete");
							var autocomplete = new google.maps.places.Autocomplete(input,
							{
								types: ["geocode"]
							});
							autocomplete.bindTo("bounds", map);
							var infowindow = new google.maps.InfoWindow();
							google.maps.event.addListener(autocomplete, "place_changed", function (event)
							{
								infowindow.close();
								var place = autocomplete.getPlace();
								if (place.geometry.viewport)
								{
									map.fitBounds(place.geometry.viewport);
								}
								else
								{
									map.setCenter(place.geometry.location);
								}
								jQuery("#ux_txt_latitude").val(place.geometry.location.lat());
							
								jQuery("#ux_txt_longitude").val(place.geometry.location.lng());
							});
							<?php
							if(!isset($_REQUEST["pgon_id"]))
							{
								?>
								shape_new = new google.maps.Polygon({
									draggable: true,
									strokeColor: line_color,
									strokeOpacity: line_opacity,
									fillColor: polygon_color,
									fillOpacity: polygon_opacity
								});
								shape_new.setMap(map);
								google.maps.event.addListener(map, "click", addPoint);
								google.maps.event.addListener(shape_new, "dragend", getPolygonCoords);
								google.maps.event.addListener(shape_new.getPath(), "insert_at", getPolygonCoords);
								google.maps.event.addListener(shape_new.getPath(), "set_at", getPolygonCoords);
							<?php
							}
						break;
						case "gmb_add_polyline":
							?>
							var input = document.getElementById("geocomplete");
							var autocomplete = new google.maps.places.Autocomplete(input,
							{
								types: ["geocode"]
							});
							autocomplete.bindTo("bounds", map);
							var infowindow = new google.maps.InfoWindow();
							google.maps.event.addListener(autocomplete, "place_changed", function (event)
							{
								infowindow.close();
								var place = autocomplete.getPlace();
								if (place.geometry.viewport)
								{
									map.fitBounds(place.geometry.viewport);
								}
								else
								{
									map.setCenter(place.geometry.location);
								}
								jQuery("#ux_txt_latitude").val(place.geometry.location.lat());
							
								jQuery("#ux_txt_longitude").val(place.geometry.location.lng());
							});
							<?php
							if(!isset($_REQUEST["pline_id"]))
							{
								?>
									if(polyline_border_type == 2)
									{
										var lineSymbol = 
										{
											path: "M 0,-1 0,1 0,-1",
											strokeOpacity: polyline_opacity,
											strokeColor: polyline_color,
											strokeWeight: polyline_thicknes
										};
										line = new google.maps.Polyline(
										{
											draggable:true,
											strokeOpacity: 0,
											icons: [{
												icon: lineSymbol,
												offset: "0",
												repeat: "20px"
											}]
										});
										line.setMap(map);
									}
									else if(polyline_border_type == 1)
									{
										var lineSymbol = 
										{
											path: "M 0,-0.1 0,0.1",
											strokeOpacity: polyline_opacity,
											strokeColor: polyline_color,
											strokeWeight: polyline_thicknes
										};
										line = new google.maps.Polyline(
										{
											draggable:true,
											strokeOpacity: 0,
											icons: [{
											icon: lineSymbol,
											offset: "0",
											repeat: "10px"
											}]
										});
										line.setMap(map);
									}
									else
									{
										line = new google.maps.Polyline(
										{
											draggable:true,
											strokeOpacity: polyline_opacity,
											strokeColor: polyline_color,
											strokeWeight: polyline_thicknes
										});
										line.setMap(map);
									}
								google.maps.event.addListener(map, "click", addNewPoint);
								google.maps.event.addListener(line, "dragend", getPolylineCoords);
								google.maps.event.addListener(line.getPath(), "insert_at", getPolylineCoords);
								google.maps.event.addListener(line.getPath(), "set_at", getPolylineCoords);
							<?php
							}
						break;
						case "gmb_edit_location":
							?>
							marker_location = new google.maps.Marker(
							{
								position: latlng,
								map: map,
								draggable:true,
								animation: google.maps.Animation.DROP,
							});
							var input = document.getElementById("geocomplete");
							var autocomplete = new google.maps.places.Autocomplete(input, 
							{
								types: ["geocode"]
							});
							autocomplete.bindTo("bounds", map);
							google.maps.event.addListener(autocomplete, "place_changed", function (event)
							{
								
								var place = autocomplete.getPlace();
								if (place.geometry.viewport) 
								{
									map.fitBounds(place.geometry.viewport);
								}
								else
								{
									map.setCenter(place.geometry.location);
								}
								moveMarker(place.name, place.geometry.location);
								geocodePosition(marker_location.getPosition());
								jQuery("#lat").val(place.geometry.location.lat());
								jQuery("#lng").val(place.geometry.location.lng());
							});
							google.maps.event.addListener(marker_location, "dragend", function(event) 
							{
								geocodePosition(marker_location.getPosition());
								map.panTo(marker_location.getPosition());
								jQuery("#lat").val(event.latLng.lat());
								jQuery("#lng").val(event.latLng.lng());
							});
							function moveMarker(placeName, latlng)
							{
								marker_location.setPosition(latlng);
							}
							<?php
						break;
						case"gmb_edit_marker":
							if(isset($_REQUEST["mid"]))
							{
								?>
								var position = new google.maps.LatLng("<?php echo $map_location_latitude;?>", "<?php echo $map_location_longitude;?>");
								bounds.extend(position);
								marker = new google.maps.Marker(
								{
									position: position,
									map: map,
									draggable: true,
									title: "",
									animation: google.maps.Animation.<?php echo $map_marker_animation_update == 1 ? "BOUNCE" : "DROP";?>,
									icon : "<?php echo $map_marker_icon_update;?>"
								});
								marker_count = 0;
								jQuery("#ux_btn_clear_marker").css("display","block");
								google.maps.event.addListener(marker, "dragend", function(event) 
								{
									geocodePosition(marker.getPosition());
									map.panTo(marker.getPosition());
									jQuery("#ux_txt_latitude").val(event.latLng.lat());
									jQuery("#ux_txt_longitude").val(event.latLng.lng());
								});
								<?php
							}
							?>
							var input = document.getElementById("geocomplete_marker");
							var autocomplete = new google.maps.places.Autocomplete(input,
							{
								types: ["geocode"]
							});
							autocomplete.bindTo("bounds", map);
							google.maps.event.addListener(autocomplete, "place_changed", function (event)
							{
								var place = autocomplete.getPlace();
								if (place.geometry.viewport)
								{
									map.fitBounds(place.geometry.viewport);
								}
								else
								{
									map.setCenter(place.geometry.location);
								}
								jQuery("#ux_txt_latitude").val(place.geometry.location.lat());
							
								jQuery("#ux_txt_longitude").val(place.geometry.location.lng());
							});
							function moveMarker(placeName, latlng)
							{
								marker.setPosition(latlng);
							}
							<?php
						break;
						case "gmb_edit_polygon":
							if(isset($_REQUEST["pgon_id"]))
							{
								?>
								var polygon_cord_exist = [
								<?php
									$polygon_data_lat = explode("\n", $map_polygon_data_update);
									for($polygon_lat = 0; $polygon_lat < count($polygon_data_lat)-1; $polygon_lat++)
									{
										if($polygon_lat == count($polygon_data_lat) - 2)
										{
											?>
												new google.maps.LatLng(<?php echo $polygon_data_lat[$polygon_lat];?>)
											<?php
										}
										else
										{
											?>
												new google.maps.LatLng(<?php echo $polygon_data_lat[$polygon_lat];?>),
											<?php
										}
									}
								?>
								];
								shape_new = new google.maps.Polygon({
									paths: polygon_cord_exist,
									draggable: true,
									strokeColor: "<?php echo $map_polygon_line_color_update ;?>",
									strokeOpacity: <?php echo $map_polygon_line_opacity_update ;?>,
									fillColor: "<?php echo $map_polygon_color_update ;?>",
									fillOpacity: <?php echo $map_polygon_opacity_update ;?>
								});
								shape_new.setMap(map);
								google.maps.event.addListener(map, "click", addPoint);
								google.maps.event.addListener(shape_new, "dragend", getPolygonCoords);
								google.maps.event.addListener(shape_new.getPath(), "insert_at", getPolygonCoords);
								google.maps.event.addListener(shape_new.getPath(), "set_at", getPolygonCoords);
							<?php
							}
							if(!isset($_REQUEST["pgon_id"]))
							{
								?>
								shape_new = new google.maps.Polygon({
									draggable: true,
									strokeColor: line_color,
									strokeOpacity: line_opacity,
									fillColor: polygon_color,
									fillOpacity: polygon_opacity
								});
								shape_new.setMap(map);
								google.maps.event.addListener(map, "click", addPoint);
								google.maps.event.addListener(shape_new, "dragend", getPolygonCoords);
								google.maps.event.addListener(shape_new.getPath(), "insert_at", getPolygonCoords);
								google.maps.event.addListener(shape_new.getPath(), "set_at", getPolygonCoords);
							<?php
							}
						break;
						case "gmb_edit_polyline":
							?>
							var input = document.getElementById("geocomplete");
							var autocomplete = new google.maps.places.Autocomplete(input,
							{
								types: ["geocode"]
							});
							autocomplete.bindTo("bounds", map);
							var infowindow = new google.maps.InfoWindow();
							google.maps.event.addListener(autocomplete, "place_changed", function (event)
							{
								infowindow.close();
								var place = autocomplete.getPlace();
								if (place.geometry.viewport)
								{
									map.fitBounds(place.geometry.viewport);
								}
								else
								{
									map.setCenter(place.geometry.location);
								}
								jQuery("#ux_txt_latitude").val(place.geometry.location.lat());
							
								jQuery("#ux_txt_longitude").val(place.geometry.location.lng());
							});
							<?php
							if(isset($_REQUEST["pline_id"]))
							{
								?>
								var polyline_cords = [
								<?php
								$polyline_data_lat = explode("\n", $map_polyline_data_update);
								for($flag1=0;$flag1<count($polyline_data_lat)-1;$flag1++)
								{
									if($flag1 == count($polyline_data_lat) - 2)
									{
										?>
											new google.maps.LatLng(<?php echo $polyline_data_lat[$flag1];?>)
										<?php
									}
									else
									{
										?>
											new google.maps.LatLng(<?php echo $polyline_data_lat[$flag1];?>),
										<?php
									}
								}
								?>
								];
								<?php
								switch($map_polyline_type_update)
								{
									case "2":
										?>
										var lineSymbol = 
										{
											path: "M 0,-1 0,1 0,-1",
											strokeOpacity: <?php echo $map_polyline_opacity_update;?>,
											strokeColor: "<?php echo $map_polyline_color_update ;?>",
											strokeWeight: <?php echo $map_polyline_thickness_update ;?>
										};
										line = new google.maps.Polyline(
										{
											path: polyline_cords,
											draggable:true,
											strokeOpacity: 0,
											icons: [{
											icon: lineSymbol,
											offset: "0",
											repeat: "20px"
											}]
										});
										line.setMap(map);
										google.maps.event.addListener(map, "click", addNewPoint);
										google.maps.event.addListener(line, "dragend", getPolylineCoords);
										google.maps.event.addListener(line.getPath(), "insert_at", getPolylineCoords);
										google.maps.event.addListener(line.getPath(), "set_at", getPolylineCoords);
										<?php 
									break;
									case "1":
										?>
										var lineSymbol = 
										{
											path: "M 0,-0.1 0,0.1",
											strokeOpacity: <?php echo $map_polyline_opacity_update;?>,
											strokeColor: "<?php echo $map_polyline_color_update;?>",
											strokeWeight: <?php echo $map_polyline_thickness_update;?>
										};
										line = new google.maps.Polyline(
										{
											path: polyline_cords,
											draggable:true,
											strokeOpacity: 0,
											icons: [{
											icon: lineSymbol,
											offset: "0",
											repeat: "20px"
											}]
										});
										line.setMap(map);
										google.maps.event.addListener(map, "click", addNewPoint);
										google.maps.event.addListener(line, "dragend", getPolylineCoords);
										google.maps.event.addListener(line.getPath(), "insert_at", getPolylineCoords);
										google.maps.event.addListener(line.getPath(), "set_at", getPolylineCoords);
										<?php
									break;
									default:
										?>
										line = new google.maps.Polyline(
										{
											path: polyline_cords,
											draggable:true,
											strokeOpacity: <?php echo $map_polyline_opacity_update;?>,
											strokeColor: "<?php echo $map_polyline_color_update;?>",
											strokeWeight: <?php echo $map_polyline_thickness_update;?>
										});
										line.setMap(map);
										google.maps.event.addListener(map, "click", addNewPoint);
										google.maps.event.addListener(line, "dragend", getPolylineCoords);
										google.maps.event.addListener(line.getPath(), "insert_at", getPolylineCoords);
										google.maps.event.addListener(line.getPath(), "set_at", getPolylineCoords);
										<?php
									break;
								}
							}
							if(!isset($_REQUEST["pline_id"]))
							{
								?>
								if(polyline_border_type == 2)
								{
									var lineSymbol = 
									{
										path: "M 0,-1 0,1 0,-1",
										strokeOpacity: polyline_opacity,
										strokeColor: polyline_color,
										strokeWeight: polyline_thicknes
									};
									line = new google.maps.Polyline(
									{
										draggable:true,
										strokeOpacity: 0,
										icons: [{
											icon: lineSymbol,
											offset: "0",
											repeat: "20px"
										}]
									});
									line.setMap(map);
								}
								else if(polyline_border_type == 1)
								{
									var lineSymbol = 
									{
										path: "M 0,-0.1 0,0.1",
										strokeOpacity: polyline_opacity,
										strokeColor: polyline_color,
										strokeWeight: polyline_thicknes
									};
									line = new google.maps.Polyline(
									{
										draggable:true,
										strokeOpacity: 0,
										icons: [{
										icon: lineSymbol,
										offset: "0",
										repeat: "10px"
										}]
									});
									line.setMap(map);
								}
								else if(polyline_border_type == 0)
								{
									line = new google.maps.Polyline(
									{
										draggable:true,
										strokeOpacity: polyline_opacity,
										strokeColor: polyline_color,
										strokeWeight: polyline_thicknes
									});
									line.setMap(map);
								}
								google.maps.event.addListener(map, "click", addNewPoint);
								google.maps.event.addListener(line, "dragend", getPolylineCoords);
								google.maps.event.addListener(line.getPath(), "insert_at", getPolylineCoords);
								google.maps.event.addListener(line.getPath(), "set_at", getPolylineCoords);
							<?php
							}
						break;
					}
				}
				if(!isset($_REQUEST["mid"]) && isset($marker))
				{
					foreach ($marker as $marker_key )
					{
						?>
						var latlng = new google.maps.LatLng("<?php echo $marker_key["marker_latitude"];?>", "<?php echo $marker_key["marker_longitude"];?>");
						bounds.extend(latlng);
						marker<?php echo $marker_key["id"];?> = new google.maps.Marker(
						{
							position: latlng,
							map: map,
							title: "<?php echo $marker_key["marker_name"];?>",
							animation: google.maps.Animation.<?php echo $marker_key["animation"] == 1 ? "BOUNCE" : "DROP";?>,
							icon : "<?php echo $marker_key["map_marker"];?>",
							id: "marker_10"
						});
						<?php
					}
				}
				if(!isset($_REQUEST["pgon_id"]) && isset($polygon))
				{
					foreach ($polygon as $polygon_key )
					{
						?>
						var polygon_cords = [
						<?php
							$polygon_data_latlng = explode("\n", $polygon_key["polygon_data"]);
							for($polygon_latlng = 0; $polygon_latlng < count($polygon_data_latlng)-1; $polygon_latlng++)
							{
								if($polygon_latlng == count($polygon_data_latlng) - 2)
								{
									?>
										new google.maps.LatLng(<?php echo $polygon_data_latlng[$polygon_latlng];?>)
									<?php
								}
								else
								{
									?>
										new google.maps.LatLng(<?php echo $polygon_data_latlng[$polygon_latlng];?>),
									<?php
								}
							}
						?>
						];
						shape = new google.maps.Polygon({
							paths: polygon_cords,
							strokeColor: "<?php echo $polygon_key["polygon_line_color"];?>",
							strokeOpacity: <?php echo $polygon_key["polygon_line_opacity"];?>,
							fillColor: "<?php echo $polygon_key["polygon_color"];?>",
							fillOpacity: <?php echo $polygon_key["polygon_opacity"];?>
						});
						shape.setMap(map);
						<?php
					}
				}
				if(!isset($_REQUEST["pline_id"]) && isset($polyline))
				{
					foreach ($polyline as $polyline_key )
					{
						?>
						var polyline_cords = [
						<?php
						$polyline_data_lat = explode("\n", $polyline_key["polyline_data"]);
						for($flag1 = 0;$flag1<count($polyline_data_lat)-1;$flag1++)
						{
							if($flag1 == count($polyline_data_lat) - 2)
							{
								?>
								new google.maps.LatLng(<?php echo $polyline_data_lat[$flag1];?>)
								<?php
							}
							else
							{
								?>
								new google.maps.LatLng(<?php echo $polyline_data_lat[$flag1];?>),
								<?php
							}
						}
						?>
						];
						<?php 
						switch($polyline_key["polyline_type"])
						{
							case "2":
							?>
								var lineSymbol = {
									path: "M 0,-1 0,1 0,-1",
									strokeWeight: <?php echo $polyline_key["polyline_thickness"];?>,
									strokeOpacity: <?php echo $polyline_key["polyline_opacity"];?>,
									scale: 3
								};
								var polyline_draw = new google.maps.Polyline({
									path: polyline_cords,
									strokeColor: "<?php echo $polyline_key["polyline_color"];?>",
									strokeOpacity: 0,
									icons: [{
										icon: lineSymbol,
										offset: "0",
										repeat: "20px"}],
										map: map
									});
									polyline_draw.setMap(map);
							<?php
							break;
							case "1":
								?>
								var lineSymbol = {
									path: "M 0,-0.1 0,0.1",
									strokeWeight: <?php echo $polyline_key["polyline_thickness"];?>,
									strokeOpacity: <?php echo $polyline_key["polyline_opacity"];?>,
									scale: 3
									};
								var polyline_draw = new google.maps.Polyline({
									path: polyline_cords,
									strokeColor: "<?php echo $polyline_key["polyline_color"];?>",
									strokeOpacity: 0,
									icons: [{
										icon: lineSymbol,
										offset: "100%",
										repeat: "10px"}],
										map: map
									});
									polyline_draw.setMap(map);
									<?php
							break;
							default:
								?>
								var polyline_draw = new google.maps.Polyline({
									path: polyline_cords,
									strokeColor: "<?php echo $polyline_key["polyline_color"];?>",
									strokeOpacity: <?php echo $polyline_key["polyline_opacity"];?>,
									map: map
									});
									polyline_draw.setMap(map);
									<?php
							break;
						}
					}
				}
				?>
			}
			
			function codeLatLng() 
			{
				var latitude = jQuery("#lat").val(); 
				var longitude= jQuery("#lng").val(); 
				var latlng = new google.maps.LatLng(latitude, longitude);
				geocoder.geocode({"latLng": latlng}, function(results, status)
				{
					if (status == google.maps.GeocoderStatus.OK) 
					{
						if (results[1])
						{
							var mapOptions = 
							{
								scrollwheel: true,
								zoom: 12,
								styles : map_theme,
								center: latlng,
								mapTypeId: google.maps.MapTypeId.<?php echo isset($map_type) ? $map_type : "ROADMAP";?>
							}
							map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
							marker_location = new google.maps.Marker({
							position: latlng,
							draggable:true,
							map: map
							});
							google.maps.event.addListener(marker_location, "dragend", function(event) 
							{
								geocodePosition(marker_location.getPosition());
								map.panTo(marker_location.getPosition());
								jQuery("#lat").val(event.latLng.lat());
								jQuery("#lng").val(event.latLng.lng());
							});	
							infowindow.setContent(results[1].formatted_address);
							jQuery("#geocomplete").val(results[1].formatted_address);
							infowindow.open(map, marker_location);
							infowindow.close();
							var str = results[1].formatted_address;
							var res = str.split(",");
							var count_len = res.length - 1;
							for(i = count_len;i<res.length;i++)
							{
								jQuery("#country").val(res[i]);
							}
						}
						else
						{
							alert("No results found");
						}
					}
					else 
					{
						alert("Geocoder failed due to: " + status);
					}
				});
			}
			
			function geocodePosition(pos)
			{
				geocoder = new google.maps.Geocoder();
				geocoder.geocode({
				latLng: pos
				},
				function(responses)
				{
					if (responses && responses.length > 0)
					{
						var str = responses[0].formatted_address;
						var res = str.split(",");
						var count_len = res.length - 1;
						for(i = count_len;i<res.length;i++)
						{
							jQuery("#country").val(res[i]);
						}
						jQuery("#geocomplete").val(responses[0].formatted_address);
					}
					else
					{
						var error_message = jQuery("<div id=\"top-error\" class=\"top-right top-error\" style=\"display: block;\"><div class=\"top-error-notification\"></div><div class=\"top-error-notification ui-corner-all growl-top-error\" ><div onclick=\"error_message_close();\" id=\"close-top-error\" class=\"top-error-close\">x</div><div class=\"top-error-header\"><?php _e("Error!",  map_bank); ?></div><div class=\"top-error-top-error\"><?php _e("Cannot determine Country at this location.", map_bank); ?></div></div></div>");
						jQuery("body").append(error_message);
					}
				});
			}
			
			function marker_img_select(img_id)
			{
				if(marker_count == 0 && jQuery("#geocomplete_marker").val() != "")
				{
					jQuery("#ux_btn_clear_marker").css("display","block");
					var image = jQuery("#img_"+img_id).attr("src");
					jQuery(".selected_marker").removeAttr("style");
					jQuery("#marker_icon_image").val(image);
					images = jQuery("#img_"+img_id).attr("style","background-color: rgb(248, 248, 9);border: 1px solid rgb(245, 20, 20);");
						var latitude1 = jQuery("#ux_txt_latitude").val(); 
						var longitude1= jQuery("#ux_txt_longitude").val();
						latlng1 = new google.maps.LatLng(latitude1, longitude1);
						marker = new google.maps.Marker(
						{
							position: latlng1,
							map: map,
							draggable: true,
							animation: google.maps.Animation.DROP,
							icon : image,
							id: "marker_"+marker_count
						});
					marker_count++;
					google.maps.event.addListener(marker, "dragend", function(event) 
					{
						geocodePosition(marker.getPosition());
						map.panTo(marker.getPosition());
						jQuery("#ux_txt_latitude").val(event.latLng.lat());
						jQuery("#ux_txt_longitude").val(event.latLng.lng());
					});	
					jQuery("#ux_txt_latitude").val(event.latLng.lat());
					jQuery("#ux_txt_longitude").val(event.latLng.lng());
				}
				else if(marker_count != 0 && jQuery("#geocomplete_marker").val() != "")
				{
					clearMarkers();
					marker_count = 0;
					marker_img_select(img_id)
					marker_count == 1;
				}
			}
			
			function clearMarkers() 
			{
				marker_count = 0;
				removeMarker(marker, "marker_1");
			}
			
			var removeMarker = function(marker, markerId)
			{
				marker.setMap(null); // set markers setMap to null to remove it from map
				delete marker[markerId]; // delete marker instance from markers object
			}
			
			function addPoint(e) 
			{
				var vertices = shape_new.getPath();
				vertices.push(e.latLng);
			}
			
			function getPolygonCoords() 
			{
				var len = shape_new.getPath().getLength();
				var htmlStr = "";
				for (var i = 0; i < len; i++) 
				{
					htmlStr += shape_new.getPath().getAt(i).toUrlValue(5) + "\n";
				}
				jQuery("#ux_txt_polygon_data").val(htmlStr);
			}
			
			function clearpolygon() 
			{
				shape_new.setMap(null);
				initialize();
				jQuery("#ux_txt_polygon_data").val("");
			}
			function addNewPoint(e)
			{
				var path = line.getPath();
				path.push(e.latLng);
			}
			
			function clearpolyline() 
			{
				line.setMap(null);
				initialize();
				jQuery("#ux_txt_polyline_data").val("");
			}
			
			function getPolylineCoords() 
			{
				var len = line.getPath().getLength();
				var htmlStr = "";
				for (var i = 0; i < len; i++) 
				{
					htmlStr += line.getPath().getAt(i).toUrlValue(5) +"\n";
				}
				jQuery("#ux_txt_polyline_data").val(htmlStr);
			}
		</script>
	<?php
	}
}
?>