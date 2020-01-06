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
	<h2 class="content-header-title">Template</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		{if isset($activeAccount)}<li><a href="/account/details.php?code={$activeAccount.account_code}">{$activeAccount.account_name}</a></li>{/if}
		<li><a href="/template/">Template</a></li>
		<li>{if isset($templateData)}{$templateData.template_cipher}{else}Add a template{/if}</li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($templateData)}{$templateData.template_cipher}{else}Add a template{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="/template/details.php{if isset($templateData)}?code={$templateData.template_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data">	
                <div class="form-group">
					<label for="template_public">Is this a public template</label>
					<select id="template_public" name="template_public" class="form-control">
						<option value="0" {if $templateData.template_public eq '0'}selected{/if}> NO </option>
						<option value="1" {if $templateData.template_public eq '1'}selected{/if}> YES </option>
					</select>
					{if isset($errorArray.template_public)}<span class="error">{$errorArray.template_public}</span>{else}<span class="smalltext">Should administrators of municipalities be able to see these.</span>{/if}					  
                </div>			  
				{if !isset($templateData)}
                <div class="form-group">
					<label for="template_category">Category</label>
					<select id="template_category" name="template_category" class="form-control" {if isset($templateData)}readonly{/if}>
						<option value="EMAIL" {if $templateData.template_category eq 'EMAIL'}selected{/if}> EMAIL </option>
						<option value="SMS" {if $templateData.template_category eq 'SMS'}selected{/if}> SMS </option>
					</select>
					{if isset($errorArray.template_category)}<span class="error">{$errorArray.template_category}</span>{else}<span class="smalltext">Please select a category</span>{/if}					  
                </div>				
                <div class="form-group">
					<label for="template_cipher">Cipher</label>
					<input type="text" id="template_cipher" name="template_cipher" class="form-control" data-required="true" value="{$templateData.template_cipher}" />
					{if isset($errorArray.template_cipher)}<span class="error">{$errorArray.template_cipher}</span>{else}<span class="smalltext">cipher of the template</span>{/if}					  
                </div>
                <div class="form-group">
					<label for="template_type">Type</label>
					<select id="template_type" name="template_type" class="form-control">
						<option value=""> ------ </option>
						<option value="ENQUIRY" {if $templateData.template_type eq 'ENQUIRY'}selected{/if}> ENQUIRY </option>
						<option value="PARTICIPANT" {if $templateData.template_type eq 'PARTICIPANT'}selected{/if}> PARTICIPANT </option>
						<option value="ACCOUNT" {if $templateData.template_type eq 'ACCOUNT'}selected{/if}> ACCOUNT </option>
						<option value="ADMIN" {if $templateData.template_type eq 'ADMIN'}selected{/if}> ADMIN </option>						
					</select>
					{if isset($errorArray.template_type)}<span class="error">{$errorArray.template_type}</span>{else}<span class="smalltext">Select section of a category if a category has sections under it.</span>{/if}					  
                </div>
				{else}
                <div class="form-group">
					<label for="template_category">Category</label><br />
					{$templateData.template_category}<br /><br />
					<label for="template_cipher">Cipher</label><br />
					{$templateData.template_cipher}<br /><br />
					<label for="template_type">Type</label><br />
					{$templateData.template_type}<br />							
                </div>
				<input type="hidden" id="template_category" name="template_category" value="{$templateData.template_category}" />
				<input type="hidden" id="template_cipher" name="template_cipher" value="{$templateData.template_cipher}	" />
				<input type="hidden" id="template_type" name="template_type" value="{$templateData.template_type}" />
				{/if}
                <div class="form-group SMS">
					<label for="template_message">Message</label>
					<textarea id="template_message" name="template_message" class="form-control" rows="5">{$templateData.template_message}</textarea>
					<span class="smalltext error" id="template_count">0 characters entered.</span><br />
					{if isset($errorArray.template_message)}<span class="error">{$errorArray.template_message}</span>{else}<span class="smalltext">SMS message which is less than 140 characters.</span>{/if}					  
                </div>				
                <div class="form-group EMAIL">
					<label for="template_subject">Subject</label>
					<input type="text" id="template_subject" name="template_subject" class="form-control" value="{$templateData.template_subject}" />
					{if isset($errorArray.template_subject)}<span class="error">{$errorArray.template_subject}</span>{else}<span class="smalltext">Please add the subject of this email</span>{/if}					  
                </div>				
                <div class="form-group EMAIL">
					<label for="htmlfile">Upload HTML / HTM file</label>
					<input type="file" id="htmlfile" name="htmlfile" />
					{if isset($errorArray.htmlfile)}<span class="error">{$errorArray.htmlfile}</span>{/if}
					<br /><span>N.B.: Only upload the html or htm files.</span>
					{if isset($templateData)}
						{if $templateData.template_file neq ''}
							<br />
							<p>
								<a href="/template/view.php?code={$templateData.template_code}" target="_blank">{$config.site}{$templateData.template_file}</a>
							</p>
						{/if}
					{/if}
                </div>
                <div class="form-group EMAIL">
					<label for="imagefiles">Image Upload</label>
					<input type="file" id="imagefiles[]" name="imagefiles[]" multiple />
					{if isset($errorArray.imagefiles)}<span class="error">{$errorArray.imagefiles}</span>{/if}
					<br /><span>N.B.: Upload only jpg, jpeg, png or gif images</span>
                </div>					
                <div class="form-group"><button type="submit" class="btn btn-primary">Validate and Submit</button></div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/template/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{if isset($templateData)}
				<a href="/template/details.php?code={$templateData.template_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{/if}
			</div> <!-- /.list-group -->
		</div>
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
{include_php file='includes/footer.php'}
{include_php file='includes/javascript.php'}
{literal}
<script type="text/javascript">
$( document ).ready(function() {
	$("#template_category").change(function() {
	  categoryChange(); 
	  return false;
	});
	categoryChange();
	messageCount();
});

function messageCount() {
	$("#template_message").keyup(function () {
		var i = $("#template_message").val().length;
		$("#template_count").html(i+' characters entered.');
		if (i > 320) {
			$('#template_count').removeClass('success');
			$('#template_count').addClass('error');
		} else if(i == 0) {
			$('#template_count').removeClass('success');
			$('#template_count').addClass('error');
		} else {
			$('#template_count').removeClass('error');
			$('#template_count').addClass('success');
		} 
	});	
	return false;
}
function categoryChange() {
	var category = $( "#template_category" ).val();
	
	if(category == 'EMAIL') {
		$(".SMS").hide();
		$(".EMAIL").show();
		messageCount();
	} else if(category == 'SMS') {
		$(".SMS").show();
		$(".EMAIL").hide();
	}
	return false;
}
</script>
{/literal}
</html>