<?php /* Smarty version 2.6.20, created on 2019-09-08 12:05:20
         compiled from device/details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'device/details.tpl', 54, false),)), $this); ?>
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
	<h2 class="content-header-title">Device</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/">Device</a></li>
		<li><?php if (isset ( $this->_tpl_vars['deviceData'] )): ?><a href="/device/details.php?code=<?php echo $this->_tpl_vars['deviceData']['device_code']; ?>
"><?php echo $this->_tpl_vars['deviceData']['device_name']; ?>
</a><?php else: ?>Add a device<?php endif; ?></li>		
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['deviceData'] )): ?><?php echo $this->_tpl_vars['deviceData']['device_name']; ?>
 - <?php echo $this->_tpl_vars['deviceData']['device_code']; ?>
<?php else: ?>Add a device<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/device/details.php<?php if (isset ( $this->_tpl_vars['deviceData'] )): ?>?code=<?php echo $this->_tpl_vars['deviceData']['device_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">	
					<div class="col-sm-12">	
						<div class="form-group">
							<label for="device_name">Name / Description</label>
							<input type="text" id="device_name" name="device_name" class="form-control" value="<?php echo $this->_tpl_vars['deviceData']['device_name']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['device_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['device_name']; ?>
</span><?php endif; ?>					  
						</div>
					</div>				
				</div>	
				<div class="row">
					<div class="col-sm-6">				  
						<div class="form-group">
							<label for="campus_code">Campus</label>
							<select id="campus_code" name="campus_code" class="form-control">
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campusPairs']), $this);?>

							</select>
							<p>Please select a campus</p>				  
						</div>
					</div>
					<div class="col-sm-6">				  
						<div class="form-group">
							<label for="room_code">Room</label>
							<select id="room_code" name="room_code" class="form-control">
								<option value=""> --- Please select a campus first. --- </option>
							</select>
							<p>Please select a campus first.</p>				  
						</div>
					</div>							
				</div>					
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['deviceData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/device/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['deviceData'] )): ?>
				<a href="/device/details.php?code=<?php echo $this->_tpl_vars['deviceData']['device_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>	
				<a href="/device/logs.php?code=<?php echo $this->_tpl_vars['deviceData']['device_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Logs
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
$(document).ready(function() {
	$("#campus_code").change(function() {
		getRooms($(this).val());
	});	
	getRooms($("#campus_code").val());
});

function getRooms(campus) {
	$.ajax({
		type: "POST",
		url: "/device/details.php'; ?>
<?php if (isset ( $this->_tpl_vars['deviceData'] )): ?>?code=<?php echo $this->_tpl_vars['deviceData']['device_code']; ?>
<?php endif; ?><?php echo '",
		data: "getRooms=1&campus="+campus,
		dataType: "html",
		success: function(data){
			$(\'#room_code\').html(data);
		}
	});
}
</script>
'; ?>

</body>
</html>