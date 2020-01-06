<?php /* Smarty version 2.6.20, created on 2019-09-07 21:17:05
         compiled from configuration/class/details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'configuration/class/details.tpl', 75, false),)), $this); ?>
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
	<h2 class="content-header-title">Class</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/configuration/class/">Class</a></li>
		<li><?php if (isset ( $this->_tpl_vars['classData'] )): ?><a href="/configuration/class/details.php?code=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
"><?php echo $this->_tpl_vars['classData']['class_name']; ?>
</a><?php else: ?>Add a class<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['classData'] )): ?><?php echo $this->_tpl_vars['classData']['class_name']; ?>
 <?php echo $this->_tpl_vars['classData']['class_surname']; ?>
<?php else: ?>Add a class<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/class/details.php<?php if (isset ( $this->_tpl_vars['classData'] )): ?>?code=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="class_cipher">Code</label>
							<input type="text" id="class_cipher" name="class_cipher" class="form-control"  value="<?php echo $this->_tpl_vars['classData']['class_cipher']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['class_cipher'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['class_cipher']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="year_code" class="error">Year</label>
							<input type="text" id="year_code" name="year_code" class="form-control"  value="<?php echo $this->_tpl_vars['classData']['year_code']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['year_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['year_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>				
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="semester_code">Semester</label>
							<input type="text" id="semester_code" name="semester_code" class="form-control"  value="<?php echo $this->_tpl_vars['classData']['semester_code']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['semester_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['semester_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>					
					<div class="col-sm-6">
						<div class="form-group">
							<label for="class_name" class="error">Name</label>
							<input type="text" id="class_name" name="class_name" class="form-control"  value="<?php echo $this->_tpl_vars['classData']['class_name']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['class_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['class_name']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>				  
				<div class="row">	
					<div class="col-sm-4">
						<div class="form-group">
							<label for="faculty_code" class="error">Faculty</label>
							<select id="faculty_code" name="faculty_code" class="form-control">
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['facultypairs'],'selected' => $this->_tpl_vars['classData']['faculty_code']), $this);?>

							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['course_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['course_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>	
					<div class="col-sm-4">
						<div class="form-group">
							<label for="department_code" class="error">Department</label>
							<select id="department_code" name="department_code" class="form-control">
								<option value=""> --- Select a faculty --- </option>
							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['course_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['course_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>	
					<div class="col-sm-4">
						<div class="form-group">
							<label for="course_code" class="error">Course</label>
							<select id="course_code" name="course_code" class="form-control">
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['coursepairs'],'selected' => $this->_tpl_vars['classData']['course_code']), $this);?>

							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['course_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['course_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>						
				</div>				  				
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['classData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
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
				<?php if (isset ( $this->_tpl_vars['classData'] )): ?>
				<a href="/configuration/class/details.php?code=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
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
	$("#department_code").change(function() {
		getCourses();
	});	
	getDepartments();
});
function getDepartments() {
	$.ajax({
		type: "GET",
		url: "details.php'; ?>
<?php if (isset ( $this->_tpl_vars['classData'] )): ?>?code=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
<?php endif; ?><?php echo '",
		data: "faculty="+$(\'#faculty_code\').val(),
		dataType: "html",
		success: function(data){
			$(\'#department_code\').html(data);
			getCourses();
		}
	});	
}
function getCourses() {
	$.ajax({
		type: "GET",
		url: "details.php'; ?>
<?php if (isset ( $this->_tpl_vars['classData'] )): ?>?code=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
<?php endif; ?><?php echo '",
		data: "department="+$(\'#department_code\').val(),
		dataType: "html",
		success: function(data){
			$(\'#course_code\').html(data);
		}
	});	
}
</script>
'; ?>

</body>
</html>