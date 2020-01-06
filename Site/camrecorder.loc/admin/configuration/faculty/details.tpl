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
	<h2 class="content-header-title">Faculty</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#">{$activeAccount.account_name}</a></li>	
		<li><a href="/configuration/faculty/">Faculty</a></li>
		<li>{if isset($facultyData)}<a href="/configuration/faculty/details.php?code={$facultyData.faculty_code}">{$facultyData.faculty_name} {$facultyData.faculty_surname}</a>{else}Add a faculty{/if}</li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($facultyData)}{$facultyData.faculty_name} {$facultyData.faculty_surname}{else}Add a faculty{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/faculty/details.php{if isset($facultyData)}?code={$facultyData.faculty_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-12">	
						<div class="form-group">
							<label for="faculty_name">Name</label>
							<input type="text" id="faculty_name" name="faculty_name" class="form-control"  value="{$facultyData.faculty_name}" />
							{if isset($errorArray.faculty_name)}<span class="error">{$errorArray.faculty_name}</span>{/if}					  
						</div>
					</div>				
				</div>			
                <div class="form-group">
					<button type="submit" class="btn btn-primary">{if isset($facultyData)}Update{else}Add{/if}</button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/faculty/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{if isset($facultyData)}
				<a href="/configuration/faculty/details.php?code={$facultyData.faculty_code}" class="list-group-item">
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