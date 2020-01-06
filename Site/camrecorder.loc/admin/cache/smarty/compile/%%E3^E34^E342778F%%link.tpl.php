<?php /* Smarty version 2.6.20, created on 2018-04-15 18:15:52
         compiled from communicate/link.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'communicate/link.tpl', 46, false),)), $this); ?>
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
			<li class="active">Wards to send</li>
		</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>Communicate List
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="linkForm" name="linkForm" action="/communicate/link.php?code=<?php echo $this->_tpl_vars['communicateData']['communicate_code']; ?>
" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">
				<td><?php if ($this->_tpl_vars['communicateData']['communicate_active'] == '0'): ?>
				<p>Add new ward(s) below</p>	
				<div class="row">
					<div class="col-sm-12">				
						<div class="form-group">
							<label for="link_child_code">Ward</label>
							<select id="link_child_code" name="link_child_code" class="form-control">
								<option value=""> ---- </option>
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['demarcationPairs']), $this);?>

							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['link_child_code'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['link_child_code']; ?>
</em><?php else: ?><em class="smalltext">Ward to send to.</em><?php endif; ?>
						</div>	
					</div>
				</div>				
                <div class="form-group">
				<button type="button" class="btn btn-primary" onclick="submitForm('addone'); return false;">Save Link</button>
				<button type="button" class="btn btn-primary fr" onclick="submitForm('addall'); return false;">Link All</button></div>
				<input type="hidden" value="" id="add" name="add" />
				<?php endif; ?>				
				<p>Below is a list of all the wards to send this communication to.</p>
				<table class="table table-bordered">	
					<thead>
						<tr>
							<td width="10%">Ward ID</td>
							<td width="15%">Ward Name</td>
							<td width="5%"></td>
						</tr>
					</thead>
					<tbody>
					<?php $_from = $this->_tpl_vars['linkData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
					<tr>
						<td><?php echo $this->_tpl_vars['item']['demarcation_id']; ?>
</td>
						<td><?php echo $this->_tpl_vars['item']['demarcation_name']; ?>
</td>
						<td><?php if ($this->_tpl_vars['communicateData']['communicate_active'] == '0'): ?><button onclick="deleteModal('<?php echo $this->_tpl_vars['item']['link_code']; ?>
', '<?php echo $this->_tpl_vars['communicateData']['communicate_code']; ?>
', 'link'); return false;">Delete</button><?php else: ?>N/A<?php endif; ?></td>
					</tr>
					<?php endforeach; else: ?>
					<tr><td align="center" colspan="3">There are currently no items</td></tr>					
					<?php endif; unset($_from); ?>
					</tbody>					  
				</table>
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
<script type="text/javascript" language="javascript">
function submitForm(type) {
	$(\'#add\').val(type);
	document.forms.linkForm.submit();
	return false;
}
</script>
'; ?>

</body>
</html>