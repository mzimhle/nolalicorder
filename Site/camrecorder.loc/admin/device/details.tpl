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
</head>
<body>
{include_php file='includes/header.php'}
<div class="container">
  <div class="content">
    <div class="content-container">
	<div class="content-header">
	<h2 class="content-header-title">Device</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#">{$activeAccount.account_name}</a></li>	
		<li><a href="/">Device</a></li>
		<li>{if isset($deviceData)}<a href="/device/details.php?code={$deviceData.device_code}">{$deviceData.device_name}</a>{else}Add a device{/if}</li>		
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($deviceData)}{$deviceData.device_name} - {$deviceData.device_code}{else}Add a device{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/device/details.php{if isset($deviceData)}?code={$deviceData.device_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">	
					<div class="col-sm-12">	
						<div class="form-group">
							<label for="device_name">Name / Description</label>
							<input type="text" id="device_name" name="device_name" class="form-control" value="{$deviceData.device_name}" />
							{if isset($errorArray.device_name)}<span class="error">{$errorArray.device_name}</span>{/if}					  
						</div>
					</div>				
				</div>	
				<div class="row">
					<div class="col-sm-6">				  
						<div class="form-group">
							<label for="campus_code">Campus</label>
							<select id="campus_code" name="campus_code" class="form-control">
								{html_options options=$campusPairs}
							</select>
							<p>Please select a campus</p>				  
						</div>
					</div>
					<div class="col-sm-6">				  
						<div class="form-group">
							<label for="room_code">Room</label>
							<select id="room_code" name="room_code" class="form-control">
								<option value=""> --- Please select a campus first. --- </option>
							</select>
							<p>Please select a campus first.</p>				  
						</div>
					</div>							
				</div>					
                <div class="form-group">
					<button type="submit" class="btn btn-primary">{if isset($deviceData)}Update{else}Add{/if}</button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/device/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{if isset($deviceData)}
				<a href="/device/details.php?code={$deviceData.device_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>	
				<a href="/device/logs.php?code={$deviceData.device_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Logs
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>				
				{/if}
			</div> <!-- /.list-group -->
		</div>
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
{include_php file='includes/footer.php'}
{include_php file='includes/javascript.php'}
{literal}
<script type="text/javascript">
$(document).ready(function() {
	$("#campus_code").change(function() {
		getRooms($(this).val());
	});	
	getRooms($("#campus_code").val());
});

function getRooms(campus) {
	$.ajax({
		type: "POST",
		url: "/device/details.php{/literal}{if isset($deviceData)}?code={$deviceData.device_code}{/if}{literal}",
		data: "getRooms=1&campus="+campus,
		dataType: "html",
		success: function(data){
			$('#room_code').html(data);
		}
	});
}
</script>
{/literal}
</body>
</html>