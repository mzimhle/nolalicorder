<?php /* Smarty version 2.6.20, created on 2019-08-07 22:57:06
         compiled from schedule/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'schedule/default.tpl', 99, false),)), $this); ?>
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
	
	<?php echo '
	<style type="text/css">
		.schedule-rows td {
		  width: 80px;
		  height: 30px;
		  margin: 3px;
		  padding: 5px;
		  background-color: #3498DB;
		  cursor: pointer;
		}
		.schedule-rows td:first-child {
		  background-color: transparent;
		  text-align: right;
		  position: relative;
		  top: -12px;
		}
		.schedule-rows td[data-selected],
		.schedule-rows td[data-selecting] { background-color: #E74C3C; }
		.schedule-rows td[data-disabled] { opacity: 0.55; }
	</style>
	'; ?>

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
		<li><a href="/participant/">Schedule</a></li>
		<li class="active">Schedules</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-12">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				Schedule of <?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>

              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/grade/schedule/" method="POST" data-validate="parsley" class="form parsley-form">
				<div class="row">
					<div class="col-sm-2">				  
						<div class="form-group">
							<label for="schedule_day">Choose a day</label>
							<select id="schedule_day" name="schedule_day" class="form-control">
								<option value=""> --- Select a day --- </option>
								<option value="1"> Monday </option>
								<option value="2"> Tuesday</option>
								<option value="3"> Wednsday </option>
								<option value="4"> Thursday </option>
								<option value="5"> Friday </option>
								<option value="6"> Saterday </option>
								<option value="7"> Sunday </option>
							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['schedule_day'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['schedule_day']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
					<div class="col-sm-5">				
						<div class="form-group">
							<label for="class_name">Select a class from below ( add first 3 characters. )</label>
							<input type="text" id="class_name" name="class_name"  size="20" class="form-control" />
							<input type="hidden" id="class_code" name="class_code" size="20"  />
							<?php if (isset ( $this->_tpl_vars['errorArray']['class_code'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['class_code']; ?>
</em><?php endif; ?>
						</div>	
					</div>		
					<div class="col-sm-5">				
						<div class="form-group">
							<label for="participant_name">Select a teacher from below ( add first 3 characters. )</label>
							<input type="text" id="participant_name" name="participant_name"  size="20" class="form-control" />
							<input type="hidden" id="participant_code" name="participant_code" size="20"  />
							<?php if (isset ( $this->_tpl_vars['errorArray']['participant_code'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['participant_code']; ?>
</em><?php endif; ?>
						</div>	
					</div>						
				</div>	
				<div class="row">
					<div class="col-sm-8">	
						<div class="form-group">
							<label for="room_code">Room</label>
							<select id="room_code" name="room_code" class="form-control">
								<option value=""> ------- </option>
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['roomPairs']), $this);?>

							</select>				  
						</div>
					</div>							
					<div class="col-sm-2">				
						<div class="form-group">
							<label for="schedule_time_start">Class start time</label>
							<input type="text" id="schedule_time_start" name="schedule_time_start"  size="20" class="form-control" placeholder="E.G. 07:30" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['schedule_time_start'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['schedule_time_start']; ?>
</em><?php endif; ?>
						</div>	
					</div>
					<div class="col-sm-2">				
						<div class="form-group">
							<label for="schedule_time_end">Class end time</label>
							<input type="text" id="schedule_time_end" name="schedule_time_end"  size="20" class="form-control" placeholder="E.G. 08:30" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['schedule_time_end'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['schedule_time_end']; ?>
</em><?php endif; ?>
						</div>	
					</div>					
				</div>		
				<div class="row"><div class="col-sm-12"><div class="form-group error" id="schedule_error" name="schedule_error"></div></div></div>
                <div class="form-group"><button type="button" onclick="addSchedule(); return false;" class="btn btn-primary">Add</button></div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-hand-o-up"></i>
				Participant List
              </h3>
            </div> <!-- /.portlet-header -->	
			<div class="portlet-content">
				<div class="row">
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="filter_day">Day of the week</label>
							<select id="filter_day" name="filter_day" class="form-control">
								<option value=""> ------- </option>
								<option value=""> --- Select a day --- </option>
								<option value="1"> Monday </option>
								<option value="2"> Tuesday</option>
								<option value="3"> Wednsday </option>
								<option value="4"> Thursday </option>
								<option value="5"> Friday </option>
								<option value="6"> Saterday </option>
								<option value="7"> Sunday </option>
							</select>				  
						</div>
					</div>					
					<div class="col-sm-3">	
						<div class="form-group">
							<label for="filter_room">Room</label>
							<select id="filter_room" name="filter_room" class="form-control">
								<option value=""> ------- </option>
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['roomPairs']), $this);?>

							</select>				  
						</div>
					</div>
					<div class="col-sm-7">				
						<div class="form-group">
							<label for="filter_search">Search by teacher, class and course</label>
							<input type="text" id="filter_search" name="filter_search" class="form-control" value="" />
						</div>
					</div>					
				</div>							
				<div class="form-group">
					<button type="button" onclick="getRecords(); return false;" class="btn btn-primary">Search</button>
					<button type="button" onclick="csv(); return false;" class="btn">Download CSV</button>
				</div>
				<p>There are <span id="result_count" name="result_count" class="success">0</span> records showing. We are displaying <span id="result_display" name="result_display" class="success">0</span> records per page.</p>
				<div id="tableContent" align="center">
					<!-- Start Content Table -->
					<div class="content_table">
						<img src="/images/ajax_loader.gif" />
					</div>
					 <!-- End Content Table -->	
				</div>
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

<?php echo '
<script type="text/javascript" language="javascript">
$(document).ready(function(){ 
	$( "#class_name" ).autocomplete({
		source: "/feeds/class.php",
		minLength: 2,
		select: function( event, ui ) {
			if(ui.item.id == \'\') {
				$(\'#class_name\').html(\'\');
				$(\'#class_code\').val(\'\');					
			} else {
				$(\'#class_name\').html(\'<b>\' + ui.item.value + \'</b>\');
				$(\'#class_code\').val(ui.item.id);	
			}
			$(\'#class_name\').val(\'\');										
		}
	});

	$( "#participant_name" ).autocomplete({
		source: "/feeds/participant.php",
		minLength: 2,
		select: function( event, ui ) {
			if(ui.item.id == \'\') {
				$(\'#participant_name\').html(\'\');
				$(\'#participant_code\').val(\'\');					
			} else {
				$(\'#participant_name\').html(\'<b>\' + ui.item.value + \'</b>\');
				$(\'#participant_code\').val(ui.item.id);	
			}
			$(\'#participant_name\').val(\'\');										
		}
	});
	getRecords();
});

function addSchedule() {
	$.ajax({
		type: "POST",
		url: "default.php",
		data: "schedule_add=1&schedule_day="+$(\'#schedule_day\').val()+"&class_code="+$(\'#class_code\').val()+"&participant_code="+$(\'#participant_code\').val()+"&room_code="+$(\'#room_code\').val()+"&schedule_time_start="+$(\'#schedule_time_start\').val()+"&schedule_time_end="+$(\'#schedule_time_end\').val(),
		dataType: "json",
		success: function(data){
			if(data.result == 1) {
				oTable.fnDraw();
				$(\'#schedule_day\').val(\'\');
				$(\'#class_name\').val(\'\');
				$(\'#participant_name\').val(\'\');
				$(\'#class_code\').val(\'\');
				$(\'#participant_code\').val(\'\');
				$(\'#room_code\').val(\'\');
				$(\'#schedule_time_start\').val(\'\');
				$(\'#schedule_time_end\').val(\'\');
				$(\'#schedule_error\').html(\'\');
			} else {
				$(\'#schedule_error\').html(data.error);
			}
		}
	});
}

function getRecords() {
	var html		= \'\';

	var filter_search	= $(\'#filter_search\').val() != \'undefined\' ? $(\'#filter_search\').val() : \'\';
	var filter_room		= $(\'#filter_room\').val() != \'undefined\' ? $(\'#filter_room\').val() : \'\';
	var filter_day		= $(\'#filter_day\').val() != \'undefined\' ? $(\'#filter_day\').val() : \'\';
	/* Clear table contants first. */			
	$(\'#tableContent\').html(\'\');
	$(\'#tableContent\').html(\'<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable">						<thead><tr><th>Course / Class</th><th>Teacher</th><th>Room</th><th>Day</th><th>Start Time</th><th>End Time</th><th></th></tr></thead><tbody id="participantbody"><tr><td colspan="7" align="center"><img src="/images/ajax_loader.gif" /></td></tr></tbody></table>\');	

	oTable = $(\'#dataTable\').dataTable({
		"bJQueryUI": true,
		"aoColumns" : [
			{ sWidth: "30%" },
			{ sWidth: "15%" },
			{ sWidth: "20%" },
			{ sWidth: "5%" },
			{ sWidth: "10%" },
			{ sWidth: "10%" },
			{ sWidth: "5%" }
		],
		"sPaginationType": "full_numbers",							
		"bSort": false,
		"bFilter": false,
		"bInfo": false,
		"iDisplayStart": 0,
		"iDisplayLength": 20,				
		"bLengthChange": false,									
		"bProcessing": true,
		"bServerSide": true,		
		"sAjaxSource": "?action=search&filter_csv=0&filter_search="+filter_search+"&filter_room="+filter_room+"&filter_day="+filter_day,
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			$.getJSON( sSource, aoData, function (json) {
				if (json.result === false) {
					$(\'#participantbody\').html(\'<tr><td colspan="7" align="center">No results</td></tr>\');
				} else {
					$(\'#result_count\').html(json.iTotalDisplayRecords);
					$(\'#result_display\').html(json.iTotalRecords);
				}
				fnCallback(json);
			});
		},
		"fnDrawCallback": function(){
		}
	});
	return false;
}
</script>
'; ?>


</html>