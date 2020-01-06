<?php /* Smarty version 2.6.20, created on 2019-08-26 23:56:02
         compiled from default.tpl */ ?>
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
	<script src="/library/javascript/mediaelement-and-player.min.js"></script>
	<link rel="stylesheet" href="/css/mejs-controls.css" media="screen">	
	<?php echo '
	<style type="text/css">
		.mejs-container { margin: 0 auto; }
	</style>	
	'; ?>

</head>
<body>
  <!-- News Page Layout –––––––––––––––––––––––––––––––––––––––––––––––––– -->
	<div class="all_content news_layout animsition container-fluid">
		<div class="row">
			<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/header.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

			<div class="main_content container"><!-- main_content -->
				<div class="posts_sidebar clearfix"><!--Start Posts Areaa -->
					<div class="posts_areaa col-md-12"><!-- posts_areaa -->
						<div class="row">
							<div class="post_header"><!-- post_header -->
								<h1>Your Classes</h1>
								<span class="title_divider"></span><br />
								<p>Welcome <strong><?php echo $this->_tpl_vars['activeParticipant']['participant_name']; ?>
</strong>, below is a list of all your classes. Please select any of them and you will see available videos.</p>
							</div><!-- // post_header -->
							<!-- block_posts block_6 -->
							<?php if (isset ( $this->_tpl_vars['studentData'] )): ?>
							<!-- block_posts block_5 -->
							<div class="block_posts block_5">
								<!-- block_inner -->
								<div class="block_inner" style="padding: 0 30px 0 30px;">
								<?php $_from = $this->_tpl_vars['studentData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['student']):
?>
								<div class="col-sm-4">
									<div class="faculty-logo">
										<a href="/videos.php?class=<?php echo $this->_tpl_vars['student']['class_code']; ?>
">
											<img src="/images/class.png" alt="<?php echo $this->_tpl_vars['student']['class_name']; ?>
, <?php echo $this->_tpl_vars['student']['course_name']; ?>
" title="<?php echo $this->_tpl_vars['student']['class_name']; ?>
, <?php echo $this->_tpl_vars['student']['course_name']; ?>
" class="img-responsive" onerror="this.src = 'http://placehold.it/213x100';">
											<?php echo $this->_tpl_vars['student']['class_name']; ?>
, <?php echo $this->_tpl_vars['student']['course_name']; ?>
, <?php echo $this->_tpl_vars['student']['department_name']; ?>

										</a>
									</div>
								</div>
								<?php endforeach; endif; unset($_from); ?>
								</div>
								<!-- // block_inner -->
							</div>
							<!-- // block_posts block_5 -->
							<?php endif; ?>
							<!-- // block_posts block_6 -->
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
<?php echo '
<script type="text/javascript" language="Javascript">
$(document).ready(function() {
	$(\'video\').mediaelementplayer({
		alwaysShowControls: false,
		videoVolume: \'horizontal\',
		features: [\'playpause\',\'progress\',\'volume\',\'fullscreen\']
	});
});
</script>
'; ?>

</body>
</html>