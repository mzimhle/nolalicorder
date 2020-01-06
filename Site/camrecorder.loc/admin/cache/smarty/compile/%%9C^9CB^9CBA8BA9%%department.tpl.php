<?php /* Smarty version 2.6.20, created on 2019-08-13 15:51:39
         compiled from configuration/faculty/department.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'configuration/faculty/department.tpl', 64, false),)), $this); ?>
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
		<h2 class="content-header-title">Department</h2>
		<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/">Configuration</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li><a href="/">Faculty</a></li>
		<li><a href="/"><?php echo $this->_tpl_vars['facultyData']['faculty_name']; ?>
</a></li>
		<li class="active">Departments</li>
		</ol>
	</div>	  
	<div class="row">
        <div class="col-md-12">		
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-hand-o-up"></i>
                Department List
              </h3>
            </div> <!-- /.portlet-header -->	
			<div class="portlet-content">		
				<div class="form-group">
				  <label for="filter_search">Search for department name</label>
				  <input type="text" id="filter_search" name="filter_search" class="form-control" value="" />
				</div>				
				<div class="form-group">
					<button type="button" onclick="getRecords(); return false;" class="btn btn-primary">Search</button>
					<button type="button" onclick="csv(); return false;" class="btn">Download CSV</button>
				</div>
				<p>There are <span id="result_count" name="result_count" class="success">0</span> records showing. We are displaying <span id="result_display" name="result_display" class="success">0</span> records per page.</p>
				<div id="tableContent" align="center">
					<!-- Start Content Table -->
					<div class="department_table">
						<img src="/images/ajax_loader.gif" />
					 </div>
					 <!-- End Content Table -->
				</div>
            </div> <!-- /.portlet-content -->
			<div class="portlet-content">
              <form id="detailsForm" action="#" method="POST" enctype="multipart/form-data"> 
				<p>Below is where you can add the department below for this faculty <?php echo $this->_tpl_vars['facultyData']['faculty_name']; ?>
</p>
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group">
							<label for="faculty_code" class="error">Faculty</label>
							<select id="faculty_code" name="faculty_code" class="form-control">
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['facultypairs'],'selected' => $this->_tpl_vars['departmentData']['faculty_code']), $this);?>

							</select>
							<?php if (isset ( $this->_tpl_vars['errorArray']['faculty_code'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['faculty_code']; ?>
</span><?php endif; ?>					  
						</div>
					</div>					
					<div class="col-sm-9">	
						<div class="form-group">
							<label for="department_name" class="error">Name</label>
							<input type="text" id="department_name" name="department_name" class="form-control"  value="<?php echo $this->_tpl_vars['departmentData']['department_name']; ?>
" />
							<?php if (isset ( $this->_tpl_vars['errorArray']['department_name'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['department_name']; ?>
</span><?php endif; ?>					  
						</div>
					</div>
				</div>			
                <div class="form-group">
					<button type="submit" class="btn btn-primary"><?php if (isset ( $this->_tpl_vars['departmentData'] )): ?>Update<?php else: ?>Add<?php endif; ?></button>
				</div>
              </form>
			</div>
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
	
	$(\'#tableContent\').html(\'<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable"><thead><tr><th></th><th>Faculty</th><th>Name</th><th></th></tr></thead><tbody id="departmentbody"><tr><td colspan="3" align="center"><img src="/images/ajax_loader.gif" /></td></tr></tbody></table>\');	

	oTable = $(\'#dataTable\').dataTable({
		"bJQueryUI": true,
		"aoColumns" : [
			{ sWidth: "15%" },
			{ sWidth: "40%" },
			{ sWidth: "40%" },
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
		"sAjaxSource": "?faculty='; ?>
<?php echo $this->_tpl_vars['facultyData']['faculty_code']; ?>
<?php echo '&action=search&filter_csv=0&filter_search="+filter_search,
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			$.getJSON( sSource, aoData, function (json) {
				if (json.result === false) {
					$(\'#departmentbody\').html(\'<tr><td colspan="3" align="center">No results</td></tr>\');
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
	window.location.href	= "/configuration/department/?action=search&filter_csv=1&filter_search="+filter_search;
	return false;
}
</script>
'; ?>

</body>
</html>