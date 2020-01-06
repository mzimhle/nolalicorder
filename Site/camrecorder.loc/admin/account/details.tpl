<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<title>Nolali - CamRecorder</title>
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
	<h2 class="content-header-title">Account</h2>
	<ol class="breadcrumb">
		<li><a href="/">Home</a></li>
		<li><a href="/account/">Account</a></li>
		<li>{if isset($accountData)}<a href="/account/details.php?code={$accountData.account_code}">{$accountData.account_name}</a>{else}Add a account{/if}</li>
		<li class="active">Details</li>
	</ol>
	</div>	
      <div class="row">
        <div class="col-sm-9">
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				{if isset($accountData)}{$accountData.account_name}{else}Add an account{/if}
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="detailsForm" action="/account/details.php{if isset($accountData)}?code={$accountData.account_code}{/if}" method="POST" data-validate="parsley" class="form parsley-form" enctype="multipart/form-data"> 
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_name" class="error">Name</label>
							<input type="text" id="account_name" name="account_name" class="form-control" value="{$accountData.account_name}" />
							{if isset($errorArray.account_name)}<span class="error">{$errorArray.account_name}</span>{/if}					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_site" class="error">Website</label>
							<input type="text" id="account_site" name="account_site" class="form-control" value="{$accountData.account_site}" />
							{if isset($errorArray.account_site)}<span class="error">{$errorArray.account_site}</span>{/if}					  
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_contact_cellphone" class="error">Cellphone Number</label>
							<input type="text" id="account_contact_cellphone" name="account_contact_cellphone" class="form-control" value="{$accountData.account_contact_cellphone}" />
							{if isset($errorArray.account_contact_cellphone)}<span class="error">{$errorArray.account_contact_cellphone}</span>{/if}					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_contact_telephone">Telephone Number</label>
							<input type="text" id="account_contact_telephone" name="account_contact_telephone" class="form-control" value="{$accountData.account_contact_telephone}" />
							{if isset($errorArray.account_contact_telephone)}<span class="error">{$errorArray.account_contact_telephone}</span>{/if}					  
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_contact_email" class="error">Email Address</label>
							<input type="text" id="account_contact_email" name="account_contact_email" class="form-control" value="{$accountData.account_contact_email}" />
							{if isset($errorArray.account_contact_email)}<span class="error">{$errorArray.account_contact_email}</span>{/if}					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_contact_fax">Fax Number</label>
							<input type="text" id="account_contact_fax" name="account_contact_fax" class="form-control" value="{$accountData.account_contact_fax}" />
							{if isset($errorArray.account_contact_fax)}<span class="error">{$errorArray.account_contact_fax}</span>{/if}					  
						</div>
					</div>
				</div>		
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_social_twitter">Twitter Handler</label>
							<input type="text" id="account_social_twitter" name="account_social_twitter" class="form-control" value="{$accountData.account_social_twitter}" />
							{if isset($errorArray.account_social_twitter)}<span class="error">{$errorArray.account_social_twitter}</span>{/if}					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_social_instagram">Instagram Handler</label>
							<input type="text" id="account_social_instagram" name="account_social_instagram" class="form-control" value="{$accountData.account_social_instagram}" />
							{if isset($errorArray.account_social_instagram)}<span class="error">{$errorArray.account_social_instagram}</span>{/if}					  
						</div>
					</div>
				</div>		
				<div class="row">
					<div class="col-sm-6">			  
						<div class="form-group">
							<label for="account_social_facebook">Facebook URL</label>
							<input type="text" id="account_social_facebook" name="account_social_facebook" class="form-control" value="{$accountData.account_social_facebook}" />
							{if isset($errorArray.account_social_facebook)}<span class="error">{$errorArray.account_social_facebook}</span>{/if}					  
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="form-group">
							<label for="account_social_linkedin">LinkedIn Account URL</label>
							<input type="text" id="account_social_linkedin" name="account_social_linkedin" class="form-control" value="{$accountData.account_social_linkedin}" />
							{if isset($errorArray.account_social_linkedin)}<span class="error">{$errorArray.account_social_linkedin}</span>{/if}					  
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-sm-12">			  
						<div class="form-group">
							<label for="account_address_physical" class="error">Physical Address</label>
							<input type="text" id="account_address_physical" name="account_address_physical" class="form-control" value="{$accountData.account_address_physical}" />
							{if isset($errorArray.account_address_physical)}<span class="error">{$errorArray.account_address_physical}</span>{/if}					  
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">				
						<div class="form-group">
							<label for="account_address_postal">Postal Address</label>
							<input type="text" id="account_address_postal" name="account_address_postal" class="form-control" value="{$accountData.account_address_postal}" />
							{if isset($errorArray.account_address_postal)}<span class="error">{$errorArray.account_address_postal}</span>{/if}					  
						</div>
					</div>
				</div>
                <div class="form-group">
					<button type="submit" class="btn btn-primary">{if isset($accountData)}Update{else}Add{/if}</button>
				</div>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->	
		<div class="col-sm-3">
			<div class="list-group">  
				<a href="/account/" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;List
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				{if isset($accountData)}
				<a href="/account/details.php?code={$accountData.account_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Details
				  <i class="fa fa-chevron-right list-group-chevron"></i>
				</a>
				<a href="/account/media.php?code={$accountData.account_code}" class="list-group-item">
				  <i class="fa fa-asterisk"></i> &nbsp;&nbsp;Media
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
</html>