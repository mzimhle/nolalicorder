<?php /* Smarty version 2.6.20, created on 2019-08-27 14:02:50
         compiled from videos.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'videos.tpl', 70, false),)), $this); ?>
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
								<h1><?php echo $this->_tpl_vars['classData']['class_name']; ?>
 Videos</h1>
								<span class="title_divider"></span><br />
								<p>Below are all videos of the lecturers that occured in with this subjct.</p>
							</div><!-- // post_header -->
							<!-- block_posts block_5 -->
							<div class="block_posts block_5">
								<!-- block_inner -->
								<div class="block_inner" style="padding: 0 30px 0 30px;">
									<?php if ($this->_tpl_vars['paginator']['pageCount'] > 1): ?>
									<div class="pagination" style="float: right">
										<?php if ($this->_tpl_vars['paginator']['current'] > 1): ?>
										<a href="/videos.php?class=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
&page=<?php echo $this->_tpl_vars['paginator']['first']; ?>
">&laquo;</a>
										<?php endif; ?>									
										<?php $_from = $this->_tpl_vars['paginator']['pagesInRange']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
										<a href="/videos.php?class=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
&page=<?php echo $this->_tpl_vars['page']; ?>
" <?php if ($this->_tpl_vars['page'] == $this->_tpl_vars['paginator']['current']): ?>class="active"<?php endif; ?>><?php echo $this->_tpl_vars['page']; ?>
</a>
										<?php endforeach; endif; unset($_from); ?>
										<?php if ($this->_tpl_vars['paginator']['next'] != ''): ?>
										<a href="/videos.php?class=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
&page=<?php echo $this->_tpl_vars['paginator']['next']; ?>
">&raquo;</a>
										<?php endif; ?>										
									</div>	
									<?php endif; ?>								
									<?php $_from = $this->_tpl_vars['studentItems']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['student']):
?>
									<div style="float: left;  margin: 10px; width: 100%; border-style: solid; ">
										<video width="100%" height="100%" poster="/images/preview.png" class="responsive">
											<source src="<?php echo $this->_tpl_vars['config']['site']; ?>
<?php echo $this->_tpl_vars['student']['schedule_video_path']; ?>
" type="<?php echo $this->_tpl_vars['student']['schedule_video_format']; ?>
">
										</video>	
										<p style="margin: 10px;">
											<b><?php echo $this->_tpl_vars['student']['class_cipher']; ?>
 - <?php echo $this->_tpl_vars['student']['class_name']; ?>
, <?php echo $this->_tpl_vars['student']['course_name']; ?>
</b><br />
											<span><?php echo ((is_array($_tmp=$this->_tpl_vars['student']['schedule_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%A, %B %e, %Y") : smarty_modifier_date_format($_tmp, "%A, %B %e, %Y")); ?>
 <br />From <?php echo $this->_tpl_vars['student']['schedule_time_start']; ?>
 to <?php echo $this->_tpl_vars['student']['schedule_time_end']; ?>
</span><br />
											<b><?php echo $this->_tpl_vars['student']['teacher_name']; ?>
</b> was at <b><?php echo $this->_tpl_vars['student']['room_name']; ?>
, <?php echo $this->_tpl_vars['student']['campus_name']; ?>
</b><br />
										</p>
									</div>
									<?php endforeach; else: ?>
									<div class="hamzh-alert red">There are currently no videos uploaded for this class</div>	
									<?php endif; unset($_from); ?>
									<?php if ($this->_tpl_vars['paginator']['pageCount'] > 1): ?>
									<div class="pagination" style="float: right">
										<?php if ($this->_tpl_vars['paginator']['current'] > 1): ?>
										<a href="/videos.php?class=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
&page=<?php echo $this->_tpl_vars['paginator']['first']; ?>
">&laquo;</a>
										<?php endif; ?>									
										<?php $_from = $this->_tpl_vars['paginator']['pagesInRange']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
										<a href="/videos.php?class=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
&page=<?php echo $this->_tpl_vars['page']; ?>
" <?php if ($this->_tpl_vars['page'] == $this->_tpl_vars['paginator']['current']): ?>class="active"<?php endif; ?>><?php echo $this->_tpl_vars['page']; ?>
</a>
										<?php endforeach; endif; unset($_from); ?>
										<?php if ($this->_tpl_vars['paginator']['next'] != ''): ?>
										<a href="/videos.php?class=<?php echo $this->_tpl_vars['classData']['class_code']; ?>
&page=<?php echo $this->_tpl_vars['paginator']['next']; ?>
">&raquo;</a>
										<?php endif; ?>
									</div>
									<?php endif; ?>
								</div>
								<!-- // block_inner -->
							</div>
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