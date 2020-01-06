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
			<li><a href="/participant/">Student</a></li>
			<li><a href="/participant/details.php?code={$participantData.participant_code}">{$participantData.participant_name}</a></li>
			<li class="active">Classes</li>
		</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>Students List
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/participant/class.php?code={$participantData.participant_code}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">			
				<p>Below is a list of all classes you are linked to.</p>
				<table class="table table-bordered">	
					<thead>
						<tr>
							<td>Year</td>
							<td>Course</td>
							<td>Name</td>
							<td width="5%"></td>
						</tr>
					</thead>
					<tbody>
					{foreach from=$studentData item=item}
					<tr>
						<td>{$item.year_code}</td>
						<td>{$item.course_name}</td>
						<td>{$item.class_name}</td>
						<td><button onclick="deleteModal('{$item.student_code}', '{$participantData.participant_code}', 'class'); return false;">Delete</button></td>
					</tr>
					{foreachelse}
						<tr>
							<td align="center" colspan="3">There are currently no items</td>
						</tr>					
					{/foreach}
					</tbody>					  
				</table>
				<p>Add new class below</p>	
				<div class="row">
					<div class="col-sm-12">				
						<div class="form-group">
							<label for="class_name">Search for a class by faculty, department, code, course or by its name below</label>
							<input type="text" id="class_name" name="class_name"  size="20" class="form-control"   />
							<input type="hidden" id="class_code" name="class_code" size="20"  />
							{if isset($errorArray.class_code)}<em class="error">{$errorArray.class_code}</em>{else}<em class="smalltext">Add first 3 letters of the person's name, surname, email or cellphone number</em>{/if}
						</div>	
					</div>
				</div>			
                <div class="form-group"><button type="submit" class="btn btn-primary">Save</button></div>
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
				<a href="/participant/details.php?code={$participantData.participant_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/participant/class.php?code={$participantData.participant_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Classes
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
	$( "#class_name" ).autocomplete({
		source: "/feeds/class.php",
		minLength: 2,
		select: function( event, ui ) {
			if(ui.item.id != '') {
				$('#class_name').html(ui.item.name);
				$('#class_code').val(ui.item.id);	
			} else {
				$('#class_name').html('');
				$('#class_code').val('');	
			}			
		}
	});
});
</script>
{/literal}
</body>
</html>