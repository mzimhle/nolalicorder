<?php /* Smarty version 2.6.20, created on 2018-05-20 09:37:51
         compiled from product/details.tpl */ ?>
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
		<li><a href="/product/">Product</a></li>
		<li><?php if (isset ( $this->_tpl_vars['productData'] )): ?><?php echo $this->_tpl_vars['productData']['product_name']; ?>
<?php else: ?>Add a product<?php endif; ?></li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				<?php if (isset ( $this->_tpl_vars['productData'] )): ?><?php echo $this->_tpl_vars['productData']['product_name']; ?>
<?php else: ?>Add a product<?php endif; ?>
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" name="detailsForm" action="/product/details.php<?php if (isset ( $this->_tpl_vars['productData'] )): ?>?code=<?php echo $this->_tpl_vars['productData']['product_code']; ?>
<?php endif; ?>" method="POST" data-validate="parsley" class="form parsley-form">	
                <div class="form-group">
					<label for="product_cipher">Cipher</label>
					<input type="text" id="product_cipher" name="product_cipher" class="form-control" value="<?php echo $this->_tpl_vars['productData']['product_cipher']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['product_cipher'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['product_cipher']; ?>
</span><?php endif; ?>	
                </div>			  
                <div class="form-group">
					<label for="product_name">Name</label>
					<input type="text" id="product_name" name="product_name" class="form-control" value="<?php echo $this->_tpl_vars['productData']['product_name']; ?>
" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['product_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['product_name']; ?>
</span><?php endif; ?>	
                </div>
                <div class="form-group">
					<label for="product_name">Description</label>
					<textarea id="product_description" name="product_description" class="form-control" rows="4"><?php echo $this->_tpl_vars['productData']['product_description']; ?>
</textarea>
					<?php if (isset ( $this->_tpl_vars['errorArray']['product_description'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['product_description']; ?>
</span><?php endif; ?>	
                </div>				
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['productData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>				
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
				<?php if (isset ( $this->_tpl_vars['productData'] )): ?>
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