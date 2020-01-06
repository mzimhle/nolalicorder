<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Communication Tool</title>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
{include_php file='includes/css.php'}
</head>
<body class="account-bg">
<div class="account-wrapper">
  <div class="account-logo">
    <img src="/images/logo.png" title="Communication Tool" alt="Communication Tool" />
  </div>
    <div class="account-body">
      <h3 class="account-body-title">Communication Tool</h3>
      <h5 class="account-body-subtitle">Please sign in to get access.</h5>	
      <form class="form account-form" method="POST" action="login.php">
        <div class="form-group">
          <label for="username" class="placeholder">Username</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="Email Address" tabindex="1">
        </div> <!-- /.form-group -->
        <div class="form-group">
          <label for="password" class="placeholder">Password</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="Password" tabindex="2">
        </div> <!-- /.form-group -->		
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-block btn-lg" tabindex="4">
            Signin &nbsp; <i class="fa fa-play-circle"></i>
          </button>
        </div> <!-- /.form-group -->
			<span class="error">{$loginmessage}</span>
      </form>
    </div> <!-- /.account-body -->
  </div> <!-- /.account-wrapper -->
	{include_php file='includes/js.php'}
</body>
</html>
