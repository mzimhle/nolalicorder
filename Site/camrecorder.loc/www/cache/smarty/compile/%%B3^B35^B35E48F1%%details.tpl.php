<?php /* Smarty version 2.6.20, created on 2018-05-02 20:57:46
         compiled from subscription/details.tpl */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Nolali Configuration</title>
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
	<h2 class="content-header-title">Subscription</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/account/details.php?code=<?php echo $this->_tpl_vars['activeAccount']['account_code']; ?>
"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>
		<li><a href="/subscription/">Subscription</a></li>
		<li><?php if (isset ( $this->_tpl_vars['subscriptionData'] )): ?><?php echo $this->_tpl_vars['subscriptionData']['subscription_name']; ?>
<?php else: ?>Add a subscription<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['subscriptionData'] )): ?><?php echo $this->_tpl_vars['subscriptionData']['subscription_name']; ?>
<?php else: ?>Add a subscription<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" name="detailsForm" action="/subscription/details.php<?php if (isset ( $this->_tpl_vars['subscriptionData'] )): ?>?code=<?php echo $this->_tpl_vars['subscriptionData']['subscription_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form">	
                <div class="form-group">
					<label for="subscription_name">Name</label>
					<input type="text" id="subscription_name" name="subscription_name" class="form-control" value="<?php echo $this->_tpl_vars['subscriptionData']['subscription_name']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['subscription_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['subscription_name']; ?>
</span><?php endif; ?>	
                </div>
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['subscriptionData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>				
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/subscription/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['subscriptionData'] )): ?>
				<a href="/subscription/details.php?code=<?php echo $this->_tpl_vars['subscriptionData']['subscription_code']; ?>
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