<?php /* Smarty version 2.6.20, created on 2019-09-08 01:01:27
         compiled from configuration/course/details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'configuration/course/details.tpl', 62, false),)), $this); ?>
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
	<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/css.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

</head>
<body>
<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/header.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<div class="container">
  <div class="content">
    <div class="content-container">
	<div class="content-header">
	<h2 class="content-header-title">Course</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/configuration/course/">Course</a></li>
		<li><?php if (isset ( $this->_tpl_vars['courseData'] )): ?><a href="/configuration/course/details.php?code=<?php echo $this->_tpl_vars['courseData']['course_code']; ?>
"><?php echo $this->_tpl_vars['courseData']['course_name']; ?>
</a><?php else: ?>Add a course<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['courseData'] )): ?><?php echo $this->_tpl_vars['courseData']['course_name']; ?>
 <?php echo $this->_tpl_vars['courseData']['course_surname']; ?>
<?php else: ?>Add a course<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/course/details.php<?php if (isset ( $this->_tpl_vars['courseData'] )): ?>?code=<?php echo $this->_tpl_vars['courseData']['course_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">	
					<div class="col-sm-3">	
						<div class="form-group">
							<label for="course_cipher">Code</label>
							<input type="text" id="course_cipher" name="course_cipher" class="form-control"  value="<?php echo $this->_tpl_vars['courseData']['course_cipher']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['course_cipher'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['course_cipher']; ?>
</span><?php endif; ?>					  
						</div>
					</div>				
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="course_name" class="error">Name</label>
							<input type="text" id="course_name" name="course_name" class="form-control"  value="<?php echo $this->_tpl_vars['courseData']['course_name']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['course_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['course_name']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label for="faculty_code" class="error">Faculty</label>
							<select id="faculty_code" name="faculty_code" class="form-control">
								<option value=""> ---- </option>
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['facultypairs'],'selected' => $this->_tpl_vars['courseData']['faculty_code']), $this);?>

							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['faculty_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['faculty_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>		
					<div class="col-sm-4">
						<div class="form-group">
							<label for="department_code" class="error">Department</label>
							<select id="department_code" name="department_code" class="form-control">
								<option value=""> --- Please select faculty --- </option>
							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['department_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['department_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>						
					<div class="col-sm-4">
						<div class="form-group">
							<label for="qualification_code" class="error">Qualification</label>
							<select id="qualification_code" name="qualification_code" class="form-control">
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['qualificationpairs'],'selected' => $this->_tpl_vars['courseData']['qualification_code']), $this);?>

							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['qualification_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['qualification_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>					
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['courseData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
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
				<?php if (isset ( $this->_tpl_vars['courseData'] )): ?>
				<a href="/configuration/course/details.php?code=<?php echo $this->_tpl_vars['courseData']['course_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/configuration/course/schedule.php?code=<?php echo $this->_tpl_vars['courseData']['course_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Schedule
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>					
				<?php endif; ?>
			</div> <!-- /.list-group -->
		</div>
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/footer.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/javascript.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<?php echo '
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
		url: "details.php'; ?>
<?php if (isset ( $this->_tpl_vars['courseData'] )): ?>?code=<?php echo $this->_tpl_vars['courseData']['course_code']; ?>
<?php endif; ?><?php echo '",
		data: "faculty="+$(\'#faculty_code\').val(),
		dataType: "html",
		success: function(data){
			$(\'#department_code\').html(data);
		}
	});	
}
</script>
'; ?>

</body>
</html>