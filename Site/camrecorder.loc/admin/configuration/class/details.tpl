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
	<h2 class="content-header-title">Class</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#">{$activeAccount.account_name}</a></li>	
		<li><a href="/configuration/class/">Class</a></li>
		<li>{if isset($classData)}<a href="/configuration/class/details.php?code={$classData.class_code}">{$classData.class_name}</a>{else}Add a class{/if}</li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($classData)}{$classData.class_name} {$classData.class_surname}{else}Add a class{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/class/details.php{if isset($classData)}?code={$classData.class_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="class_cipher">Code</label>
							<input type="text" id="class_cipher" name="class_cipher" class="form-control"  value="{$classData.class_cipher}" />
							{if isset($errorArray.class_cipher)}<span class="error">{$errorArray.class_cipher}</span>{/if}					  
						</div>
					</div>
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="year_code" class="error">Year</label>
							<input type="text" id="year_code" name="year_code" class="form-control"  value="{$classData.year_code}" />
							{if isset($errorArray.year_code)}<span class="error">{$errorArray.year_code}</span>{/if}					  
						</div>
					</div>				
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="semester_code">Semester</label>
							<input type="text" id="semester_code" name="semester_code" class="form-control"  value="{$classData.semester_code}" />
							{if isset($errorArray.semester_code)}<span class="error">{$errorArray.semester_code}</span>{/if}					  
						</div>
					</div>					
					<div class="col-sm-6">
						<div class="form-group">
							<label for="class_name" class="error">Name</label>
							<input type="text" id="class_name" name="class_name" class="form-control"  value="{$classData.class_name}" />
							{if isset($errorArray.class_name)}<span class="error">{$errorArray.class_name}</span>{/if}					  
						</div>
					</div>
				</div>				  
				<div class="row">	
					<div class="col-sm-4">
						<div class="form-group">
							<label for="faculty_code" class="error">Faculty</label>
							<select id="faculty_code" name="faculty_code" class="form-control">
								{html_options options=$facultypairs selected=$classData.faculty_code}
							</select>
							{if isset($errorArray.course_code)}<span class="error">{$errorArray.course_code}</span>{/if}					  
						</div>
					</div>	
					<div class="col-sm-4">
						<div class="form-group">
							<label for="department_code" class="error">Department</label>
							<select id="department_code" name="department_code" class="form-control">
								<option value=""> --- Select a faculty --- </option>
							</select>
							{if isset($errorArray.course_code)}<span class="error">{$errorArray.course_code}</span>{/if}					  
						</div>
					</div>	
					<div class="col-sm-4">
						<div class="form-group">
							<label for="course_code" class="error">Course</label>
							<select id="course_code" name="course_code" class="form-control">
								{html_options options=$coursepairs selected=$classData.course_code}
							</select>
							{if isset($errorArray.course_code)}<span class="error">{$errorArray.course_code}</span>{/if}					  
						</div>
					</div>						
				</div>				  				
                <div class="form-group">
					<button type="submit" class="btn btn-primary">{if isset($classData)}Update{else}Add{/if}</button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/class/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{if isset($classData)}
				<a href="/configuration/class/details.php?code={$classData.class_code}" class="list-group-item">
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
{literal}
<script type="text/javascript">
$(document).ready(function(){
	$("#faculty_code").change(function() {
		getDepartments();
	});
	$("#department_code").change(function() {
		getCourses();
	});	
	getDepartments();
});
function getDepartments() {
	$.ajax({
		type: "GET",
		url: "details.php{/literal}{if isset($classData)}?code={$classData.class_code}{/if}{literal}",
		data: "faculty="+$('#faculty_code').val(),
		dataType: "html",
		success: function(data){
			$('#department_code').html(data);
			getCourses();
		}
	});	
}
function getCourses() {
	$.ajax({
		type: "GET",
		url: "details.php{/literal}{if isset($classData)}?code={$classData.class_code}{/if}{literal}",
		data: "department="+$('#department_code').val(),
		dataType: "html",
		success: function(data){
			$('#course_code').html(data);
		}
	});	
}
</script>
{/literal}
</body>
</html>