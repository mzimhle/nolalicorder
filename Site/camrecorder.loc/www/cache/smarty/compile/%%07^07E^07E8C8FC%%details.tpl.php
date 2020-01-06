<?php /* Smarty version 2.6.20, created on 2019-08-07 11:50:54
         compiled from configuration/campus/details.tpl */ ?>
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
	<h2 class="content-header-title">Campus</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/configuration/campus/">Campus</a></li>
		<li><?php if (isset ( $this->_tpl_vars['campusData'] )): ?><a href="/configuration/campus/details.php?code=<?php echo $this->_tpl_vars['campusData']['campus_code']; ?>
"><?php echo $this->_tpl_vars['campusData']['campus_name']; ?>
 <?php echo $this->_tpl_vars['campusData']['campus_surname']; ?>
</a><?php else: ?>Add a campus<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['campusData'] )): ?><?php echo $this->_tpl_vars['campusData']['campus_name']; ?>
 <?php echo $this->_tpl_vars['campusData']['campus_surname']; ?>
<?php else: ?>Add a campus<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/campus/details.php<?php if (isset ( $this->_tpl_vars['campusData'] )): ?>?code=<?php echo $this->_tpl_vars['campusData']['campus_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="campus_cipher">Code / cipher</label>
							<input type="text" id="campus_cipher" name="campus_cipher" class="form-control"  value="<?php echo $this->_tpl_vars['campusData']['campus_cipher']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['campus_cipher'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['campus_cipher']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="campus_name">Name</label>
							<input type="text" id="campus_name" name="campus_name" class="form-control"  value="<?php echo $this->_tpl_vars['campusData']['campus_name']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['campus_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['campus_name']; ?>
</span><?php endif; ?>					  
						</div>
					</div>				
				</div>
				<div class="row">
					<div class="col-sm-12">	
						<div class="form-group">
							<label for="campus_map_address">Physical Address</label>
							<input type="text" id="campus_map_address" name="campus_map_address" class="form-control"  value="<?php echo $this->_tpl_vars['campusData']['campus_map_address']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['campus_map_address'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['campus_map_address']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>			
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['campusData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/campus/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['campusData'] )): ?>
				<a href="/configuration/campus/details.php?code=<?php echo $this->_tpl_vars['campusData']['campus_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/configuration/campus/map.php?code=<?php echo $this->_tpl_vars['campusData']['campus_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Map Location
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