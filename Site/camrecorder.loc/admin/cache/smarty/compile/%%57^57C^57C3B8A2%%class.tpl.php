<?php /* Smarty version 2.6.20, created on 2019-09-07 21:12:24
         compiled from participant/class.tpl */ ?>
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
		<h2 class="content-header-title">Students</h2>
		<ol class="breadcrumb">
			<li><a href="/">Home</a></li>
			<li><a href="/"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
			<li><a href="/participant/">Student</a></li>
			<li><a href="/participant/details.php?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
"><?php echo $this->_tpl_vars['participantData']['participant_name']; ?>
</a></li>
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
              <form id="validate-basic" action="/participant/class.php?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">			
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
					<?php $_from = $this->_tpl_vars['studentData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
					<tr>
						<td><?php echo $this->_tpl_vars['item']['year_code']; ?>
</td>
						<td><?php echo $this->_tpl_vars['item']['course_name']; ?>
</td>
						<td><?php echo $this->_tpl_vars['item']['class_name']; ?>
</td>
						<td><button onclick="deleteModal('<?php echo $this->_tpl_vars['item']['student_code']; ?>
', '<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
', 'class'); return false;">Delete</button></td>
					</tr>
					<?php endforeach; else: ?>
						<tr>
							<td align="center" colspan="3">There are currently no items</td>
						</tr>					
					<?php endif; unset($_from); ?>
					</tbody>					  
				</table>
				<p>Add new class below</p>	
				<div class="row">
					<div class="col-sm-12">				
						<div class="form-group">
							<label for="class_name">Search for a class by faculty, department, code, course or by its name below</label>
							<input type="text" id="class_name" name="class_name"  size="20" class="form-control"   />
							<input type="hidden" id="class_code" name="class_code" size="20"  />
							<?php if (isset ( $this->_tpl_vars['errorArray']['class_code'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['class_code']; ?>
</em><?php else: ?><em class="smalltext">Add first 3 letters of the person's name, surname, email or cellphone number</em><?php endif; ?>
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
				<a href="/participant/details.php?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/participant/class.php?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Classes
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>			
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
<script type="text/javascript" language="javascript">
$(document).ready(function() {
	$( "#class_name" ).autocomplete({
		source: "/feeds/class.php",
		minLength: 2,
		select: function( event, ui ) {
			if(ui.item.id != \'\') {
				$(\'#class_name\').html(ui.item.name);
				$(\'#class_code\').val(ui.item.id);	
			} else {
				$(\'#class_name\').html(\'\');
				$(\'#class_code\').val(\'\');	
			}			
		}
	});
});
</script>
'; ?>

</body>
</html>