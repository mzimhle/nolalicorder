<?php /* Smarty version 2.6.20, created on 2018-05-19 23:11:05
         compiled from product/price.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'number_format', 'product/price.tpl', 51, false),)), $this); ?>
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
		<h2 class="content-header-title">Product</h2>
		<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/product/">Products</a></li>
		<li><a href="/product/details.php?code=<?php echo $this->_tpl_vars['productData']['product_code']; ?>
"><?php echo $this->_tpl_vars['productData']['product_name']; ?>
</a></li>
		<li class="active">Prices</li>
		</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3><i class="fa fa-tasks"></i>Price List</h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/product/price.php?code=<?php echo $this->_tpl_vars['productData']['product_code']; ?>
" method="POST" data-validate="parsley" class="form parsley-form">			
				<p>Below is a list of your past and present product prices. The green highlighted row is the current product price.</p>
				<table class="table table-bordered">	
					<thead>
						<tr>
							<td>ID</td>
							<td>Amount</td>
							<td>Quantity</td>
							<td>Start Date</td>
							<td>End Date</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
					<?php $_from = $this->_tpl_vars['priceData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
					<tr>
						<td <?php if ($this->_tpl_vars['item']['price_active'] == '1'): ?>class="success"<?php else: ?>class="error"<?php endif; ?>><?php echo $this->_tpl_vars['item']['price_id']; ?>
</td>
						<td <?php if ($this->_tpl_vars['item']['price_active'] == '1'): ?>class="success"<?php else: ?>class="error"<?php endif; ?>>R <?php echo ((is_array($_tmp=$this->_tpl_vars['item']['price_amount'])) ? $this->_run_mod_handler('number_format', true, $_tmp, 2, ".", ",") : number_format($_tmp, 2, ".", ",")); ?>
</td>
						<td <?php if ($this->_tpl_vars['item']['price_active'] == '1'): ?>class="success"<?php else: ?>class="error"<?php endif; ?>><?php echo $this->_tpl_vars['item']['price_quantity']; ?>
</td>
						<td <?php if ($this->_tpl_vars['item']['price_active'] == '1'): ?>class="success"<?php else: ?>class="error"<?php endif; ?>><?php echo $this->_tpl_vars['item']['price_date_start']; ?>
</td>
						<td <?php if ($this->_tpl_vars['item']['price_active'] == '1'): ?>class="success"<?php else: ?>class="error"<?php endif; ?>><?php echo $this->_tpl_vars['item']['price_date_end']; ?>
</td>
						<td <?php if ($this->_tpl_vars['item']['price_active'] == '1'): ?>class="success"<?php else: ?>class="error"<?php endif; ?>><?php if ($this->_tpl_vars['item']['price_active'] == '1'): ?><button onclick="deleteModal('<?php echo $this->_tpl_vars['item']['price_code']; ?>
', '<?php echo $this->_tpl_vars['productData']['product_code']; ?>
', 'price'); return false;">Deactivate</button><?php else: ?>N / A<?php endif; ?></td>
					</tr>			     
					<?php endforeach; else: ?>
						<tr>
							<td align="center" colspan="6">There are currently no items</td>
						</tr>					
					<?php endif; unset($_from); ?>
					</tbody>					  
				</table>
				<p>Add new price below</p>		
                <div class="form-group">
					<label for="price_amount">Price</label>
					<input type="text" id="price_amount" name="price_amount"  size="20" class="form-control" data-required="true"  />
					<?php if (isset ( $this->_tpl_vars['errorArray']['price_amount'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['price_amount']; ?>
</em><?php else: ?><em class="smalltext">Please add only number separated by a period ( . ) for cents.</em><?php endif; ?>
                </div>
                <div class="form-group">
					<label for="price_quantity">Quantity of these items, minimum is 1</label>
					<input type="text" id="price_quantity" name="price_quantity"  size="20" class="form-control" data-required="true" value="1" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['price_quantity'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['price_quantity']; ?>
</em><?php else: ?><em class="smalltext">This needs to be equal or greater than one item.</em><?php endif; ?>
                </div>							
                <div class="form-group"><button type="submit" class="btn btn-primary">Save</button></div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->		
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/product/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/product/details.php?code=<?php echo $this->_tpl_vars['productData']['product_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/product/price.php?code=<?php echo $this->_tpl_vars['productData']['product_code']; ?>
" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Price(s)
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

</html>