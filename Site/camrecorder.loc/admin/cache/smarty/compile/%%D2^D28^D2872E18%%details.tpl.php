<?php /* Smarty version 2.6.20, created on 2019-09-07 21:25:27
         compiled from configuration/qualification/details.tpl */ ?>
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
	<h2 class="content-header-title">Qualification</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/configuration/qualification/">Qualification</a></li>
		<li><?php if (isset ( $this->_tpl_vars['qualificationData'] )): ?><a href="/configuration/qualification/details.php?code=<?php echo $this->_tpl_vars['qualificationData']['qualification_code']; ?>
"><?php echo $this->_tpl_vars['qualificationData']['qualification_name']; ?>
 <?php echo $this->_tpl_vars['qualificationData']['qualification_surname']; ?>
</a><?php else: ?>Add a qualification<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['qualificationData'] )): ?><?php echo $this->_tpl_vars['qualificationData']['qualification_name']; ?>
 <?php echo $this->_tpl_vars['qualificationData']['qualification_surname']; ?>
<?php else: ?>Add a qualification<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/configuration/qualification/details.php<?php if (isset ( $this->_tpl_vars['qualificationData'] )): ?>?code=<?php echo $this->_tpl_vars['qualificationData']['qualification_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-3">	
						<div class="form-group">
							<label for="qualification_cipher">Code</label>
							<input type="text" id="qualification_cipher" name="qualification_cipher" class="form-control"  value="<?php echo $this->_tpl_vars['qualificationData']['qualification_cipher']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['qualification_cipher'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['qualification_cipher']; ?>
</span><?php endif; ?>					  
						</div>
					</div>				
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="qualification_name">Name</label>
							<input type="text" id="qualification_name" name="qualification_name" class="form-control"  value="<?php echo $this->_tpl_vars['qualificationData']['qualification_name']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['qualification_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['qualification_name']; ?>
</span><?php endif; ?>					  
						</div>
					</div>				
				</div>			
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['qualificationData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/qualification/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['qualificationData'] )): ?>
				<a href="/configuration/qualification/details.php?code=<?php echo $this->_tpl_vars['qualificationData']['qualification_code']; ?>
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