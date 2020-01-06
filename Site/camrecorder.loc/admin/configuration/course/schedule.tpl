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
	{literal}
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
	{/literal}
</head>
<body>
{include_php file='includes/header.php'}
<div class="container">
  <div class="content">
    <div class="content-container">
	<div class="content-header">
	<h2 class="content-header-title">Course</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#">{$activeAccount.account_name}</a></li>	
		<li><a href="/configuration/course/">Course</a></li>
		<li><a href="/configuration/course/details.php?code={$courseData.course_code}">{$courseData.course_name}</a></li>
		<li class="active">Schedules</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				Schedule of the course {$courseData.course_name}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="#" method="POST" data-validate="parsley" class="form parsley-form">
				<div class="row">
					<div class="col-sm-2">				  
						<div class="form-group">
							<label for="schedule_date">Choose a date</label>
							<input type="text" id="schedule_date" name="schedule_date"  size="20" class="form-control" />
							{if isset($errorArray.schedule_date)}<span class="error">{$errorArray.schedule_date}</span>{/if}					  
						</div>
					</div>
					<div class="col-sm-5">				
						<div class="form-group">
							<label for="class_name">Class</label>
							<select id="class_code" name="class_code" class="form-control">
								<option value=""> ------- </option>
								{html_options options=$classPairs}
							</select>
							{if isset($errorArray.class_code)}<em class="error">{$errorArray.class_code}</em>{/if}
						</div>	
					</div>		
					<div class="col-sm-5">				
						<div class="form-group">
							<label for="participant_name">Select a teacher from below ( add first 3 characters. )</label>
							<input type="text" id="participant_name" name="participant_name"  size="20" class="form-control" />
							<input type="hidden" id="participant_code" name="participant_code" size="20"  />
							{if isset($errorArray.participant_code)}<em class="error">{$errorArray.participant_code}</em>{/if}
						</div>	
					</div>						
				</div>	
				<div class="row">
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="regularity_code">Regularity</label>
							<select id="regularity_code" name="regularity_code" class="form-control">
								<option value=""> ------- </option>
								<option value="ONCEOFF"> Once Off </option>
								<option value="WEEK"> Rest of the Week </option>
								<option value="MONTH"> Rest of the month </option>
							</select>				  
						</div>
					</div>				
					<div class="col-sm-5">	
						<div class="form-group">
							<label for="room_code">Room</label>
							<select id="room_code" name="room_code" class="form-control">
								<option value=""> ------- </option>
								{html_options options=$roomPairs}
							</select>				  
						</div>
					</div>
					<div class="col-sm-2">				
						<div class="form-group">
							<label for="schedule_time_start">Class start time</label>
							<input type="text" id="schedule_time_start" name="schedule_time_start"  size="20" class="form-control" placeholder="E.G. 07:30" />
							{if isset($errorArray.schedule_time_start)}<em class="error">{$errorArray.schedule_time_start}</em>{/if}
						</div>	
					</div>
					<div class="col-sm-2">				
						<div class="form-group">
							<label for="schedule_time_end">Class end time</label>
							<input type="text" id="schedule_time_end" name="schedule_time_end"  size="20" class="form-control" placeholder="E.G. 08:30" />
							{if isset($errorArray.schedule_time_end)}<em class="error">{$errorArray.schedule_time_end}</em>{/if}
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
							<label for="filter_date">Date</label>
							<input type="text" id="filter_date" name="filter_date" class="form-control" value="" />							
						</div>
					</div>		
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="filter_month">Month</label>
							<select id="filter_month" name="filter_month" class="form-control">
								<option value=""> ------- </option>
								<option value="1"> January </option>
								<option value="2"> February </option>
								<option value="3"> March </option>
								<option value="4"> April </option>
								<option value="5"> May </option>
								<option value="6"> June </option>
								<option value="7"> July </option>
								<option value="8"> August </option>
								<option value="9"> September </option>
								<option value="10"> October </option>
								<option value="11"> November </option>
								<option value="12"> December </option>
							</select>				  
						</div>
					</div>		
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="filter_year">Year</label>
							<select id="filter_year" name="filter_year" class="form-control">
								<option value=""> ------- </option>
								<option value="2019"> 2019 </option>
								<option value="2020"> 2020 </option>
								<option value="2021"> 2021 </option>
								<option value="2022"> 2022 </option>
								<option value="2023"> 2023 </option>
								<option value="2024"> 2024 </option>
								<option value="2025"> 2025 </option>
								<option value="2026"> 2026 </option>
								<option value="2027"> 2027 </option>
								<option value="2028"> 2028 </option>
								<option value="2029"> 2029 </option>
							</select>				  
						</div>
					</div>					
					<div class="col-sm-2">	
						<div class="form-group">
							<label for="filter_class">Class</label>
							<select id="filter_class" name="filter_class" class="form-control">
								<option value=""> ------- </option>
								{html_options options=$classPairs}
							</select>				  
						</div>
					</div>					
					<div class="col-sm-4">	
						<div class="form-group">
							<label for="filter_room">Room</label>
							<select id="filter_room" name="filter_room" class="form-control">
								<option value=""> ------- </option>
								{html_options options=$roomPairs}
							</select>				  
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
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/configuration/course/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/configuration/course/details.php?code={$courseData.course_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/configuration/course/schedule.php?code={$courseData.course_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Schedule
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
			</div> <!-- /.list-group -->
		</div>		
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
{include_php file='includes/footer.php'}
{include_php file='includes/javascript.php'}
{literal}
<script type="text/javascript" language="javascript">
$(document).ready(function(){ 

	$( "#participant_name" ).autocomplete({
		source: "/feeds/participant.php",
		minLength: 2,
		select: function( event, ui ) {
			if(ui.item.id == '') {
				$('#participant_name').html('');
				$('#participant_code').val('');					
			} else {
				$('#participant_name').html('<b>' + ui.item.value + '</b>');
				$('#participant_code').val(ui.item.id);	
			}
			$('#participant_name').val('');										
		}
	});
	$("#schedule_date").datepicker({ dateFormat: 'yy-mm-dd', minDate: '0' });
	$("#filter_date").datepicker({ dateFormat: 'yy-mm-dd' });
	getRecords();
});

function addSchedule() {
	$.ajax({
		type: "POST",
		url: "/configuration/course/schedule.php?code={/literal}{$courseData.course_code}{literal}",
		data: "schedule_add=1&schedule_date="+$('#schedule_date').val()+"&class_code="+$('#class_code').val()+"&participant_code="+$('#participant_code').val()+"&room_code="+$('#room_code').val()+"&schedule_time_start="+$('#schedule_time_start').val()+"&schedule_time_end="+$('#schedule_time_end').val()+"&regularity_code="+$('#regularity_code').val(),
		dataType: "json",
		success: function(data){
			if(data.result == 1) {
				oTable.fnDraw();
				$('#schedule_date').val('');
				$('#class_name').val('');
				$('#participant_name').val('');
				$('#class_code').val('');
				$('#participant_code').val('');
				$('#room_code').val('');
				$('#schedule_time_start').val('');
				$('#schedule_time_end').val('');
				$('#regularity_code').val('');
				$('#schedule_error').html('');
			} else {
				$('#schedule_error').html(data.error);
			}
		}
	});
}

function getRecords() {
	var html		= '';

	var filter_room		= $('#filter_room').val() != 'undefined' ? $('#filter_room').val() : '';
	var filter_class	= $('#filter_class').val() != 'undefined' ? $('#filter_class').val() : '';
	var filter_date		= $('#filter_date').val() != 'undefined' ? $('#filter_date').val() : '';
	var filter_year		= $('#filter_year').val() != 'undefined' ? $('#filter_year').val() : '';
	var filter_month	= $('#filter_month').val() != 'undefined' ? $('#filter_month').val() : '';

	/* Clear table contants first. */			
	$('#tableContent').html('');
	$('#tableContent').html('<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable">						<thead><tr><th></th><th>Course / Class</th><th>Teacher</th><th>Room</th><th>Day</th><th>Start Time</th><th>End Time</th><th></th></tr></thead><tbody id="participantbody"><tr><td colspan="8" align="center"><img src="/images/ajax_loader.gif" /></td></tr></tbody></table>');	

	oTable = $('#dataTable').dataTable({
		"bJQueryUI": true,
		"aoColumns" : [
			{ sWidth: "5%" },
			{ sWidth: "25%" },
			{ sWidth: "20%" },
			{ sWidth: "10%" },
			{ sWidth: "10%" },
			{ sWidth: "5%" },
			{ sWidth: "5%" },
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
		"sAjaxSource": "/configuration/course/schedule.php?code={/literal}{$courseData.course_code}{literal}&action=search&filter_csv=0&filter_class="+filter_class+"&filter_room="+filter_room+"&filter_date="+filter_date+"&filter_month="+filter_month+"&filter_year="+filter_year,
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			$.getJSON( sSource, aoData, function (json) {
				if (json.result === false) {
					$('#participantbody').html('<tr><td colspan="8" align="center">No results</td></tr>');
				} else {
					$('#result_count').html(json.iTotalDisplayRecords);
					$('#result_display').html(json.iTotalRecords);
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
{/literal}
</html>