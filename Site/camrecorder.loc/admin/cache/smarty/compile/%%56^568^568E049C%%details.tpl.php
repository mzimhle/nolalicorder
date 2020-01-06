<?php /* Smarty version 2.6.20, created on 2018-07-24 22:16:50
         compiled from campaign/details.tpl */ ?>
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
	<h2 class="content-header-title">Campaigns</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>
		<li><a href="/campaign/">Campaigns</a></li>
		<li><?php if (isset ( $this->_tpl_vars['campaignData'] )): ?><?php echo $this->_tpl_vars['campaignData']['campaign_name']; ?>
<?php else: ?>Add a campaign<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
					<?php if (isset ( $this->_tpl_vars['campaignData'] )): ?>Edit a campaign<?php else: ?>Add a campaign<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/campaign/details.php<?php if (isset ( $this->_tpl_vars['campaignData'] )): ?>?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form">
                <div class="form-group">
					<label for="campaign_name">Name</label>
					<input type="text" id="campaign_name" name="campaign_name" class="form-control"  value="<?php echo $this->_tpl_vars['campaignData']['campaign_name']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['campaign_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['campaign_name']; ?>
</span><?php else: ?><span class="smalltext">Full name of the campaign as it will be seen on the website</span><?php endif; ?>					  
                </div>
                <div class="form-group">
					<label for="campaign_from_name">eMail From Name</label>
					<input type="text" id="campaign_from_name" name="campaign_from_name" class="form-control"  value="<?php echo $this->_tpl_vars['campaignData']['campaign_from_name']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['campaign_from_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['campaign_from_name']; ?>
</span><?php else: ?><span class="smalltext">Email from person's name</span><?php endif; ?>					  
                </div>	
                <div class="form-group">
					<label for="campaign_from_email">eMail From Email</label>
					<input type="text" id="campaign_from_email" name="campaign_from_email" class="form-control"  value="<?php echo $this->_tpl_vars['campaignData']['campaign_from_email']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['campaign_from_email'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['campaign_from_email']; ?>
</span><?php else: ?><span class="smalltext">Email address from person.</span><?php endif; ?>					  
                </div>					
                <div class="form-group"><button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['campaignData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button></div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->
		<div class="col-sm-3">
			<div class="list-group">  
				<a class="list-group-item" href="/campaign/">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['campaignData'] )): ?>
				<a class="list-group-item" href="/campaign/details.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>				
				<a class="list-group-item" href="/campaign/sms.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Send SMSs
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a class="list-group-item" href="/campaign/email.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Send Emails
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