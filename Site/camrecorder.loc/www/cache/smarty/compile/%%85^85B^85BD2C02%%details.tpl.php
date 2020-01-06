<?php /* Smarty version 2.6.20, created on 2019-08-05 15:36:05
         compiled from template/details.tpl */ ?>
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
	<h2 class="content-header-title">Template</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<?php if (isset ( $this->_tpl_vars['activeAccount'] )): ?><li><a href="/account/details.php?code=<?php echo $this->_tpl_vars['activeAccount']['account_code']; ?>
"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li><?php endif; ?>
		<li><a href="/template/">Template</a></li>
		<li><?php if (isset ( $this->_tpl_vars['templateData'] )): ?><?php echo $this->_tpl_vars['templateData']['template_cipher']; ?>
<?php else: ?>Add a template<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['templateData'] )): ?><?php echo $this->_tpl_vars['templateData']['template_cipher']; ?>
<?php else: ?>Add a template<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/template/details.php<?php if (isset ( $this->_tpl_vars['templateData'] )): ?>?code=<?php echo $this->_tpl_vars['templateData']['template_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">	
                <div class="form-group">
					<label for="template_public">Is this a public template</label>
					<select id="template_public" name="template_public" class="form-control">
						<option value="0" <?php if ($this->_tpl_vars['templateData']['template_public'] == '0'): ?>selected<?php endif; ?>> NO </option>
						<option value="1" <?php if ($this->_tpl_vars['templateData']['template_public'] == '1'): ?>selected<?php endif; ?>> YES </option>
					</select>
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_public'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_public']; ?>
</span><?php else: ?><span class="smalltext">Should administrators of municipalities be able to see these.</span><?php endif; ?>					  
                </div>			  
				<?php if (! isset ( $this->_tpl_vars['templateData'] )): ?>
                <div class="form-group">
					<label for="template_category">Category</label>
					<select id="template_category" name="template_category" class="form-control" <?php if (isset ( $this->_tpl_vars['templateData'] )): ?>readonly<?php endif; ?>>
						<option value="EMAIL" <?php if ($this->_tpl_vars['templateData']['template_category'] == 'EMAIL'): ?>selected<?php endif; ?>> EMAIL </option>
						<option value="SMS" <?php if ($this->_tpl_vars['templateData']['template_category'] == 'SMS'): ?>selected<?php endif; ?>> SMS </option>
					</select>
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_category'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_category']; ?>
</span><?php else: ?><span class="smalltext">Please select a category</span><?php endif; ?>					  
                </div>				
                <div class="form-group">
					<label for="template_cipher">Cipher</label>
					<input type="text" id="template_cipher" name="template_cipher" class="form-control" data-required="true" value="<?php echo $this->_tpl_vars['templateData']['template_cipher']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_cipher'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_cipher']; ?>
</span><?php else: ?><span class="smalltext">cipher of the template</span><?php endif; ?>					  
                </div>
                <div class="form-group">
					<label for="template_type">Type</label>
					<select id="template_type" name="template_type" class="form-control">
						<option value=""> ------ </option>
						<option value="ENQUIRY" <?php if ($this->_tpl_vars['templateData']['template_type'] == 'ENQUIRY'): ?>selected<?php endif; ?>> ENQUIRY </option>
						<option value="PARTICIPANT" <?php if ($this->_tpl_vars['templateData']['template_type'] == 'PARTICIPANT'): ?>selected<?php endif; ?>> PARTICIPANT </option>
						<option value="ACCOUNT" <?php if ($this->_tpl_vars['templateData']['template_type'] == 'ACCOUNT'): ?>selected<?php endif; ?>> ACCOUNT </option>
						<option value="ADMIN" <?php if ($this->_tpl_vars['templateData']['template_type'] == 'ADMIN'): ?>selected<?php endif; ?>> ADMIN </option>						
					</select>
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_type'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_type']; ?>
</span><?php else: ?><span class="smalltext">Select section of a category if a category has sections under it.</span><?php endif; ?>					  
                </div>
				<?php else: ?>
                <div class="form-group">
					<label for="template_category">Category</label><br />
					<?php echo $this->_tpl_vars['templateData']['template_category']; ?>
