<?php /* Smarty version 2.6.20, created on 2018-04-15 18:15:31
         compiled from communicate/default.tpl */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Municipal System</title>
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
		<h2 class="content-header-title">Communicate</h2>
		<ol class="breadcrumb">
			<li><a href="/">Home</a></li>
			<li><a href="/"><?php echo $this->_tpl_vars['activeMunicipality']['municipality_name']; ?>
</a></li>
			<li class="active">Communicate</li>
		</ol>
	</div>	  
	<div class="row">
        <div class="col-md-12">		
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-hand-o-up"></i>
                Communicate List
              </h3>
            </div> <!-- /.portlet-header -->	
			<div class="portlet-content">
				<button class="btn btn-secondary fr" type="button" onclick="link('/communicate/details.php'); return false;">Add a new communicate</button><br/ ><br />
				<div class="row">
					<div class="col-md-6 col-sm-3">
						<div class="form-group">
							<label for="filter_search">Search by name, email and cellphone</label>
							<input type="text" id="filter_search" name="filter_search" class="form-control" value="" />
						</div>					
					</div>
					<div class="col-md-6 col-sm-3">
						<div class="form-group">
							<label for="filter_type">Type of communication</label>
							<select id="filter_type" name="filter_type" class="form-control">
								<option value=""> ---------- </option>
								<option value="EMAIL"> EMAIL </option>
								<option value="SMS"> SMS </option>
							</select>
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

function sendCommunicateModal(id) {
	$(\'#communicatecode\').val(id);
	$(\'#sendCommunicateModal\').modal(\'show\');
	return false;
}

function sendCommunicate() {

	var code	= $(\'#communicatecode\').val();

	$.ajax({
		type: "GET",
		url: "default.php",
		data: "sendcommunicate="+code,
		dataType: "json",
		success: function(data){		
			$(\'#sendCommunicateModal\').modal(\'hide\');
			if(data.result == 1) {				
				if(typeof oTable === \'undefined\') {
					window.location.href = window.location.href;
				} else {
					oTable.fnDraw(false);
				}
			} else {
				$.howl ({
				  type: \'info\'
				  , title: \'Notification\'
				  , content: data.error
				  , sticky: $(this).data (\'sticky\')
				  , lifetime: 7500
				  , iconCls: $(this).data (\'icon\')
				});	
			}
		}
	});								

	return false;
}
	
$(document).ready(function() {
	getRecords();
});

function getRecords() {
	var html			= \'\';
	var filter_search	= $(\'#filter_search\').val() != \'undefined\' ? $(\'#filter_search\').val() : \'\';
	var filter_type	= $(\'#filter_type\').val() != \'undefined\' ? $(\'#filter_type\').val() : \'\';
	/* Clear table contants first. */			
	$(\'#tableContent\').html(\'\');
	$(\'#tableContent\').html(\'<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable"><thead><tr><th></th><th></th><th>Subject</th><th>Message</th><th>Wards</th><th>People</th><th>Sent</th><th></th></tr></thead><tbody id="communicatebody"><tr><td colspan="8" align="center"></td></tr></tbody></table>\');	

	oTable = $(\'#dataTable\').dataTable({
		"bJQueryUI": true,
		"aoColumns" : [
			{sWidth: "10%"},
			{sWidth: "5%"},
			{sWidth: "25%"},
			{sWidth: "50%"},
			{sWidth: "5%"},
			{sWidth: "5%"},
			{sWidth: "5%"},
			{sWidth: "5%"}
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
		"sAjaxSource": "?action=search&filter_csv=0&filter_search="+filter_search,
		"fnServerData": function ( sSource, aoData, fnCallback ) {
			$.getJSON( sSource, aoData, function (json) {
				if (json.result === false) {
					$(\'#communicatebody\').html(\'<tr><td colspan="8" align="center">No results</td></tr>\');
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

<!-- Modal -->
<div class="modal fade" id="sendCommunicateModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Confirmation</h4>
			</div>
			<div class="modal-body">
			Are you sure you want to send out this communication, once sent , it cannot be un-done? Please confirm if you are sure.<br /><br />
			Please also make sure you have selected all the wards needed to send to.
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
				<button class="btn btn-warning" type="button" onclick="javascript:sendCommunicate();">I'm sure, send it</button>
				<input type="hidden" id="communicatecode" name="communicatecode" value="" />
			</div>
		</div>
	</div>
</div>
<!-- modal -->

</body>
</html>