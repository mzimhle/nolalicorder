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
	<h2 class="content-header-title">Room</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#">{$activeAccount.account_name}</a></li>	
		<li><a href="/configuration/room/">Room</a></li>
		<li>{if isset($roomData)}<a href="/configuration/room/details.php?code={$roomData.room_code}">{$roomData.room_name} {$roomData.room_surname}</a>{else}Add a room{/if}</li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($roomData)}{$roomData.room_name} {$roomData.room_surname}{else}Add a room{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/room/details.php{if isset($roomData)}?code={$roomData.room_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="room_cipher">Code / cipher</label>
							<input type="text" id="room_cipher" name="room_cipher" class="form-control"  value="{$roomData.room_cipher}" />
							{if isset($errorArray.room_cipher)}<span class="error">{$errorArray.room_cipher}</span>{/if}					  
						</div>
					</div>				
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="room_name" class="error">Name</label>
							<input type="text" id="room_name" name="room_name" class="form-control"  value="{$roomData.room_name}" />
							{if isset($errorArray.room_name)}<span class="error">{$errorArray.room_name}</span>{/if}					  
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="campus_code" class="error">Campus</label>
							<select id="campus_code" name="campus_code" class="form-control">
								{html_options options=$campuspairs selected=$roomData.campus_code}
							</select>
							{if isset($errorArray.campus_code)}<span class="error">{$errorArray.campus_code}</span>{/if}					  
						</div>
					</div>					
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="room_location">Room location</label>
							<input type="text" id="room_location" name="room_location" class="form-control"  value="{$roomData.room_location}" />
							{if isset($errorArray.room_location)}<span class="error">{$errorArray.room_location}</span>{/if}					  
						</div>
					</div>
				</div>			
                <div class="form-group">
					<button type="submit" class="btn btn-primary">{if isset($roomData)}Update{else}Add{/if}</button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/room/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{if isset($roomData)}
				<a href="/configuration/room/details.php?code={$roomData.room_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
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
</body>
</html>