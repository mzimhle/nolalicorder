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
	{include_php file='includes/css.php'}	
	<link rel="stylesheet" href="/css/mejs-controls.css" media="screen">		
</head>
<body>
{include_php file='includes/header.php'}
<div class="container">
  <div class="content">
    <div class="content-container">
	<div class="content-header">
	<h2 class="content-header-title">Schedule</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">{$activeAccount.account_name}</a></li>
		<li><a href="/schedule/">Schedule</a></li>
		<li><a href="/schedule/">{$scheduleData.course_name} - {$scheduleData.class_name}</a></li>
		<li><a href="/schedule/">{$scheduleData.schedule_date} From {$scheduleData.schedule_time_start} till {$scheduleData.schedule_time_end} at {$scheduleData.room_name}</a></li>
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
					<source src="{$config.site}{$scheduleData.schedule_video_path}" type="{$scheduleData.schedule_video_format}">
				</video>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
{include_php file='includes/footer.php'}
{include_php file='includes/javascript.php'}
<script src="/library/javascript/mediaelement-and-player.min.js"></script>
{literal}
<script type="text/javascript" language="Javascript">
$(document).ready(function() {
	$('video').mediaelementplayer({
		alwaysShowControls: false,
		videoVolume: 'horizontal',
		features: ['playpause','progress','volume','fullscreen']
	});
});
</script>
{/literal}
</body>
</html>