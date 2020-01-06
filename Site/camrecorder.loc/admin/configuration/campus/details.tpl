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
	<h2 class="content-header-title">Campus</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#">{$activeAccount.account_name}</a></li>	
		<li><a href="/configuration/campus/">Campus</a></li>
		<li>{if isset($campusData)}<a href="/configuration/campus/details.php?code={$campusData.campus_code}">{$campusData.campus_name} {$campusData.campus_surname}</a>{else}Add a campus{/if}</li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($campusData)}{$campusData.campus_name} {$campusData.campus_surname}{else}Add a campus{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/campus/details.php{if isset($campusData)}?code={$campusData.campus_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="campus_cipher">Code / cipher</label>
							<input type="text" id="campus_cipher" name="campus_cipher" class="form-control"  value="{$campusData.campus_cipher}" />
							{if isset($errorArray.campus_cipher)}<span class="error">{$errorArray.campus_cipher}</span>{/if}					  
						</div>
					</div>
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="campus_name">Name</label>
							<input type="text" id="campus_name" name="campus_name" class="form-control"  value="{$campusData.campus_name}" />
							{if isset($errorArray.campus_name)}<span class="error">{$errorArray.campus_name}</span>{/if}					  
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12">	
						<div class="form-group">
							<label for="campus_map_address">Physical Address</label>
							<input type="text" id="campus_map_address" name="campus_map_address" class="form-control"  value="{$campusData.campus_map_address}" />
							{if isset($errorArray.campus_map_address)}<span class="error">{$errorArray.campus_map_address}</span>{/if}					  
						</div>
					</div>
				</div>			
                <div class="form-group">
					<button type="submit" class="btn btn-primary">{if isset($campusData)}Update{else}Add{/if}</button>
				</div>
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
				{if isset($campusData)}
				<a href="/configuration/campus/details.php?code={$campusData.campus_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/configuration/campus/map.php?code={$campusData.campus_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Map Location
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