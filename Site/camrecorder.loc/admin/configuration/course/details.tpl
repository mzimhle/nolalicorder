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
	<h2 class="content-header-title">Course</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#">{$activeAccount.account_name}</a></li>	
		<li><a href="/configuration/course/">Course</a></li>
		<li>{if isset($courseData)}<a href="/configuration/course/details.php?code={$courseData.course_code}">{$courseData.course_name}</a>{else}Add a course{/if}</li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($courseData)}{$courseData.course_name} {$courseData.course_surname}{else}Add a course{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/course/details.php{if isset($courseData)}?code={$courseData.course_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">	
					<div class="col-sm-3">	
						<div class="form-group">
							<label for="course_cipher">Code</label>
							<input type="text" id="course_cipher" name="course_cipher" class="form-control"  value="{$courseData.course_cipher}" />
							{if isset($errorArray.course_cipher)}<span class="error">{$errorArray.course_cipher}</span>{/if}					  
						</div>
					</div>				
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="course_name" class="error">Name</label>
							<input type="text" id="course_name" name="course_name" class="form-control"  value="{$courseData.course_name}" />
							{if isset($errorArray.course_name)}<span class="error">{$errorArray.course_name}</span>{/if}					  
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="faculty_code" class="error">Faculty</label>
							<select id="faculty_code" name="faculty_code" class="form-control">
								<option value=""> ---- </option>
								{html_options options=$facultypairs selected=$courseData.faculty_code}
							</select>
							{if isset($errorArray.faculty_code)}<span class="error">{$errorArray.faculty_code}</span>{/if}					  
						</div>
					</div>		
					<div class="col-sm-4">
						<div class="form-group">
							<label for="department_code" class="error">Department</label>
							<select id="department_code" name="department_code" class="form-control">
								<option value=""> --- Please select faculty --- </option>
							</select>
							{if isset($errorArray.department_code)}<span class="error">{$errorArray.department_code}</span>{/if}					  
						</div>
					</div>						
					<div class="col-sm-4">
						<div class="form-group">
							<label for="qualification_code" class="error">Qualification</label>
							<select id="qualification_code" name="qualification_code" class="form-control">
								{html_options options=$qualificationpairs selected=$courseData.qualification_code}
							</select>
							{if isset($errorArray.qualification_code)}<span class="error">{$errorArray.qualification_code}</span>{/if}					  
						</div>
					</div>
				</div>					
                <div class="form-group">
					<button type="submit" class="btn btn-primary">{if isset($courseData)}Update{else}Add{/if}</button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/course/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{if isset($courseData)}
				<a href="/configuration/course/details.php?code={$courseData.course_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/configuration/course/schedule.php?code={$courseData.course_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Schedule
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
	getDepartments();
});
function getDepartments() {
	$.ajax({
		type: "GET",
		url: "details.php{/literal}{if isset($courseData)}?code={$courseData.course_code}{/if}{literal}",
		data: "faculty="+$('#faculty_code').val(),
		dataType: "html",
		success: function(data){
			$('#department_code').html(data);
		}
	});	
}
</script>
{/literal}
</body>
</html>