<?php /* Smarty version 2.6.20, created on 2019-08-07 15:58:49
         compiled from configuration/class/default.tpl */ ?>
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

</head>
<body>
<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/header.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<div class="container">
  <div class="content">
    <div class="content-container">
	<div class="content-header">
		<h2 class="content-header-title">Class</h2>
		<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/">Class</a></li>
		<li class="active">List</li>
		</ol>
	</div>	  
	<div class="row">
        <div class="col-md-12">		
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-hand-o-up"></i>
                Class List
              </h3>
            </div> <!-- /.portlet-header -->	
			<div class="portlet-content">		
				<div class="form-group">
				  <label for="filter_search">Search for class name</label>
				  <input type="text" id="filter_search" name="filter_search" class="form-control" value="" />
				</div>				
				<div class="form-group">
					<button type="button" onclick="getRecords(); return false;" class="btn btn-primary">Search</button>
					<button type="button" onclick="csv(); return false;" class="btn">Download CSV</button>
					<button class="btn btn-secondary fr" type="button" onclick="link('/configuration/class/details.php'); return false;">Add a new class</button>
				</div>
				<p>There are <span id="result_count" name="result_count" class="success">0</span> records showing. We are displaying <span id="result_display" name="result_display" class="success">0</span> records per page.</p>
				<div id="tableContent" align="center">
					<!-- Start Content Table -->
					<div class="class_table">
						<img src="/images/ajax_loader.gif" />
					 </div>
					 <!-- End Content Table -->	
				</div>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
	</div> <!-- /.content -->
</div> <!-- /.container -->
<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/footer.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/javascript.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<?php echo '
<script type="text/javascript">
$(document).ready(function() {
	getRecords();
});

function getRecords() {
	var html		= \'\';

	var filter_search	= $(\'#filter_search\').val() != \'undefined\' ? $(\'#filter_search\').val() : \'\';
	
	/* Clear table contants first. */		
	$(\'#tableContent\').html(\'\');
	
	$(\'#tableContent\').html(\'<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable"><thead><tr><th>Faculty</th><th>Department</th><th>Qualification</th><th>Course</th><th>Year</th><th>Semester</th><th>Code</th><th>Name</th><th></th></tr></thead><tbody id="classbody"><tr><td colspan="9" align="center"><img src="/images/ajax_loader.gif" /></td></tr></tbody></table>\');	

	oTable = $(\'#dataTable\').dataTable({
		"bJQueryUI": true,
		"aoColumns" : [
			{ sWidth: "10%" },
			{ sWidth: "15%" },
			{ sWidth: "15%" },
			{ sWidth: "10%" },
			{ sWidth: "5%" },
			{ sWidth: "5%" },
			{ sWidth: "5%" },
			{ sWidth: "20%" },
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
					$(\'#classbody\').html(\'<tr><td colspan="9" align="center">No results</td></tr>\');
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

function csv() {
	var filter_search		= $(\'#filter_search\').val() != \'undefined\' ? $(\'#filter_search\').val() : \'\';
	window.location.href	= "/configuration/class/?action=search&filter_csv=1&filter_search="+filter_search;
	return false;
}
</script>
'; ?>

</body>
</html>