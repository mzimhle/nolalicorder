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
	<h2 class="content-header-title">Students</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">{$activeAccount.account_name}</a></li>
		<li><a href="/participant/">Students</a></li>
		<li>{if isset($participantData)}<a href="/participant/details.php?code={$participantData.participant_code}">{$participantData.participant_name} {$participantData.participant_surname}</a>{else}Add a participant{/if}</li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($participantData)}{$participantData.participant_name} {$participantData.participant_surname}{else}Add a participant{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/participant/details.php{if isset($participantData)}?code={$participantData.participant_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
                <div class="form-group">
					<label for="participant_name">Name</label>
					<input type="text" id="participant_name" name="participant_name" class="form-control"  value="{$participantData.participant_name}" />
					{if isset($errorArray.participant_name)}<span class="error">{$errorArray.participant_name}</span>{/if}					  
                </div>
                <div class="form-group">
					<label for="participant_email">Email</label>
					<input type="text" id="participant_email" name="participant_email" class="form-control"  value="{$participantData.participant_email}" />
					{if isset($errorArray.participant_email)}<span class="error">{$errorArray.participant_email}</span>{/if}					  
                </div>
                <div class="form-group">
					<label for="participant_cellphone">Cellphone</label>
					<input type="text" id="participant_cellphone" name="participant_cellphone" class="form-control"  value="{$participantData.participant_cellphone}" />
					{if isset($errorArray.participant_cellphone)}<span class="error">{$errorArray.participant_cellphone}</span>{/if}					  
                </div>				
                <div class="form-group">
					<button type="submit" class="btn btn-primary">{if isset($participantData)}Update{else}Add{/if}</button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/participant/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{if isset($participantData)}
				<a href="/participant/details.php?code={$participantData.participant_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/participant/class.php?code={$participantData.participant_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Classes
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