<?php /* Smarty version 2.6.20, created on 2019-08-05 17:01:13
         compiled from account/details.tpl */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Nolali - CamRecorder</title>
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
	<h2 class="content-header-title">Account</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/account/">Account</a></li>
		<li><?php if (isset ( $this->_tpl_vars['accountData'] )): ?><a href="/account/details.php?code=<?php echo $this->_tpl_vars['accountData']['account_code']; ?>
"><?php echo $this->_tpl_vars['accountData']['account_name']; ?>
</a><?php else: ?>Add a account<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['accountData'] )): ?><?php echo $this->_tpl_vars['accountData']['account_name']; ?>
<?php else: ?>Add an account<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/account/details.php<?php if (isset ( $this->_tpl_vars['accountData'] )): ?>?code=<?php echo $this->_tpl_vars['accountData']['account_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_name" class="error">Name</label>
							<input type="text" id="account_name" name="account_name" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_name']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_name']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_site" class="error">Website</label>
							<input type="text" id="account_site" name="account_site" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_site']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_site'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_site']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_contact_cellphone" class="error">Cellphone Number</label>
							<input type="text" id="account_contact_cellphone" name="account_contact_cellphone" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_contact_cellphone']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_contact_cellphone'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_contact_cellphone']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_contact_telephone">Telephone Number</label>
							<input type="text" id="account_contact_telephone" name="account_contact_telephone" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_contact_telephone']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_contact_telephone'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_contact_telephone']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_contact_email" class="error">Email Address</label>
							<input type="text" id="account_contact_email" name="account_contact_email" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_contact_email']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_contact_email'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_contact_email']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_contact_fax">Fax Number</label>
							<input type="text" id="account_contact_fax" name="account_contact_fax" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_contact_fax']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_contact_fax'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_contact_fax']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>		
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_social_twitter">Twitter Handler</label>
							<input type="text" id="account_social_twitter" name="account_social_twitter" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_social_twitter']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_social_twitter'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_social_twitter']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_social_instagram">Instagram Handler</label>
							<input type="text" id="account_social_instagram" name="account_social_instagram" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_social_instagram']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_social_instagram'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_social_instagram']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>		
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_social_facebook">Facebook URL</label>
							<input type="text" id="account_social_facebook" name="account_social_facebook" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_social_facebook']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_social_facebook'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_social_facebook']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_social_linkedin">LinkedIn Account URL</label>
							<input type="text" id="account_social_linkedin" name="account_social_linkedin" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_social_linkedin']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_social_linkedin'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_social_linkedin']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-sm-12">			  
						<div class="form-group">
							<label for="account_address_physical" class="error">Physical Address</label>
							<input type="text" id="account_address_physical" name="account_address_physical" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_address_physical']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_address_physical'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_address_physical']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">				
						<div class="form-group">
							<label for="account_address_postal">Postal Address</label>
							<input type="text" id="account_address_postal" name="account_address_postal" class="form-control" value="<?php echo $this->_tpl_vars['accountData']['account_address_postal']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['account_address_postal'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['account_address_postal']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['accountData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/account/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['accountData'] )): ?>
				<a href="/account/details.php?code=<?php echo $this->_tpl_vars['accountData']['account_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/account/media.php?code=<?php echo $this->_tpl_vars['accountData']['account_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Media
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