<?php /* Smarty version 2.6.20, created on 2019-09-07 23:01:07
         compiled from schedule/view.tpl */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Nolali - CamCorder</title>
	<meta charset="utf-8">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width">
	<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/css.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>
	
	<link rel="stylesheet" href="/css/mejs-controls.css" media="screen">		
</head>
<body>
<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/header.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<div class="container">
  <div class="content">
    <div class="content-container">
	<div class="content-header">
	<h2 class="content-header-title">Schedule</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>
		<li><a href="/schedule/">Schedule</a></li>
		<li><a href="/schedule/"><?php echo $this->_tpl_vars['scheduleData']['course_name']; ?>
 - <?php echo $this->_tpl_vars['scheduleData']['class_name']; ?>
</a></li>
		<li><a href="/schedule/"><?php echo $this->_tpl_vars['scheduleData']['schedule_date']; ?>
 From <?php echo $this->_tpl_vars['scheduleData']['schedule_time_start']; ?>
 till <?php echo $this->_tpl_vars['scheduleData']['schedule_time_end']; ?>
 at <?php echo $this->_tpl_vars['scheduleData']['room_name']; ?>
</a></li>
		<li class="active">Watch Video</li>
	</ol>
	</div>
      <div class="row">
        <div class="col-sm-12">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				Watch Video
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
				<video width="100%" height="100%" poster="/images/preview.png" class="responsive">
					<source src="<?php echo $this->_tpl_vars['config']['site']; ?>
<?php echo $this->_tpl_vars['scheduleData']['schedule_video_path']; ?>
" type="<?php echo $this->_tpl_vars['scheduleData']['schedule_video_format']; ?>
">
				</video>
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

<script src="/library/javascript/mediaelement-and-player.min.js"></script>
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