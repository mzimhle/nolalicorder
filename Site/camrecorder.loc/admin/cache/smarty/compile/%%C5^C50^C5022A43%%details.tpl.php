<?php /* Smarty version 2.6.20, created on 2018-04-15 18:15:37
         compiled from communicate/details.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'communicate/details.tpl', 50, false),)), $this); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Municipal System</title>
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
	<h2 class="content-header-title">Communicate</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/municipality/details.php?code=<?php echo $this->_tpl_vars['activeMunicipality']['municipality_code']; ?>
"><?php echo $this->_tpl_vars['activeMunicipality']['municipality_name']; ?>
</a></li>
		<li><a href="/communicate/">Communicate</a></li>
		<li><?php if (isset ( $this->_tpl_vars['communicateData'] )): ?><?php echo $this->_tpl_vars['communicateData']['communicate_subject']; ?>
<?php else: ?>Add a communicate<?php endif; ?></li>
		<?php if (isset ( $this->_tpl_vars['templateData'] )): ?>
		<li><a href="#"><?php echo $this->_tpl_vars['templateData']['template_category']; ?>
 - <?php echo $this->_tpl_vars['templateData']['template_cipher']; ?>
</a></li>
		<?php endif; ?>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['communicateData'] )): ?><?php echo $this->_tpl_vars['communicateData']['communicate_subject']; ?>
<?php else: ?>Add a communicate<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
				<?php if ($this->_tpl_vars['communicateData']['communicate_active'] == '0'): ?>
				<p class="">PLEASE NOTE: You will not be able to to update or change this communication as it has already been sent out.</p>				
				<?php endif; ?>
              <form id="detailsForm" name="detailsForm" action="/communicate/details.php<?php if (isset ( $this->_tpl_vars['communicateData'] )): ?>?code=<?php echo $this->_tpl_vars['communicateData']['communicate_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">	
				<?php if (! isset ( $this->_tpl_vars['templateData'] )): ?>
                <div class="form-group">
					<label for="template_code">Template</label>
					<select id="template_code" name="template_code" class="form-control">
						<option value=""> -------- </option>
						<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['templatePairs']), $this);?>

					</select>
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_code']; ?>
</span><?php else: ?><span class="smalltext">Please select a template</span><?php endif; ?>					  
                </div>
				<?php else: ?>
				<input type="hidden" value="<?php echo $this->_tpl_vars['templateData']['template_code']; ?>
" name="template_code" id="template_code" />
				<?php endif; ?>
				<?php if (isset ( $this->_tpl_vars['templateData'] )): ?>
                <div class="form-group">
					<label for="template_cipher">Cipher</label>
					<input type="text" id="template_cipher" name="template_cipher" class="form-control" data-required="true" value="<?php echo $this->_tpl_vars['templateData']['template_cipher']; ?>
" readonly disabled />
					<span class="smalltext">Code / Cipher of the template</span>
                </div>
                <div class="form-group">
					<label for="communicate_subject">Subject</label>
					<input type="text" id="communicate_subject" name="communicate_subject" class="form-control" value="<?php echo $this->_tpl_vars['communicateData']['communicate_subject']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['communicate_subject'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['communicate_subject']; ?>
</span><?php else: ?><span class="smalltext">Please add the subject of this email/sms</span><?php endif; ?>					  
                </div>
				<?php if ($this->_tpl_vars['templateData']['template_category'] == 'SMS'): ?>				
                <div class="form-group">
					<label for="communicate_text">Message</label>
					<textarea id="communicate_text" name="communicate_text" class="form-control" rows="5"><?php echo $this->_tpl_vars['communicateData']['communicate_text']; ?>
</textarea>
					<span class="smalltext error" id="communicate_count">0 characters entered.</span><br />
					<?php if (isset ( $this->_tpl_vars['errorArray']['communicate_text'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['communicate_text']; ?>
</span><?php else: ?><span class="smalltext">SMS message which is less than 140 characters.</span><?php endif; ?>					  
                </div>
				<?php else: ?>
                <div class="form-group">
					<label for="communicate_text">Message</label>
					<textarea type="text" id="communicate_text" name="communicate_text" class="form-control" data-required="true" style="width: 100%; height: 700px;"><?php echo $this->_tpl_vars['communicateData']['communicate_text']; ?>
</textarea>					
					<?php if (isset ( $this->_tpl_vars['errorArray']['communicate_text'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['communicate_text']; ?>
</span><?php endif; ?>					  
                </div>
				<?php endif; ?>
				<?php endif; ?>				
                <?php if (isset ( $this->_tpl_vars['templateData'] )): ?>
				<?php if (! isset ( $this->_tpl_vars['communicateData'] )): ?>
				<div class="form-group"><button type="button" class="btn btn-primary" onclick="submitForm(); return false;">Validate and Submit</button></div>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['communicateData']['communicate_active'] == '0'): ?>
				<div class="form-group"><button type="button" class="btn btn-primary" onclick="submitForm(); return false;">Validate and Submit</button></div>
				<?php endif; ?>
				<?php else: ?>
				<div class="form-group"><button type="button" class="btn btn-primary" onclick="getTemplate(); return false;">Get Template</button></div>
				<?php endif; ?>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/communicate/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<?php if (isset ( $this->_tpl_vars['communicateData'] )): ?>
				<a href="/communicate/details.php?code=<?php echo $this->_tpl_vars['communicateData']['communicate_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/communicate/link.php?code=<?php echo $this->_tpl_vars['communicateData']['communicate_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Wards to Send
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/communicate/comm.php?code=<?php echo $this->_tpl_vars['communicateData']['communicate_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Comms
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

<script type="text/javascript" language="javascript" src="/library/javascript/nicedit/nicEdit.js"></script>
<?php echo '
<script type="text/javascript">
'; ?>
<?php if (! isset ( $this->_tpl_vars['templateData'] )): ?><?php echo '
function getTemplate() {
	if($(\'#template_code\').val() != \'\') {
		window.location.href = window.location.href+\'?template=\'+$(\'#template_code\').val();
	} else {
		$.howl ({
		  type: \'danger\'
		  , title: \'Action Please\'
		  , content: \'Please select a template to link to a communicate first.\'
		  , sticky: $(this).data (\'sticky\')
		  , lifetime: 7500
		  , iconCls: $(this).data (\'icon\')
		});	
	}
}
'; ?>
<?php endif; ?><?php echo '
'; ?>
<?php if (isset ( $this->_tpl_vars['templateData'] )): ?><?php echo '
function submitForm() {
	'; ?>
<?php if ($this->_tpl_vars['templateData']['template_category'] == 'EMAIL'): ?>nicEditors.findEditor('communicate_text').saveContent();<?php endif; ?><?php echo '
	document.forms.detailsForm.submit();					 
}
$(document).ready(function() {
	'; ?>
<?php if ($this->_tpl_vars['templateData']['template_category'] == 'EMAIL'): ?><?php echo '
	new nicEditor({
		iconsPath : \'/library/javascript/nicedit/nicEditorIcons.gif\',
		maxHeight : \'800\',
		uploadURI : \'/library/javascript/nicedit/nicUpload.php\'
	}).panelInstance(\'communicate_text\');
	'; ?>
	
	<?php else: ?>
	messageCount();
	<?php endif; ?><?php echo '
});
'; ?>
<?php if ($this->_tpl_vars['templateData']['template_category'] == 'SMS'): ?><?php echo '
function messageCount() {
	$("#communicate_text").keyup(function () {
		var i = $("#communicate_text").val().length;
		$("#communicate_count").html(i+\' characters entered.\');
		if (i > 140) {
			$(\'#communicate_count\').removeClass(\'success\');
			$(\'#communicate_count\').addClass(\'error\');
		} else if(i == 0) {
			$(\'#communicate_count\').removeClass(\'success\');
			$(\'#communicate_count\').addClass(\'error\');
		} else {
			$(\'#communicate_count\').removeClass(\'error\');
			$(\'#communicate_count\').addClass(\'success\');
		} 
	});	
	return false;
}
'; ?>
<?php endif; ?><?php echo '
'; ?>
<?php endif; ?><?php echo '
</script>
'; ?>

</html>