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
	{include_php file='includes/css.php'}
</head>
<body>
{include_php file='includes/header.php'}
<div class="container">
  <div class="content">
    <div class="content-container">
	<div class="content-header">
		<h2 class="content-header-title">Templates</h2>
		<ol class="breadcrumb">
			<li><a href="/">Home</a></li>
			{if isset($activeAccount)}<li><a href="/account/details.php?code={$activeAccount.account_code}">{$activeAccount.account_name}</a></li>{/if}
			<li><a href="/template/">Templates</a></li>
			<li class="active">List</li>
		</ol>
	</div>	
      <div class="row">
        <div class="col-sm-12">
		<button class="btn btn-secondary fr" type="button" onclick="link('/template/details.php'); return false;">Add a new template</button><br/ ><br />
          <div class="portlet">
            <div class="portlet-header">
              <h3>
                <i class="fa fa-tasks"></i>
				Template list
              </h3>
            </div> <!-- /.portlet-header -->
            <div class="portlet-content">
              <form id="validate-basic" action="#" method="POST" data-validate="parsley" class="form parsley-form">	
				{if isset($activeAccount)}These are <b>{$activeAccount.account_name}</b> templates.<br /><br />{/if}
				<table class="table table-bordered">	
					<thead>
						<tr>
							<td width="3%">Public</td>
							<td width="5%">Cipher</td>
							<td width="5%">Type</td>
							<td width="5%">Category</td>
							<td width="60%">View</td>
							<td width="3%"></td>
						</tr>
					</thead>
					<tbody>
					{foreach from=$templateData item=item}
					<tr>
						<td>{if $item.template_public eq '1'}YES{else}NO{/if}</td>
						<td><a href="/template/details.php?code={$item.template_code}">{$item.template_cipher}</a></td>
						<td>{$item.template_type}</td>
						<td>{$item.template_category}</td>
						<td>
						{if $item.template_category eq 'EMAIL'}
						<a href="/template/view.php?code={$item.template_code}" target="_blank">{$item.template_subject}</a>
						{else}
						{$item.template_message}
						{/if}
						</td>
						<td><button value="Delete" class="btn btn-danger" onclick="deleteModal('{$item.template_code}', '', 'default'); return false;">Delete</button></td>
					</tr>			     
					{foreachelse}
					<tr><td align="center" colspan="6">There are currently no items</td></tr>
					{/foreach}
					</tbody>					  
				</table>
              </form>
            </div> <!-- /.portlet-content -->
          </div> <!-- /.portlet -->
        </div> <!-- /.col -->				
      </div> <!-- /.row -->
    </div> <!-- /.content-container -->
  </div> <!-- /.content -->
</div> <!-- /.container -->
{include_php file='includes/footer.php'}
{include_php file='includes/javascript.php'}
</html>