<?php /* Smarty version 2.6.20, created on 2019-09-07 18:17:00
         compiled from configuration/room/details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'configuration/room/details.tpl', 61, false),)), $this); ?>
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
	<h2 class="content-header-title">Room</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/configuration/room/">Room</a></li>
		<li><?php if (isset ( $this->_tpl_vars['roomData'] )): ?><a href="/configuration/room/details.php?code=<?php echo $this->_tpl_vars['roomData']['room_code']; ?>
"><?php echo $this->_tpl_vars['roomData']['room_name']; ?>
 <?php echo $this->_tpl_vars['roomData']['room_surname']; ?>
</a><?php else: ?>Add a room<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['roomData'] )): ?><?php echo $this->_tpl_vars['roomData']['room_name']; ?>
 <?php echo $this->_tpl_vars['roomData']['room_surname']; ?>
<?php else: ?>Add a room<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/room/details.php<?php if (isset ( $this->_tpl_vars['roomData'] )): ?>?code=<?php echo $this->_tpl_vars['roomData']['room_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="room_cipher">Code / cipher</label>
							<input type="text" id="room_cipher" name="room_cipher" class="form-control"  value="<?php echo $this->_tpl_vars['roomData']['room_cipher']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['room_cipher'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['room_cipher']; ?>
</span><?php endif; ?>					  
						</div>
					</div>				
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="room_name" class="error">Name</label>
							<input type="text" id="room_name" name="room_name" class="form-control"  value="<?php echo $this->_tpl_vars['roomData']['room_name']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['room_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['room_name']; ?>
</span><?php endif; ?>					  
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="campus_code" class="error">Campus</label>
							<select id="campus_code" name="campus_code" class="form-control">
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['campuspairs'],'selected' => $this->_tpl_vars['roomData']['campus_code']), $this);?>

							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['campus_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['campus_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>					
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="room_location">Room location</label>
							<input type="text" id="room_location" name="room_location" class="form-control"  value="<?php echo $this->_tpl_vars['roomData']['room_location']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['room_location'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['room_location']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>			
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['roomData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/room/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['roomData'] )): ?>
				<a href="/configuration/room/details.php?code=<?php echo $this->_tpl_vars['roomData']['room_code']; ?>
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

</body>
</html>