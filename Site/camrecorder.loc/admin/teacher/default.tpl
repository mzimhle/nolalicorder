<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Mailbok - Communication Tool</title>
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
		<h2 class="content-header-title">Teachers</h2>
		<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="#">{$activeAccount.account_name}</a></li>	
		<li class="active">Teachers</li>
		</ol>
	</div>	  
	<div class="row">
        <div class="col-md-12">		
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-hand-o-up"></i>
                Teacher List
              </h3>
            </div> <!-- /.portlet-header -->	
			<div class="portlet-content">
				<button class="btn btn-secondary fr" type="button" onclick="link('/teacher/details.php'); return false;">Add a new participant</button><br/ ><br />			
				<div class="form-group">
				  <label for="filter_search">Search for participant name, email, cellphone and/or subscription</label>
				  <input type="text" id="filter_search" name="filter_search" class="form-control" value="" />
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
	</div> <!-- /.content -->
      <div class="row">
        <div class="col-sm-12">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
					Import CSV file
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/teacher/" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">	
                <div class="form-group">
					<label for="importfile">Upload CSV File</label>
					<input type="file" id="importfile" name="importfile" data-required="true" value="" />
					{if isset($errorArray.importfile)}<br /><span class="error">{$errorArray.importfile}</span>{/if}					  
                </div>	
                <div class="form-group"><button type="submit" class="btn btn-primary">Validate and Submit</button></div>
				<p>Please note, the format for the csv or txt file should be as follows, e.g.:<p>		
				<p class="success">name,email,number<br />
				mandla mhlupheki,mandla@gmail.com<br />
				mandla,mandla@gmail.com,<br />
				</p><br />
				<p>Below will be the list of imported subscriber details</p>
				<table class="table table-bordered table-highlight">	
					<thead>
						<th align="left">Total</th>
						<th align="left">Added </th>
						<th align="left">Not Added</th>
						<th align="left">Duplicate Cell</th>
						<th align="left">Duplicate Email</th>
					</thead>
					<tbody>
						<tr>
							{if isset($errors)}
							<td>{$errors.total|default:'0'}</td>
							<td>{$errors.successful|default:'0'}</td>
							<td>{$errors.baddata|default:'0'}</td>
							<td>{$errors.duplicatecell|default:'0'}</td>
							<td>{$errors.duplicateemail|default:'0'}</td>
							{else}
							<td colspan="6">No import done yet.</td>
							{/if}
						</tr>
						{if isset($errors)}
						<tr>
							<td colspan="6">{$errors.badlines}</td>
						</tr>						
						{/if}
					</tbody>
				</table>
				<input type="hidden" id="import_file" name="import_file" value="1" />				
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->		
      </div> <!-- /.row -->  
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
	
	$('#tableContent').html('<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable"><thead><tr><th>Full name</th><th>Email</th><th>Cellphone</th><th>Password</th><th></th></tr></thead><tbody id="participantbody"><tr><td colspan="6" align="center"><img src="/images/ajax_loader.gif" /></td></tr></tbody></table>');	

	oTable = $('#dataTable').dataTable({
		"bJQueryUI": true,
		"aoColumns" : [
			{ sWidth: "50%" },
			{ sWidth: "30%" },
			{ sWidth: "10%" },
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
		"sAjaxSource": "?action=search&filter_csv=0&filter_search="+filter_search,
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			$.getJSON( sSource, aoData, function (json) {
				if (json.result === false) {
					$('#participantbody').html('<tr><td colspan="6" align="center">No results</td></tr>');
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
	window.location.href	= "/teacher/?action=search&filter_csv=1&filter_search="+filter_search;
	return false;
}
</script>
{/literal}
</body>
</html>