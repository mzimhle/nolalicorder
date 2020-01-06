<?php /* Smarty version 2.6.20, created on 2019-09-07 21:12:27
         compiled from participant/details.tpl */ ?>
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
		<li><a href="/participant/">Students</a></li>
		<li><?php if (isset ( $this->_tpl_vars['participantData'] )): ?><a href="/participant/details.php?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
"><?php echo $this->_tpl_vars['participantData']['participant_name']; ?>
 <?php echo $this->_tpl_vars['participantData']['participant_surname']; ?>
</a><?php else: ?>Add a participant<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['participantData'] )): ?><?php echo $this->_tpl_vars['participantData']['participant_name']; ?>
 <?php echo $this->_tpl_vars['participantData']['participant_surname']; ?>
<?php else: ?>Add a participant<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/participant/details.php<?php if (isset ( $this->_tpl_vars['participantData'] )): ?>?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
                <div class="form-group">
					<label for="participant_name">Name</label>
					<input type="text" id="participant_name" name="participant_name" class="form-control"  value="<?php echo $this->_tpl_vars['participantData']['participant_name']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['participant_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['participant_name']; ?>
</span><?php endif; ?>					  
                </div>
                <div class="form-group">
					<label for="participant_email">Email</label>
					<input type="text" id="participant_email" name="participant_email" class="form-control"  value="<?php echo $this->_tpl_vars['participantData']['participant_email']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['participant_email'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['participant_email']; ?>
</span><?php endif; ?>					  
                </div>
                <div class="form-group">
					<label for="participant_cellphone">Cellphone</label>
					<input type="text" id="participant_cellphone" name="participant_cellphone" class="form-control"  value="<?php echo $this->_tpl_vars['participantData']['participant_cellphone']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['participant_cellphone'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['participant_cellphone']; ?>
</span><?php endif; ?>					  
                </div>				
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['participantData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>
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
				<?php if (isset ( $this->_tpl_vars['participantData'] )): ?>
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