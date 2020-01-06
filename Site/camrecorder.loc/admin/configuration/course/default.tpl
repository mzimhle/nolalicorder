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
		<li><a href="/">Course</a></li>
		<li class="active">List</li>
		</ol>
	</div>	  
	<div class="row">
        <div class="col-md-12">		
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-hand-o-up"></i>
                Course List
              </h3>
            </div> <!-- /.portlet-header -->	
			<div class="portlet-content">		
				<div class="form-group">
				  <label for="filter_search">Search for course name</label>
				  <input type="text" id="filter_search" name="filter_search" class="form-control" value="" />
				</div>				
				<div class="form-group">
					<button type="button" onclick="getRecords(); return false;" class="btn btn-primary">Search</button>
					<button class="btn btn-secondary" type="button" onclick="link('/configuration/course/details.php'); return false;">Add a new course</button>
				</div>
				<p>There are <span id="result_count" name="result_count" class="success">0</span> records showing. We are displaying <span id="result_display" name="result_display" class="success">0</span> records per page.</p>
				<div id="tableContent" align="center">
					<!-- Start Content Table -->
					<div class="course_table">
						<img src="/images/ajax_loader.gif" />
					 </div>
					 <!-- End Content Table -->	
				</div>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
	</div> <!-- /.content -->
</div> <!-- /.container -->
{include_php file='includes/footer.php'}
{include_php file='includes/javascript.php'}
{literal}
<script type="text/javascript">
$(document).ready(function() {
	getRecords();
});

function getRecords() {
	var html		= '';

	var filter_search	= $('#filter_search').val() != 'undefined' ? $('#filter_search').val() : '';
	
	/* Clear table contants first. */		
	$('#tableContent').html('');
	
	$('#tableContent').html('<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable"><thead><tr><th>Department</th><th>Qualification</th><th>Code</th><th>Name</th><th></th></tr></thead><tbody id="coursebody"><tr><td colspan="3" align="center"><img src="/images/ajax_loader.gif" /></td></tr></tbody></table>');	

	oTable = $('#dataTable').dataTable({
		"bJQueryUI": true,
		"aoColumns" : [
			{ sWidth: "15%" },
			{ sWidth: "15%" },
			{ sWidth: "5%" },
			{ sWidth: "60%" },
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
		"sAjaxSource": "?action=search&filter_csv=0&filter_search="+filter_search,
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			$.getJSON( sSource, aoData, function (json) {
				if (json.result === false) {
					$('#coursebody').html('<tr><td colspan="5" align="center">No results</td></tr>');
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

function csv() {
	var filter_search		= $('#filter_search').val() != 'undefined' ? $('#filter_search').val() : '';
	window.location.href	= "/configuration/course/?action=search&filter_csv=1&filter_search="+filter_search;
	return false;
}
</script>
{/literal}
</body>
</html>