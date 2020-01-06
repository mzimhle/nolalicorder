<?php /* Smarty version 2.6.20, created on 2018-05-08 11:45:14
         compiled from campaign/email.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'campaign/email.tpl', 106, false),)), $this); ?>
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
		<li><a href="/campaign/details.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
"><?php echo $this->_tpl_vars['campaignData']['campaign_name']; ?>
</a></li>
		<li class="active">Send eMails</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php echo $this->_tpl_vars['campaignData']['campaign_name']; ?>

              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
				<form data-validate="parsley" class="form parsley-form">
				<p><b>Email templates</b></p>
				<p>Below is a list of all emails created for this campaign.</p>
				<table class="table table-bordered">	
					<thead>
						<tr>
							<td></td>
							<td width="90%">Subject</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
					<?php $_from = $this->_tpl_vars['templateData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
						<tr>
							<td align="left">
								<?php if ($this->_tpl_vars['item']['template_active'] == '1'): ?>
								Sent
								<?php else: ?>
								<?php if ($this->_tpl_vars['item']['subscription_count'] != '0'): ?>
								<button value='Send' class='btn btn-success' onclick="sendTemplateModal('<?php echo $this->_tpl_vars['item']['template_code']; ?>
'); return false;">Send</button>
								<?php else: ?>
								<span class="error">No Subscriptions</span>
								<?php endif; ?>
								<?php endif; ?>
							</td>						
							<td align="left">
								<?php if ($this->_tpl_vars['item']['template_active'] == '1'): ?>
								<p class="success">The message was sent to the selected subscription subscribers.</p>
								<p>To view who received the EMAIL on the list, <a href="/campaign/comm.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
&template=<?php echo $this->_tpl_vars['item']['template_code']; ?>
">please click on this link</a></p>
								<i><b>"<?php echo $this->_tpl_vars['item']['template_subject']; ?>
"</b></i>
								<?php else: ?>
								<i id="message_<?php echo $this->_tpl_vars['item']['template_code']; ?>
"><b><a href="/campaign/email.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
&updatelink=<?php echo $this->_tpl_vars['item']['template_code']; ?>
">"<?php echo $this->_tpl_vars['item']['template_subject']; ?>
"</a></b></i>
								<?php endif; ?>
								<p>Template: <b><?php echo $this->_tpl_vars['item']['template_cipher']; ?>
</b></p>
								<p>Link subscriptions to send to:</p>
								<table 
								class="table table-striped table-bordered" 
								>
									<thead>
										<tr>
											<th <?php if ($this->_tpl_vars['item']['template_active'] == '1'): ?>colspan="2"<?php endif; ?>>Subscription Name</th>
											<?php if ($this->_tpl_vars['item']['template_active'] == '0'): ?>
											<th></th>
											<?php endif; ?>
										</tr>
									</thead>							
								   <tbody>
								  <?php $_from = $this->_tpl_vars['item']['subscriptions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link']):
?>
								  <tr>
									<td align="left" <?php if ($this->_tpl_vars['item']['template_active'] == '1'): ?>colspan="2"<?php endif; ?>><?php echo $this->_tpl_vars['link']['subscription_name']; ?>
</td>
									<?php if ($this->_tpl_vars['item']['template_active'] == '0'): ?>
									<td>
										<button value="Delete" class="btn btn-danger" onclick="deleteModal('<?php echo $this->_tpl_vars['link']['link_code']; ?>
', '<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
', 'email'); return false;">Delete</button>
									</td>
									<?php endif; ?>
								  </tr>
								<?php endforeach; else: ?>
								<tr><td colspan="2">No tags have been added yet</td></tr>
								  <?php endif; unset($_from); ?>
								  </tbody>
								</table>

								<p><a href="/campaign/view_email.php?link=<?php echo $this->_tpl_vars['item']['template_code']; ?>
" target="_blank">Preview Template</a></p>

								<?php if ($this->_tpl_vars['item']['template_active'] == '0'): ?>
								<div class="form-group">
								  <label for="subscription_code_<?php echo $this->_tpl_vars['item']['template_code']; ?>
">Add subscription</label>
								  <select id="subscription_code_<?php echo $this->_tpl_vars['item']['template_code']; ?>
" name="subscription_code_<?php echo $this->_tpl_vars['item']['template_code']; ?>
" class="form-control">
									<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['subscriptionPairs']), $this);?>

								  </select>
								  <?php if (isset ( $this->_tpl_vars['errorArray']['subscription_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['subscription_code']; ?>
</span><?php endif; ?>					  
								</div>					
								<div class="form-group">
								<button value="Add" type="button" class="btn btn-secondary" onclick="linkSubscriptionModal('<?php echo $this->_tpl_vars['item']['template_code']; ?>
'); return false;">Add</button>
								</div>	
								<?php endif; ?>
							</td>
							<td align="left">
								<?php if ($this->_tpl_vars['item']['template_active'] == '1'): ?>
								Sent
								<?php else: ?>							
								<button value='Delete' class='btn btn-danger' onclick="deleteModal('<?php echo $this->_tpl_vars['item']['template_code']; ?>
', '<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
', 'email'); return false;">Delete</button>
								<?php endif; ?>
							</td>
						</tr>			     
					<?php endforeach; else: ?>
						<tr>
							<td align="center" colspan="3">There are currently no items</td>
						</tr>					
					<?php endif; unset($_from); ?>
					</tbody>					  
				</table>			
              </form>
			<p><b>Add / update email template</b></p>
			<p>Below is where you can add / update an email template</p>			  
              <form id="detailsForm" name="detailsForm" action="/campaign/email.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
<?php if (isset ( $this->_tpl_vars['templateUpdate'] )): ?>&updatelink=<?php echo $this->_tpl_vars['templateUpdate']['template_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form">	
				<?php if (isset ( $this->_tpl_vars['templateUpdate'] )): ?>
                <div class="form-group">
					<p>Update the mailer with subject:</p>
					<p class="success"><?php echo $this->_tpl_vars['templateUpdate']['template_subject']; ?>
</p>				  
                </div>		
				<?php endif; ?>
                <div class="form-group">
					<label for="template_subject">Subject</label>
					<input type="text" id="template_subject" name="template_subject" class="form-control" value="<?php echo $this->_tpl_vars['templateUpdate']['template_subject']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_subject'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_subject']; ?>
</span><?php else: ?><span class="smalltext">Please add the subject of this email/sms</span><?php endif; ?>					  
                </div>			
                <div class="form-group">
					<label for="template_message">Message</label>
					<textarea type="text" id="template_message" name="template_message" class="form-control" data-required="true" style="width: 100%; height: 700px;"><?php echo $this->_tpl_vars['templateUpdate']['template_message']; ?>
</textarea>
					<?php if (isset ( $this->_tpl_vars['errorArray']['template_message'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['template_message']; ?>
</span><?php endif; ?>					  
                </div>
				<div class="form-group"><button type="button" class="btn btn-primary" onclick="submitForm(); return false;"><?php if (isset ( $this->_tpl_vars['templateUpdate'] )): ?>Update<?php else: ?>Add<?php endif; ?></button></div>
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
				<a class="list-group-item" href="/campaign/comm.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Communication Sent Out
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>				
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
function submitForm() {
	nicEditors.findEditor(\'template_message\').saveContent();
	document.forms.detailsForm.submit();					 
}
$(document).ready(function() {
	new nicEditor({
		iconsPath : \'/library/javascript/nicedit/nicEditorIcons.gif\',
		maxHeight : \'800\',
		uploadURI : \'/library/javascript/nicedit/nicUpload.php\'
	}).panelInstance(\'template_message\');
});

function linkSubscriptionModal(templatecode) {
	var message 			= $(\'#message_\'+templatecode).html();
	var subscriptioncode 	= $(\'#subscription_code_\'+templatecode+\' :selected\').val();
	var subscriptiontext 	= $(\'#subscription_code_\'+templatecode+\' :selected\').text();

	$(\'#templatecode\').val(templatecode);
	$(\'#modal_message\').html(message);
	$(\'#subscriptioncode\').val(subscriptioncode);
	$(\'#subscriptiontext\').html(subscriptiontext);

	$(\'#linkSubscriptionModal\').modal(\'show\');
	return false;
}

function linkSubscription() {
	
	var code 			= $(\'#code\').val();
	var templatecode	= $(\'#templatecode\').val();
	var subscription	= $(\'#subscriptioncode\').val();
	
	$.ajax({
		type: "GET",
		url: "email.php?code='; ?>
<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
<?php echo '",
		data: "subscriptoin_link=1&templatecode="+templatecode+"&subscription="+subscription,
		dataType: "json",
		success: function(data){
			if(data.result == 1) {
				window.location.href = window.location.href;
			} else {
				$(\'#linkSubscriptionModal\').modal(\'hide\');
				$.howl ({
				  type: \'info\'
				  , title: \'Notification\'
				  , content: data.error
				  , sticky: $(this).data (\'sticky\')
				  , lifetime: 7500
				  , iconCls: $(this).data (\'icon\')
				});	
			}
		}
	});								

	return false;
}	
function sendTemplateModal(id) {
	$(\'#linkcode\').val(id);
	$(\'#sendTemplateModal\').modal(\'show\');
	return false;
}	
	
function sendTemplate() {

	var code	= $(\'#linkcode\').val();

	$.ajax({
		type: "GET",
		url: "email.php?code='; ?>
<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
<?php echo '",
		data: "sendlink="+code,
		dataType: "json",
		success: function(data){		
			$(\'#sendTemplateModal\').modal(\'hide\');
			if(data.result == 1) {				
				if(typeof oTable === \'undefined\') {
					window.location.href = window.location.href;
				} else {
					oTable.fnDraw(false);
				}
			} else {
				$.howl ({
				  type: \'info\'
				  , title: \'Notification\'
				  , content: data.error
				  , sticky: $(this).data (\'sticky\')
				  , lifetime: 7500
				  , iconCls: $(this).data (\'icon\')
				});	
			}
		}
	});								

	return false;
}	
</script>
'; ?>

<!-- Modal -->
<div class="modal fade" id="sendTemplateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
			Are you sure you want to send out this message?<br />
			Please also make sure you have selected all the subscriptions you need.
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
				<button class="btn btn-warning" type="button" onclick="javascript:sendTemplate();">I'm sure, send it</button>
				<input type="hidden" id="linkcode" name="linkcode" value="" />
			</div>
		</div>
	</div>
</div>
<!-- modal -->
<!-- Modal -->
<div class="modal fade" id="linkSubscriptionModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add subscription</h4>
			</div>
			<div class="modal-body">
				Are you sure you want this subscription, <b id="subscriptiontext"></b>, link to this message: <br /><br /><i id="modal_message"></i></b>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
				<button class="btn btn-warning" type="button" onclick="javascript:linkSubscription();">Link Subscription to message</button>
				<input type="hidden" id="code" name="code" value="<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
" />
				<input type="hidden" id="subscriptioncode" name="subscriptioncode" value="" />
				<input type="hidden" id="templatecode" name="templatecode" value=""/>
			</div>
		</div>
	</div>
</div>
<!-- modal -->
</html>