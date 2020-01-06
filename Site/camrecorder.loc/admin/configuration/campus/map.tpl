<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Nolali - CamCorder</title>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	{include_php file='includes/css.php'}
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLb1Gh_CBS7xQMQGSYq-vYfQDB-x-arGY"
  type="text/javascript"></script>
{literal}
<script type="text/javascript">
var map;
var marker;

function mapa()
{
	var opts = {'center': new google.maps.LatLng({/literal}{$campusData.campus_map_latitude|default:"-33.9285481685662"}, {$campusData.campus_map_longitude|default:"18.42681884765625"}{literal}), 'zoom':14, 'mapTypeId': google.maps.MapTypeId.SATELLITE }
	map = new google.maps.Map(document.getElementById('mapdiv'),opts);
	
	marker = new google.maps.Marker({
		position: new google.maps.LatLng({/literal}{$campusData.campus_map_latitude|default:"-33.9285481685662"}, {$campusData.campus_map_longitude|default:"18.42681884765625"}{literal}),
		map: map
	});
	
	google.maps.event.addListener(map,'click',function(event) {
		
	//call function to create marker
		if (marker) {
			marker.setMap(null);
			marker = null;
		}
		
		document.getElementById('campus_map_latitude').value = event.latLng.lat();
		document.getElementById('campus_map_longitude').value = event.latLng.lng();
		marker = new google.maps.Marker({
			position: event.latLng,
			map: map
		});
	});
}

</script>
{/literal}	
</head>
<body onload="mapa()">
{include_php file='includes/header.php'}
<div class="container">
  <div class="content">
    <div class="content-container">
	<div class="content-header">
		<h2 class="content-header-title">Campus</h2>
		<ol class="breadcrumb">
			<li><a href="/">Home</a></li>
			<li><a href="/">Configuration</a></li>
			<li><a href="#">{$activeAccount.account_name}</a></li>	
			<li><a href="/configuration/campus/">Campus</a></li>
			<li><a href="/configuration/campus/details.php?code={$campusData.campus_code}">{$campusData.campus_name}</a></li>
			<li class="active">Map Location</li>
		</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				Campus Location
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/configuration/campus/map.php?code={$campusData.campus_code}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">			
				<p>Please select by clicking the actual location of the campus below:</p>
				<div class="form-group">
					<div id="mapdiv" style="height: 400px !important;"></div>
				</div>
				<p>Below are the selected coordinates from the map.</p>	
                <div class="form-group">
					<label for="campus_map_latitude">Latitude</label>
					<input type="text" id="campus_map_latitude" name="campus_map_latitude" class="form-control" readonly value="{$campusData.campus_map_latitude}" />
					{if isset($errorArray.campus_map_latitude)}<span class="error">{$errorArray.campus_map_latitude}</span>{else}<span class="smalltext">Latitude coordinate of the content</span>{/if}					  
                </div>
                <div class="form-group">
					<label for="campus_map_longitude">Longitude</label>
					<input type="text" id="campus_map_longitude" name="campus_map_longitude" class="form-control" readonly value="{$campusData.campus_map_longitude}" />
					{if isset($errorArray.campus_map_longitude)}<span class="error">{$errorArray.campus_map_longitude}</span>{else}<span class="smalltext">Longitude coordinate of the content</span>{/if}					  
                </div>
                <div class="form-group"><button type="submit" class="btn btn-primary">Upload and Save</button></div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->		
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/campus/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/configuration/campus/details.php?code={$campusData.campus_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/configuration/campus/map.php?code={$campusData.campus_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Map Location
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>				
			</div> <!-- /.list-group -->
		</div>		
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
{include_php file='includes/footer.php'}
{include_php file='includes/javascript.php'}
{literal}
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$('#mapdiv').css("height",$('.portlet-content').height());
	$('#mapdiv').css("width",$('.portlet-content').width());
	google.maps.event.trigger(map, 'resize');
	map.setZoom( map.getZoom() );
});
</script>
{/literal}
</html>