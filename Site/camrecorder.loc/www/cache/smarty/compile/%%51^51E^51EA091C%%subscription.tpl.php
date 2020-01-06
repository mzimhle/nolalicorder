<?php /* Smarty version 2.6.20, created on 2018-05-02 14:50:53
         compiled from participant/subscription.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'participant/subscription.tpl', 42, false),)), $this); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Mailbok - Communication Tool</title>
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
	<h2 class="content-header-title">Participants</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/participant/">Participants</a></li>
		<li><a href="/participant/details.php?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
"><?php echo $this->_tpl_vars['participantData']['participant_name']; ?>
 <?php echo $this->_tpl_vars['participantData']['participant_surname']; ?>
</a></li>
		<li class="active">Subscriptions</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
					Link subscription to <?php echo $this->_tpl_vars['participantData']['participant_name']; ?>
 <?php echo $this->_tpl_vars['participantData']['participant_surname']; ?>

              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/participant/subscription.php?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
" method="POST" data-validate="parsley" class="form parsley-form">			
				<div class="form-group">
					<label for="subscription_code">Choose a subscription</label>
					<select id="subscription_code" name="subscription_code" class="form-control">
					<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['subscriptionPairs']), $this);?>

					</select>
					<?php if (isset ( $this->_tpl_vars['errorArray']['subscription_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['subscription_code']; ?>
</span><?php endif; ?>					  
				</div>
                <div class="form-group"><button type="submit" class="btn btn-primary">Link</button></div>
				<div class="form-group">
					<label for="subscription_code">Currently selected subscriptions</label>				  
					<table class="table table-bordered">	
						<thead>
							<tr>
								<th>Name</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php $_from = $this->_tpl_vars['linkData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
							<tr>
								<td><?php echo $this->_tpl_vars['item']['subscription_name']; ?>
</td>
								<td>
								<?php if ($this->_tpl_vars['item']['subscription_primary'] == '0'): ?>
								<button value='Delete' class='btn btn-danger' onclick="deleteModal('<?php echo $this->_tpl_vars['item']['link_code']; ?>
', '<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
', 'subscription'); return false;">Delete</button><?php else: ?>N/A<?php endif; ?>
								</td>
							</tr>
							<?php endforeach; else: ?>
							<tr><td colspan="2">There is no subscription selectedd yet.</td></tr>
							<?php endif; unset($_from); ?>
						</tbody>
					</table>
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
				<a href="/participant/subscription.php?code=<?php echo $this->_tpl_vars['participantData']['participant_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Subscriptions
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