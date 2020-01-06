  <div class="navbar">  <div class="container">    <!-- <div class="navbar-collapse collapse"> -->		<div class="navbar-header">			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">			<i class="fa fa-cogs"></i>			</button>			<a class="navbar-brand navbar-brand-image" href="/">				<img src="/images/logo.png" title="Communication Tool" alt="Communication Tool" />			</a>					</div><!-- /.navbar-header -->				<div class="navbar-collapse collapse" style="height: auto;">			<ul class="nav navbar-nav navbar-right"> 				{if isset($activeAccount)}<li><a href="#">{$activeAccount.account_name}</a></li>{/if}				<li><a href="/logout.php">Logout</a></li>			</ul>		</div>		    <!--/ </div> .navbar-collapse -->  </div> <!-- /.container --></div> <!-- /.navbar --><div class="mainbar">	<div class="container">		<button type="button" class="btn mainbar-toggle" data-toggle="collapse" data-target=".mainbar-collapse">		  <i class="fa fa-bars"></i>		</button> 		<div class="mainbar-collapse collapse">		  <ul class="nav navbar-nav mainbar-nav">			<li {if $currentPage eq ''}class="active"{/if}>				<a href="/">					<i class="fa fa-h-square"></i>					Home				</a>			</li>				<li {if $currentPage eq 'account'}class="active"{/if}>				<a href="/account/">					<i class="fa fa-users"></i>					Account				</a>			</li>			<li {if $currentPage eq 'template'}class="active"{/if}>				<a href="/template/">					<i class="fa fa-users"></i>					Template				</a>			</li>						{if isset($activeAccount)}				<li class="dropdown {if $currentPage eq 'configuration'}active{/if}">			  <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown">				<i class="fa fa-users"></i>				Configuration				<span class="caret"></span>			  </a>			  <ul class="dropdown-menu">				<li><a href="/configuration/campus/"><i class="fa fa-calendar nav-icon"></i> Campus</a></li>				<li><a href="/configuration/room/"><i class="fa fa-calendar nav-icon"></i> Room</a></li>				<li><a href="/configuration/faculty/"><i class="fa fa-calendar nav-icon"></i> Faculty</a></li>				<li><a href="/configuration/department/"><i class="fa fa-user nav-icon"></i> Department</a></li>				<li><a href="/configuration/qualification/"><i class="fa fa-calendar nav-icon"></i> Qualification</a></li>				<li><a href="/configuration/course/"><i class="fa fa-calendar nav-icon"></i> Course</a></li>				<li><a href="/configuration/class/"><i class="fa fa-calendar nav-icon"></i> Class</a></li>			  </ul>			</li>					<li {if $currentPage eq 'participant'}class="active"{/if}>				<a href="/participant/">					<i class="fa fa-users"></i>					Students				</a>			</li>			<li {if $currentPage eq 'teacher'}class="active"{/if}>				<a href="/teacher/">					<i class="fa fa-users"></i>					Teacher				</a>			</li>			<li {if $currentPage eq 'device'}class="active"{/if}>				<a href="/device/">					<i class="fa fa-users"></i>					Device				</a>			</li>						{/if}				  </ul> 		</div> <!-- /.navbar-collapse -->	</div> <!-- /.container --> </div> <!-- /.mainbar -->