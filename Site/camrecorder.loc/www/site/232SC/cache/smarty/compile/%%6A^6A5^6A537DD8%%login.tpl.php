<?php /* Smarty version 2.6.20, created on 2019-08-26 23:45:03
         compiled from login.tpl */ ?>
<!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]>
<!--> <!--<![endif]-->
<html lang="en">
<head>
	<!-- Basic Page Needs ––––––––––––––––––––––––––––––––––––––––––––––––––-->
	<meta charset="utf-8">
	<title><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- Mobile Specific Metas ––––––––––––––––––––––––––––––––––––––––––––––––––-->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- CSS –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link rel="stylesheet" type="text/css" href="/css/style.css">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-theme.css">
	<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
	<!-- Favicon –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<link rel="icon" type="image/png" href="/images/favicon.png">
	<!-- Javascript  –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<script type="text/javascript" src="/library/javascript/jquery.v2.1.3.js"></script>
	<script type="text/javascript" src="/library/javascript/bootstrap.min.js"></script>
</head>
<body>
	<!-- News Page Layout –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<div class="all_content news_layout animsition container-fluid">
		<div class="row">
			<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/header.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

			<div class="main_content container"><!-- main_content -->
				<div class="posts_sidebar clearfix"><!--Start Posts Areaa -->
					<div class="posts_areaa col-md-8"><!-- posts_areaa -->
						<div class="row">
							<div class="widget widget_login"><!-- Start widget Widget Login -->
								<h4 class="widget_title">Login</h4>
								<div class="widget_login">
									<p>Good day, please log in with your email address as well as your password to view your class videos.</p>
									<form>
										<label>Username ( Email address )</label>
										<input type="text" value="" id="login_participant_username" name="login_participant_username"  />
										<label>Password</label>
										<input type="password" value="" id="login_participant_password" name="login_participant_password" />
										<input type="button" value="Login" id="Participantlogin" name="Participantlogin" class="button" onclick="submitLogin(); return false;" /><br /><br />
										<div class="hamzh-alert red hide" id="login_errors"></div>	
									</form>
								</div>
							</div>
							<!-- End Widget Latest Tweets -->
							<?php echo '
							<script type="text/javascript" language="javascript">
							function submitLogin() {
								$(\'#Participantlogin\').val(\'Please wait....\');
								$.ajax({
									type: "POST",
									url: "/login.php",
									data: "participant_password="+$(\'#login_participant_password\').val()+"&participant_username="+$(\'#login_participant_username\').val()+"&Participantlogin=1",
									dataType: "json",
									success: function(data){
										if(data.length == 0) {
											window.location.href = \'/\';
										} else {
											var html = \'\';
											for(var i = 0; i < data.length; i++) {
												html += data[i]+\'<br />\';
											}
											$(\'#login_errors\').removeClass("hide");
											$(\'#login_errors\').addClass("show");
											$(\'#login_errors\').html(html);
										}
									}
								});
								$(\'#Participantlogin\').val(\'Login\');
								return false;
							}
							</script>
							'; ?>

						</div>
					</div><!--End Posts Areaa -->
				</div><!-- Posts And Sidebar -->
			</div><!-- main_content -->
			<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/footer.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

		</div><!-- End row -->
	</div><!-- End all_content -->
	<!-- End Document –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<!-- Javascript –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<script type="text/javascript" src="/library/javascript/modernizr.min.js"></script>
	<script type="text/javascript" src="/library/javascript/owl.carousel.js"></script>
	<script type="text/javascript" src="/library/javascript/isotope.js"></script>
	<script type="text/javascript" src="/library/javascript/jquery.jribbble-1.0.1.ugly.js"></script>
	<script type="text/javascript" src="/library/javascript/jquery.bxslider.min.js"></script>
	<script type="text/javascript" src="/library/javascript/hamzh.js"></script>
</body>
</html>