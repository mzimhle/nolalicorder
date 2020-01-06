<?php /* Smarty version 2.6.20, created on 2019-08-05 19:28:22
         compiled from design/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'design/default.tpl', 52, false),)), $this); ?>
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
		<h2 class="content-header-title">Designs</h2>
		<ol class="breadcrumb">
			<li><a href="/">Home</a></li>
			<li><a href="/account/details.php?code=<?php echo $this->_tpl_vars['activeAccount']['account_code']; ?>
"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>
			<li><a href="/design/">Designs</a></li>
			<li class="active">List</li>
		</ol>
	</div>	
      <div class="row">
        <div class="col-sm-12">
		<button class="btn btn-secondary fr" type="button" onclick="link('/design/details.php'); return false;">Add a new design</button><br/ ><br />
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				Design list
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="#" method="POST" data-validate="parsley" class="form parsley-form">	
				These are <b><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</b> designs.<br /><br />
				<table class="table table-bordered">	
					<thead>
						<tr>
							<td width="10%">Added</td>
							<td width="60%">Name</td>
							<td width="3%">View</td>
							<td width="3%"></td>
						</tr>
					</thead>
					<tbody>
					<?php $_from = $this->_tpl_vars['designData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
					<tr>
						<td><?php echo ((is_array($_tmp=$this->_tpl_vars['item']['design_added'])) ? $this->_run_mod_handler('date_format', true, $_tmp) : smarty_modifier_date_format($_tmp)); ?>
</td>
						<td><a href="/design/details.php?code=<?php echo $this->_tpl_vars['item']['design_code']; ?>
"><?php echo $this->_tpl_vars['item']['design_name']; ?>
</a></td>
						<td>
							<a href="/design/view.php?code=<?php echo $this->_tpl_vars['item']['design_code']; ?>
" target="_blank">View</a>
						</td>
						<td><button value="Delete" class="btn btn-danger" onclick="deleteModal('<?php echo $this->_tpl_vars['item']['design_code']; ?>
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