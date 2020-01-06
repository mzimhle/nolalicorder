<script src="/library/javascript/libs/jquery-1.10.1.min.js"></script>
<script src="/library/javascript/libs/jquery-ui-1.10.3.js"></script>
<script src="/library/javascript/libs/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="/library/javascript/libs/excanvas.compiled.js"></script>
<![endif]-->
<!-- Plugin JS -->
<script src="/library/javascript/plugins/icheck/jquery.icheck.js"></script>
<script src="/library/javascript/plugins/select2/select2.js"></script>
<script src="/library/javascript/libs/raphael-2.1.2.min.js"></script>
<script src="/library/javascript/plugins/morris/morris.min.js"></script>
<script src="/library/javascript/plugins/sparkline/jquery.sparkline.min.js"></script>
<script src="/library/javascript/plugins/nicescroll/jquery.nicescroll.min.js"></script>
<script src="/library/javascript/plugins/fullcalendar/fullcalendar.min.js"></script>

<script src="/library/javascript/plugins/parsley/parsley.js"></script>
<script src="/library/javascript/plugins/icheck/jquery.icheck.js"></script>
<!-- <script src="/library/javascript/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="/library/javascript/plugins/timepicker/bootstrap-timepicker.js"></script> -->
<script src="/library/javascript/plugins/simplecolorpicker/jquery.simplecolorpicker.js"></script>
<script src="/library/javascript/plugins/magnific/jquery.magnific-popup.min.js"></script>
<script src="/library/javascript/plugins/howl/howl.js"></script>
<!-- App JS -->
<script src="/library/javascript/target-admin.js"></script>
<!-- Plugin JS -->
<script src="/library/javascript/demos/dashboard.js"></script>
<script src="/library/javascript/demos/calendar.js"></script>
<!--
<script src="/library/javascript/demos/charts/morris/area.js"></script>
<script src="/library/javascript/demos/charts/morris/donut.js"></script>
-->
<script src="/library/javascript/datatables/jquery.dataTables.js"></script>
<script src="/library/javascript/datatables/jquery.truncatable.js"></script>

<script src="/library/javascript/jquery-ui-time-picker.js"></script>

