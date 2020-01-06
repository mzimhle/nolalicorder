<?php /* Smarty version 2.6.20, created on 2019-08-07 09:38:10
         compiled from template/default.tpl */ ?>
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
		<h2 class="content-header-title">Templates</h2>
		<ol class="breadcrumb">
			<li><a href="/">Home</a></li>
			<?php if (isset ( $this->_tpl_vars['activeAccount'] )): ?><li><a href="/account/details.php?code=<?php echo $this->_tpl_vars['activeAccount']['account_code']; ?>
"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li><?php endif; ?>
			<li><a href="/template/">Templates</a></li>
			<li class="active">List</li>
		</ol>
	</div>	
      <div class="row">
        <div class="col-sm-12">
		<button class="btn btn-secondary fr" type="button" onclick="link('/template/details.php'); return false;">Add a new template</button><br/ ><br />
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				Template list
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="#" method="POST" data-validate="parsley" class="form parsley-form">	
				<?php if (isset ( $this->_tpl_vars['activeAccount'] )): ?>These are <b><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</b> templates.<br /><br /><?php endif; ?>
				<table class="table table-bordered">	
					<thead>
						<tr>
							<td width="3%">Public</td>
							<td width="5%">Cipher</td>
							<td width="5%">Type</td>
							<td width="5%">Category</td>
							<td width="60%">View</td>
							<td width="3%"></td>
						</tr>
					</thead>
					<tbody>
					<?php $_from = $this->_tpl_vars['templateData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
					<tr>
						<td><?php if ($this->_tpl_vars['item']['template_public'] == '1'): ?>YES<?php else: ?>NO<?php endif; ?></td>
						<td><a href="/template/details.php?code=<?php echo $this->_tpl_vars['item']['template_code']; ?>
"><?php echo $this->_tpl_vars['item']['template_cipher']; ?>
</a></td>
						<td><?php echo $this->_tpl_vars['item']['template_type']; ?>
</td>
						<td><?php echo $this->_tpl_vars['item']['template_category']; ?>
</td>
						<td>
						<?php if ($this->_tpl_vars['item']['template_category'] == 'EMAIL'): ?>
						<a href="/template/view.php?code=<?php echo $this->_tpl_vars['item']['template_code']; ?>
" target="_blank"><?php echo $this->_tpl_vars['item']['template_subject']; ?>
</a>
						<?php else: ?>
						<?php echo $this->_tpl_vars['item']['template_message']; ?>

						<?php endif; ?>
						</td>
						<td><button value="Delete" class="btn btn-danger" onclick="deleteModal('<?php echo $this->_tpl_vars['item']['template_code']; ?>
', '', 'default'); return false;">Delete</button></td>
					</tr>			     
					<?php endforeach; else: ?>
					<tr><td align="center" colspan="6">There are currently no items</td></tr>
					<?php endif; unset($_from); ?>
					</tbody>					  
				</table>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->				
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/footer.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/javascript.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

</html>