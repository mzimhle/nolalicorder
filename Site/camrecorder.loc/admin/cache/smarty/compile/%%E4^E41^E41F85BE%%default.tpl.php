<?php /* Smarty version 2.6.20, created on 2019-09-07 23:03:34
         compiled from participant/default.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'participant/default.tpl', 128, false),)), $this); ?>
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
		<h2 class="content-header-title">Students</h2>
		<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="#"><?php echo $this->_tpl_vars['activeAccount']['account_name']; ?>
</a></li>	
		<li class="active">Students</li>
		</ol>
	</div>	  
	<div class="row">
        <div class="col-md-12">		
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-hand-o-up"></i>
                Student List
              </h3>
            </div> <!-- /.portlet-header -->	
			<div class="portlet-content">			
				<div class="form-group">
				  <label for="filter_search">Search for participant name, email, cellphone and/or subscription</label>
				  <input type="text" id="filter_search" name="filter_search" class="form-control" value="" />
				</div>				
				<div class="form-group">
					<button type="button" onclick="getRecords(); return false;" class="btn btn-primary">Search</button>
					<button class="btn btn-secondary" type="button" onclick="link('/participant/details.php'); return false;">Add a new participant</button>					
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
              <form id="validate-basic" action="/participant/" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">
				<div class="row">
					<div class="col-sm-12">				
						<div class="form-group">
							<label for="class_name">Search for a class by faculty, department, code, course or by its name below</label>
							<input type="text" id="class_name" name="class_name"  size="20" class="form-control"   />
							<input type="hidden" id="class_code" name="class_code" size="20"  />
							<?php if (isset ( $this->_tpl_vars['errorArray']['class_name'] )): ?><em class="error"><?php echo $this->_tpl_vars['errorArray']['class_name']; ?>
</em><?php else: ?><em class="smalltext">Add first 3 letters of the person's name, surname, email or cellphone number</em><?php endif; ?>
						</div>	
					</div>
				</div>	
				<div class="row">
					<div class="col-sm-12">				
						<div class="form-group">
							<label for="class_list">Selected classes below</label>
							<table class="table table-bordered table-highlight">	
								<thead>
									<tr>
										<th width="20%">Faculty</th>
										<th width="20%">Department</th>
										<th width="10%">Course</th>
										<th width="5%">Year</th>
										<th width="5">Semester</th>
										<th width="5">Qualification</th>
										<th width="5">Code</th>
										<th width="20">Class</th>										
										<th width="5%"></th>
									</tr>
								</thead>
								<tbody id="classList">
									<tr>
										<td colspan="9">Please select at least one class.</td>
									</tr>
								</tbody>
							</table>
							<?php if (isset ( $this->_tpl_vars['errorArray']['class_list'] )): ?><span class="error"><?php echo $this->_tpl_vars['errorArray']['class_list']; ?>
</span><?php endif; ?>					  
						</div>	
					</div>
				</div>
                <div class="form-group">
					<label for="importfile">Upload CSV File</label>
					<input type="file" id="importfile" name="importfile" data-required="true" value="" />
					<?php if (isset ( $this->_tpl_vars['errorArray']['importfile'] )): ?><br /><span class="error"><?php echo $this->_tpl_vars['errorArray']['importfile']; ?>
</span><?php endif; ?>					  
                </div>	
                <div class="form-group"><button type="submit" class="btn btn-primary">Validate and Submit</button></div>
				<p>Please note, the format for the csv or txt file should be as follows, e.g.:<p>		
				<p class="success">
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
							<?php if (isset ( $this->_tpl_vars['errors'] )): ?>
							<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['errors']['total'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td>
							<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['errors']['successful'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td>
							<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['errors']['baddata'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td>
							<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['errors']['duplicatecell'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td>
							<td><?php echo ((is_array($_tmp=@$this->_tpl_vars['errors']['duplicateemail'])) ? $this->_run_mod_handler('default', true, $_tmp, '0') : smarty_modifier_default($_tmp, '0')); ?>
</td>
							<?php else: ?>
							<td colspan="6">No import done yet.</td>
							<?php endif; ?>
						</tr>
						<?php if (isset ( $this->_tpl_vars['errors'] )): ?>
						<tr>
							<td colspan="6"><?php echo $this->_tpl_vars['errors']['badlines']; ?>
</td>
						</tr>						
						<?php endif; ?>
					</tbody>
				</table>
				<input type="hidden" id="import_file" name="import_file" value="1" />				
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->		
      </div> <!-- /.row -->  
</div> <!-- /.container -->
<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/footer.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<?php require_once(SMARTY_CORE_DIR . 'core.smarty_include_php.php');
smarty_core_smarty_include_php(array('smarty_file' => 'includes/javascript.php', 'smarty_assign' => '', 'smarty_once' => false, 'smarty_include_vars' => array()), $this); ?>

<?php echo '
<script type="text/javascript">
$(document).ready(function() {
	getRecords();
	$( "#class_name" ).autocomplete({
		source: "/feeds/class.php",
		minLength: 2,
		select: function( event, ui ) {
			if(ui.item.id != \'\') {
				addOption(ui.item);
			}
		},
		change: function( event, ui ) {
			$(\'#class_name\').val(\'\');
			$(\'#class_code\').val(\'\');
		}
	});
});

function addOption(item) {

	var html = \'<tr class="classListItem \'+item.id+\'"><td>\'+item.faculty+\'</td><td>\'+item.department+\'</td><td>\'+item.course+\'</td><td>\'+item.year+\'</td><td>\'+item.semester+\'</td><td>\'+item.qualification+\'</td><td>\'+item.code+\'</td><td>\'+item.class+\'</td><td><button value="Delete" onclick="deleteClass(\\\'\'+item.id+\'\\\'); return false;">Delete</button><input type="hidden" id="class_list[]" name="class_list[]" value="\'+item.id+\'" /></td></tr>\';

	if($(\'.classListItem\').length == 0) {
		$(\'#classList\').html(html);
	} else {
		if($(\'.\'+item.id).length == 0) {
			$(\'#classList\').append(html);
		} else {
			alert("Class has already been added.");
		}
	}
	return false;
}

function getRecords() {
	var html			= \'\';
	var filter_search	= $(\'#filter_search\').val() != \'undefined\' ? $(\'#filter_search\').val() : \'\';
	/* Clear table contants first. */			
	$(\'#tableContent\').html(\'\');
	$(\'#tableContent\').html(\'<table cellpadding="0" cellspacing="0" width="100%" border="0" class="display" id="dataTable"><thead><tr><th>Full name</th><th>Email</th><th>Cellphone</th><th>Password</th><th></th></tr></thead><tbody id="participantbody"><tr><td colspan="6" align="center"><img src="/images/ajax_loader.gif" /></td></tr></tbody></table>\');	

	oTable = $(\'#dataTable\').dataTable({
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
					$(\'#participantbody\').html(\'<tr><td colspan="6" align="center">No results</td></tr>\');
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
	window.location.href	= "/participant/?action=search&filter_csv=1&filter_search="+filter_search;
	return false;
}
</script>
'; ?>

</body>
</html>