{literal}
<script type="text/javascript">

	$(document).ready(function(){
		$("#socialmessage").keyup(function () {
			var i = $("#socialmessage").val().length;
			$("#socialcount").html(i+' characters entered.');
			if (i > 255) {
				$('#socialcount').removeClass('success');
				$('#socialcount').addClass('error');
			} else if(i == 0) {
				$('#socialcount').removeClass('success');
				$('#socialcount').addClass('error');
			} else {
				$('#socialcount').removeClass('error');
				$('#socialcount').addClass('success');
			} 
		});
	});
	
	function alertMessage(type, subject, message) {
		$.howl ({
		  type: type
		  , title: subject
		  , content: message
		  , sticky: $(this).data ('sticky')
		  , lifetime: 7500
		  , iconCls: $(this).data ('icon')
		});			
		return false;
	}
	function deleteitem() {
		
		var id 		= $('#itemcode').val();
		var page 	= $('#itempage').val();
		var code 	= $('#maincode').val();
		
		parameter = '';
		if(code != '') {
			parameter = '&code='+code;
		}
		
		$.ajax({
			type: "GET",
			url: page+".php",
			data: "delete_code="+id+parameter,
			dataType: "json",
			success: function(data){
				if(data.result == 1) {
					if(typeof oTable != 'undefined') {
						$('#deleteModal').modal('hide');
						oTable.fnDraw();
					} else {
						window.location.href = window.location.href;
					}
				} else {
					
					$('#deleteModal').modal('hide');
					
					$.howl ({
					  type: 'danger'
					  , title: 'Error Message'
					  , content: data.error
					  , sticky: $(this).data ('sticky')
					  , lifetime: 7500
					  , iconCls: $(this).data ('icon')
					});
					if(typeof oTable != 'undefined') {
						oTable.fnDraw();
					}							
				}
			}
		});
		
		return false;
	}

	function deleteModal(id, code, page) {
		$('#itemcode').val(id);
		$('#maincode').val(code);
		$('#itempage').val(page);
		$('#deleteModal').modal('show');
		return false;
	}
	
	function changeSubStatus() {
		var id 		= $('#itemsubcode').val();
		var status	= $('#itemsubstatus').val();
		var page 	= $('#itemsubpage').val();	
		var parent 	= $('#itemsubparent').val();
		
		$.ajax({
				type: "GET",
				url: page+".php?code="+parent,
				data: "status_code="+id+"&status="+status,
				dataType: "json",
				success: function(data){
					if(data.result == 1) {
						window.location.href = window.location.href;
					} else {
						$('#statusModal').modal('hide');
						$.howl ({
						  type: 'info'
						  , title: 'Notification'
						  , content: data.error
						  , sticky: $(this).data ('sticky')
						  , lifetime: 7500
						  , iconCls: $(this).data ('icon')
						});	
					}
				}
		});								

		return false;		
	}
	
	function statusSubModal(id, status, page, parent) {
		$('#itemsubcode').val(id);
		$('#itemsubstatus').val(status);
		$('#itemsubpage').val(page);
		$('#itemsubparent').val(parent);
		$('#statusSubModal').modal('show');
	}
	
	function changestatus() {
		
		var id 		= $('#itemcode').val();
		var status	= $('#itemstatus').val();
		var page 	= $('#itempage').val();
		
		$.ajax({
				type: "GET",
				url: page+".php",
				data: "status_code="+id+"&status="+status,
				dataType: "json",
				success: function(data){
					if(data.result == 1) {
						window.location.href = window.location.href;
					} else {
						$('#statusModal').modal('hide');
						$.howl ({
						  type: 'info'
						  , title: 'Notification'
						  , content: data.error
						  , sticky: $(this).data ('sticky')
						  , lifetime: 7500
						  , iconCls: $(this).data ('icon')
						});	
					}
				}
		});								

		return false;
	}

	function createBit(idtext) {

		var url	= $('#'+idtext).val();

		$.ajax({
			type: "GET",
			url: "/includes/javascript.php",
			data: "create_bit="+encodeURI(url),
			dataType: "html",
			success: function(data){
				$('.socialbiturl').removeClass('error');
				$('.socialbiturl').removeClass('success');
				if(data != '') {
					$('.socialbiturl').html(data);
					$('.socialbiturl').addClass('success');
				} else {
					$('.socialbiturl').html('Not created, please try again.');
					$('.socialbiturl').addClass('error');
				}
			}
		});								

		return false;
	}
	
	function createSocial() {

		var message	= $('#socialmessage').val();
		var type		= $('#socialtype').val();
		var code		= $('#socialcode').val();

		$.ajax({
			type: "GET",
			url: "/includes/javascript.php?create_social=1",
			data: "message="+message+"&type="+type+"&code="+code,
			dataType: "json",
			success: function(data){
			
				$('.social').removeClass('error');
				$('.social').removeClass('success');

				if(data.result) {
					$('.social').html(data.error);
				} else {
					$('.social').html('Could not add social.');
				}

			}
		});								

		return false;
	}
	
	function statusModal(id, status, page) {
		$('#itemcode').val(id);
		$('#itemstatus').val(status);
		$('#itempage').val(page);
		$('#statusModal').modal('show');
	}

	function viewModal(title, page) {
		$('#itemtitle').html(title);
		$('#itempage').html(page);
		$('#viewModal').modal('show');
	}
	
	function postModal(type, code) {
		$('#socialtype').val(type);
		$('#socialcode').val(code);
		$('#socialModal').modal('show');
		return false;
	}	
	
	function vetModal(code, type, name, page) {
		$.ajax({
			type: "GET",
			url: "/includes/javascript.php",
			data: "vet_select="+type,
			dataType: "html",
			success: function(data){
				$('#vetcode').val(code);
				$('#vettype').val(type);
				
				$('#vetpage').val(page);
				$('.itemname').html(name);
				$('.itemtype').html(type);
				$('#vetstatus').html(data);
				$('#vetModal').modal('show');
			}
		});	

		return false;
	}

	function vet() {

		var code 		= $('#vetcode').val();
		var type		= $('#vettype').val();
		var message	= $('#vetmessage').val();
		var status		= $('#vetstatus :selected').val();
		var page		= $('#vetpage').val();

		$.ajax({
			type: "GET",
			url: "/includes/javascript.php",
			data: "vet_code="+code+"&type="+type+"&message="+message+"&status="+status,
			dataType: "json",
			success: function(data){				
				if(data.result == 1) {
					$('#vetModal').modal('hide');
					if(typeof oTable === 'undefined') {
						window.location.href = window.location.href;
					} else {
						oTable.fnDraw(false);
					}
				} else {
					$('#veterror').html(data.error);
				}
			}
		});								

		return false;
	}	
