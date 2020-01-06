<?php /* Smarty version 2.6.20, created on 2018-05-08 11:48:07
         compiled from campaign/comm.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'campaign/comm.tpl', 43, false),)), $this); ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>MailBok - Communication Tool</title>
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
		<h2 class="content-header-title">Campaign</h2>
		<ol class="breadcrumb">
			<li><a href="/">Home</a></li>
			<li><a href="/"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>
			<li><a href="/campaign/">Campaign</a></li>
			<li><a href="/campaign/details.php?code=<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
"><?php echo $this->_tpl_vars['campaignData']['campaign_name']; ?>
</a></li>
			<li class="active">Comms</li>
		</ol>
	</div>	  
	<div class="row">
        <div class="col-md-12">		
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-hand-o-up"></i>
                Campaign List
              </h3>
            </div> <!-- /.portlet-header -->	
			<div class="portlet-content">
				<div class="row">
					<div class="col-md-6 col-sm-3">
						<div class="form-group">
							<label for="filter_search">Template</label>
							<select id="filter_template" name="filter_template" class="form-control">
								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['templatePairs']), $this);?>

								<?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['linkPairs']), $this);?>

							</select>							
						</div>					
					</div>
					<div class="col-md-6 col-sm-3">
						<div class="form-group">
							<label for="filter_search">Search by name, email and cellphone</label>
							<input type="text" id="filter_search" name="filter_search" class="form-control" value="" />
						</div>					
					</div>						
				</div>				
				<div class="form-group">
					<button type="button" onclick="getRecords(); return false;" class="btn btn-primary">Search</button>
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

	var html			= \'\';
	var filter_search	= $(\'#filter_search\').val() != \'undefined\' ? $(\'#filter_search\').val() : \'\';
	var filter_template	= $(\'#filter_template\').val() != \'undefined\' ? $(\'#filter_template\').val() : \'\';

	/* Clear table contants first. */			
	$(\'#tableContent\').html(\'\');
	$(\'#tableContent\').html(\'<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable"><thead><tr><th>Type</th><th>Name</th><th>Contact</th><th>Result</th><th>Subject / Message</th></tr></thead><tbody id="campaignbody"><tr><td colspan="5" align="center"></td></tr></tbody></table>\');	

	oTable = $(\'#dataTable\').dataTable({
		"bJQueryUI": true,
		"aoColumns" : [
			{sWidth: "5%"},
			{sWidth: "10%"},
			{sWidth: "15%"},
			{sWidth: "15%"},
			{sWidth: "50%"}
		],
		"sPaginationType": "full_numbers",							
		"bSort": false,
		"bFilter": false,
		"bInfo": false,
		"iDisplayStart": 0,
		"iDisplayLength": 100,				
		"bLengthChange": false,									
		"bProcessing": true,
		"bServerSide": true,		
		"sAjaxSource": "?code='; ?>
<?php echo $this->_tpl_vars['campaignData']['campaign_code']; ?>
<?php echo '&action=search&filter_csv=0&filter_template="+filter_template+"&filter_search="+filter_search,
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			$.getJSON( sSource, aoData, function (json) {
				if (json.result === false) {
					$(\'#campaignbody\').html(\'<tr><td colspan="5" align="center">No results</td></tr>\');
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

</body>
</html>