<br /><br />
					<label for="template_cipher">Cipher</label><br />
					<?php echo $this->_tpl_vars['templateData']['template_cipher']; ?>
<br /><br />
					<label for="template_type">Type</label><br />
					<?php echo $this->_tpl_vars['templateData']['template_type']; ?>
<br />							
                </div>
				<input type="hidden" id="template_category" name="template_category" value="<?php echo $this->_tpl_vars['templateData']['template_category']; ?>
" />
				<input type="hidden" id="template_cipher" name="template_cipher" value="<?php echo $this->_tpl_vars['templateData']['template_cipher']; ?>
	" />
				<input type="hidden" id="template_type" name="template_type" value="<?php echo $this->_tpl_vars['templateData']['template_type']; ?>
" />
				<?php endif; ?>
                <div class="form-group SMS">
					<label for="template_message">Message</label>
					<textarea id="template_message" name="template_message" class="form-control" rows="5"><?php echo $this->_tpl_vars['templateData']['template_message']; ?>
</textarea>
					<span class="smalltext error" id="template_count">0 characters entered.</span><br />
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_message'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_message']; ?>
</span><?php else: ?><span class="smalltext">SMS message which is less than 140 characters.</span><?php endif; ?>					  
                </div>				
                <div class="form-group EMAIL">
					<label for="template_subject">Subject</label>
					<input type="text" id="template_subject" name="template_subject" class="form-control" value="<?php echo $this->_tpl_vars['templateData']['template_subject']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_subject'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_subject']; ?>
</span><?php else: ?><span class="smalltext">Please add the subject of this email</span><?php endif; ?>					  
                </div>				
                <div class="form-group EMAIL">
					<label for="htmlfile">Upload HTML / HTM file</label>
					<input type="file" id="htmlfile" name="htmlfile" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['htmlfile'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['htmlfile']; ?>
</span><?php endif; ?>
					<br /><span>N.B.: Only upload the html or htm files.</span>
					<?php if (isset ( $this->_tpl_vars['templateData'] )): ?>
						<?php if ($this->_tpl_vars['templateData']['template_file'] != ''): ?>
							<br />
							<p>
								<a href="/template/view.php?code=<?php echo $this->_tpl_vars['templateData']['template_code']; ?>
" target="_blank"><?php echo $this->_tpl_vars['config']['site']; ?>
<?php echo $this->_tpl_vars['templateData']['template_file']; ?>
</a>
							</p>
						<?php endif; ?>
					<?php endif; ?>
                </div>
                <div class="form-group EMAIL">
					<label for="imagefiles">Image Upload</label>
					<input type="file" id="imagefiles[]" name="imagefiles[]" multiple />
					<?php if (isset ( $this->_tpl_vars['errorArray']['imagefiles'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['imagefiles']; ?>
</span><?php endif; ?>
					<br /><span>N.B.: Upload only jpg, jpeg, png or gif images</span>
                </div>					
                <div class="form-group"><button type="submit" class="btn btn-primary">Validate and Submit</button></div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/template/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['templateData'] )): ?>
				<a href="/template/details.php?code=<?php echo $this->_tpl_vars['templateData']['template_code']; ?>
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

<?php echo '
<script type="text/javascript">
$( document ).ready(function() {
	$("#template_category").change(function() {
	  categoryChange(); 
	  return false;
	});
	categoryChange();
	messageCount();
});

function messageCount() {
	$("#template_message").keyup(function () {
		var i = $("#template_message").val().length;
		$("#template_count").html(i+\' characters entered.\');
		if (i > 320) {
			$(\'#template_count\').removeClass(\'success\');
			$(\'#template_count\').addClass(\'error\');
		} else if(i == 0) {
			$(\'#template_count\').removeClass(\'success\');
			$(\'#template_count\').addClass(\'error\');
		} else {
			$(\'#template_count\').removeClass(\'error\');
			$(\'#template_count\').addClass(\'success\');
		} 
	});	
	return false;
}
function categoryChange() {
	var category = $( "#template_category" ).val();
	
	if(category == \'EMAIL\') {
		$(".SMS").hide();
		$(".EMAIL").show();
		messageCount();
	} else if(category == \'SMS\') {
		$(".SMS").show();
		$(".EMAIL").hide();
	}
	return false;
}
</script>
'; ?>

</html>