</script>
{/literal}
<!-- Modal -->
<div class="modal fade" id="vetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Vet <span class="itemname success"></span> which / who is a <span class="itemtype success"></span></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="vetstatus">Vet</label>
					<select id="vetstatus" name="vetstatus" class="form-control">
						<option value=""> --------- </option>
					</select>
				</div>				
				<div class="form-group">
					<label for="vetmessage">Message</label>
					<textarea id="vetmessage" name="vetmessage" class="form-control"></textarea>
				</div>
				<p id="veterror" class="error"></p>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
				<button class="btn btn-warning" type="button" onclick="javascript:vet();">Vet</button>
					<input type="hidden" id="vettype" name="vettype" value="" />	
					<input type="hidden" id="vetcode" name="vetcode" value="" />					
			</div>
		</div>
	</div>
</div>
<!-- modal -->
<!-- Modal -->
<div class="modal fade" id="socialModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Add social media</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="socialmessage">Message</label>
					<textarea id="socialmessage" name="socialmessage" class="form-control"></textarea>
					<p id="socialcount">0 characters entered.</p>
					<br />
					<p class="social"></p>
					<button class="btn btn-warning" type="button" onclick="javascript:createSocial();">Add social</button>
					<input type="hidden" id="socialtype" name="socialtype" value="" />
					<input type="hidden" id="socialcode" name="socialcode" value="" />					
				</div>
				<hr />
				<div class="form-group">
					<label for="socialbit">Create Bit</label>
					<input type="text" id="socialbit" name="socialbit" class="form-control" />
					<br />
					<p class="socialbiturl"></p>
					<button class="btn" type="button" onclick="javascript:createBit('socialbit');">Create bit</button>
				</div>				
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
			</div>
		</div>
	</div>
</div>
<!-- modal -->
<!-- Modal -->
<div class="modal fade" id="statusSubModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Change Status</h4>
			</div>
			<div class="modal-body">Are you sure you want to change this item's status?</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
				<button class="btn btn-warning" type="button" onclick="javascript:changeSubStatus();">Change Item Status</button>
				<input type="hidden" id="itemsubcode" name="itemsubcode" value="" />
				<input type="hidden" id="itemsubstatus" name="itemsubstatus" value="" />
				<input type="hidden" id="itemsubpage" name="itemsubpage" value=""/>
				<input type="hidden" id="itemsubparent" name="itemsubparent" value=""/>
			</div>
		</div>
	</div>
</div>
<!-- modal -->
<!-- Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="itemtitle" name="itemtitle">View Information</h4>
			</div>
			<div class="modal-body" id="itempage" name="itempage"></div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Close</button>
			</div>
		</div>
	</div>
</div>
<!-- modal -->
<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Delete Item</h4>
			</div>
			<div class="modal-body">Are you sure you want to delete this item?</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
				<button class="btn btn-warning" type="button" onclick="javascript:deleteitem();">Delete Item</button>
				<input type="hidden" id="itemcode" name="itemcode" value=""/>
				<input type="hidden" id="itempage" name="itempage" value=""/>
				<input type="hidden" id="maincode" name="maincode" value=""/>
			</div>
		</div>
	</div>
</div>
<!-- modal -->
<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Change Item Status</h4>
			</div>
			<div class="modal-body">Are you sure you want to change this item's status?</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
				<button class="btn btn-warning" type="button" onclick="javascript:changestatus();">Change Item Status</button>
				<input type="hidden" id="itemcode" name="itemcode" value="" />
				<input type="hidden" id="itemstatus" name="itemstatus" value="" />
				<input type="hidden" id="itempage" name="itempage" value=""/>
			</div>
		</div>
	</div>
</div>
<!-- modal -->