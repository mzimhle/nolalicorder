<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Account System</title>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	{include_php file='includes/css.php'}
</head>
<body>
{include_php file='includes/header.php'}
<div class="container">
  <div class="content">
    <div class="content-container">  
      <div class="row">
        <div class="col-md-12">
          <div class="portlet">
            <div class="portlet-header">
              <h3><i class="fa fa-calendar"></i>Administration</h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/" method="POST" data-validate="parsley" class="form parsley-form">
				<p>Please select a account before you can continue.</p>
                <div class="form-group">
					<label for="id">Select a account</label>
					<select id="account_code" name="account_code" class="form-control">
						<option value=""> ---- </option>
						{html_options options=$accountPairs selected=$account}
					</select>
					{if isset($errorArray.account_code)}<br /><span class="error">{$errorArray.account_code}</span>{/if}
                </div>				
				<div class="form-group"><button type="submit" class="btn btn-primary">Enter</button></div>				
			  </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col-md-8 -->
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
{include_php file='includes/footer.php'}
{include_php file='includes/javascript.php'}
</body>
</html>
