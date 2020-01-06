<?php /* Smarty version 2.6.20, created on 2019-08-05 19:28:25
         compiled from design/details.tpl */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>MailBok - Communication Tool</title>
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
	<h2 class="content-header-title">Design</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/account/details.php?code=<?php echo $this->_tpl_vars['activeAccount']['account_code']; ?>
"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>
		<li><a href="/design/">Design</a></li>
		<li><?php if (isset ( $this->_tpl_vars['designData'] )): ?><?php echo $this->_tpl_vars['designData']['design_name']; ?>
<?php else: ?>Add a design<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['designData'] )): ?><?php echo $this->_tpl_vars['designData']['design_name']; ?>
<?php else: ?>Add a design<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/design/details.php<?php if (isset ( $this->_tpl_vars['designData'] )): ?>?code=<?php echo $this->_tpl_vars['designData']['design_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">			  		
                <div class="form-group">
					<label for="design_name">Name</label>
					<input type="text" id="design_name" name="design_name" class="form-control" data-required="true" value="<?php echo $this->_tpl_vars['designData']['design_name']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['design_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['design_name']; ?>
</span><?php else: ?><span class="smalltext">Name of the design</span><?php endif; ?>					  
                </div>			
                <div class="form-group">
					<label for="htmlfile">Upload HTML / HTM file</label>
					<input type="file" id="htmlfile" name="htmlfile" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['htmlfile'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['htmlfile']; ?>
</span><?php endif; ?>
					<br /><span>N.B.: Only upload the html or htm files.</span>
					<br /><span class="success">When uploading images, fonts or css files on the design, please use the following path: src="[image]/imagename.jpg"</span>					
					<?php if (isset ( $this->_tpl_vars['designData'] )): ?>
						<?php if ($this->_tpl_vars['designData']['design_file'] != ''): ?>
							<br />
							<p>
								<a href="/design/view.php?code=<?php echo $this->_tpl_vars['designData']['design_code']; ?>
" target="_blank"><?php echo $this->_tpl_vars['config']['site']; ?>
<?php echo $this->_tpl_vars['designData']['design_file']; ?>
</a>
							</p>
						<?php endif; ?>
					<?php endif; ?>
                </div>
                <div class="form-group">
					<label for="imagefiles">Image Upload</label>
					<input type="file" id="imagefiles[]" name="imagefiles[]" multiple />
					<?php if (isset ( $this->_tpl_vars['errorArray']['imagefiles'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['imagefiles']; ?>
</span><?php endif; ?>
					<br /><span>N.B.: Upload only otf, ttf, css, jpg, jpeg, png or gif images</span>
                </div>					
                <div class="form-group"><button type="submit" class="btn btn-primary">Validate and Submit</button></div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/design/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['designData'] )): ?>
				<a href="/design/details.php?code=<?php echo $this->_tpl_vars['designData']['design_code']; ?>
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

</html>